<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BarangayModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_barangays() {
        $this->db->select('brgy_ID, brgy_name');
        $this->db->from('barangay');
        $this->db->order_by('brgy_name', 'ASC');
        return $this->db->get()->result();
    }
        public function get_barangay_by_id($id)
    {
        return $this->db->get_where('barangay', ['brgy_ID' => $id])->row();
    }
}