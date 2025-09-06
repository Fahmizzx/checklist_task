<?php

namespace App\Modules\Ruangan\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'tb_users';
    protected $primaryKey = 'id_role';
    protected $allowedFields = ['nama', 'username', 'role', 'password', 'created_at', 'updated_at'];
}
