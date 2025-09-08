<?php

namespace App\Modules\Pemetaan\Models;


use CodeIgniter\Model;

class ChecklistMaintanceModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_checklist_maintance';
    protected $primaryKey       = 'id_checklist_maintance';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ruangan', 'id_aktivitas', 'id_periodik', 'waktu', 'standar', 'status', 'created_at', 'updated_at', 'deleted_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;


    public function getAllWithRelasi()
    {
        return $this->select('tb_checklist_maintance.*, tb_aktivitas.aktivitas, tb_ruangan.ruangan, tb_periodik.periodik')
            ->join('tb_aktivitas', 'tb_aktivitas.id_aktivitas = tb_checklist_maintance.id_aktivitas')
            ->join('tb_ruangan', 'tb_ruangan.id_ruangan = tb_checklist_maintance.id_ruangan')
            ->join('tb_periodik', 'tb_periodik.id_periodik = tb_checklist_maintance.id_periodik')
            ->findAll();
    }

    public function getAllWithLokasiRole()
    {
        return $this->select('tb_checklist_maintance.*, tb_ruangan.ruangan, tb_aktivitas.aktivitas, tb_periodik.periodik, tb_waktu.id_waktu, tb_lokasi.nama_lokasi, tb_lokasi.id_lokasi, tb_role.role, tb_role.id_role')
            ->join('tb_ruangan', 'tb_ruangan.id_ruangan = tb_checklist_maintance.id_ruangan', 'left')
            ->join('tb_aktivitas', 'tb_aktivitas.id_aktivitas = tb_checklist_maintance.id_aktivitas', 'left')
            ->join('tb_periodik', 'tb_periodik.id_periodik = tb_checklist_maintance.id_periodik', 'left')
            ->join('tb_waktu', 'tb_waktu.id_waktu = tb_checklist_maintance.id_checklist_maintance', 'left')
            ->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi', 'left')
            ->join('tb_role', 'tb_role.id_role = tb_ruangan.id_role', 'left')
            ->groupBy('tb_ruangan.id_ruangan')
            ->orderBy('id_checklist_maintance', 'DESC')
            ->get()
            ->getResult();
    }
    public function getByIdWithLokasiRole($id, $id_ruangan)
    {
        return $this->select('tb_checklist_maintance.*, tb_ruangan.ruangan, tb_aktivitas.aktivitas, tb_periodik.periodik, tb_waktu.id_waktu, tb_lokasi.nama_lokasi, tb_lokasi.id_lokasi, tb_role.role, tb_waktu.waktu')
            ->join('tb_ruangan', 'tb_ruangan.id_ruangan = tb_checklist_maintance.id_ruangan', 'left')
            ->join('tb_aktivitas', 'tb_aktivitas.id_aktivitas = tb_checklist_maintance.id_aktivitas', 'left')
            ->join('tb_periodik', 'tb_periodik.id_periodik = tb_checklist_maintance.id_periodik', 'left')
            ->join('tb_waktu', 'tb_waktu.id_waktu = tb_checklist_maintance.id_checklist_maintance', 'left')
            ->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi', 'left')
            ->join('tb_role', 'tb_role.id_role = tb_ruangan.id_role', 'left')
            // ->groupBy('tb_ruangan.id_ruangan')
            // ->where('tb_checklist_maintance.id_checklist_maintance', $id)
            ->where('tb_checklist_maintance.id_ruangan', $id_ruangan)
            ->orderBy('tb_waktu.waktu', 'DESC')
            ->get()
            ->getResult();
    }

    public function saveAktivitas($aktivitas)
    {
        $data = ['aktivitas' => $aktivitas];
        $this->db->table('tb_aktivitas')->insert($data);
        return ['id_aktivitas' => $this->db->insertID()];
    }

    public function saveAktivitasModal($aktivitas)
    {
        $data = ['aktivitas' => $aktivitas];
        $this->db->table('tb_aktivitas')->insert($data);
        return ['id_aktivitas' => $this->db->insertID()];
    }

    public function saveWaktu($waktu)
    {
        $data = ['waktu' => $waktu];
        $this->db->table('tb_waktu')->insert($data);
        return ['id_waktu' => $this->db->insertID()];
    }


    // Fungsi update aktivitas berdasarkan id_aktivitas
    public function updateAktivitas($id_aktivitas, $aktivitasBaru)
    {
        return $this->db->table('tb_aktivitas')
            ->where('id_aktivitas', $id_aktivitas)
            ->update(['aktivitas' => $aktivitasBaru]);

        return $result;
    }

    public function updateChecklist($id, $data)
    {
        return $this->update($id, $data);
    }

    // Relasi dengan tb_waktu (satu checklist_maintance memiliki banyak waktu)
    public function waktu()
    {
        return $this->hasMany(WaktuModel::class, 'id_checklist_maintance', 'id_waktu');
    }
}
