<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogController extends Admin_Controller { // Secure for Admins only

    public function __construct() {
        parent::__construct();
        $this->load->model('LogModel');
    }

    // Display the activity logs page
    public function index() {
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Activity Logs');
        $this->load->view('templates/header');
        $this->load->view('pages/activity_logs');
        $this->load->view('templates/footer');
    }

    // AJAX endpoint for the activity logs table
    public function get_log_data() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $list = $this->LogModel->get_logs_serverside();
        $data = [];
        foreach ($list['data'] as $log) {
            $row = [];
            $row[] = $log->log_ID;
            $row[] = html_escape($log->account_name);
            $row[] = html_escape($log->action_type);
            $row[] = $log->created_at;
            $data[] = $row;
        }
        $output = ["draw" => $list['draw'], "recordsTotal" => $list['recordsTotal'], "recordsFiltered" => $list['recordsFiltered'], "data" => $data];
        echo json_encode($output);
    }
}