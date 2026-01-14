<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportsController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ReportModel');
        $this->load->model('BarangayModel');
        $this->load->model('LogModel');
    }

    // This is the main reports page
    public function index() {
        $user_type = $this->session->userdata('user_type');
        $data['user_type'] = $user_type;

        if ($user_type == 'admin') {
            $data['barangays'] = $this->BarangayModel->get_barangays();
            $data['selected_barangay_id'] = null;
            $data['selected_barangay_name'] = 'Select Barangays';
        } else {
            $secretary_brgy_id = $this->session->userdata('brgy_ID');
            $secretary_brgy_info = $this->BarangayModel->get_barangay_by_id($secretary_brgy_id);
            $data['barangays'] = [];
            $data['selected_barangay_id'] = $secretary_brgy_id;
            $data['selected_barangay_name'] = $secretary_brgy_info ? $secretary_brgy_info->brgy_name : 'Your Barangay';
        }
        
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Reports Page');
        $this->load->view('templates/header');
        $this->load->view('pages/reports', $data);
        $this->load->view('templates/footer');
    }

    // This function shows the chart view page
    public function display_chart() {
        $report_type = $this->input->get('report_type');
        $barangay_ids_str = $this->input->get('barangays');

        // Use the powerful helper function to get ALL the necessary data in one go
        $report_data = $this->_get_report_data($report_type, $barangay_ids_str);

        // Pass all the necessary data to the view
        $data['title'] = $report_data['title'];
        $data['headers'] = $report_data['headers'];
        $data['results'] = $report_data['results'];
        $data['chart_type'] = $report_data['chart_type'];
        
        // Directly encode the chart data for the view's JavaScript
        $data['chart_data_json'] = json_encode($report_data['chart_data']);

        $this->load->view('templates/header');
        $this->load->view('pages/report_chart', $data);
        $this->load->view('templates/footer');
    }

    // This function shows the printable report page
    public function download_report() {
        $report_type = $this->input->get('report_type');
        $barangay_ids_str = $this->input->get('barangays');

        // Use the same powerful helper function
        $report_data = $this->_get_report_data($report_type, $barangay_ids_str);
        
        // Prepare data for the printable view
        $data['report_title'] = $report_data['title'];
        $data['columns'] = $report_data['headers'];
        $data['results'] = $report_data['results'];
        $data['chart_data_json'] = json_encode($report_data['chart_data']);
        $data['chart_type'] = $report_data['chart_type'];

        $this->load->view('pages/printable_report', $data);
    }

    // --- PRIVATE HELPER FUNCTION ---
    // This is the "brain" of the report generation.
    private function _get_report_data($report_type, $barangay_ids_str) {
        // Handle the 'all' option from the dropdown
        if ($barangay_ids_str === 'all') {
            $all_barangays = $this->BarangayModel->get_barangays();
            $barangay_ids = array_column($all_barangays, 'brgy_ID');
        } else {
            $barangay_ids = explode(',', $barangay_ids_str);
        }

        // Initialize the master data array
        $data = [
            'results' => [], 'headers' => [], 'title' => 'Generated Report', 
            'chart_type' => 'bar', 'chart_data' => null
        ];

        switch ($report_type) {
            case 'population-summary':
                $chart_data = $this->ReportModel->get_population_summary($barangay_ids);
                $data['title'] = 'Population Summary';
                $data['headers'] = ['Barangay', 'Total Residents'];
                // FIX: Check if data exists before creating the results table
                if (!empty($chart_data['labels'])) {
                    $data['results'] = array_map(null, $chart_data['labels'], $chart_data['datasets'][0]['data']);
                }
                $data['chart_data'] = $chart_data;
                $data['chart_type'] = 'bar';
                break;
            case 'gender-distribution':
                $chart_data = $this->ReportModel->get_gender_distribution_by_barangays($barangay_ids);
                $data['title'] = 'Gender Distribution';
                $data['headers'] = ['Gender', 'Count'];
                if (!empty($chart_data['labels'])) {
                    $data['results'] = array_map(null, $chart_data['labels'], $chart_data['datasets'][0]['data']);
                }
                $data['chart_data'] = $chart_data;
                $data['chart_type'] = 'pie';
                break;
           case 'family-count':
                $chart_data = $this->ReportModel->get_family_counts($barangay_ids);
                $data['title'] = 'Family Count by Barangay';
                $data['headers'] = ['Barangay', 'Number of Families'];
                if (!empty($chart_data['labels'])) {
                    $data['results'] = array_map(null, $chart_data['labels'], $chart_data['datasets'][0]['data']);
                }
                $data['chart_data'] = $chart_data;
                $data['chart_type'] = 'bar'; // <-- THE CHANGE IS HERE
                break;
            case 'age-group':
                $chart_data = $this->ReportModel->get_age_group_distribution($barangay_ids);
                $data['title'] = 'Age Group Distribution';
                $data['headers'] = ['Age Group', 'Count'];
                if (!empty($chart_data['labels'])) {
                    $data['results'] = array_map(null, $chart_data['labels'], $chart_data['datasets'][0]['data']);
                }
                $data['chart_data'] = $chart_data;
                $data['chart_type'] = 'doughnut';
                break;
            case 'resident_type':
                $chart_data = $this->ReportModel->get_resident_type_distribution($barangay_ids);
                $data['title'] = 'Resident Type Distribution';
                $data['headers'] = ['Resident Type', 'Count'];
                if (!empty($chart_data['labels'])) {
                    $data['results'] = array_map(null, $chart_data['labels'], $chart_data['datasets'][0]['data']);
                }
                $data['chart_data'] = $chart_data;
                $data['chart_type'] = 'pie';
                break;
            case 'registered-voters':
                $data['title'] = 'List of Registered Voters';
                $data['headers'] = ['Barangay', 'Last Name', 'First Name', 'Middle Name', 'Birthdate'];
                $data['results'] = $this->ReportModel->get_voters_list($barangay_ids);
                $data['chart_type'] = 'table'; // No chart for this one
                break;
            case 'currently-studying':
                 $data['title'] = 'List of Individuals Currently Studying';
                 $data['headers'] = ['Barangay', 'Last Name', 'First Name', 'Middle Name', 'Occupation'];
                 $data['results'] = $this->ReportModel->get_studying_list($barangay_ids);
                 $data['chart_type'] = 'table'; // No chart for this one
                break;
        }
        
        return $data;
    }
}