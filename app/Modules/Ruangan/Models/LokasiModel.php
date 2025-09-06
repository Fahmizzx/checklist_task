<?php

namespace App\Modules\Ruangan\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table = 'tb_lokasi';
    protected $primaryKey = 'id_lokasi';
    protected $allowedFields = ['nama_lokasi', 'created_at', 'updated_at'];
}
