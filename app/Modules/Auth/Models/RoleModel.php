<?php 

namespace App\Modules\Auth\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'tb_role';
    protected $primaryKey       = 'id_role';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['role', 'full']; 

    protected $useTimestamps = false;
}
