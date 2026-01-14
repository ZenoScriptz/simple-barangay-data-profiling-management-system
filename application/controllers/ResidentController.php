<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResidentController extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Security Check: Ensure user is logged in for all resident management functions
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Load necessary models for this controller
        $this->load->model('ResidentModel');
        $this->load->model('BarangayModel');
        $this->load->model('LogModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    // Default function: list all residents
    public function index() {
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Residents');
        $this->load->view('templates/header');
        $this->load->view('pages/residentmng');
        $this->load->view('templates/footer');
    }

    // Show the form to add a new resident
    public function add() {
        $data['barangays'] = $this->BarangayModel->get_barangays();
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Add Resident Form');
        $this->load->view('templates/header');
        $this->load->view('pages/residentmngadd', $data);
        $this->load->view('templates/footer');
    }
    

    // Process the form submission for creating a new resident
    public function create() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('brgy_ID', 'Barangay', 'required|integer');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('residents/add'); // Redirect to the add form
        } else {
            $data = [
                'first_name' => $this->input->post('first_name'), 'middle_name' => $this->input->post('middle_name'),
                'last_name' => $this->input->post('last_name'), 'birth_date' => $this->input->post('birth_date'),
                'gender' => $this->input->post('gender'), 'religion' => $this->input->post('religion'),
                'isStudying' => $this->input->post('isStudying') ? 1 : 0, 'isVoter' => $this->input->post('isVoter') ? 1 : 0,
                'contact_number' => $this->input->post('contact_number'), 'occupation' => $this->input->post('occupation'),
                'resident_type' => $this->input->post('resident_type'), 'resident_role' => $this->input->post('resident_role'),
                'street' => $this->input->post('street'), 'purok' => $this->input->post('purok'),
                'brgy_ID' => $this->input->post('brgy_ID')
            ];
            $resident_id = $this->ResidentModel->insert_resident($data);
            if ($resident_id) {
                $this->LogModel->log_activity($this->session->userdata('user_id'), 'Add Resident ID ' . $resident_id);
                $this->session->set_flashdata('message', 'Resident added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add resident.');
            }
            redirect('residents'); // Redirect to the main list
        }
    }

    // Show the form to edit an existing resident
    public function edit($id) {
        $data['resident'] = $this->ResidentModel->get_resident_by_id($id);
        $data['barangays'] = $this->BarangayModel->get_barangays();
        if (empty($data['resident'])) {
            redirect('residents');
        }
        $this->load->view('templates/header');
        $this->load->view('pages/residentmngedit', $data);
        $this->load->view('templates/footer');
    }

    // Process the form submission for updating a resident
    public function update($id) {
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('brgy_ID', 'Barangay', 'required|integer');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $this->edit($id); // Reload the edit form with errors
            return;
        }
        $update_data = [
            'first_name' => $this->input->post('first_name'), 'middle_name' => $this->input->post('middle_name'),
            'last_name' => $this->input->post('last_name'), 'birth_date' => $this->input->post('birth_date'),
            'gender' => $this->input->post('gender'), 'religion' => $this->input->post('religion'),
            'isStudying' => $this->input->post('isStudying') ? 1 : 0, 'isVoter' => $this->input->post('isVoter') ? 1 : 0,
            'contact_number' => $this->input->post('contact_number'), 'occupation' => $this->input->post('occupation'),
            'resident_type' => $this->input->post('resident_type'), 'resident_role' => $this->input->post('resident_role'),
            'street' => $this->input->post('street'), 'purok' => $this->input->post('purok'),
            'brgy_ID' => $this->input->post('brgy_ID')
        ];
        if ($this->ResidentModel->update_resident($id, $update_data)) {
            $this->LogModel->log_activity($this->session->userdata('user_id'), 'Update Resident ID ' . $id);
            $this->session->set_flashdata('message', 'Resident updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update resident.');
        }
        redirect('residents');
    }

    // Delete a resident
    public function delete($id) {
        if ($this->ResidentModel->delete_resident($id)) {
            $this->LogModel->log_activity($this->session->userdata('user_id'), 'Delete Resident ID ' . $id);
            $this->session->set_flashdata('message', 'Resident deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete resident.');
        }
        redirect('residents');
    }

    

    // AJAX endpoint for server-side DataTables
    public function get_resident_data() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $user_type = $this->session->userdata('user_type');
        $brgy_ID = ($user_type == 'secretary') ? $this->session->userdata('brgy_ID') : null;
        $list = $this->ResidentModel->get_residents_serverside($brgy_ID);
        $data = [];
        foreach ($list['data'] as $resident) {
            $row = [];
            $row[] = $resident->resident_ID;
            $row[] = html_escape(trim($resident->first_name . ' ' . $resident->middle_name . ' ' . $resident->last_name));
            $row[] = html_escape($resident->brgy_name);
            $row[] = html_escape($resident->purok);
            $row[] = html_escape($resident->resident_role);
            $row[] = html_escape($resident->contact_number);
            $edit_url = site_url('residents/edit/' . $resident->resident_ID);
            $delete_url = site_url('residents/delete/' . $resident->resident_ID);
            $row[] = '<a href="' . $edit_url . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="' . $delete_url . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i></a>';
            $row[] = html_escape($resident->gender);
            $row[] = html_escape($resident->street);
            $row[] = html_escape($resident->occupation);
            $data[] = $row;
        }
        $output = ["draw" => $list['draw'], "recordsTotal" => $list['recordsTotal'], "recordsFiltered" => $list['recordsFiltered'], "data" => $data];
        echo json_encode($output);
    }
}