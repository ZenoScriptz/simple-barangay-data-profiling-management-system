<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barangay extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('ResidentModel');
        $this->load->model('BarangayModel');
        $this->load->model('LogModel');
        $this->load->model('SupportModel');
        $this->load->model('ReportModel');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('url');
    }


    // --- OTHER FEATURES & LOGS ---
    public function famreg()
    {
        $this->LogModel->log_activity($this->session->userdata('user_id'), 'View Family Lists Page');
        $this->load->view('templates/header');
        $this->load->view('pages/famreg');
        $this->load->view('templates/footer');
    }

   

  

}