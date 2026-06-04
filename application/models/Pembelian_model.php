<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {

    public function get_all() {
        $this->db->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.merek, mobil.no_polisi, user.nama_lengkap as nama_admin');
        $this->db->from('pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left');
        $this->db->join('mobil', 'mobil.id_mobil = pembelian.id_mobil', 'left');
        $this->db->join('user', 'user.id_user = pembelian.id_user', 'left');
        $this->db->order_by('pembelian.id_pembelian', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.merek, mobil.no_polisi, user.nama_lengkap as nama_admin');
        $this->db->from('pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left');
        $this->db->join('mobil', 'mobil.id_mobil = pembelian.id_mobil', 'left');
        $this->db->join('user', 'user.id_user = pembelian.id_user', 'left');
        $this->db->where('pembelian.id_pembelian', $id);
        return $this->db->get()->row_array();
    }

    public function insert($data) {
        $this->db->insert('pembelian', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id_pembelian', $id);
        return $this->db->update('pembelian', $data);
    }
}
