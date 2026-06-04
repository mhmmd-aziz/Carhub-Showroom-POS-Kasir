<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->session->userdata('id_user')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    /**
     * Proses login.
     * BUG-017 FIX: Support password_hash (bcrypt) sebagai standar baru,
     * dengan backward compatibility untuk password MD5 lama.
     * Saat login berhasil dengan MD5, password di-upgrade ke bcrypt secara otomatis.
     */
    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi');
            redirect('auth');
            return;
        }

        $user = $this->db->get_where('user', ['username' => $username])->row_array();

        if ($user) {
            $valid = false;

            // Cek apakah password menggunakan bcrypt (dimulai dengan $2y$)
            if (substr($user['password'], 0, 4) === '$2y$') {
                // Password sudah bcrypt
                $valid = password_verify($password, $user['password']);
            } else {
                // Password masih MD5 (legacy) — cek dan auto-upgrade
                if (md5($password) === $user['password']) {
                    $valid = true;
                    // Auto-upgrade password ke bcrypt
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $this->db->where('id_user', $user['id_user']);
                    $this->db->update('user', ['password' => $new_hash]);
                }
            }

            if ($valid) {
                $session_data = [
                    'id_user'      => $user['id_user'],
                    'username'     => $user['username'],
                    'role'         => $user['role'],
                    'nama_lengkap' => $user['nama_lengkap']
                ];
                $this->session->set_userdata($session_data);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Password salah. Periksa kembali password Anda.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan.');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
