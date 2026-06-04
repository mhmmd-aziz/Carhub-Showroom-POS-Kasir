<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Load models later when we have them
    }

    public function index() {
        $data['title'] = 'Dashboard';
        
        $current_month = date('m');
        $current_year = date('Y');

        // Total Pemesanan bulan ini
        $this->db->where('MONTH(tgl_pesan)', $current_month);
        $this->db->where('YEAR(tgl_pesan)', $current_year);
        $data['total_pemesanan'] = $this->db->count_all_results('pemesanan');

        // Stok Tersedia
        $this->db->select_sum('stok');
        $query_stok = $this->db->get('mobil')->row();
        $data['stok_tersedia'] = $query_stok->stok ?? 0;

        // Total Customer
        $data['total_customer'] = $this->db->count_all('customer');

        // Total Penjualan bulan ini (Lunas)
        $this->db->select_sum('total_bayaran');
        $this->db->where('status_pelunasan', 'lunas');
        $this->db->where('MONTH(tgl_penjualan)', $current_month);
        $this->db->where('YEAR(tgl_penjualan)', $current_year);
        $query_penjualan = $this->db->get('penjualan')->row();
        $data['total_penjualan'] = $query_penjualan->total_bayaran ?? 0;

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
            $chart_brands_labels = ['Belum ada data'];
            $chart_brands_data = [1];
        }
        
        $data['chart_brands_labels'] = json_encode($chart_brands_labels);
        $data['chart_brands_data'] = json_encode($chart_brands_data);

        $this->render_page('dashboard/index', $data);
    }
}
