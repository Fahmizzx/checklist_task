<?php 

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Models\UserModel;
use App\Modules\Auth\Models\RoleModel;
use App\Modules\Admin\Models\PermissionModel;
use CodeIgniter\Controller;

class UserController extends Controller
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
            'validation' => \Config\Services::validation()
        ];
        
        return view('auth/login', $data);
    }

    public function loginprocess()
    {
        $session = session();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $user = $this->userModel->where('username', $username)->first();

        if ($user) {
            if ($user['banned'] == 1) {
                session()->setFlashdata('error_msg', 'Akun Anda telah diblokir. Silakan hubungi Admin.');
                return redirect()->to(base_url('auth/login'))->withInput();
            }
            if ($user['aktivasi'] == 0) {
                session()->setFlashdata('error_msg', 'Akun Anda belum diaktifkan. Silakan tunggu persetujuan Admin.');
                return redirect()->to(base_url('auth/login'))->withInput();
            }
            if (password_verify($password, $user['password'])) {
                
                $sessionData = [
                    'id_user'     => $user['id_users'], 
                    'username'    => $user['username'],
                    'nama'        => $user['nama'],
                    'email'       => $user['email'],
                    'role'        => $user['role'],
                    'logged_in'   => true
                ];
                $session->set($sessionData);
                return redirect()->to(base_url('dashboard'));
            } else {
                session()->setFlashdata('error_msg', 'Password yang Anda masukkan salah.');
                return redirect()->to(base_url('auth/login'))->withInput();
            }
        } else {
            session()->setFlashdata('error_msg', 'Username tidak ditemukan.');
            return redirect()->to(base_url('auth/login'))->withInput();
        }
    }

    public function register()
    {
        $data = [
            'validation' => \Config\Services::validation(),
        ];
        return view('auth/register', $data);
    }

    public function registerprocess()
    {
        helper(['form']);
        
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[50]',
            'username'         => 'required|min_length[3]|max_length[20]|is_unique[tb_users.username]',
            'email'            => 'required|valid_email|is_unique[tb_users.email]',
            'password'         => 'required|max_length[200]',
            'confirm_password' => 'required|matches[password]'
        ];

        $errors = [
            'confirm_password' => [
                'matches' => 'Konfirmasi password tidak cocok dengan password.'
            ]
        ];

        if ($this->validate($rules, $errors)) {
            $data = [
                'nama'       => $this->request->getVar('nama'),
                'username'   => $this->request->getVar('username'),
                'email'      => $this->request->getVar('email'),
                'password'   => $this->request->getVar('password'),
                'role'       => null,
                'aktivasi'   => 0,
                'banned'     => 0
            ];
            $this->userModel->save($data);

            session()->setFlashdata('success_msg', 'Registrasi berhasil. Akun Anda menunggu persetujuan Admin.');
            return redirect()->to(base_url('auth/login'));
        } else {
            $data['validation'] = $this->validator;
            return view('auth/register', $data);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}
