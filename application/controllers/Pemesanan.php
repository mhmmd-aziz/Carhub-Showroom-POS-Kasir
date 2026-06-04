<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemesanan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pemesanan_model');
        $this->load->model('Customer_model');
        $this->load->model('Mobil_model');
    }

    /**
     * Index: tampilkan daftar pemesanan.
     * Sebelum tampil, cek dan hanguskan pemesanan yang jatuh tempo DP-nya.
     * Sesuai CLAUDE.md: "Jika DP tidak dibayar dalam 7 hari → booking hangus, mobil kembali tersedia"
     */
    public function index() {
        $this->_proses_hangus_kadaluarsa();

        $data['title'] = 'Data Pemesanan';
        $data['pemesanan'] = $this->Pemesanan_model->get_all();
        $this->render_page('pemesanan/index', $data);
    }

    /**
     * Proses auto-hangus: periksa pemesanan yang melewati jatuh tempo DP.
     */
    private function _proses_hangus_kadaluarsa() {
        $kadaluarsa = $this->Pemesanan_model->get_kadaluarsa();
        foreach ($kadaluarsa as $p) {
            // Update status pemesanan menjadi hangus
            $this->Pemesanan_model->update($p['id_pemesanan'], [
                'status_pemesanan' => 'hangus'
            ]);
            // Kembalikan status mobil menjadi tersedia (stok bertambah kembali)
            $mobil = $this->Mobil_model->get_by_id($p['id_mobil']);
            if ($mobil) {
                $this->Mobil_model->update($p['id_mobil'], [
                    'status_stok' => 'tersedia',
                    'stok' => $mobil['stok'] + 1
                ]);
            }
        }
    }

    public function tambah() {
        $this->check_admin_access();
        $data['title'] = 'Buat Pemesanan Baru';
        $data['customers'] = $this->Customer_model->get_all();

        // Hanya tampilkan mobil yang berstatus tersedia dan stok > 0
        $this->db->where('mobil.status_stok', 'tersedia');
        $this->db->where('mobil.stok >', 0);
        $data['mobils'] = $this->Mobil_model->get_all();

        $this->render_page('pemesanan/tambah', $data);
    }

    public function store() {
        $this->check_admin_access();

        // === SERVER-SIDE VALIDATION ===
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_customer', 'Customer', 'required|integer');
        $this->form_validation->set_rules('id_mobil', 'Mobil', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Data tidak valid: ' . validation_errors(' ', ' | '));
            redirect('pemesanan/tambah');
            return;
        }

        $id_mobil = $this->input->post('id_mobil', true);
        $mobil = $this->Mobil_model->get_by_id($id_mobil);

        // Validasi stok dan status mobil
        if (!$mobil || $mobil['stok'] <= 0 || $mobil['status_stok'] !== 'tersedia') {
            $this->session->set_flashdata('error', 'Mobil tidak tersedia atau stok habis. Pilih mobil lain.');
            redirect('pemesanan/tambah');
            return;
        }

        // Snapshot harga jual saat ini (immutable setelah order dibuat)
        $harga_jual = $mobil['harga_jual'];
        $dp_minimal = $harga_jual * 0.30; // DP minimal 30%

        // Batas waktu DP adalah 7 hari dari sekarang
        $tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days'));

        $data = [
            'id_customer'        => $this->input->post('id_customer', true),
            'id_mobil'           => $id_mobil,
            'tgl_pesan'          => date('Y-m-d'),
            'harga_jual_snapshot' => $harga_jual, // Simpan snapshot harga
            'harga_dp'           => $dp_minimal,
            'nilai_tanda_jadi'   => 500000,
            'dp_minimal'         => $dp_minimal,
            'tgl_jatuh_tempo'    => $tgl_jatuh_tempo,
            'status_pemesanan'   => 'menunggu'
        ];

        $this->Pemesanan_model->insert($data);

        // Update status mobil menjadi booking DAN kurangi stok
        $this->Mobil_model->update($id_mobil, [
            'status_stok' => 'booking',
            'stok'        => $mobil['stok'] - 1  // FIX BUG-001: kurangi stok
        ]);

        $this->session->set_flashdata('success', 'Pemesanan berhasil dibuat. Silakan proses pembayaran Tanda Jadi (Rp 500.000) segera.');
        redirect('pemesanan');
    }

    /**
     * Form pembatalan pemesanan.
     * Sesuai CLAUDE.md Tahap 4: Pembatalan hanya bisa dilakukan jika status masih 'bukti_pesanan'.
     */
    public function batal($id_pemesanan) {
        $this->check_admin_access();
        $pemesanan = $this->Pemesanan_model->get_by_id($id_pemesanan);

        if (!$pemesanan) {
            $this->session->set_flashdata('error', 'Data pemesanan tidak ditemukan.');
            redirect('pemesanan');
            return;
        }

        // Hanya bisa dibatalkan jika status masih menunggu atau bukti_pesanan
        if (!in_array($pemesanan['status_pemesanan'], ['menunggu', 'bukti_pesanan'])) {
            $this->session->set_flashdata('error', 'Pemesanan tidak dapat dibatalkan. Status saat ini: ' . $pemesanan['status_pemesanan']);
            redirect('pemesanan');
            return;
        }

        $data['title'] = 'Batalkan Pemesanan';
        $data['pemesanan'] = $pemesanan;
        $data['sudah_bayar_tanda_jadi'] = ($pemesanan['status_pemesanan'] === 'bukti_pesanan');
        $data['dalam_7_hari'] = (strtotime($pemesanan['tgl_jatuh_tempo']) >= strtotime('today'));
        $this->render_page('pemesanan/batal', $data);
    }

    /**
     * Proses pembatalan pemesanan.
     * Sesuai CLAUDE.md:
     * - Jika < 7 hari: admin input alasan → hitung refund → status mobil tersedia
     * - Jika > 7 hari: status hangus → mobil tersedia (tanda jadi hangus)
     */
    public function proses_batal() {
        $this->check_admin_access();
        $id_pemesanan = $this->input->post('id_pemesanan');
        $pemesanan = $this->Pemesanan_model->get_by_id($id_pemesanan);

        if (!$pemesanan || !in_array($pemesanan['status_pemesanan'], ['menunggu', 'bukti_pesanan'])) {
            $this->session->set_flashdata('error', 'Pemesanan tidak valid untuk dibatalkan.');
            redirect('pemesanan');
            return;
        }

        $alasan = $this->input->post('alasan_batal', true);
        $dalam_7_hari = (strtotime($pemesanan['tgl_jatuh_tempo']) >= strtotime('today'));

        if ($dalam_7_hari) {
            // Batalkan: refund tanda jadi (logika bisnis: catat refund)
            $status_baru = 'batal';
        } else {
            // Hangus: tanda jadi tidak dikembalikan
            $status_baru = 'hangus';
        }

        // Update status pemesanan
        $this->Pemesanan_model->update($id_pemesanan, [
            'status_pemesanan' => $status_baru,
            'alasan_batal'     => $alasan
        ]);

        // Kembalikan stok dan status mobil ke tersedia
        $mobil = $this->Mobil_model->get_by_id($pemesanan['id_mobil']);
        if ($mobil) {
            $this->Mobil_model->update($pemesanan['id_mobil'], [
                'status_stok' => 'tersedia',
                'stok'        => $mobil['stok'] + 1
            ]);
        }

        $pesan = ($status_baru === 'batal')
            ? 'Pemesanan berhasil dibatalkan. Tanda jadi dikembalikan ke customer.'
            : 'Pemesanan dinyatakan hangus. Tanda jadi tidak dikembalikan.';

        $this->session->set_flashdata('success', $pesan);
        redirect('pemesanan');
    }
}
