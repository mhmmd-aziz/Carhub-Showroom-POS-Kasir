<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_mobil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Penyerahan_mobil_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Mobil_model');
    }

    public function index() {
        $data['title'] = 'Histori Penyerahan Mobil';
        $data['penyerahan'] = $this->Penyerahan_mobil_model->get_all();
        $this->render_page('penyerahan_mobil/index', $data);
    }

    public function serah($id_penjualan) {
        $this->check_admin_access();
        $data['title'] = 'Form Penyerahan Mobil';
        $penjualan = $this->Penjualan_model->get_by_id($id_penjualan);

        if (!$penjualan || $penjualan['status_pelunasan'] != 'lunas') {
            $this->session->set_flashdata('error', 'Transaksi belum lunas atau tidak valid.');
            redirect('penjualan');
            return;
        }

        // FIX BUG-008: Cek apakah sudah pernah ada penyerahan untuk penjualan ini
        $existing = $this->Penyerahan_mobil_model->get_by_penjualan($id_penjualan);
        if ($existing) {
            $this->session->set_flashdata('error', 'Penyerahan untuk transaksi ini sudah pernah dilakukan.');
            redirect('penyerahan_mobil');
            return;
        }

        $data['penjualan'] = $penjualan;
        $this->render_page('penyerahan_mobil/serah', $data);
    }

    /**
     * Proses penyerahan mobil ke customer.
     * FIX BUG-006: Status mobil di-set ke 'terjual' (ENUM sudah diperbaiki di DB).
     * FIX BUG-007: Guard stok agar tidak negatif.
     * FIX BUG-008: Double-check duplikasi penyerahan sebelum insert.
     */
    public function proses_serah() {
        $this->check_admin_access();
        $id_penjualan = $this->input->post('id_penjualan');
        $penjualan = $this->Penjualan_model->get_by_id($id_penjualan);

        if (!$penjualan) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan.');
            redirect('penjualan');
            return;
        }

        // FIX BUG-008: Cek duplikasi (double-check di proses juga)
        $existing = $this->Penyerahan_mobil_model->get_by_penjualan($id_penjualan);
        if ($existing) {
            $this->session->set_flashdata('error', 'Penyerahan untuk transaksi ini sudah pernah dilakukan.');
            redirect('penyerahan_mobil');
            return;
        }

        $data_serah = [
            'id_penjualan'     => $id_penjualan,
            'tgl_serah_unit'   => $this->input->post('tgl_serah_unit'),
            'tgl_serah_bpkb'   => $this->input->post('tgl_serah_bpkb'),
            'metode_serah'     => $this->input->post('metode_serah'),
            'nama_penerima'    => $this->input->post('nama_penerima'),
            'alamat_tujuan'    => $this->input->post('alamat_tujuan'),
            'status_penyerahan'=> 'dalam_proses', // Default: dalam proses, tunggu konfirmasi
        ];

        $this->Penyerahan_mobil_model->insert($data_serah);

        // Update status berkas di penjualan menjadi diserahkan
        $this->Penjualan_model->update($id_penjualan, ['status_berkas' => 'diserahkan']);

        // FIX BUG-006 & BUG-007: Update status mobil ke 'terjual' + guard stok
        $id_mobil = $penjualan['id_mobil'];
        $mobil    = $this->Mobil_model->get_by_id($id_mobil);
        if ($mobil) {
            // FIX BUG-007: pastikan stok tidak negatif
            $new_stok = max(0, $mobil['stok'] - 1);

            // FIX BUG-006: 'terjual' sekarang valid di ENUM
            $this->Mobil_model->update($id_mobil, [
                'stok'        => $new_stok,
                'status_stok' => 'terjual'
            ]);
        }

        $this->session->set_flashdata('success', 'Penyerahan mobil berhasil diproses. Status: Dalam Proses. Update ke Selesai setelah konfirmasi penerimaan.');
        redirect('penyerahan_mobil');
    }

    /**
     * Update status penyerahan menjadi Selesai.
     * Dipanggil setelah ada konfirmasi dari kurir/customer bahwa mobil sudah diterima.
     */
    public function selesai($id_penyerahan) {
        $this->check_admin_access();

        $penyerahan = $this->Penyerahan_mobil_model->get_by_id($id_penyerahan);
        if (!$penyerahan) {
            $this->session->set_flashdata('error', 'Data penyerahan tidak ditemukan.');
            redirect('penyerahan_mobil');
            return;
        }

        if ($penyerahan['status_penyerahan'] === 'selesai') {
            $this->session->set_flashdata('error', 'Status penyerahan sudah Selesai.');
            redirect('penyerahan_mobil');
            return;
        }

        $this->Penyerahan_mobil_model->update_status($id_penyerahan, 'selesai');
        $this->session->set_flashdata('success', 'Status penyerahan berhasil diubah menjadi Selesai.');
        redirect('penyerahan_mobil');
    }
}
