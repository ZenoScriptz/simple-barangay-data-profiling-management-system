<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function log_activity($user_id, $action_type) {
        $data = [
            'user_ID'     => $user_id,
            'action_type' => $action_type
        ];
        return $this->db->insert('activity_logs', $data);
    }

    public function get_activity_logs() {
        $this->db->select('a.*, u.username, u.user_type, u.account_name');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'a.user_ID = u.user_ID', 'left');
        $this->db->order_by('a.created_at', 'DESC');
        return $this->db->get()->result();
    }
    public function get_logs_serverside()
    {
        // THE FIX: 'u.account_name' is the correct column name.
        $column_order = ['log_ID', 'account_name', 'action_type', 'created_at'];
        $column_search = ['account_name', 'username', 'action_type']; // 'account_name' is correct here too.
        $order = ['log_ID' => 'desc'];

        $this->db->select('a.log_ID, u.account_name, a.action_type, a.created_at');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'a.user_ID = u.user_ID', 'left');

        // --- SEARCHING ---
        $searchValue = $this->input->post('search')['value'];
        if ($searchValue) {
            $this->db->group_start();
            foreach ($column_search as $i => $item) {
                if ($i === 0) $this->db->like($item, $searchValue);
                else $this->db->or_like($item, $searchValue);
            }
            $this->db->group_end();
        }

        // --- ORDERING ---
        if ($this->input->post('order')) {
            $order_column_index = $this->input->post('order')['0']['column'];
            $order_dir = $this->input->post('order')['0']['dir'];
            $this->db->order_by($column_order[$order_column_index], $order_dir);
        } else {
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // --- PAGINATION ---
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        $query = $this->db->get();
        $data = $query->result();

        // --- GET TOTAL COUNTS ---
        $this->db->from('activity_logs');
        $totalRecords = $this->db->count_all_results();
        
        $this->db->select('a.log_ID');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'a.user_ID = u.user_ID', 'left');
        if ($searchValue) {
            $this->db->group_start();
            foreach ($column_search as $i => $item) {
                if ($i === 0) $this->db->like($item, $searchValue);
                else $this->db->or_like($item, $searchValue);
            }
            $this->db->group_end();
        }
        $totalFiltered = $this->db->count_all_results();

        return ["draw" => intval($this->input->post('draw')), "recordsTotal" => intval($totalRecords), "recordsFiltered" => intval($totalFiltered), "data" => $data];
    }
}