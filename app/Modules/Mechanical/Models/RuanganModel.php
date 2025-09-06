<?php

namespace App\Modules\Mechanical\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'tb_ruangan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['uuid_ruangan', 'id_lokasi', 'ruangan', 'id_role'];
}
