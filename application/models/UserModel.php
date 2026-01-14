<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function validate_user($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            // Check the provided password against the hashed password from the database
            if (password_verify($password, $user['password'])) {
                return $user; // Passwords match
            }
        }
        return false; // User not found or password incorrect
    }

    public function get_secretaries()
    {
        $this->db->select('users.user_ID, users.account_name, users.username, users.user_status, barangay.brgy_name');
        $this->db->from('users');
        $this->db->join('barangay', 'users.brgy_ID = barangay.brgy_ID', 'left');
        $this->db->where('users.user_type', 'SECRETARY');
        return $this->db->get()->result();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('users.*, barangay.brgy_name');
        $this->db->from('users');
        $this->db->join('barangay', 'users.brgy_ID = barangay.brgy_ID', 'left');
        $this->db->where('users.user_ID', $id);
        return $this->db->get()->row();
    }

    public function insert_user($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update_user($user_id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $this->db->where('user_ID', $user_id);
        return $this->db->update('users', $data);
    }

    public function delete_user($user_id)
    {
        $this->db->where('user_ID', $user_id);
        return $this->db->delete('users');
    }

        public function get_secretaries_serverside()
    {
        $column_order = ['user_ID', 'account_name', 'username', 'brgy_name', 'user_status'];
        $column_search = ['account_name', 'username', 'brgy_name'];
        $order = ['user_ID' => 'asc'];

        $this->db->select('users.user_ID, users.account_name, users.username, users.user_status, barangay.brgy_name');
        $this->db->from('users');
        $this->db->join('barangay', 'users.brgy_ID = barangay.brgy_ID', 'left');
        $this->db->where('users.user_type', 'SECRETARY');

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
        $this->db->from('users');
        $this->db->where('user_type', 'SECRETARY');
        $totalRecords = $this->db->count_all_results();

        $this->db->select('users.user_ID');
        $this->db->from('users');
        $this->db->join('barangay', 'users.brgy_ID = barangay.brgy_ID', 'left');
        $this->db->where('user_type', 'SECRETARY');
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