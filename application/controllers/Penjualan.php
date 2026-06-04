<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Penjualan_model');
        $this->load->model('Penyerahan_mobil_model');
    }

    public function index() {
        $data['title'] = 'Data Penjualan';
        $data['penjualan'] = $this->Penjualan_model->get_all();

        // Cek masing-masing penjualan apakah sudah pernah diserahkan
        $penyerahan_ids = [];
        if (!empty($data['penjualan'])) {
            foreach ($data['penjualan'] as $p) {
                $cek = $this->Penyerahan_mobil_model->get_by_penjualan($p['id_penjualan']);
                if ($cek) {
                    $penyerahan_ids[$p['id_penjualan']] = $cek['id_penyerahan'];
                }
            }
        }
        $data['penyerahan_ids'] = $penyerahan_ids;

        $this->render_page('penjualan/index', $data);
    }
}
