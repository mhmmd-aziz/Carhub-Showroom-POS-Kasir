<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_mobil_model extends CI_Model {

    public function get_all() {
        $this->db->select('penyerahan_mobil.*, penjualan.total_bayaran, pemesanan.tgl_pesan, customer.nama as nama_customer, mobil.nama_mobil, mobil.no_polisi');
        $this->db->from('penyerahan_mobil');
        $this->db->join('penjualan', 'penjualan.id_penjualan = penyerahan_mobil.id_penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->order_by('penyerahan_mobil.id_penyerahan', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Cek apakah sudah ada penyerahan untuk penjualan tertentu.
     * Digunakan untuk mencegah duplikasi penyerahan.
     */
    public function get_by_penjualan($id_penjualan) {
        return $this->db->get_where('penyerahan_mobil', ['id_penjualan' => $id_penjualan])->row_array();
    }

    public function insert($data) {
        $this->db->insert('penyerahan_mobil', $data);
        return $this->db->insert_id();
    }
}
