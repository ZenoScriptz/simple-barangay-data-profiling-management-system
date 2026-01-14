<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupportController extends MY_Controller { // Secure by default

    public function __construct() {
        parent::__construct();
        $this->load->model('SupportModel');
        $this->load->model('LogModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    // Displays the contact form for secretaries
    public function index() {
        // Additional check to ensure only secretaries see this
        if ($this->session->userdata('user_type') != 'secretary') {
            redirect('dashboard');
        }
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Contact Support Page');
        $this->load->view('templates/header');
        $this->load->view('pages/contactsupport');
        $this->load->view('templates/footer');
    }

    // Handles the submission of the support form
    public function submit() {
        $this->form_validation->set_rules('message_content', 'Message', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
        } else {
            $data = ['user_ID' => $this->session->userdata('user_id'), 'message_content' => $this->input->post('message_content')];
            if ($this->SupportModel->insert_report($data)) {
                $this->LogModel->log_activity($this->session->userdata('user_id'), 'Submit Support Report');
                $this->session->set_flashdata('success_message', 'Your report has been submitted successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Error submitting report.');
            }
        }
        redirect('contactsupport');
    }

    // Displays the list of submitted reports for the admin
    public function reports() {
        // Admin-only check
        if ($this->session->userdata('user_type') != 'admin') {
            redirect('dashboard');
        }
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Secretary Reports');
        $this->load->view('templates/header');
        $this->load->view('pages/secretary_reports');
        $this->load->view('templates/footer');
    }

    // AJAX endpoint for the secretary reports table
    public function get_report_data() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $list = $this->SupportModel->get_reports_serverside();
        $data = [];
        foreach ($list['data'] as $report) {
            $row = [];
            $row['username'] = html_escape($report['username']);
            $row['brgy_name'] = html_escape($report['brgy_name']);
            $row['created_at'] = $report['created_at'];
            $row['message_content'] = html_escape($report['message_content']);
            $data[] = $row;
        }
        $output = ["draw" => $list['draw'], "recordsTotal" => $list['recordsTotal'], "recordsFiltered" => $list['recordsFiltered'], "data" => $data];
        echo json_encode($output);
    }
}