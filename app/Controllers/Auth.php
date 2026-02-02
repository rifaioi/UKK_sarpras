<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $model->where('username', $username)->first();

        if ($data) {
            $pass = $data['password_hash'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id'       => $data['id'],
                    'username' => $data['username'],
                    'role_id'  => $data['role_id'],
                    'nama'     => $data['nama_lengkap'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                log_activity('Login', 'User logged in');
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('/');
            }
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        log_activity('Logout', 'User logged out');
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
