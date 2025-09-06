<?php

namespace App\Modules\Pemetaan\Models;


use CodeIgniter\Model;

class PeriodikModel extends Model
{
    protected $table            = 'tb_periodik';
    protected $primaryKey       = 'id_periodik';
    // protected $allowedFields    = ['periodik'];
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
