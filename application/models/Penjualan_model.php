<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {

    public function get_all() {
        $this->db->select('penjualan.*, pemesanan.tgl_pesan, customer.nama as nama_customer, mobil.nama_mobil, mobil.no_polisi');
        $this->db->from('penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->order_by('penjualan.id_penjualan', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('penjualan.*, pemesanan.id_mobil, pemesanan.tgl_pesan, pemesanan.harga_dp, pemesanan.nilai_tanda_jadi, customer.nama as nama_customer, mobil.nama_mobil, mobil.no_polisi, mobil.harga_jual');
        $this->db->from('penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->where('penjualan.id_penjualan', $id);
        return $this->db->get()->row_array();
    }

    public function get_by_pemesanan($id_pemesanan) {
        return $this->db->get_where('penjualan', ['id_pemesanan' => $id_pemesanan])->row_array();
    }

    public function insert($data) {
        $this->db->insert('penjualan', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id_penjualan', $id);
        return $this->db->update('penjualan', $data);
    }
}
