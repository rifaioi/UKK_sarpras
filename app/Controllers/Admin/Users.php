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
        $data = [
            'users' => $this->userModel->select('users.*, roles.nama_role')
                                       ->join('roles', 'roles.id = users.role_id')
                                       ->findAll()
        ];
        return view('admin/users/index', $data);
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
            'username' => 'required',
            'password' => 'required|min_length[8]',
            'nama_lengkap' => 'required',
            'role_id' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getVar('username'),
            'password_hash' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'role_id' => $this->request->getVar('role_id'),
        ]);

        return redirect()->to('/admin/users')->with('success', 'User created successfully');
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
            'username' => "required",
            'nama_lengkap' => 'required',
            'role_id' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $id,
            'username' => $this->request->getVar('username'),
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'role_id' => $this->request->getVar('role_id'),
        ];

        if ($this->request->getVar('password')) {
            if (strlen($this->request->getVar('password')) < 8) {
                return redirect()->back()->withInput()->with('errors', ['password' => 'Password minimal 8 karakter']);
            }
            $data['password_hash'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
        }

        $this->userModel->save($data);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        log_activity('Hapus User', "Menghapus user id: $id");
        return redirect()->to('/admin/users')->with('success', 'User deleted successfully');
    }
}
