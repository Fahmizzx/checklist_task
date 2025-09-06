<?php

namespace App\Modules\Pemetaan\Models;


use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table            = 'tb_lokasi';
    protected $primaryKey       = 'id_lokasi';
    // protected $allowedFields    = ['nama_lokasi'];
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
