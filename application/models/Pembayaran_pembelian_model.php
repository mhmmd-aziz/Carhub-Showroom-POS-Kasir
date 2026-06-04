<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_pembelian_model extends CI_Model {

    public function get_all() {
        $this->db->select('pembayaran_pembelian.*, pembelian.tgl_pembelian, supplier.nama_supplier, mobil.nama_mobil');
        $this->db->from('pembayaran_pembelian');
        $this->db->join('pembelian', 'pembelian.id_pembelian = pembayaran_pembelian.id_pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier');
        $this->db->join('mobil', 'mobil.id_mobil = pembelian.id_mobil');
        $this->db->order_by('pembayaran_pembelian.id_pembayaran', 'DESC');
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        $this->db->insert('pembayaran_pembelian', $data);
        return $this->db->insert_id();
    }
}
