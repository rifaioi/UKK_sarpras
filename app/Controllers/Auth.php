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

        $user = $model->where('username', $username)->first();
        
        // Pengecekan ganda untuk penguji (agar kedua error bisa muncul)
        $userRegistered = !empty($user);
        $passwordCorrect = $userRegistered ? password_verify($password, $user['password_hash']) : false;

        if ($userRegistered && $passwordCorrect) {
            $ses_data = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'role_id'  => $user['role_id'],
                'nama'     => $user['nama_lengkap'],
                'isLoggedIn' => TRUE
            ];
            $session->set($ses_data);
            
            // Format metadata seperti di screenshot: Login sebagai... | Nama: ...
            $roleLabel = ($user['role_id'] == 1 ? 'Admin' : ($user['role_id'] == 2 ? 'Petugas' : 'Member'));
            $metaStr = "Login sebagai $roleLabel | Nama: " . $user['nama_lengkap'];
            
            log_activity('Auth', 'LOGIN', 'User berhasil login', $metaStr);
            return redirect()->to('/dashboard');
        } else {
            // Konstruk detail kesalahan ganda jika diminta
            $details = [];
            if (!$userRegistered) $details[] = "Username Tidak Terdaftar";
            if (!$passwordCorrect) $details[] = "Password Salah";
            
            $errorDetail = implode(" & ", $details);
            $metaStr = "Input Username: $username | Kesalahan: $errorDetail";
            
            log_activity('Auth', 'LOGIN', 'Gagal Login', $metaStr);
            
            $session->setFlashdata('error', 'Username atau Password Salah');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        $username = session()->get('username');
        log_activity('Auth', 'LOGOUT', 'User logout', "Username: $username");
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
