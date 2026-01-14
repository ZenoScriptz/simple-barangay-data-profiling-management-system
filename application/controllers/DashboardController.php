<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends MY_Controller {
     public function __construct() {
         parent::__construct(); // This automatically runs the security check
         // No need for the if statement here anymore!
         $this->load->model('ReportModel');
     }
 

    // The 'index' function is the default method for this controller
public function index() {
        $user_type = $this->session->userdata('user_type');
        $brgy_ID = ($user_type == 'secretary') ? $this->session->userdata('brgy_ID') : null;

        // --- 1. GET DATA FOR TOP CARDS ---
        $data['total_residents'] = $this->ReportModel->getTotalResidents($brgy_ID);
        $data['male_residents'] = $this->ReportModel->getGenderCount('Male', $brgy_ID);
        $data['female_residents'] = $this->ReportModel->getGenderCount('Female', $brgy_ID);
        $data['total_families'] = $this->ReportModel->getTotalFamilies($brgy_ID);

        // --- 2. GET DATA FOR POPULATION CHART ---
        $chart_results = $this->ReportModel->getDashboardChartData($brgy_ID);
        $barangay_data = [];
        foreach ($chart_results as $row) {
            if (!isset($barangay_data[$row->brgy_name])) $barangay_data[$row->brgy_name] = 0;
            $barangay_data[$row->brgy_name] += (int)$row->count;
        }
        $data['barangay_chart_labels'] = json_encode(array_keys($barangay_data));
        $data['barangay_chart_data'] = json_encode(array_values($barangay_data));

        // --- 3. GET DATA FOR GENDER & VOTER CHARTS ---
        // We can reuse the top card data for these charts
        $data['male_count_chart'] = $data['male_residents'];
        $data['female_count_chart'] = $data['female_residents'];
        
        $total_voters = $this->ReportModel->getTotalVoters($brgy_ID); // New function call
        $data['voter_count_chart'] = $total_voters;
        $data['non_voter_count_chart'] = $data['total_residents'] - $total_voters;
        
        // --- 4. LOAD THE VIEW ---
        $this->load->view('templates/header');
        $this->load->view('pages/dashboard', $data);
        $this->load->view('templates/footer');
    }
}