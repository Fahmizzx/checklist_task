<?php

namespace App\Modules\Gardener\Controllers;

use App\Controllers\BaseController;
use App\Modules\Gardener\Models\RuanganModel;
use App\Modules\Gardener\Models\TaskModel;
use App\Modules\Gardener\Models\WaktuModel;

class Task extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ruangan');
        $builder->select('tb_ruangan.uuid_ruangan, tb_ruangan.ruangan AS nama_ruangan, tb_lokasi.nama_lokasi');
        $builder->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi');
        $builder->groupStart()
            ->like('tb_lokasi.nama_lokasi', 'Zona Outdoor - Zona GPT')
            ->orLike('tb_lokasi.nama_lokasi', 'Zona Outdoor - Zona GKL')
            ->orLike('tb_lokasi.nama_lokasi', 'Zona Outdoor - Zona GKP')
            ->groupEnd();
        $builder->orderBy('tb_lokasi.nama_lokasi', 'ASC');
        $builder->orderBy('tb_ruangan.ruangan', 'ASC');
        $query = $builder->get();

        $data['ruangan'] = $query->getResultArray();

        return view('App\Modules\Gardener\Views\index', $data);
    }


    // fungsi untuk menampilkan checklist untuk halaman petugas
    public function checklist($uuid_ruangan)
    {
        $db = \Config\Database::connect();

        // Ambil data ruangan dan role
        $ruangan = $db->table('tb_ruangan')
            ->select('tb_ruangan.*, tb_lokasi.nama_lokasi, tb_role.role')
            ->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi')
            ->join('tb_role', 'tb_role.id_role = tb_ruangan.id_role')
            ->where('uuid_ruangan', $uuid_ruangan)
            ->get()->getRowArray();

        // Jika ruangan tidak ditemukan
        if (!$ruangan) {
            return view('App\Modules\Gardener\Views\Checklist\notfound', ['uuid_ruangan' => $uuid_ruangan]);
        }

        // Ambil checklist maintenance berdasarkan ruangan
        $checklists = $db->table('tb_checklist_maintance')
            ->select('tb_checklist_maintance.*, tb_aktivitas.aktivitas')
            ->join('tb_aktivitas', 'tb_aktivitas.id_aktivitas = tb_checklist_maintance.id_aktivitas')
            ->where('tb_checklist_maintance.id_ruangan', $ruangan['id_ruangan'])
            ->orderBy('tb_checklist_maintance.waktu', 'ASC')
            ->get()->getResultArray();

        // Jika belum ada data checklist maintenance, tampilkan notfound
        if (empty($checklists)) {
            return view('App\Modules\Gardener\Views\Checklist\notfound_task', ['uuid_ruangan' => $uuid_ruangan]);
        }

        // Ambil hanya aktivitasnya
        // $tasks = array_column($checklists, 'aktivitas');

        return view('App\Modules\Gardener\Views\Checklist\checklist_task', [
            'ruangan' => $ruangan,
            'checklists' => $checklists
        ]);
    }



    // fungsi untuk menampilkan rincian checklist task petugas di halaman admin/manager
    public function checklist_task($uuid_ruangan)
    {
        $db = \Config\Database::connect();

        // Ambil data ruangan
        $ruangan = $db->table('tb_ruangan')
            ->select('tb_ruangan.*, tb_lokasi.nama_lokasi, tb_role.role')
            ->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi')
            ->join('tb_role', 'tb_role.id_role = tb_ruangan.id_role')
            ->where('uuid_ruangan', $uuid_ruangan)
            ->get()->getRowArray();

        if (!$ruangan) {
            return view('App\Modules\Gardener\Views\Checklist\notfound', ['uuid_ruangan' => $uuid_ruangan]);
        }

        // Ambil data checklist_task yang terkait ruangan ini
        $tasks = $db->table('tb_checklist_task')
            ->select('
            tb_checklist_task.*,
            tb_checklist_maintance.standar,
            tb_checklist_maintance.id_periodik,
            tb_checklist_maintance.id_checklist_maintance,
            tb_aktivitas.aktivitas,
            tb_periodik.periodik,
            tb_users.nama
        ')
            ->join('tb_checklist_maintance', 'tb_checklist_maintance.id_checklist_maintance = tb_checklist_task.id_checklist_maintance')
            ->join('tb_aktivitas', 'tb_aktivitas.id_aktivitas = tb_checklist_maintance.id_aktivitas')
            ->join('tb_periodik', 'tb_periodik.id_periodik = tb_checklist_maintance.id_periodik', 'left')
            ->join('tb_users', 'tb_users.id_users = tb_checklist_task.id_users', 'left')
            ->where('tb_checklist_maintance.id_ruangan', $ruangan['id_ruangan'])
            ->orderBy('tb_checklist_task.created_at', 'DESC')
            ->get()->getResultArray();

        // Ambil waktu target untuk tiap id_checklist_maintance
        $waktuModel = new WaktuModel(); // pastikan model ini ada dan sesuai
        $dataWaktu = [];

        foreach ($tasks as $task) {
            $waktuList = $waktuModel
                ->where('id_checklist_maintance', $task['id_checklist_maintance'])
                ->findAll();

            foreach ($waktuList as $time) {
                if ($task['id_periodik'] == 1 && !empty($time['waktu'])) {
                    $dataWaktu[$task['id_checklist_maintance']][] = $time['waktu'];
                } elseif ($task['id_periodik'] == 2 && !empty($time['hari'])) {
                    $dataWaktu[$task['id_checklist_maintance']][] = $time['hari'];
                }
            }
        }
        // Jika tidak ada task dari petugas, arahkan ke halaman notfound
        if (empty($tasks)) {
            return view('App\Modules\Gardener\Views\Checklist\notfound', ['uuid_ruangan' => $uuid_ruangan]);
        }


        // Jika ada task petugas masuk, tampilkan halaman rincian_checklist_task
        return view('App\Modules\Gardener\Views\Checklist\rincian_checklist_task', [
            'ruangan' => $ruangan,
            'tasks'   => $tasks,
            'list_waktu' => $dataWaktu
        ]);
    }


    public function saveChecklist($uuid_ruangan)
    {
        $db = \Config\Database::connect();

        $ruangan = $db->table('tb_ruangan')
            ->where('uuid_ruangan', $uuid_ruangan)
            ->get()->getRowArray();

        if (!$ruangan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ruangan tidak ditemukan']);
        }

        $id_checklist = $this->request->getPost('id_checklist_maintance');
        $status = $this->request->getPost('status');

        if (!$id_checklist || !$status) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak valid']);
        }

        $builder = $db->table('tb_checklist_task');

        if ($status === 'Selesai') {
            // Simpan data
            $builder->insert([
                'id_checklist_maintance' => $id_checklist,
                'created_task' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } elseif ($status === 'Batal') {
            // Hapus (soft delete atau delete biasa)
            $builder->where('id_checklist_maintance', $id_checklist)->delete();
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function checklistSelesai()
    {
        return view('App\Modules\Gardener\Views\Checklist\checklist_selesai');
    }
}
