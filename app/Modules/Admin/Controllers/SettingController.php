<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Auth\Models\UserModel;
use App\Modules\Auth\Models\RoleModel;
use App\Modules\Admin\Models\PermissionModel;
use App\Modules\Admin\Models\PageModel;
use CodeIgniter\Controller;

class SettingController extends Controller
{
    protected $userModel;
    protected $roleModel;
    protected $permissionModel;
    protected $pageModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->pageModel = new PageModel();
    }

    /**
     * Menampilkan halaman CRUD untuk Role.
     */
    public function role()
    {
        $data = [
            'roles' => $this->roleModel->orderBy('role', 'ASC')->findAll(),
        ];
        return view('App\Modules\Admin\Views\setting\role', $data);
    }

    /**
     * Menampilkan halaman CRUD untuk Permission.
     */
    public function permission()
    {
        $data['pages'] = $this->pageModel->orderBy('page_name', 'ASC')->findAll();
        $data['roles'] = $this->roleModel->where('role !=', 'Admin')->findAll();
        $data['permissions'] = $this->permissionModel->findAll();

        return view('App\Modules\Admin\Views\setting\permission', $data);
    }

    /**
     * Proses membuat role baru.
     */
    public function createRole()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'role' => 'required|is_unique[tb_role.role]'
        ], [
            'role' => [
                'required' => 'Nama peran tidak boleh kosong.',
                'is_unique' => 'Nama peran ini sudah ada.'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error_msg', $validation->getErrors()['role']);
            return redirect()->to('admin/setting/role');
        }

        $this->roleModel->save([
            'role' => $this->request->getPost('role'),
            'full' => $this->request->getPost('full')
        ]);

        session()->setFlashdata('success_msg', 'Peran baru berhasil ditambahkan.');
        return redirect()->to('admin/setting/role');
    }

    /**
     * Proses memperbarui role.
     */
    public function updateRole()
    {
        $id = $this->request->getPost('id_role');
        $validation = \Config\Services::validation();
        $validation->setRules([
            'role' => "required|is_unique[tb_role.role,id_role,{$id}]"
        ], [
            'role' => [
                'required' => 'Nama peran tidak boleh kosong.',
                'is_unique' => 'Nama peran ini sudah ada.'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error_msg', $validation->getErrors()['role']);
            return redirect()->to('admin/setting/role');
        }

        $this->roleModel->update($id, [
            'role' => $this->request->getPost('role'),
            'full' => $this->request->getPost('full')
        ]);

        session()->setFlashdata('success_msg', 'Peran berhasil diperbarui.');
        return redirect()->to('admin/setting/role');
    }

    /**
     * Proses menghapus role.
     */
    public function deleteRole()
    {
        $id = $this->request->getPost('id_role');
        $role = $this->roleModel->find($id);

        if ($role) {
            $userWithRole = $this->userModel->where('role', $role['role'])->first();
            if ($userWithRole) {
                session()->setFlashdata('error_msg', 'Gagal menghapus! Peran ini sedang digunakan oleh pengguna.');
                return redirect()->to('admin/setting/role');
            }

            $this->roleModel->delete($id);
            session()->setFlashdata('success_msg', 'Peran berhasil dihapus.');
        } else {
            session()->setFlashdata('error_msg', 'Peran tidak ditemukan.');
        }

        return redirect()->to('admin/setting/role');
    }

    /**
     * Menyimpan perubahan perizinan dari form.
     */
    public function updatePermissions()
    {
        $permissions = $this->request->getPost('permissions');
        $this->permissionModel->where('role !=', 'Admin')->delete();

        if (!empty($permissions)) {
            foreach ($permissions as $role => $pages) {
                if (is_array($pages)) {
                    foreach ($pages as $pageKey) {
                        $this->permissionModel->insert(['role' => $role, 'page_key' => $pageKey]);
                    }
                }
            }
        }

        session()->setFlashdata('success_msg', 'Perizinan berhasil diperbarui.');
        return redirect()->to('admin/setting/permission');
    }

    /**
     * Proses membuat halaman baru.
     */
    public function createPage()
    {
        $data = [
            'page_key'   => $this->request->getPost('page_key'),
            'page_name'  => $this->request->getPost('page_name'),
            'parent_key' => $this->request->getPost('parent_key') ?: null,
            'url'        => $this->request->getPost('url'),
            'icon'       => $this->request->getPost('icon'),
            'menu_order' => $this->request->getPost('menu_order'),
        ];

        if (!$this->pageModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->pageModel->errors());
        }

        session()->setFlashdata('success_msg', 'Halaman baru berhasil ditambahkan.');
        return redirect()->to('admin/setting/permission');
    }

    /**
     * Proses memperbarui halaman.
     */
    public function updatePage()
    {
        $id = $this->request->getPost('id');
        $data = [
            'page_key'   => $this->request->getPost('page_key'),
            'page_name'  => $this->request->getPost('page_name'),
            'parent_key' => $this->request->getPost('parent_key') ?: null,
            'url'        => $this->request->getPost('url'),
            'icon'       => $this->request->getPost('icon'),
            'menu_order' => $this->request->getPost('menu_order'),
        ];

        if (!$this->pageModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->pageModel->errors());
        }

        session()->setFlashdata('success_msg', 'Halaman berhasil diperbarui.');
        return redirect()->to('admin/setting/permission');
    }

    /**
     * Proses menghapus halaman.
     */
    public function deletePage()
    {
        $id = $this->request->getPost('id');
        $this->pageModel->delete($id);
        session()->setFlashdata('success_msg', 'Halaman berhasil dihapus.');
        return redirect()->to('admin/setting/permission');
    }
}
