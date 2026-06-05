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
        $data['mobils']    = $this->Mobil_model->get_all();
        $this->render_page('pembelian/tambah', $data);
    }

    /**
     * Simpan transaksi pembelian baru.
     * 
     * Mendukung dua mode untuk Supplier dan Mobil:
     *  - mode 'pilih' : pilih dari dropdown (data lama)
     *  - mode 'baru'  : input data baru langsung, otomatis tersimpan ke master
     * 
     * Setelah pembelian tersimpan → redirect ke Pembayaran Pembelian.
     */
    public function store() {
        $this->check_admin_access();
        $this->load->library('form_validation');

        $mode_supplier = $this->input->post('mode_supplier'); // 'pilih' atau 'baru'
        $mode_mobil    = $this->input->post('mode_mobil');    // 'pilih' atau 'baru'

        // ── 1. Proses Supplier ────────────────────────────────────
        if ($mode_supplier === 'baru') {
            // Validasi field supplier baru
            $this->form_validation->set_rules('nama_supplier_baru', 'Nama Supplier', 'required|trim');
            $this->form_validation->set_rules('no_telp_supplier_baru', 'No. Telp Supplier', 'required|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', 'Data supplier tidak valid: ' . validation_errors(' ', ' | '));
                redirect('pembelian/tambah');
                return;
            }

            // Insert supplier baru ke master
            $data_supplier = [
                'nama_supplier' => $this->input->post('nama_supplier_baru', true),
                'alamat'        => $this->input->post('alamat_supplier_baru', true),
                'no_telp'       => $this->input->post('no_telp_supplier_baru', true),
                'email'         => $this->input->post('email_supplier_baru', true),
                'keterangan'    => $this->input->post('keterangan_supplier_baru', true),
            ];
            $this->Supplier_model->insert($data_supplier);
            $id_supplier = $this->db->insert_id();

        } else {
            // Pilih supplier dari dropdown
            $id_supplier = $this->input->post('id_supplier', true);
            if (empty($id_supplier) || !is_numeric($id_supplier)) {
                $this->session->set_flashdata('error', 'Supplier harus dipilih.');
                redirect('pembelian/tambah');
                return;
            }
        }

        // ── 2. Proses Mobil ───────────────────────────────────────
        if ($mode_mobil === 'baru') {
            // Validasi field mobil baru
            $this->form_validation->set_rules('nama_mobil_baru',  'Nama Mobil', 'required|trim');
            $this->form_validation->set_rules('merek_baru',       'Merek',      'required|trim');
            $this->form_validation->set_rules('harga_jual_baru',  'Harga Jual', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', 'Data mobil tidak valid: ' . validation_errors(' ', ' | '));
                redirect('pembelian/tambah');
                return;
            }

            $harga_beli_raw = $this->input->post('harga_beli_beli', true);
            $harga_beli     = (float)str_replace(['.', ','], ['', '.'], $harga_beli_raw);

            $harga_jual_raw = $this->input->post('harga_jual_baru', true);
            $harga_jual     = (float)str_replace(['.', ','], ['', '.'], $harga_jual_raw);

            // Insert mobil baru ke master. Stok = 0 dulu (akan bertambah setelah pembayaran pembelian)
            $data_mobil = [
                'id_supplier' => $id_supplier,
                'nama_mobil'  => $this->input->post('nama_mobil_baru', true),
                'merek'       => $this->input->post('merek_baru', true),
                'warna'       => $this->input->post('warna_baru', true),
                'tipe'        => $this->input->post('tipe_baru', true),
                'tahun'       => $this->input->post('tahun_baru', true),
                'no_polisi'   => $this->input->post('no_polisi_baru', true),
                'no_rangka'   => $this->input->post('no_rangka_baru', true),
                'no_mesin'    => $this->input->post('no_mesin_baru', true),
                'harga_beli'  => $harga_beli,
                'harga_jual'  => $harga_jual,
                'status_stok' => 'tersedia',
                'stok'        => 0, // Stok akan bertambah setelah pembayaran pembelian selesai
            ];
            $this->Mobil_model->insert($data_mobil);
            $id_mobil = $this->db->insert_id();

        } else {
            // Pilih mobil dari dropdown
            $id_mobil = $this->input->post('id_mobil', true);
            if (empty($id_mobil) || !is_numeric($id_mobil)) {
                $this->session->set_flashdata('error', 'Mobil harus dipilih.');
                redirect('pembelian/tambah');
                return;
            }

            // Harga beli dari input
            $harga_beli_raw = $this->input->post('harga_beli_beli', true);
            $harga_beli     = (float)str_replace(['.', ','], ['', '.'], $harga_beli_raw);
        }

        // ── 3. Validasi harga beli ────────────────────────────────
        $harga_beli_raw = $this->input->post('harga_beli_beli', true);
        $harga_beli_val = (float)str_replace(['.', ','], ['', '.'], $harga_beli_raw);

        if (!is_numeric($harga_beli_val) || $harga_beli_val <= 0) {
            $this->session->set_flashdata('error', 'Harga beli harus lebih dari 0.');
            redirect('pembelian/tambah');
            return;
        }

        // ── 4. Validasi tanggal ───────────────────────────────────
        $tgl_pembelian = $this->input->post('tgl_pembelian', true);
        if (empty($tgl_pembelian)) {
            $this->session->set_flashdata('error', 'Tanggal pembelian harus diisi.');
            redirect('pembelian/tambah');
            return;
        }

        // ── 5. Simpan transaksi pembelian ─────────────────────────
        $data_pembelian = [
            'id_supplier'        => $id_supplier,
            'id_mobil'           => $id_mobil,
            'id_user'            => $this->session->userdata('id_user'),
            'tgl_pembelian'      => $tgl_pembelian,
            'harga_beli_beli'    => $harga_beli_val,
            'status_pembayaran'  => 'menunggu',
            'keterangan_kondisi' => $this->input->post('keterangan_kondisi', true)
        ];

        $this->Pembelian_model->insert($data_pembelian);

        $this->session->set_flashdata('success', 'Transaksi pembelian berhasil dibuat. Silakan selesaikan pembayaran ke supplier.');
        
        // Redirect langsung ke menu Pembayaran Pembelian
        redirect('pembayaran_pembelian');
    }
}
