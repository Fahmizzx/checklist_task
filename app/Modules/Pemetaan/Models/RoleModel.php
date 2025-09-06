<?php

namespace App\Modules\Pemetaan\Models;


use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'tb_role';
    protected $primaryKey       = 'id_role';
    protected $allowedFields    = ['role'];
    // protected $returnType       = 'object';

    // Dates
    // protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    public function getAll()
    {
        return $this->findAll();
    }
}
