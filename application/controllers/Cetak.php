<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan sudah login
        if (!$this->session->userdata('id_user')) {
            redirect('auth');
        }
        $this->load->model('Pembelian_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Pemesanan_model');
        $this->load->model('Penyerahan_mobil_model');
        $this->load->model('Laporan_model');
    }

    public function faktur_pembelian($id) {
        $data['pembelian'] = $this->Pembelian_model->get_by_id($id);
        if (!$data['pembelian']) {
            show_404();
        }

        // Ambil data pembayaran jika ada
        $this->db->where('id_pembelian', $id);
        $data['pembayaran'] = $this->db->get('pembayaran_pembelian')->row_array();

        $this->load->view('cetak/faktur_pembelian', $data);
    }

    public function faktur_penjualan($id) {
        $data['penjualan'] = $this->Penjualan_model->get_by_id($id);
        if (!$data['penjualan']) {
            show_404();
        }

        // Ambil histori pembayaran customer
        $this->db->where('id_pemesanan', $data['penjualan']['id_pemesanan']);
        $this->db->order_by('id_pembayaran', 'ASC');
        $data['pembayaran'] = $this->db->get('pembayaran_penjualan')->result_array();

        $this->load->view('cetak/faktur_penjualan', $data);
    }

    public function faktur_pemesanan($id_pemesanan) {
        // Ambil data pemesanan
        $this->db->select('pemesanan.*, customer.nama as nama_customer, customer.no_telp, customer.alamat, mobil.nama_mobil, mobil.merek, mobil.no_polisi, mobil.no_rangka, mobil.no_mesin, mobil.warna, mobil.tipe, mobil.tahun');
        $this->db->from('pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->where('pemesanan.id_pemesanan', $id_pemesanan);
        
        $data['pemesanan'] = $this->db->get()->row_array();

        if (!$data['pemesanan']) {
            show_404();
        }

        $this->load->view('cetak/faktur_pemesanan', $data);
    }

    /**
     * Cetak kwitansi pembayaran individual.
     * FIX BUG-011: Tambah endpoint cetak untuk tanda jadi, kwitansi DP, kwitansi pelunasan.
     * Parameter: $id = id_pembayaran di tabel pembayaran_penjualan
     */
    public function kwitansi_pembayaran($id_pembayaran) {
        // Ambil data pembayaran dengan join ke pemesanan, customer, mobil
        $this->db->select('pp.*, ps.id_mobil, ps.harga_jual_snapshot, ps.tgl_pesan, ps.tgl_jatuh_tempo, c.nama as nama_customer, c.no_telp, c.no_ktp, m.nama_mobil, m.merek, m.no_polisi, m.tipe, m.tahun, m.warna');
        $this->db->from('pembayaran_penjualan pp');
        $this->db->join('pemesanan ps', 'ps.id_pemesanan = pp.id_pemesanan');
        $this->db->join('customer c', 'c.id_customer = ps.id_customer');
        $this->db->join('mobil m', 'm.id_mobil = ps.id_mobil');
        $this->db->where('pp.id_pembayaran', $id_pembayaran);

        $data['pembayaran'] = $this->db->get()->row_array();

        if (!$data['pembayaran']) {
            show_404();
        }

        $this->load->view('cetak/kwitansi_pembayaran', $data);
    }

    public function surat_jalan($id) {
        // Ambil penyerahan berdasarkan id_penyerahan
        $this->db->select('penyerahan_mobil.*, penjualan.total_bayaran, pemesanan.tgl_pesan, customer.nama as nama_customer, customer.no_telp, customer.alamat, mobil.nama_mobil, mobil.merek, mobil.no_polisi, mobil.no_rangka, mobil.no_mesin, mobil.warna, mobil.tipe, mobil.tahun');
        $this->db->from('penyerahan_mobil');
        $this->db->join('penjualan', 'penjualan.id_penjualan = penyerahan_mobil.id_penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        $this->db->where('penyerahan_mobil.id_penyerahan', $id);

        $data['penyerahan'] = $this->db->get()->row_array();

        if (!$data['penyerahan']) {
            show_404();
        }

        $this->load->view('cetak/surat_jalan', $data);
    }

    /**
     * Cetak laporan.
     * FIX BUG-009: Tambah jenis laporan 'pembayaran'.
     */
    public function laporan() {
        $jenis     = $this->input->get('jenis_laporan');
        $tgl_awal  = $this->input->get('tgl_awal');
        $tgl_akhir = $this->input->get('tgl_akhir');

        $data['jenis']     = $jenis;
        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;

        if ($jenis == 'penjualan') {
            $data['hasil'] = $this->Laporan_model->get_penjualan($tgl_awal, $tgl_akhir);
        } elseif ($jenis == 'pembelian') {
            $data['hasil'] = $this->Laporan_model->get_pembelian($tgl_awal, $tgl_akhir);
        } elseif ($jenis == 'pembayaran') {
            $data['hasil'] = $this->Laporan_model->get_pembayaran($tgl_awal, $tgl_akhir);
        } elseif ($jenis == 'stok') {
            $data['hasil'] = $this->Laporan_model->get_stok_mobil();
        } else {
            show_404();
        }

        $this->load->view('cetak/laporan', $data);
    }
}
