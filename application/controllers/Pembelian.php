<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Mobil_model');
    }

    public function index() {
        $data['title'] = 'Data Pembelian';
        $data['pembelian'] = $this->Pembelian_model->get_all();
        $this->render_page('pembelian/index', $data);
    }

    public function tambah() {
        $this->check_admin_access();
        $data['title'] = 'Tambah Transaksi Pembelian';
        $data['suppliers'] = $this->Supplier_model->get_all();

        // Tampilkan semua mobil (pembelian = mobil baru dari supplier, tidak dibatasi status)
        // Mobil baru bisa didaftarkan ke katalog lebih dulu, lalu dibeli via menu ini
        $data['mobils'] = $this->Mobil_model->get_all();

        $this->render_page('pembelian/tambah', $data);
    }

    /**
     * Simpan transaksi pembelian baru.
     * FIX BUG-005: Validasi server-side menggunakan CI Form Validation.
     */
    public function store() {
        $this->check_admin_access();

        // === SERVER-SIDE VALIDATION ===
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_supplier', 'Supplier', 'required|integer');
        $this->form_validation->set_rules('id_mobil', 'Mobil', 'required|integer');
        $this->form_validation->set_rules('tgl_pembelian', 'Tanggal Pembelian', 'required');
        $this->form_validation->set_rules('harga_beli_beli', 'Harga Beli', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Data tidak valid: ' . validation_errors(' ', ' | '));
            redirect('pembelian/tambah');
            return;
        }

        // Bersihkan format rupiah dari input harga
        $harga_raw = $this->input->post('harga_beli_beli', true);
        $harga = str_replace(['.', ','], ['', '.'], $harga_raw);

        // Validasi harga > 0
        if (!is_numeric($harga) || (float)$harga <= 0) {
            $this->session->set_flashdata('error', 'Harga beli harus lebih dari 0.');
            redirect('pembelian/tambah');
            return;
        }

        $data = [
            'id_supplier'        => $this->input->post('id_supplier', true),
            'id_mobil'           => $this->input->post('id_mobil', true),
            'id_user'            => $this->session->userdata('id_user'),
            'tgl_pembelian'      => $this->input->post('tgl_pembelian', true),
            'harga_beli_beli'    => $harga,
            'status_pembayaran'  => 'menunggu',
            'keterangan_kondisi' => $this->input->post('keterangan_kondisi', true)
        ];

        $this->Pembelian_model->insert($data);
        $this->session->set_flashdata('success', 'Transaksi pembelian berhasil dibuat. Silakan lakukan pembayaran ke supplier.');
        redirect('pembelian');
    }
}
