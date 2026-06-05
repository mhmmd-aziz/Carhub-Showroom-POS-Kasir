<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_penjualan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pembayaran_penjualan_model');
        $this->load->model('Pemesanan_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Mobil_model');
    }

    public function index($filter = 'semua') {
        $data['title']     = 'Data Pembayaran Penjualan';
        $data['filter']    = $filter;
        $data['pembayaran'] = $this->Pembayaran_penjualan_model->get_all($filter);
        
        // Data untuk tab
        $this->db->where('status_pemesanan', 'menunggu');
        $data['menunggu_tanda_jadi'] = $this->Pemesanan_model->get_all();

        $this->db->where('status_pemesanan', 'bukti_pesanan');
        $data['menunggu_dp'] = $this->Pemesanan_model->get_all();

        $this->db->where('status_pelunasan', 'belum_lunas');
        $data['menunggu_pelunasan'] = $this->Penjualan_model->get_all();

        $this->render_page('pembayaran_penjualan/index', $data);
    }

    /**
     * Tampilkan form pembayaran sesuai tahap aktif.
     * Tahap ditentukan dari status_pemesanan:
     *  - menunggu      → tanda_jadi (Rp 500.000)
     *  - bukti_pesanan → dp (30% harga jual)
     *  - dp            → pelunasan (harga_jual - dp - 500.000)
     * 
     * PENTING: Kalkulasi menggunakan harga_jual_snapshot (snapshot saat order dibuat),
     * bukan harga dari master mobil, agar perubahan harga tidak mempengaruhi transaksi.
     */
    public function bayar($id_pemesanan) {
        $this->check_admin_access();
        $data['title'] = 'Proses Pembayaran Customer';
        $pemesanan = $this->Pemesanan_model->get_by_id($id_pemesanan);

        if (!$pemesanan) {
            $this->session->set_flashdata('error', 'Transaksi tidak valid.');
            redirect('pemesanan');
            return;
        }

        $data['pemesanan'] = $pemesanan;

        // Gunakan harga_jual_snapshot untuk semua kalkulasi
        $harga_jual = $pemesanan['harga_jual_snapshot'];

        // Cek tahap pembayaran berdasarkan status pemesanan
        if ($pemesanan['status_pemesanan'] == 'menunggu') {
            $data['tahap']   = 'tanda_jadi';
            $data['tagihan'] = 500000;

        } elseif ($pemesanan['status_pemesanan'] == 'bukti_pesanan') {
            $data['tahap']   = 'dp';
            $data['tagihan'] = $pemesanan['harga_dp']; // DP 30% yang sudah dihitung saat order

        } elseif ($pemesanan['status_pemesanan'] == 'dp') {
            // Cek apakah sudah ada penjualan
            $penjualan = $this->Penjualan_model->get_by_pemesanan($id_pemesanan);
            $data['penjualan'] = $penjualan;
            $data['tahap']     = 'pelunasan';

            // Pelunasan = harga_jual_snapshot - DP(30%) - tanda_jadi(500.000)
            // FIX BUG-002: gunakan harga_jual_snapshot, bukan harga dari JOIN master
            $data['tagihan'] = $harga_jual - $pemesanan['harga_dp'] - 500000;

            if ($penjualan && $penjualan['status_pelunasan'] == 'lunas') {
                $this->session->set_flashdata('error', 'Transaksi ini sudah lunas.');
                redirect('penjualan');
                return;
            }
        } else {
            $this->session->set_flashdata('error', 'Status pesanan tidak valid atau sudah selesai: ' . $pemesanan['status_pemesanan']);
            redirect('pemesanan');
            return;
        }

        $this->render_page('pembayaran_penjualan/bayar', $data);
    }

    /**
     * Proses pembayaran customer.
     * FIX BUG-004: Konsistensi field:
     *   - `tahap`            → jenis tahap (tanda_jadi/dp/pelunasan) → disimpan ke jenis_pembayaran
     *   - `metode_pembayaran` → cara bayar (tunai/transfer) → disimpan ke metode_pembayaran
     */
    public function proses_bayar() {
        $this->check_admin_access();

        $id_pemesanan = $this->input->post('id_pemesanan');
        $tahap        = $this->input->post('tahap'); // tanda_jadi / dp / pelunasan
        $metode       = $this->input->post('metode_pembayaran'); // tunai / transfer

        $pemesanan = $this->Pemesanan_model->get_by_id($id_pemesanan);
        if (!$pemesanan) {
            redirect('pemesanan');
            return;
        }

        $bukti_transfer = '';
        $bukti_ktp      = '';

        // Handle upload file jika metode transfer
        if ($metode == 'transfer') {
            // Upload Bukti Transfer
            $result_transfer = $this->_upload_file('bukti_transfer', './uploads/bukti_bayar/');
            if ($result_transfer['error']) {
                $this->session->set_flashdata('error', 'Gagal upload bukti transfer: ' . $result_transfer['error']);
                redirect('pembayaran_penjualan/bayar/' . $id_pemesanan);
                return;
            }
            $bukti_transfer = $result_transfer['file_name'];
        }

        // Upload KTP wajib untuk DP dan Pelunasan, baik tunai maupun transfer
        if (in_array($tahap, ['dp', 'pelunasan'])) {
            $result_ktp = $this->_upload_file('bukti_ktp', './uploads/bukti_ktp/');
            if ($result_ktp['error']) {
                $this->session->set_flashdata('error', 'Gagal upload fotokopi KTP: ' . $result_ktp['error']);
                redirect('pembayaran_penjualan/bayar/' . $id_pemesanan);
                return;
            }
            $bukti_ktp = $result_ktp['file_name'];
        }

        // Siapkan data pembayaran
        $data_bayar = [
            'id_pemesanan'     => $id_pemesanan,
            'jenis_pembayaran' => $tahap,   // tanda_jadi / dp / pelunasan
            'metode_pembayaran'=> $metode,  // tunai / transfer
            'tgl_bayar'        => $this->input->post('tgl_bayar'),
            'jumlah_bayar'     => str_replace(['.', ','], ['', '.'], $this->input->post('jumlah_bayar')),
            'bukti_transfer'   => $bukti_transfer,
            'bukti_ktp'        => $bukti_ktp,
            'status_pemesanan' => $pemesanan['status_pemesanan'],
            'status_verifikasi'=> 1
        ];

        // Proses sesuai tahap
        if ($tahap == 'tanda_jadi') {
            $this->Pembayaran_penjualan_model->insert($data_bayar);
            $this->Pemesanan_model->update($id_pemesanan, ['status_pemesanan' => 'bukti_pesanan']);
            $this->session->set_flashdata('success', 'Tanda Jadi (Bukti Pesanan) berhasil dibayar. DP harus dilunasi dalam 7 hari.');
            redirect('pemesanan');
        }
        elseif ($tahap == 'dp') {
            $this->Pembayaran_penjualan_model->insert($data_bayar);
            $this->Pemesanan_model->update($id_pemesanan, ['status_pemesanan' => 'dp']);

            // Masukkan data ke tabel penjualan (sesuai CLAUDE.md)
            $data_penjualan = [
                'id_pemesanan'    => $id_pemesanan,
                'tgl_penjualan'   => date('Y-m-d'),
                'total_bayaran'   => $pemesanan['harga_jual_snapshot'], // Pakai snapshot
                'status_pelunasan'=> 'belum_lunas',
                'status_berkas'   => 'menunggu'
            ];
            $this->Penjualan_model->insert($data_penjualan);

            $this->session->set_flashdata('success', 'DP berhasil dibayar. Data dipindahkan ke Modul Penjualan. Silakan proses pelunasan.');
            redirect('penjualan');
        }
        elseif ($tahap == 'pelunasan') {
            $penjualan = $this->Penjualan_model->get_by_pemesanan($id_pemesanan);
            if (!$penjualan) {
                $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan.');
                redirect('penjualan');
                return;
            }

            $data_bayar['id_penjualan'] = $penjualan['id_penjualan'];
            $this->Pembayaran_penjualan_model->insert($data_bayar);
            $this->Penjualan_model->update($penjualan['id_penjualan'], ['status_pelunasan' => 'lunas']);

            $this->session->set_flashdata('success', 'Pelunasan berhasil. Silakan proses Penyerahan Mobil.');
            redirect('penjualan');
        }
    }

    /**
     * Helper upload file ke folder tertentu.
     * Mengembalikan array ['file_name' => ..., 'error' => ...].
     */
    private function _upload_file($field_name, $upload_path) {
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf',
            'max_size'      => 5120, // 5MB
            'encrypt_name'  => TRUE
        ];

        // Cek apakah ada file yang diupload
        if (empty($_FILES[$field_name]['name'])) {
            return ['file_name' => '', 'error' => 'File tidak diupload.'];
        }

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($field_name)) {
            $upload_data = $this->upload->data();
            return ['file_name' => $upload_data['file_name'], 'error' => ''];
        } else {
            return ['file_name' => '', 'error' => $this->upload->display_errors('', '')];
        }
    }
}
