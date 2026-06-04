<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Cek session, jika belum login redirect ke Auth
        if (!$this->session->userdata('id_user')) {
            redirect('auth');
        }
    }

    public function render_page($view, $data = []) {
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer', $data);
    }

    public function check_admin_access() {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses Ditolak: Anda tidak memiliki izin untuk melakukan aksi ini (Hanya Admin).');
            redirect('dashboard');
            exit;
        }
    }
}
