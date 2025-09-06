<?php

namespace App\Modules\Pemetaan\Models;


use CodeIgniter\Model;

class AktivitasModel extends Model
{
    protected $table            = 'tb_aktivitas';
    protected $primaryKey       = 'id_aktivitas';
    protected $allowedFields    = ['aktivitas', 'created_at', 'updated_at', 'deleted_at'];
    // protected $returnType       = 'object';

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getAll()
    {
        return $this->findAll();
    }
}
