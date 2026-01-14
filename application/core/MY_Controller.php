<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // This is the master security check.
        // It runs before any function in any controller that extends this class.
        if (!$this->session->userdata('is_logged_in')) {
            // If the session variable 'is_logged_in' is not found or is false,
            // set a warning message and redirect the user to the 'login' route.
            $this->session->set_flashdata('login_error', 'You must be logged in to access that page.');
            redirect('login');
        }
    }
}


class Admin_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // This is the second layer of security for admin-only sections.
        if ($this->session->userdata('user_type') != 'admin') {
            // If the logged-in user is not an 'admin', send them away.
            // Redirecting to their dashboard is a safe default.
            redirect('dashboard');
        }
    }
}