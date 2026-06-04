<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Laporan_model');
    }

    /**
     * Halaman laporan dengan filter jenis dan periode.
     * Semua filter tanggal menggunakan batas INKLUSIF (tgl_akhir mencakup seluruh hari itu).
     */
    public function index() {
        $data['title'] = 'Laporan Showroom';

        $jenis     = $this->input->get('jenis_laporan');
        $tgl_awal  = $this->input->get('tgl_awal');
        $tgl_akhir = $this->input->get('tgl_akhir');

        // Sanitasi input tanggal
        if ($tgl_awal && !$this->_valid_date($tgl_awal))  $tgl_awal  = null;
        if ($tgl_akhir && !$this->_valid_date($tgl_akhir)) $tgl_akhir = null;

        // Swap jika tgl_awal lebih besar dari tgl_akhir
        if ($tgl_awal && $tgl_akhir && $tgl_awal > $tgl_akhir) {
            list($tgl_awal, $tgl_akhir) = [$tgl_akhir, $tgl_awal];
        }

        $data['jenis']     = $jenis;
        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['hasil']     = null;
        $data['error']     = null;

        if ($jenis) {
            // Catat log request laporan ke DB (hanya jika ada tanggal)
            if ($tgl_awal && $tgl_akhir) {
                $this->Laporan_model->catat_laporan([
                    'jenis_laporan' => $jenis,
                    'periode_awal'  => $tgl_awal,
                    'periode_akhir' => $tgl_akhir,
                    'id_user'       => $this->session->userdata('id_user')
                ]);
            }

            if ($jenis == 'penjualan') {
                if (!$tgl_awal || !$tgl_akhir) {
                    $data['error'] = 'Periode tanggal wajib diisi untuk laporan penjualan.';
                } else {
                    $data['hasil'] = $this->Laporan_model->get_penjualan($tgl_awal, $tgl_akhir);
                }

            } elseif ($jenis == 'pembelian') {
                if (!$tgl_awal || !$tgl_akhir) {
                    $data['error'] = 'Periode tanggal wajib diisi untuk laporan pembelian.';
                } else {
                    $data['hasil'] = $this->Laporan_model->get_pembelian($tgl_awal, $tgl_akhir);
                }

            } elseif ($jenis == 'pembayaran') {
                if (!$tgl_awal || !$tgl_akhir) {
                    $data['error'] = 'Periode tanggal wajib diisi untuk laporan pembayaran.';
                } else {
                    $data['hasil'] = $this->Laporan_model->get_pembayaran($tgl_awal, $tgl_akhir);
                }

            } elseif ($jenis == 'stok') {
                $data['hasil'] = $this->Laporan_model->get_stok_mobil();

            } else {
                $data['error'] = 'Jenis laporan tidak valid.';
            }
        }

        $this->render_page('laporan/index', $data);
    }

    /**
     * Validasi format tanggal Y-m-d.
     */
    private function _valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
