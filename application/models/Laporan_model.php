<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    /**
     * Laporan Penjualan — filter berdasarkan tanggal penjualan (INKLUSIF).
     * Kolom: tgl_penjualan, nama_customer (dari customer.nama), nama_mobil, no_polisi,
     *         harga_jual_snapshot, total_bayaran, status_pelunasan.
     */
    public function get_penjualan($tgl_awal, $tgl_akhir) {
        $this->db->select('
            penjualan.id_penjualan,
            penjualan.tgl_penjualan,
            penjualan.total_bayaran,
            penjualan.status_pelunasan,
            penjualan.status_berkas,
            customer.nama AS nama_customer,
            customer.no_telp,
            mobil.nama_mobil,
            mobil.no_polisi,
            mobil.merek,
            pemesanan.harga_jual_snapshot,
            pemesanan.tgl_pesan
        ');
        $this->db->from('penjualan');
        $this->db->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan');
        $this->db->join('customer', 'customer.id_customer = pemesanan.id_customer');
        $this->db->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil');
        // Filter inklusif: tambahkan batas akhir hari (23:59:59)
        $this->db->where('penjualan.tgl_penjualan >=', $tgl_awal);
        $this->db->where('penjualan.tgl_penjualan <=', $tgl_akhir);
        $this->db->order_by('penjualan.tgl_penjualan', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Laporan Pembelian — filter berdasarkan tanggal pembelian (INKLUSIF).
     * Kolom: tgl_pembelian, nama_supplier, nama_mobil, no_polisi,
     *         harga_beli_beli, status_pembayaran.
     */
    public function get_pembelian($tgl_awal, $tgl_akhir) {
        $this->db->select('
            pembelian.id_pembelian,
            pembelian.tgl_pembelian,
            pembelian.harga_beli_beli,
            pembelian.status_pembayaran,
            pembelian.keterangan_kondisi,
            supplier.nama_supplier,
            mobil.nama_mobil,
            mobil.no_polisi,
            mobil.merek
        ');
        $this->db->from('pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier');
        $this->db->join('mobil', 'mobil.id_mobil = pembelian.id_mobil');
        $this->db->where('pembelian.tgl_pembelian >=', $tgl_awal);
        $this->db->where('pembelian.tgl_pembelian <=', $tgl_akhir);
        $this->db->order_by('pembelian.tgl_pembelian', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Laporan Pembayaran — rekap SEMUA pembayaran (penjualan & pembelian) per periode.
     * Digabungkan dan diurutkan berdasarkan tanggal.
     */
    public function get_pembayaran($tgl_awal, $tgl_akhir) {
        // Pembayaran penjualan (tanda jadi, dp, pelunasan)
        $this->db->select("
            pp.id_pembayaran,
            pp.tgl_bayar,
            'Penjualan' AS tipe,
            pp.jenis_pembayaran,
            pp.metode_pembayaran,
            pp.jumlah_bayar,
            c.nama AS nama_pihak,
            m.nama_mobil,
            m.no_polisi,
            pp.status_verifikasi
        ");
        $this->db->from('pembayaran_penjualan pp');
        $this->db->join('pemesanan ps', 'ps.id_pemesanan = pp.id_pemesanan');
        $this->db->join('customer c', 'c.id_customer = ps.id_customer');
        $this->db->join('mobil m', 'm.id_mobil = ps.id_mobil');
        $this->db->where('pp.tgl_bayar >=', $tgl_awal);
        $this->db->where('pp.tgl_bayar <=', $tgl_akhir);
        $penjualan_payments = $this->db->get()->result_array();

        // Pembayaran pembelian (dari supplier)
        // Catatan: tabel pembayaran_pembelian.jenis_pembayaran = tunai/transfer (metode),
        //          bukan tahap. Alias sebagai metode_pembayaran untuk konsistensi tampilan.
        $this->db->select("
            ppb.id_pembayaran,
            ppb.tgl_bayar,
            'Pembelian' AS tipe,
            'pelunasan' AS jenis_pembayaran,
            ppb.jenis_pembayaran AS metode_pembayaran,
            ppb.jumlah_bayar,
            s.nama_supplier AS nama_pihak,
            m.nama_mobil,
            m.no_polisi,
            ppb.status_verifikasi
        ");
        $this->db->from('pembayaran_pembelian ppb');
        $this->db->join('pembelian pb', 'pb.id_pembelian = ppb.id_pembelian');
        $this->db->join('supplier s', 's.id_supplier = pb.id_supplier');
        $this->db->join('mobil m', 'm.id_mobil = pb.id_mobil');
        $this->db->where('ppb.tgl_bayar >=', $tgl_awal);
        $this->db->where('ppb.tgl_bayar <=', $tgl_akhir);
        $pembelian_payments = $this->db->get()->result_array();

        // Gabungkan dan urutkan berdasarkan tanggal
        $all = array_merge($penjualan_payments, $pembelian_payments);
        usort($all, function($a, $b) {
            return strcmp($a['tgl_bayar'], $b['tgl_bayar']);
        });

        return $all;
    }

    /**
     * Laporan Stok — semua mobil yang masih di showroom (tersedia atau booking).
     */
    public function get_stok_mobil() {
        $this->db->select('mobil.*, supplier.nama_supplier');
        $this->db->from('mobil');
        $this->db->join('supplier', 'supplier.id_supplier = mobil.id_supplier', 'left');
        $this->db->where_in('mobil.status_stok', ['tersedia', 'booking']);
        $this->db->where('mobil.deleted_at IS NULL');
        $this->db->order_by('mobil.merek', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Catat log request laporan ke tabel laporan.
     */
    public function catat_laporan($data) {
        if ($this->db->table_exists('laporan')) {
            $this->db->insert('laporan', $data);
        }
    }
}
