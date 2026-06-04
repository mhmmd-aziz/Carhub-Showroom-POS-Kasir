<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemesanan_model extends CI_Model {

    /**
     * Ambil semua data pemesanan dengan join ke tabel relasi.
     * Kolom harga_jual_snapshot digunakan (bukan JOIN ke mobil)
     * untuk menghindari perubahan harga master mempengaruhi data historis.
     */
    public function get_all() {
        $this->db->select('pemesanan.*, customer.nama as nama_customer, mobil.nama_mobil, mobil.merek, mobil.no_polisi, pemesanan.harga_jual_snapshot as harga_jual');
        $this->db->from('pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->order_by('pemesanan.id_pemesanan', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('pemesanan.*, customer.nama as nama_customer, customer.no_telp, customer.no_ktp, mobil.nama_mobil, mobil.merek, mobil.no_polisi, mobil.harga_jual, pemesanan.harga_jual_snapshot');
        $this->db->from('pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->where('pemesanan.id_pemesanan', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Ambil semua pemesanan yang jatuh tempo DP-nya sudah lewat
     * dan status masih 'bukti_pesanan' (belum dibayar DP).
     */
    public function get_kadaluarsa() {
        $this->db->where('status_pemesanan', 'bukti_pesanan');
        $this->db->where('tgl_jatuh_tempo <', date('Y-m-d'));
        return $this->db->get('pemesanan')->result_array();
    }

    public function insert($data) {
        $this->db->insert('pemesanan', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id_pemesanan', $id);
        return $this->db->update('pemesanan', $data);
    }
}
