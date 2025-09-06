<?php

namespace App\Modules\Pemetaan\Models;

use CodeIgniter\Model;
use App\Modules\Pemetaan\Models\LokasiModel;
use App\Modules\Pemetaan\Models\RoleModel;

class RuanganModel extends Model
{
    protected $table = 'tb_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $allowedFields = ['id_ruangan', 'uuid_ruangan', 'id_lokasi', 'id_role', 'ruangan', 'qrcode'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function lokasi()
    {
        return $this->belongsTo(LokasiModel::class, 'id_lokasi');
    }

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'id_role');
    }

    public function getAll()
    {
        return $this->findAll();
    }

    public function updateRole($id_ruangan, $id_role)
    {
        return $this->update($id_ruangan, [
            'id_role' => $id_role
        ]);
    }

    public function getByNamaLokasi($nama_lokasi)
    {
        return $this->select('tb_ruangan.id_ruangan, tb_ruangan.ruangan')
            ->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi')
            ->where('tb_lokasi.nama_lokasi', $nama_lokasi)
            ->findAll();
    }
}
