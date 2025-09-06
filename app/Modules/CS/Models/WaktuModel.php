<?php

namespace App\Modules\CS\Models;


use CodeIgniter\Model;

class WaktuModel extends Model
{
    protected $table            = 'tb_waktu';
    protected $primaryKey       = 'id_waktu';
    protected $allowedFields    = ['id_checklist_maintance', 'id_periodik', 'waktu', 'hari', 'created_at', 'updated_at', 'deleted_at'];
    // protected $returnType       = 'object';


    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getAll()
    {
        return $this->findAll();
    }

    // Relasi dengan tb_checklist_maintance (setiap waktu dimiliki oleh satu checklist_maintance)
    // public function checklistMaintance()
    // {
    //     return $this->belongsTo(ChecklistMaintanceModel::class, 'id_checklist_maintance', 'id_checklist_maintance');
    // }
}
