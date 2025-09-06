<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userRole = $session->get('role');

        // Jika tidak ada peran di sesi, kembalikan ke login
        if (!$userRole) {
            return redirect()->to(base_url('auth/login'));
        }

        // PERBAIKAN UTAMA: Jika peran adalah 'Admin', langsung izinkan akses
        if ($userRole === 'Admin') {
            return; // Lanjutkan ke controller
        }

        // Jika tidak ada argumen peran yang dibutuhkan oleh rute, tolak akses untuk non-admin
        if (empty($arguments)) {
            return redirect()->to(base_url('dashboard'))->with('error_msg', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
        
        // Periksa apakah peran pengguna ada di dalam daftar peran yang diizinkan
        if (!in_array($userRole, $arguments)) {
            // Jika tidak diizinkan, kembalikan ke dashboard dengan pesan error
            return redirect()->to(base_url('dashboard'))->with('error_msg', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa pun setelah request
    }
}
