<?php

namespace App\Modules\Lokasi\Models;
use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table = 'tb_lokasi';
    protected $primaryKey = 'id_lokasi';
    protected $allowedFields = [
        'id_lokasi','nama_lokasi','created_at', 'updated_at', 'deleted_at'
    ];
}