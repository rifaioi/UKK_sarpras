<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        // Check if user still exists and is not deleted
        $userId = session()->get('id');
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('id', $userId)->where('is_deleted', 0)->first();

        if (!$user) {
            session()->destroy();
            return redirect()->to('/')->with('error', 'Akun Anda telah dinonaktifkan atau dihapus.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
