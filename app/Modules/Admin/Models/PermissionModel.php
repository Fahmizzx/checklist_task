<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table            = 'tb_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['role', 'page_key'];

    /**
     * Memeriksa apakah sebuah peran memiliki izin untuk mengakses halaman tertentu.
     *
     * @param string $role
     * @param string $pageKey
     * @return bool
     */
    public function hasPermission(string $role, string $pageKey): bool
    {
        if ($role === 'Admin') {
            return true; // Admin selalu memiliki akses
        }

        $permission = $this->where('role', $role)
                           ->where('page_key', $pageKey)
                           ->first();

        return !empty($permission);
    }
}
