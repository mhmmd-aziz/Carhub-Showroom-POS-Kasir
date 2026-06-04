<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_pembelian extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pembayaran_pembelian_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Mobil_model');
    }

    public function index() {
        $data['title'] = 'Data Pembayaran Pembelian';
        $data['pembayaran'] = $this->Pembayaran_pembelian_model->get_all();

        // Ambil data pembelian yang masih menunggu pembayaran
        $this->db->where('status_pembayaran', 'menunggu');
        $data['menunggu'] = $this->Pembelian_model->get_all();

        $this->render_page('pembayaran_pembelian/index', $data);
    }

    public function bayar($id_pembelian) {
        $this->check_admin_access();
        $data['title'] = 'Proses Pembayaran Pembelian';
        $data['pembelian'] = $this->Pembelian_model->get_by_id($id_pembelian);

        if (!$data['pembelian'] || $data['pembelian']['status_pembayaran'] != 'menunggu') {
            $this->session->set_flashdata('error', 'Transaksi tidak valid atau sudah dibayar.');
            redirect('pembelian');
            return;
        }

        $this->render_page('pembayaran_pembelian/bayar', $data);
    }

    /**
     * Proses pembayaran pembelian ke supplier.
     * FIX BUG-004 (sisi pembelian): Field konsisten:
     *   - jenis_pembayaran → ENUM tunai/transfer (cara bayar)
     *   - metode_pembayaran → nama bank/detail transfer
     * Setelah bayar: status pembelian → selesai, stok mobil bertambah, status_stok → tersedia.
     */
    public function proses_bayar() {
        $this->check_admin_access();
        $id_pembelian = $this->input->post('id_pembelian');
        $pembelian = $this->Pembelian_model->get_by_id($id_pembelian);

        if (!$pembelian || $pembelian['status_pembayaran'] != 'menunggu') {
            $this->session->set_flashdata('error', 'Transaksi tidak valid atau sudah dibayar.');
            redirect('pembelian');
            return;
        }

        $jenis = $this->input->post('jenis_pembayaran'); // tunai / transfer
        $bukti_transfer = '';
        $metode_pembayaran = $this->input->post('metode_pembayaran'); // nama bank (jika transfer)

        if ($jenis == 'transfer') {
            $upload_path = './uploads/bukti_bayar/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, TRUE);
            }

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => 'gif|jpg|jpeg|png|pdf',
                'max_size'      => 5120,
                'encrypt_name'  => TRUE
            ];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_transfer')) {
                $upload_data = $this->upload->data();
                $bukti_transfer = $upload_data['file_name'];
            } else {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Gagal upload bukti transfer: ' . $error);
                redirect('pembayaran_pembelian/bayar/' . $id_pembelian);
                return;
            }
        }

        // Bersihkan format rupiah
        $jumlah_raw = $this->input->post('jumlah_bayar');
        $jumlah = str_replace(['.', ','], ['', '.'], $jumlah_raw);

        $data_bayar = [
            'id_pembelian'      => $id_pembelian,
            'jenis_pembayaran'  => $jenis,             // tunai / transfer
            'metode_pembayaran' => $metode_pembayaran,  // nama bank (jika transfer)
            'tgl_bayar'         => $this->input->post('tgl_bayar'),
            'jumlah_bayar'      => $jumlah,
            'bukti_transfer'    => $bukti_transfer,
            'status_verifikasi' => 1
        ];

        // 1. Simpan pembayaran
        $this->Pembayaran_pembelian_model->insert($data_bayar);

        // 2. Update status pembelian menjadi selesai
        $this->Pembelian_model->update($id_pembelian, ['status_pembayaran' => 'selesai']);

        // 3. Update stok mobil dan status
        // Sesuai CLAUDE.md: "Setelah pembelian dibayar → status mobil = tersedia, stok bertambah"
        $mobil = $this->Mobil_model->get_by_id($pembelian['id_mobil']);
        if ($mobil) {
            $new_stok = $mobil['stok'] + 1;
            $this->Mobil_model->update($pembelian['id_mobil'], [
                'stok'        => $new_stok,
                'status_stok' => 'tersedia'
            ]);
        }

        $this->session->set_flashdata('success', 'Pembayaran berhasil diproses. Stok mobil telah bertambah.');
        redirect('pembelian');
    }
}
