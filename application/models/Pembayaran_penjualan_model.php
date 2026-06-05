<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_penjualan_model extends CI_Model {

    /**
     * Ambil semua data pembayaran, dengan opsional filter berdasarkan jenis_pembayaran.
     * 
     * @param string $filter 'semua' | 'tanda_jadi' | 'dp' | 'pelunasan' | 'batal'
     */
    public function get_all($filter = 'semua') {
        $this->db->select('pembayaran_penjualan.*, pemesanan.tgl_pesan, pemesanan.status_pemesanan as status_order, customer.nama as nama_customer, mobil.nama_mobil, mobil.merek');
        $this->db->from('pembayaran_penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left');
        $this->db->join('customer',  'customer.id_customer = pemesanan.id_customer', 'left');
        $this->db->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil', 'left');

        // Filter berdasarkan jenis pembayaran
        if ($filter === 'tanda_jadi') {
            $this->db->where('pembayaran_penjualan.jenis_pembayaran', 'tanda_jadi');
        } elseif ($filter === 'dp') {
            $this->db->where('pembayaran_penjualan.jenis_pembayaran', 'dp');
        } elseif ($filter === 'pelunasan') {
            $this->db->where('pembayaran_penjualan.jenis_pembayaran', 'pelunasan');
        } elseif ($filter === 'batal') {
            // Pembatalan: pemesanan dengan status batal atau hangus
            $this->db->where_in('pemesanan.status_pemesanan', ['batal', 'hangus']);
        }
        // 'semua' → tidak ada filter tambahan

        $this->db->order_by('pembayaran_penjualan.id_pembayaran', 'DESC');
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        $this->db->insert('pembayaran_penjualan', $data);
        return $this->db->insert_id();
    }
}
