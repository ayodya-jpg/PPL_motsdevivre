<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika belum login
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth')->with('error', 'Silahkan login terlebih dahulu.');
        }

        // (Opsional) Jika mencoba akses halaman admin tapi role bukan admin
        // Cek URL segmen, jika ada kata 'admin' dan role user bukan 'admin'
        $uri = service('uri');
        if ($uri->getSegment(1) == 'admin' && session()->get('role') != 'admin') {
             return redirect()->to('/shop')->with('error', 'Anda tidak memiliki akses Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}