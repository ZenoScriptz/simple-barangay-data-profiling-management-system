<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SecretaryController extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_logged_in') || $this->session->userdata('user_type') != 'admin') {
            redirect('login');
        }
        $this->load->model('UserModel');
        $this->load->model('BarangayModel');
        $this->load->model('LogModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    public function index() {
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Secretaries List');
        $this->load->view('templates/header');
        $this->load->view('pages/secretary');
        $this->load->view('templates/footer');
    }

    public function add() {
        $data['barangays'] = $this->BarangayModel->get_barangays();
        $this->load->view('templates/header');
        $this->load->view('pages/add_secretary', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->form_validation->set_rules('account_name', 'Full Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('brgy_ID', 'Barangay', 'required|integer');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect('secretaries/add');
        } else {
            $user_data = [
                'account_name' => $this->input->post('account_name'), 'username' => $this->input->post('username'),
                'password' => $this->input->post('password'), 'brgy_ID' => $this->input->post('brgy_ID'),
                'user_type' => 'SECRETARY', 'user_status' => 'active'
            ];
            $user_id = $this->UserModel->insert_user($user_data);
            if ($user_id) {
                $this->LogModel->log_activity($this->session->userdata('user_id'), 'Add New Secretary (ID: ' . $user_id . ')');
                $this->session->set_flashdata('success_message', 'Barangay Secretary added successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Error adding Barangay Secretary.');
            }
            redirect('secretaries');
        }
    }
    
    public function edit($id) {
        $data['secretary'] = $this->UserModel->get_user_by_id($id);
        $data['barangays'] = $this->BarangayModel->get_barangays();
        if (empty($data['secretary'])) {
            redirect('secretaries');
        }
        $this->load->view('templates/header');
        $this->load->view('pages/edit_secretary', $data);
        $this->load->view('templates/footer');
    }

    // --- THE FIX IS HERE ---
    public function update($id) {
        // Set validation rules
        $this->form_validation->set_rules('account_name', 'Full Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('brgy_ID', 'Barangay', 'required|integer');
        // Password is not required, only validated if a value is entered
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[8]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            $this->edit($id); // Reload the edit form with errors
            return;
        }

        // Prepare data for the UserModel
        $update_data = [
            'account_name' => $this->input->post('account_name'),
            'username'     => $this->input->post('username'),
            'brgy_ID'      => $this->input->post('brgy_ID'),
        ];

        // Only add the password to the update array if the user entered a new one
        if ($this->input->post('password')) {
            $update_data['password'] = $this->input->post('password');
        }

        if ($this->UserModel->update_user($id, $update_data)) {
            $this->LogModel->log_activity($this->session->userdata('user_id'), 'Update Secretary ID ' . $id);
            $this->session->set_flashdata('success_message', 'Secretary updated successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Error updating secretary.');
        }
        redirect('secretaries');
    }

    // --- AND HERE ---
    public function delete($id) {
        if ($this->UserModel->delete_user($id)) {
            $this->LogModel->log_activity($this->session->userdata('user_id'), 'Delete Secretary ID ' . $id);
            $this->session->set_flashdata('success_message', 'Secretary deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Error deleting secretary.');
        }
        redirect('secretaries');
    }

    // AJAX endpoint for server-side DataTables
    public function get_secretary_data() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $list = $this->UserModel->get_secretaries_serverside();
        $data = [];
        foreach ($list['data'] as $secretary) {
            $row = [];
            $row[] = $secretary->user_ID;
            $row[] = html_escape($secretary->account_name);
            $row[] = html_escape($secretary->username);
            $row[] = html_escape($secretary->brgy_name);
            $row[] = ucfirst(htmlspecialchars($secretary->user_status));
            $edit_url = site_url('secretaries/edit/' . $secretary->user_ID);
            $delete_url = site_url('secretaries/delete/' . $secretary->user_ID);
            $row[] = '<a href="' . $edit_url . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a> ' .
                     '<a href="' . $delete_url . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure?\');"><i class="fas fa-trash"></i></a>';
            $data[] = $row;
        }
        $output = ["draw" => $list['draw'], "recordsTotal" => $list['recordsTotal'], "recordsFiltered" => $list['recordsFiltered'], "data" => $data];
        echo json_encode($output);
    }
}