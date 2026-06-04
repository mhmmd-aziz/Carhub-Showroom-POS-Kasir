<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function get_all() {
        $this->db->where('deleted_at', NULL);
        $this->db->order_by('id_customer', 'DESC');
        return $this->db->get('customer')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('customer', ['id_customer' => $id, 'deleted_at' => NULL])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('customer', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_customer', $id);
        return $this->db->update('customer', $data);
    }

    public function delete($id) {
        $this->db->where('id_customer', $id);
        return $this->db->update('customer', ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
