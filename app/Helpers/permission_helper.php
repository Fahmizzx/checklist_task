<?php

use App\Modules\Admin\Models\PermissionModel;

if (!function_exists('hasPermission')) {
    /**
     * Memeriksa apakah pengguna yang login memiliki izin untuk mengakses halaman.
     * Cek langsung ke database untuk data yang real-time.
     *
     * @param string $pageKey Kunci untuk halaman/izin yang akan diperiksa.
     * @return bool
     */
    function hasPermission(string $pageKey): bool
    {
        $session = session();
        $userRole = $session->get('role');

        if (!$session->get('logged_in') || !$userRole) {
            return false;
        }

        // Admin selalu memiliki semua izin
        if ($userRole === 'Admin') {
            return true;
        }

        // Cek izin langsung dari database
        $permissionModel = new PermissionModel();
        $permission = $permissionModel->where('role', $userRole)
                                      ->where('page_key', $pageKey)
                                      ->first();

        return !empty($permission);
    }
}
