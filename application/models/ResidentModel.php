<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ResidentModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_resident($data)
    {
        $this->db->insert('residents', $data);
        return $this->db->insert_id();
    }

    public function get_residents($brgy_ID = null)
    {
        $this->db->select('r.*, b.brgy_name');
        $this->db->from('residents r');
        $this->db->join('barangay b', 'r.brgy_ID = b.brgy_ID', 'left');

        // If a specific barangay ID is provided, filter the results
        if ($brgy_ID !== null) {
            $this->db->where('r.brgy_ID', $brgy_ID);
        }

        return $this->db->get()->result();
    }

    public function get_resident_by_id($id)
    {
        return $this->db->get_where('residents', ['resident_ID' => $id])->row();
    }

    public function update_resident($id, $data)
    {
        $this->db->where('resident_ID', $id);
        return $this->db->update('residents', $data);
    }

    public function delete_resident($id)
    {
        $this->db->where('resident_ID', $id);
        return $this->db->delete('residents');
    }

    public function get_families($familyName = '', $purok = '')
    {
        $this->db->select('last_name, first_name, gender, occupation, street, purok');
        $this->db->from('residents');
        if (!empty($familyName)) {
            $this->db->like('last_name', $familyName);
        }
        if (!empty($purok)) {
            $this->db->like('purok', $purok);
        }
        return $this->db->get()->result();
    }
    public function get_residents_serverside($brgy_ID = null)
    {
        // Columns that can be searched and ordered
        $column_order = ['resident_ID', 'first_name', 'brgy_name', 'purok', 'resident_role', 'contact_number'];
        $column_search = ['first_name', 'middle_name', 'last_name', 'brgy_name', 'purok', 'contact_number', 'street', 'occupation'];
        $order = ['resident_ID' => 'asc']; // Default order

        // Base query with ALL necessary columns
        $this->db->select('r.resident_ID, r.first_name, r.middle_name, r.last_name, b.brgy_name, r.purok, r.resident_role, r.contact_number, r.gender, r.street, r.occupation');
        $this->db->from('residents r');
        $this->db->join('barangay b', 'r.brgy_ID = b.brgy_ID', 'left');

        // Data scoping for secretaries
        if ($brgy_ID !== null) {
            $this->db->where('r.brgy_ID', $brgy_ID);
        }

        // --- SEARCHING ---
        $searchValue = $this->input->post('search')['value'];
        if ($searchValue) {
            $this->db->group_start();
            foreach ($column_search as $i => $item) {
                if ($i === 0) {
                    $this->db->like($item, $searchValue);
                } else {
                    $this->db->or_like($item, $searchValue);
                }
            }
            $this->db->group_end();
        }

        // --- ORDERING ---
        if ($this->input->post('order')) {
            $order_column_index = $this->input->post('order')['0']['column'];
            $order_dir = $this->input->post('order')['0']['dir'];
            $this->db->order_by($column_order[$order_column_index], $order_dir);
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // --- PAGINATION ---
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        $query = $this->db->get();
        $data = $query->result();

        // --- GET TOTAL COUNTS ---
        $this->db->from('residents');
        if ($brgy_ID !== null)
            $this->db->where('brgy_ID', $brgy_ID);
        $totalRecords = $this->db->count_all_results();

        $this->db->select('r.resident_ID');
        $this->db->from('residents r');
        $this->db->join('barangay b', 'r.brgy_ID = b.brgy_ID', 'left');
        if ($brgy_ID !== null)
            $this->db->where('r.brgy_ID', $brgy_ID);
        if ($searchValue) {
            $this->db->group_start();
            foreach ($column_search as $i => $item) {
                if ($i === 0)
                    $this->db->like($item, $searchValue);
                else
                    $this->db->or_like($item, $searchValue);
            }
            $this->db->group_end();
        }
        $totalFiltered = $this->db->count_all_results();

        return [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];
    }
}