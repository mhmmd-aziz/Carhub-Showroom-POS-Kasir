<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Supplier_model');
    }

    public function index() {
        $data['title'] = 'Data Supplier';
        $data['suppliers'] = $this->Supplier_model->get_all();
        $this->render_page('supplier/index', $data);
    }

    public function store() {
        $this->check_admin_access();
        $data = [
            'nama_supplier' => $this->input->post('nama_supplier', true),
            'no_telp' => $this->input->post('no_telp', true),
            'email' => $this->input->post('email', true),
            'alamat' => $this->input->post('alamat', true),
            'keterangan' => $this->input->post('keterangan', true)
        ];

        $this->Supplier_model->insert($data);
        $this->session->set_flashdata('success', 'Data supplier berhasil ditambahkan');
        redirect('supplier');
    }

    public function update() {
        $this->check_admin_access();
        $id = $this->input->post('id_supplier');
        $data = [
            'nama_supplier' => $this->input->post('nama_supplier', true),
            'no_telp' => $this->input->post('no_telp', true),
            'email' => $this->input->post('email', true),
            'alamat' => $this->input->post('alamat', true),
            'keterangan' => $this->input->post('keterangan', true)
        ];

        $this->Supplier_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data supplier berhasil diubah');
        redirect('supplier');
    }

    public function delete($id) {
        $this->check_admin_access();
        $this->Supplier_model->delete($id);
        $this->session->set_flashdata('success', 'Data supplier berhasil dihapus');
        redirect('supplier');
    }
}
