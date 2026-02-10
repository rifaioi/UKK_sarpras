<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class Users extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $builder = $this->userModel->select('users.*, roles.nama_role')
                                   ->join('roles', 'roles.id = users.role_id')
                                   ->where('users.is_deleted', 0);
        
        $data = [
            'users' => $builder->findAll()
        ];
        return view('admin/users/index', $data);
    }

    public function trash()
    {
        $builder = $this->userModel->select('users.*, roles.nama_role')
                                   ->join('roles', 'roles.id = users.role_id')
                                   ->where('users.is_deleted', 1);
        
        $data = [
            'users' => $builder->findAll()
        ];
        return view('admin/users/trash', $data);
    }

    public function create()
    {
        $data = [
            'roles' => $this->roleModel->findAll()
        ];
        return view('admin/users/form', $data);
    }

    public function store()
    {
        $rules = [
            'username'     => 'required|min_length[4]|max_length[100]|is_unique[users.username]',
            'password'     => 'required|min_length[8]',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'role_id'      => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'username'      => $this->request->getVar('username'),
            'password_hash' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'nama_lengkap'  => $this->request->getVar('nama_lengkap'),
            'role_id'       => $this->request->getVar('role_id'),
        ];

        if ($this->userModel->save($saveData)) {
            $newId = $this->userModel->getInsertID();
            log_activity('Tambah User', "Menambah user baru: " . $saveData['username'] . " (ID: $newId)");
            return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function edit($id)
    {
        $data = [
            'user' => $this->userModel->find($id),
            'roles' => $this->roleModel->findAll()
        ];
        return view('admin/users/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'username'     => "required|min_length[4]|max_length[100]|is_unique[users.username,id,$id]",
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'role_id'      => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'           => $id,
            'username'     => $this->request->getVar('username'),
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'role_id'      => $this->request->getVar('role_id'),
        ];

        if ($this->request->getVar('password')) {
            $data['password_hash'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
        }

        if ($this->userModel->save($data)) {
            // Update session if editing self
            if ($id == session()->get('id')) {
                session()->set('nama', $data['nama_lengkap']);
                session()->set('username', $data['username']);
            }
            log_activity('Update User', "Memperbarui profil user ID: $id (" . $data['username'] . ")");
            return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function delete($id)
    {
        $currentUserId = session()->get('id');
        
        $this->userModel->update($id, ['is_deleted' => 1]);
        log_activity('Hapus User', "Menghapus user id: $id");

        if ($id == $currentUserId) {
            session()->destroy();
            return redirect()->to('/')->with('success', 'Akun Anda telah dihapus. Anda telah dikeluarkan dari sistem.');
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus');
    }

    public function restore($id)
    {
        $this->userModel->update($id, ['is_deleted' => 0]);
        log_activity('Restore User', "Mengembalikan user id: $id");
        return redirect()->to('/admin/users?show_deleted=1')->with('success', 'User berhasil dikembalikan');
    }
}
