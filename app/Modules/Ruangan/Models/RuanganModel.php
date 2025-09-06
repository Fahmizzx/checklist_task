<?php

namespace App\Modules\Ruangan\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'tb_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $allowedFields = [
        'uuid_ruangan',
        'id_lokasi',
        'id_role',
        'ruangan',
        'qrcode',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
