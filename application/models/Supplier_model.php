<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {

    public function get_all() {
        $this->db->where('deleted_at', NULL);
        $this->db->order_by('id_supplier', 'DESC');
        return $this->db->get('supplier')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('supplier', ['id_supplier' => $id, 'deleted_at' => NULL])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('supplier', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', $data);
    }

    public function delete($id) {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
