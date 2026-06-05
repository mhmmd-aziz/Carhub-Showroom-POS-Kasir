<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['title'] = 'Dashboard';
        
        $current_month = date('m');
        $current_year = date('Y');

        // Total Customer (terdaftar, tidak dihapus)
        $this->db->where('deleted_at', NULL);
        $data['total_customer'] = $this->db->count_all_results('customer');

        // Total Supplier (terdaftar, tidak dihapus)
        $this->db->where('deleted_at', NULL);
        $data['total_supplier'] = $this->db->count_all_results('supplier');

        // Total Mobil (aktif, tidak dihapus)
        $this->db->where('deleted_at', NULL);
        $data['total_mobil'] = $this->db->count_all_results('mobil');

        // --- CHART DATA ---
        
        // 1. Bar Chart: Monthly Sales (Penjualan Lunas per bulan di tahun ini)
        $monthly_sales = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select_sum('total_bayaran');
            $this->db->where('status_pelunasan', 'lunas');
            $this->db->where('MONTH(tgl_penjualan)', $i);
            $this->db->where('YEAR(tgl_penjualan)', $current_year);
            $res = $this->db->get('penjualan')->row();
            $monthly_sales[] = (float)($res->total_bayaran ?? 0);
        }
        $data['chart_sales'] = json_encode($monthly_sales);

        // 2. Doughnut Chart: Penjualan per Merek (Mobil Terlaris)
        $this->db->select('m.merek, COUNT(p.id_penjualan) as total');
        $this->db->from('penjualan p');
        $this->db->join('pemesanan ps', 'p.id_pemesanan = ps.id_pemesanan');
        $this->db->join('mobil m', 'ps.id_mobil = m.id_mobil');
        $this->db->where('p.status_pelunasan', 'lunas');
        $this->db->group_by('m.merek');
        $brands = $this->db->get()->result_array();

        $chart_brands_labels = [];
        $chart_brands_data = [];
        foreach ($brands as $b) {
            $chart_brands_labels[] = $b['merek'];
            $chart_brands_data[] = (int)$b['total'];
        }
        
        if (empty($chart_brands_labels)) {
            // Tampilkan distribusi stok per merek sebagai fallback
            $this->db->select('merek, COUNT(*) as total');
            $this->db->where('deleted_at', NULL);
            $this->db->group_by('merek');
            $stok_brands = $this->db->get('mobil')->result_array();
            foreach ($stok_brands as $b) {
                $chart_brands_labels[] = $b['merek'];
                $chart_brands_data[] = (int)$b['total'];
            }
            if (empty($chart_brands_labels)) {
                $chart_brands_labels = ['Belum ada data'];
                $chart_brands_data = [1];
            }
        }
        
        $data['chart_brands_labels'] = json_encode($chart_brands_labels);
        $data['chart_brands_data'] = json_encode($chart_brands_data);

        $this->render_page('dashboard/index', $data);
    }
}
