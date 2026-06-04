<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_model');
    }

    public function index() {
        $data['title'] = 'Data Customer';
        $data['customers'] = $this->Customer_model->get_all();
        $this->render_page('customer/index', $data);
    }

    public function store() {
        $this->check_admin_access();
        $data = [
            'nama' => $this->input->post('nama', true),
            'no_ktp' => $this->input->post('no_ktp', true),
            'no_telp' => $this->input->post('no_telp', true),
            'email' => $this->input->post('email', true),
            'alamat' => $this->input->post('alamat', true)
        ];

        $this->Customer_model->insert($data);
        $this->session->set_flashdata('success', 'Data customer berhasil ditambahkan');
        redirect('customer');
    }

    public function update() {
        $this->check_admin_access();
        $id = $this->input->post('id_customer');
        $data = [
            'nama' => $this->input->post('nama', true),
            'no_ktp' => $this->input->post('no_ktp', true),
            'no_telp' => $this->input->post('no_telp', true),
            'email' => $this->input->post('email', true),
            'alamat' => $this->input->post('alamat', true)
        ];

        $this->Customer_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data customer berhasil diubah');
        redirect('customer');
    }

    public function delete($id) {
        $this->check_admin_access();
        $this->Customer_model->delete($id);
        $this->session->set_flashdata('success', 'Data customer berhasil dihapus');
        redirect('customer');
    }
}
