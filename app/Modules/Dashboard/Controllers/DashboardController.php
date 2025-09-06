<?php 

namespace App\Modules\Dashboard\Controllers; 

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // Cek apakah user sudah login. Filter 'auth' di Routes.php akan menangani ini,
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        // Ambil data user dari session.
        $data = [
            'id_users'    => session()->get('id_users'), 
            'nama'       => session()->get('nama'),
            'username'   => session()->get('username'),
            'role'       => session()->get('role')
        ];

        // Tampilkan view dashboard umum dengan data pengguna
        return view('dashboard/index', $data); 
    }
}
