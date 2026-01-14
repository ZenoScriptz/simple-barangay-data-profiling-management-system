<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupportModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_report($data) {
        return $this->db->insert('contact_support', $data);
    }

    public function get_sec_reports() {
        $this->db->select('u.username, u.account_name, b.brgy_name, cs.message_content, cs.created_at');
        $this->db->from('contact_support cs');
        $this->db->join('users u', 'cs.user_ID = u.user_ID', 'left');
        $this->db->join('barangay b', 'u.brgy_ID = b.brgy_ID', 'left');
        $this->db->order_by('cs.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_reports_serverside()
{
    // ... (Build the full server-side query here, similar to get_residents_serverside)
    // You would select from 'contact_support', join 'users', join 'barangay'
    // And handle searching, ordering, and pagination.
    // For now, let's just get the data to prove it works.

    $this->db->select('u.username, b.brgy_name, cs.created_at, cs.message_content');
    $this->db->from('contact_support cs');
    $this->db->join('users u', 'cs.user_ID = u.user_ID', 'left');
    $this->db->join('barangay b', 'u.brgy_ID = b.brgy_ID', 'left');
    
    // This is a simplified version. A full server-side implementation would be more complex.
    $query = $this->db->get();
    $results = $query->result_array();

    return [
        "draw" => intval($this->input->post('draw')),
        "recordsTotal" => count($results),
        "recordsFiltered" => count($results),
        "data" => $results
    ];
}
}