<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobil_model extends CI_Model {

    public function get_all() {
        $this->db->select('mobil.*, supplier.nama_supplier');
        $this->db->from('mobil');
        $this->db->join('supplier', 'supplier.id_supplier = mobil.id_supplier', 'left');
        $this->db->where('mobil.deleted_at', NULL);
        $this->db->order_by('mobil.id_mobil', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('mobil', ['id_mobil' => $id, 'deleted_at' => NULL])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('mobil', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_mobil', $id);
        return $this->db->update('mobil', $data);
    }

    public function delete($id) {
        $this->db->where('id_mobil', $id);
        return $this->db->update('mobil', ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
