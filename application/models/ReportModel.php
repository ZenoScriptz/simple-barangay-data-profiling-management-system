<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // --- DASHBOARD FUNCTIONS ---

    public function getTotalResidents($brgy_ID = null)
    {
        if ($brgy_ID !== null) {
            $this->db->where('brgy_ID', $brgy_ID);
        }
        return $this->db->count_all_results('residents');
    }

    public function getGenderCount($gender, $brgy_ID = null)
    {
        $this->db->where('gender', $gender);
        if ($brgy_ID !== null) {
            $this->db->where('brgy_ID', $brgy_ID);
        }
        return $this->db->count_all_results('residents');
    }

    public function getTotalFamilies($brgy_ID = null)
    {
        $this->db->where('resident_role', 'Head');
        if ($brgy_ID !== null) {
            $this->db->where('brgy_ID', $brgy_ID);
        }
        return $this->db->count_all_results('residents');
    }

    public function getDashboardChartData($brgy_ID = null)
    {
        $this->db->select('b.brgy_name, r.gender, COUNT(r.resident_ID) as count');
        $this->db->from('residents r')->join('barangay b', 'r.brgy_ID = b.brgy_ID', 'left');
        if ($brgy_ID !== null) {
            $this->db->where('r.brgy_ID', $brgy_ID);
        }
        $this->db->group_by('b.brgy_name, r.gender');
        return $this->db->get()->result();
    }

    public function getTotalVoters($brgy_ID = null)
    {
        $this->db->where('isVoter', 1);
        if ($brgy_ID !== null) {
            $this->db->where('brgy_ID', $brgy_ID);
        }
        return $this->db->count_all_results('residents');
    }

    // --- REPORT GENERATION FUNCTIONS ---

    public function get_gender_distribution_by_barangays($barangay_ids)
    {
        $this->db->select("SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male_count, SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female_count");
        $this->db->from('residents')->where_in('brgy_ID', $barangay_ids);
        $result = $this->db->get()->row();
        return ['labels' => ['Male', 'Female'], 'datasets' => [['data' => [(int) $result->male_count, (int) $result->female_count], 'backgroundColor' => ['#4e73df', '#e74a3b']]]];
    }

    public function get_population_summary($barangay_ids)
    {
        $this->db->select('b.brgy_name, COUNT(r.resident_ID) as count')->from('residents r')->join('barangay b', 'r.brgy_ID = b.brgy_ID')->where_in('r.brgy_ID', $barangay_ids)->group_by('b.brgy_name');
        $results = $this->db->get()->result();
        return ['labels' => array_column($results, 'brgy_name'), 'datasets' => [['label' => 'Total Residents', 'data' => array_column($results, 'count'), 'backgroundColor' => '#4e73df']]];
    }

    public function get_family_counts($barangay_ids)
    {
        $this->db->select('b.brgy_name, COUNT(r.resident_ID) as count')->from('residents r')->join('barangay b', 'r.brgy_ID = b.brgy_ID')->where('r.resident_role', 'Head')->where_in('r.brgy_ID', $barangay_ids)->group_by('b.brgy_name');
        $results = $this->db->get()->result();
        return ['labels' => array_column($results, 'brgy_name'), 'datasets' => [['label' => 'Number of Families', 'data' => array_column($results, 'count'), 'backgroundColor' => '#1cc88a']]];
    }

  public function get_age_group_distribution($barangay_ids)
    {
        // This query now uses descriptive labels directly in the SQL aliases.
        $this->db->select("
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 0 AND 1 THEN 1 ELSE 0 END) as 'Infant (0-1)',
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 2 AND 12 THEN 1 ELSE 0 END) as 'Child (2-12)',
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 13 AND 19 THEN 1 ELSE 0 END) as 'Teen (13-19)',
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 20 AND 39 THEN 1 ELSE 0 END) as 'Young Adult (20-39)',
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 40 AND 59 THEN 1 ELSE 0 END) as 'Adult (40-59)',
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 60 THEN 1 ELSE 0 END) as 'Senior Citizen (60+)'
        ", FALSE); // The FALSE here prevents CodeIgniter from trying to escape the aliases

        $this->db->from('residents');
        
        // Handle the 'all' case for barangay IDs
        if (!in_array('all', $barangay_ids)) {
            $this->db->where_in('brgy_ID', $barangay_ids);
        }

        $result = $this->db->get()->row_array();

        // Prepare the data for Chart.js
        return [
            'labels' => array_keys($result), // This will now be ['Infant (0-1)', 'Child (2-12)', ...]
            'datasets' => [[
                'label' => 'Residents by Age Group', // Add a label for the dataset
                'data' => array_values($result),
                'backgroundColor' => [ // Provide a color for each age group
                    '#4e73df', // Blue
                    '#1cc88a', // Green
                    '#36b9cc', // Teal
                    '#f6c23e', // Yellow
                    '#fd7e14', // Orange
                    '#e74a3b'  // Red
                ]
            ]]
        ];
    }
    public function getTotalResidentsInBarangays($barangay_ids) {
        if (!empty($barangay_ids)) {
            $this->db->where_in('brgy_ID', $barangay_ids);
        }
        return $this->db->count_all_results('residents');
    }

    public function get_resident_type_distribution($barangay_ids)
    {
        $this->db->select('resident_type, COUNT(resident_ID) as count')->from('residents')->where_in('brgy_ID', $barangay_ids)->group_by('resident_type');
        $results = $this->db->get()->result();
        return ['labels' => array_column($results, 'resident_type'), 'datasets' => [['data' => array_column($results, 'count'), 'backgroundColor' => ['#36b9cc', '#5a5c69']]]];
    }

    public function get_voters_list($barangay_ids)
    {
        $this->db->select('b.brgy_name, r.last_name, r.first_name, r.middle_name, r.birth_date');
        $this->db->from('residents r')->join('barangay b', 'r.brgy_ID = b.brgy_ID');
        $this->db->where('r.isVoter', 1);
        if (!in_array('all', $barangay_ids)) {
            $this->db->where_in('r.brgy_ID', $barangay_ids);
        }
        $this->db->order_by('b.brgy_name, r.last_name, r.first_name');
        return $this->db->get()->result_array();
    }

    public function get_studying_list($barangay_ids)
    {
        $this->db->select('b.brgy_name, r.last_name, r.first_name, r.middle_name, r.occupation');
        $this->db->from('residents r')->join('barangay b', 'r.brgy_ID = b.brgy_ID');
        $this->db->where('r.isStudying', 1);
        if (!in_array('all', $barangay_ids)) {
            $this->db->where_in('r.brgy_ID', $barangay_ids);
        }
        $this->db->order_by('b.brgy_name, r.last_name, r.first_name');
        return $this->db->get()->result_array();
    }
}