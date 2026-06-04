<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mobil_model');
        $this->load->model('Supplier_model');
    }

    public function index() {
        $data['title'] = 'Data Mobil';
        $data['mobils'] = $this->Mobil_model->get_all();
        $data['suppliers'] = $this->Supplier_model->get_all();
        $this->render_page('mobil/index', $data);
    }

    public function store() {
        $this->check_admin_access();
        $data = [
            'id_supplier' => $this->input->post('id_supplier', true),
            'nama_mobil' => $this->input->post('nama_mobil', true),
            'merek' => $this->input->post('merek', true),
            'warna' => $this->input->post('warna', true),
            'tipe' => $this->input->post('tipe', true),
            'tahun' => $this->input->post('tahun', true),
            'no_polisi' => $this->input->post('no_polisi', true),
            'no_rangka' => $this->input->post('no_rangka', true),
            'no_mesin' => $this->input->post('no_mesin', true),
            'harga_beli' => (float)$this->input->post('harga_beli', true),
            'harga_jual' => (float)$this->input->post('harga_jual', true),
            'stok' => $this->input->post('stok', true),
            'status_stok' => 'tersedia',
            'status_bpkb' => $this->input->post('status_bpkb', true),
            'status_mobil' => $this->input->post('status_mobil', true),
        ];

        $this->Mobil_model->insert($data);
        $this->session->set_flashdata('success', 'Data mobil berhasil ditambahkan');
        redirect('mobil');
    }

    public function update() {
        $this->check_admin_access();
        $id = $this->input->post('id_mobil');
        $data = [
            'id_supplier' => $this->input->post('id_supplier', true),
            'nama_mobil' => $this->input->post('nama_mobil', true),
            'merek' => $this->input->post('merek', true),
            'warna' => $this->input->post('warna', true),
            'tipe' => $this->input->post('tipe', true),
            'tahun' => $this->input->post('tahun', true),
            'no_polisi' => $this->input->post('no_polisi', true),
            'no_rangka' => $this->input->post('no_rangka', true),
            'no_mesin' => $this->input->post('no_mesin', true),
            'harga_beli' => (float)$this->input->post('harga_beli', true),
            'harga_jual' => (float)$this->input->post('harga_jual', true),
            'stok' => $this->input->post('stok', true),
            'status_bpkb' => $this->input->post('status_bpkb', true),
            'status_mobil' => $this->input->post('status_mobil', true),
        ];

        $this->Mobil_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data mobil berhasil diubah');
        redirect('mobil');
    }

    public function delete($id) {
        $this->check_admin_access();
        $this->Mobil_model->delete($id);
        $this->session->set_flashdata('success', 'Data mobil berhasil dihapus');
        redirect('mobil');
    }
}
