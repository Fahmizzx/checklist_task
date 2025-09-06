<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Auth\Models\UserModel;
use App\Modules\Auth\Models\RoleModel;
use CodeIgniter\Controller;

class UsersController extends Controller
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
        $session = session();
        $loggedInUserId = $session->get('id_user');

        $users = $this->userModel
            ->where('id_users !=', $loggedInUserId)
            ->orderBy('aktivasi', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $roles = $this->roleModel->whereNotIn('role', ['Admin'])->findAll();

        $data = [
            'users'      => $users,
            'roles'      => $roles,
        ];

        return view('App\Modules\Admin\Views\users\index', $data);
    }

    public function storeUser()
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

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Jika validasi berhasil, simpan data
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

        session()->setFlashdata('success_msg', 'Pengguna baru berhasil ditambahkan dan menunggu persetujuan.');
        return redirect()->to(base_url('admin/users'));
    }


    public function update($userId)
    {
        $session = session();
        $action = $this->request->getPost('action');

        if (!$action) {
            return redirect()->to(base_url('admin/users'))->with('error_msg', 'Aksi tidak valid.');
        }

        $dataToUpdate = [];
        $successMessage = '';

        switch ($action) {
            case 'update_role':
                $newRole = $this->request->getPost('role');
                if (empty($newRole)) {
                    return redirect()->to(base_url('admin/users'))->with('error_msg', 'Silakan pilih peran untuk pengguna.');
                }
                $dataToUpdate = [
                    'role'     => $newRole,
                    'aktivasi' => 1,
                    'banned'   => 0
                ];
                $successMessage = 'Peran pengguna berhasil diperbarui.';
                break;

            case 'ban':
                $dataToUpdate = ['banned' => 1];
                $successMessage = 'Pengguna berhasil diblokir.';
                break;

            case 'unban':
                $dataToUpdate = ['banned' => 0];
                $successMessage = 'Blokir pengguna berhasil dibuka.';
                break;

            default:
                return redirect()->to(base_url('admin/users'))->with('error_msg', 'Aksi tidak dikenal.');
        }

        if ($this->userModel->update($userId, $dataToUpdate)) {
            $session->setFlashdata('success_msg', $successMessage);
        } else {
            $session->setFlashdata('error_msg', 'Gagal memperbarui status pengguna.');
        }

        return redirect()->to(base_url('admin/users'));
    }

    public function deleteUser()
    {
        $id = $this->request->getPost('id_users');
        $user = $this->userModel->find($id);

        if ($user) {
            $this->userModel->delete($id);
            session()->setFlashdata('success_msg', 'Pengguna berhasil dihapus.');
        } else {
            session()->setFlashdata('error_msg', 'Pengguna tidak ditemukan.');
        }

        return redirect()->to('admin/users');
    }
}
