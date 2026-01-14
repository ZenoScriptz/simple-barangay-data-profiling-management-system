<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * AuthController
 *
 * This controller is responsible for all public authentication tasks:
 * - Showing the login form.
 * - Processing login attempts.
 * - Handling user logout.
 *
 * It extends the base CI_Controller because it must be accessible to users
 * who are not yet logged in.
 */
class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load the models and libraries needed for authentication
        $this->load->model('UserModel');
        $this->load->model('LogModel');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('url');
    }

    /**
     * Shows the login page or processes the login form submission.
     */
    public function login() {
        // If the user is already logged in, they don't need to see the login page again.
        // Redirect them straight to the main application dashboard.
        if ($this->session->userdata('is_logged_in')) {
            redirect('dashboard');
        }

        // Set validation rules for the login form
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        // Check if the form was submitted and validated
        if ($this->form_validation->run() == FALSE) {
            // If validation fails (or it's the first time loading the page), show the login view.
            $this->load->view('pages/brgylogin');
        } else {
            // Validation passed, try to log the user in.
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Use the UserModel to check the credentials
            $user = $this->UserModel->validate_user($username, $password);

            if ($user) {
                // SUCCESS: User is valid. Create the session.
                $session_data = [
                    'user_id'      => $user['user_ID'],
                    'username'     => $user['username'],
                    'account_name' => $user['account_name'],
                    'user_type'    => strtolower($user['user_type']),
                    'brgy_ID'      => $user['brgy_ID'],
                    'is_logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                
                // Log the successful login action
                $this->LogModel->log_activity($user['user_ID'], 'Login');
                
                // Send the user to the main dashboard.
                redirect('dashboard');
            } else {
                // FAILURE: Invalid credentials. Set an error message and show the login page again.
                $this->session->set_flashdata('login_error', 'Invalid username or password.');
                redirect('login');
            }
        }
    }

    /**
     * Logs the user out by destroying the session.
     */
    public function logout() {
        if ($this->session->userdata('is_logged_in')) {
            // Log the logout action before destroying the session
            $this->LogModel->log_activity($this->session->userdata('user_id'), 'Logout');
        }
        
        // Destroy all session data
        $this->session->sess_destroy();
        
        // Redirect to the login page.
        redirect('login');
    }
}