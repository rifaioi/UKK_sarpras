<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $id = session()->get('id');
        $user = $this->userModel->find($id);
        
        // Sync session just in case it was updated elsewhere in DB
        session()->set('nama', $user['nama_lengkap']);
        session()->set('username', $user['username']);

        return view('member/profile', ['user' => $user]);
    }

    public function update()
    {
        $id = session()->get('id');
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'username'     => "required|alpha_numeric|min_length[4]|max_length[100]|is_unique[users.username,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'username'     => $this->request->getVar('username'),
        ];

        if ($this->userModel->update($id, $data)) {
            session()->set('nama', $data['nama_lengkap']);
            session()->set('username', $data['username']);
            
            log_activity('Member Profile', 'Ubah Profil', 'Peminjam memperbarui informasi profil');
            return redirect()->to('/member/profile')->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
    }

    public function update_password()
    {
        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[8]|differs[password_lama]',
            'konfirmasi_password' => 'required|matches[password_baru]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = session()->get('id');
        $user = $this->userModel->find($id);

        if (!password_verify($this->request->getVar('password_lama'), $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        $this->userModel->update($id, [
            'password_hash' => password_hash($this->request->getVar('password_baru'), PASSWORD_BCRYPT)
        ]);
        
        log_activity('Member Profile', 'Ubah Password', 'Peminjam mengubah password sendiri');

        return redirect()->to('/member/profile')->with('success', 'Password berhasil diubah.');
    }
}
