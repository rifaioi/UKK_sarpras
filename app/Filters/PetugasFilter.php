<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PetugasFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role_id') != 2) { // 2 = Petugas
            // If Admin (1) tries to access, maybe redirect to Admin Dashboard?
            // User said "jangan bisa saling login".
            return redirect()->back()->with('error', 'Akses Ditolak. Halaman ini khusus Petugas.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
