<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_penjualan_model extends CI_Model {

    public function get_all() {
        $this->db->select('pembayaran_penjualan.*, pemesanan.tgl_pesan, customer.nama as nama_customer, mobil.nama_mobil');
        $this->db->from('pembayaran_penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil', 'left');
        $this->db->order_by('pembayaran_penjualan.id_pembayaran', 'DESC');
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        $this->db->insert('pembayaran_penjualan', $data);
        return $this->db->insert_id();
    }
}
