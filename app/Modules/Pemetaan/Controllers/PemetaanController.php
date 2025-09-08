<?php

namespace App\Modules\Pemetaan\Controllers;

use App\Modules\Pemetaan\Models\ChecklistMaintanceModel;
use App\Modules\Pemetaan\Models\LokasiModel;
use App\Modules\Pemetaan\Models\RuanganModel;
use App\Modules\Pemetaan\Models\RoleModel;
use App\Modules\Pemetaan\Models\PeriodikModel;
use App\Modules\Pemetaan\Models\WaktuModel;
use App\Modules\Pemetaan\Models\AktivitasModel;
use CodeIgniter\Controller;
use Carbon\Carbon;

class PemetaanController extends Controller
{
    protected $checklistmaintenanceModel;
    protected $lokasiModel;
    protected $ruanganModel;
    protected $roleModel;
    protected $periodikModel;
    protected $waktuModel;
    protected $aktivitasModel;
    protected $db;

    public function __construct()
    {
        $this->checklistmaintenanceModel = new ChecklistMaintanceModel();
        $this->lokasiModel = new LokasiModel();
        $this->ruanganModel = new RuanganModel();
        $this->roleModel = new RoleModel();
        $this->periodikModel = new PeriodikModel();
        $this->waktuModel = new WaktuModel();
        $this->aktivitasModel = new AktivitasModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];

        $data['tb_checklist_maintance']   = $this->checklistmaintenanceModel->getAllWithLokasiRole();
        $data['tb_lokasi']   = $this->lokasiModel->getAll();
        $data['tb_role']   = $this->roleModel->getAll();
        $data['tb_ruangan']   = $this->ruanganModel->getAll();
        $data['tb_periodik']   = $this->periodikModel->getAll();
        $data['tb_waktu']   = $this->waktuModel->getAll();
        return view('\App\Modules\Pemetaan\Views\index', $data);
    }

    public function create()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];

        // return view('auth/login', $data);

        $data['tb_lokasi']   = $this->lokasiModel->getAll();
        $data['tb_role']   = $this->roleModel->where('id_role !=', 5)->findAll();
        $data['tb_periodik']   = $this->periodikModel->getAll();
        $data['tb_ruangan']   = $this->ruanganModel->getAll();
        $data['tb_waktu']   = $this->waktuModel->getAll();
        return view('\App\Modules\Pemetaan\Views\create', $data);
    }

    public function detail($id, $id_ruangan)
    {
        $checklist = $this->checklistmaintenanceModel->getByIdWithLokasiRole($id, $id_ruangan);
        if (empty($checklist)) {
            // Debug: apakah data kosong?
            dd("Data checklist kosong untuk id_ruangan =$id, $id_ruangan");
        }
        $data['tb_checklist_maintance'] = $checklist;
        $data['first_maintance'] = $checklist[0] ?? null;

        // dd($data['tb_checklist_maintance']);

        $dataWaktu = [];

        if (!empty($data['tb_checklist_maintance'])) {
            foreach ($data['tb_checklist_maintance'] as $maintance) {
                $waktuList = $this->waktuModel
                    ->where('id_checklist_maintance', $maintance->id_checklist_maintance)
                    ->findAll();

                foreach ($waktuList as $time) {
                    if ($maintance->id_periodik == 1 && !empty($time['waktu'])) {
                        $dataWaktu[$maintance->id_checklist_maintance][] = $time['waktu'];
                    } elseif ($maintance->id_periodik == 2 && !empty($time['hari'])) {
                        $dataWaktu[$maintance->id_checklist_maintance][] = $time['hari'];
                    }
                }
            }
        }

        // dd($data['tb_checklist_maintance']);

        $data['list_waktu'] = $dataWaktu;

        // Ambil data waktu berdasarkan id_checklist_maintance

        // dd($data['list_waktu']);
        $data['tb_lokasi']   = $this->lokasiModel->getAll();
        $data['tb_role']   = $this->roleModel->getAll();
        $data['tb_periodik']   = $this->periodikModel->getAll();
        $data['tb_aktivitas']   = $this->aktivitasModel->getAll();
        $data['tb_ruangan']   = $this->ruanganModel->where('id_lokasi', 4)->findAll();
        // dd($data);
        return view('\App\Modules\Pemetaan\Views\detail', $data);
    }


    // fungsi untuk mendapatkan ruangan berdasarkan id lokasi
    public function getRuanganByLokasi($id_lokasi)
    {
        $ruangan = $this->ruanganModel->where('id_lokasi', $id_lokasi)->findAll();
        return $this->response->setJSON($ruangan);
    }

    // fungsi untuk mendapatkan ruangan berdasarkan nama lokasi
    public function getRuanganByNamaLokasi($nama_lokasi)
    {
        $nama_lokasi = urldecode($nama_lokasi);
        $ruangan = $this->ruanganModel->getByNamaLokasi($nama_lokasi);
        return $this->response->setJSON($ruangan);
    }



    // fungsi untuk menyimpan data ketika tambah data di halaman create

    public function store()
    {
        $validation = \Config\Services::validation();

        // Validasi utama (bukan nested)
        $rules = [
            'lokasi'  => 'required|is_natural_no_zero',
            'petugas' => 'required|is_natural_no_zero',
        ];

        $customMessages = [
            'lokasi'  => [
                'required' => 'Silakan pilih lokasi terlebih dahulu.',
                'is_natural_no_zero' => 'Silakan pilih lokasi yang valid.',
            ],
            'petugas' => [
                'required' => 'Silakan pilih petugas terlebih dahulu.',
                'is_natural_no_zero' => 'Silakan pilih petugas yang valid.',
            ],
        ];

        if (!$this->validate($rules, $customMessages)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ])->setStatusCode(422);
        }

        // Ambil data POST
        $post = $this->request->getPost();
        $aktivitasList = $post['list_aktivitas'] ?? [];

        // Validasi manual nested repeater
        if (empty($aktivitasList) || !is_array($aktivitasList)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => ['list_aktivitas' => 'Minimal 1 aktivitas wajib diisi.']
            ])->setStatusCode(422);
        }

        // Proses penyimpanan ke database
        $checklistModel = new ChecklistMaintanceModel();
        $waktuModel = new WaktuModel();

        // var_dump($aktivitasList);
        // dd($aktivitasList);
        
        // $resultAktivitas = $checklistModel->saveAktivitas($aktivitasList['aktivitas']);
        // $id_aktivitas = $resultAktivitas['id_aktivitas'];

        // $checklistModel->insert([
        //         'id_ruangan'    => $aktivitasList['ruangan'],
        //         'id_aktivitas'  => $id_aktivitas,
        //         'id_periodik'   => $aktivitasList['periodik'],
        //         'standar'       => $aktivitasList['standar'],
        //     ]);
        // $checklistId = $checklistModel->getInsertID();

        // $waktuModel->save([
        //     'id_checklist_maintance'    => $checklistId,
        //     'id_periodik'               => $aktivitasList['periodik'],
        //     'waktu'                     => $aktivitasList['waktu'],
        //     'id_ruangan'                => $aktivitasList['ruangan'],
        // ]);
        

        foreach ($aktivitasList as $aktivitas) {

            // Simpan aktivitas
            $resultAktivitas = $checklistModel->saveAktivitas($aktivitas['aktivitas']);
            $id_aktivitas = $resultAktivitas['id_aktivitas'];


            // Simpan checklist maintenance
            $checklistModel->insert([
                'id_ruangan'    => $aktivitas['ruangan'],
                'id_aktivitas'  => $id_aktivitas,
                'id_periodik'   => $aktivitas['periodik'],
                'standar'       => $aktivitas['standar'],
                'waktu'         => $aktivitas['waktu'],
            ]);
            $checklistId = $checklistModel->getInsertID();

            $waktuModel->insert([
                'id_checklist_maintance'    => $checklistId,
                'id_periodik'               => $aktivitas['periodik'],
                'waktu'                     => $aktivitas['waktu'],
                'id_ruangan'                => $aktivitas['ruangan'],
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data pemetaan checklist berhasil disimpan.'
        ]);
    }



    public function editAktivitas($id)
    {
        $data['tb_checklist_maintance'] = $this->checklistmaintenanceModel->find($id);
        return view('pemetaan/detail/', $data);
    }

    public function editPemetaan($id)
    {
        $data['tb_checklist_maintance']   = $this->checklistmaintenanceModel->getAllWithLokasiRole()->find($id);
        return view('/pemetaan', $data);
    }


    // fungsi update di halaman index
    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'lokasi'    => 'required|is_natural_no_zero',
            'ruangan'   => 'required|is_natural_no_zero',
            'petugas'   => 'required|is_natural_no_zero',
            'periodik'  => 'required|is_natural_no_zero',
        ];

        $messages = [
            'lokasi' => [
                'required' => 'Silakan pilih lokasi.',
                'is_natural_no_zero' => 'Lokasi tidak valid.',
            ],
            'ruangan' => [
                'required' => 'Silakan pilih ruangan.',
                'is_natural_no_zero' => 'Ruangan tidak valid.',
            ],
            'petugas' => [
                'required' => 'Silakan pilih petugas.',
                'is_natural_no_zero' => 'Petugas tidak valid.',
            ],
            'periodik' => [
                'required' => 'Silakan pilih periodik.',
                'is_natural_no_zero' => 'Periodik tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ])->setStatusCode(422);
        }

        // Update checklist_maintenance
        $this->checklistmaintenanceModel->update($id, [
            'id_lokasi' => $this->request->getPost('lokasi'),
            'id_ruangan' => $this->request->getPost('ruangan'),
            'id_role' => $this->request->getPost('role'),
            'id_periodik' => $this->request->getPost('periodik')
        ]);

        // Update juga petugas pada tb_ruangan
        $ruanganModel = new RuanganModel();
        $ruanganModel->updateRole($this->request->getPost('ruangan'), $this->request->getPost('petugas'));

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.'
        ]);
    }



    // fungsi untuk hapus data di halaman index
    public function delete($id)
    {
        $this->checklistmaintenanceModel->delete($id);
        $this->aktivitasModel->delete($id);
        $this->waktuModel->delete($id);

        return redirect()->to('pemetaan');
    }


    // fungsi hapus data di halaman detail pemetaan (hapus keseluruhan tampilan)
    public function deletePemetaan($id, $id_ruangan)
    {
        $this->checklistmaintenanceModel->delete($id, $id_ruangan);
        // $this->aktivitasModel->delete($id);
        // $this->waktuModel->delete($id);
    }


    // fungsi hapus di halaman detail (hapus data di dalam tabel)
    public function deleteAktivitas($id)
    {
        $checklistModel = new ChecklistMaintanceModel();
        $aktivitasModel = new AktivitasModel();
        $waktuModel = new WaktuModel();

        // Ambil id_aktivitas dari checklist_maintance
        $checklist = $checklistModel->find($id);
        if (!$checklist) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        $id_aktivitas = $checklist['id_aktivitas'];

        // Hapus dari tabel waktu
        $waktuModel->where('id_checklist_maintance', $id)->delete();

        // Hapus dari tabel checklist_maintance
        $checklistModel->delete($id);

        // Hapus dari tabel aktivitas
        $aktivitasModel->delete($id_aktivitas);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }




    // fungsi untuk tambah/simpan aktivitas di halaman detail
    // public function storeAktivitas($id, $id_ruangan)
    // {
    //     $post = $this->request->getPost();
    //     // dd($post);

    //     $checklistmaintenanceModel = new ChecklistMaintanceModel();
    //     $waktuModel = new WaktuModel();
    //     $ruanganModel = new RuanganModel();

    //     // Simpan aktivitas ke tabel aktivitas
    //     $result = $checklistmaintenanceModel->saveAktivitasModal($post['aktivitas']);
    //     $id_aktivitas = $result['id_aktivitas'];

    //     // Simpan ke tabel checklist_maintance
    //     $dataChecklist = [
    //         'id_checklist_maintance' => $post['id_checklist_maintance'],
    //         'id_ruangan' => $post['ruangan'],
    //         'id_aktivitas' => $id_aktivitas,
    //         'id_periodik' => $post['periodik'],
    //         'standar' => $post['standar']
    //     ];

    //     $checklistmaintenanceModel->insert($dataChecklist);
    //     $checklistId = $checklistmaintenanceModel->getInsertID(); // ID untuk foreign key di tabel waktu

    //     // Simpan data waktu/hari
    //     if (isset($post['list_waktu']) && is_array($post['list_waktu'])) {
    //         foreach ($post['list_waktu'] as $item) {
    //             $dataWaktu = [
    //                 'id_checklist_maintance' => $checklistId,
    //                 'id_periodik' => $post['periodik'],
    //             ];

    //             if ($post['periodik'] == '1' && !empty($item['waktu'])) {
    //                 // Jika harian, simpan waktu
    //                 $dataWaktu['waktu'] = $item['waktu'];
    //             } elseif ($post['periodik'] == '2' && !empty($item['hari'])) {
    //                 // Jika mingguan, simpan hari
    //                 $dataWaktu['hari'] = $item['hari'];
    //             } else {
    //                 continue; // Lewati jika tidak valid
    //             }

    //             $waktuModel->save($dataWaktu);
    //         }
    //     }

    //     return redirect()->to('pemetaan/detail/' . $id . '/' . $id_ruangan);
    // }

    public function storeAktivitas($id, $id_ruangan)
    {
        $validation = \Config\Services::validation();
        $post = $this->request->getPost();

        // Debug tanpa menghentikan eksekusi
        log_message('debug', 'Post data: ' . print_r($post, true));

        // Validasi manual
        $errors = [];

        if (empty($post['ruangan'])) {
            $errors['ruangan'] = 'Ruangan wajib dipilih.';
        }

        if (empty($post['aktivitas'])) {
            $errors['aktivitas'] = 'Deskripsi aktivitas wajib diisi.';
        }

        if (empty($post['periodik'])) {
            $errors['periodik'] = 'Periodik wajib dipilih.';
        }

        if (empty($post['standar'])) {
            $errors['standar'] = 'Standar wajib diisi.';
        }

        if (empty($post['list_waktu']) || !is_array($post['list_waktu'])) {
            $errors['list_waktu'] = 'Minimal 1 waktu/hari wajib diisi.';
        } else {
            foreach ($post['list_waktu'] as $i => $waktuItem) {
                if ($post['periodik'] === "1" && empty($waktuItem['waktu'])) {
                    $errors["list_waktu.$i.waktu"] = 'Waktu wajib diisi untuk periodik harian.';
                }
                if ($post['periodik'] === "2" && empty($waktuItem['hari'])) {
                    $errors["list_waktu.$i.hari"] = 'Hari wajib dipilih untuk periodik mingguan.';
                }
            }
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $errors
            ])->setStatusCode(422);
        }

        // Simpan data ke database
        $checklistmaintanceModel = new ChecklistMaintanceModel();
        $waktuModel = new WaktuModel();

        $resultAktivitas = $checklistmaintanceModel->saveAktivitasModal($post['aktivitas']);
        $id_aktivitas = $resultAktivitas['id_aktivitas'];

        $dataChecklist = [
            'id_checklist_maintance' => $post['id_checklist_maintance'],
            'id_ruangan'    => $post['ruangan'],
            'id_aktivitas'  => $id_aktivitas,
            'id_periodik'   => $post['periodik'],
            'standar'       => $post['standar'],
        ];

        $checklistmaintanceModel->insert($dataChecklist);
        $checklistId = $checklistmaintanceModel->getInsertID();

        foreach ($post['list_waktu'] as $item) {
            $waktuModel->insert([
                'id_checklist_maintance' => $checklistId,
                'id_periodik'            => $post['periodik'],
                'waktu'                  => $post['periodik'] === "1" ? $item['waktu'] : null,
                // 'hari'                   => $post['periodik'] === "2" ? $item['hari'] : null,
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Aktivitas checklist berhasil ditambahkan.',
            'id_checklist_maintance' => $id,
            'id_ruangan' => $id_ruangan,
        ]);
    }




    // Update di halaman detail
    public function updateAktivitas($id, $id_ruangan)
    {
        $checklist = $this->checklistmaintenanceModel->find($id);
        if (!$checklist) {
            return redirect()->back()->with('error', 'Checklist tidak ditemukan.');
        }

        // dd($this->request->getPost());
        $id_aktivitas = $checklist['id_aktivitas'];

        $dataAktivitas = [
            'aktivitas' => $this->request->getPost('aktivitas')
        ];
        $this->db->table('tb_aktivitas')->where('id_aktivitas', $id_aktivitas)->update($dataAktivitas);

        $dataChecklist = [
            'id_ruangan'  => $this->request->getPost('id_ruangan'),
            'id_periodik' => $this->request->getPost('periodik'),
            'standar'     => $this->request->getPost('standar'),
        ];
        $this->db->table('tb_checklist_maintance')->where('id_checklist_maintance', $id)->update($dataChecklist);


        $listWaktu = $this->request->getPost('list_waktu');
        // dd($listWaktu);
        $idWaktuPakai = [];

        if (is_array($listWaktu)) {
            foreach ($listWaktu as $item) {
                $id_waktu = $item['id_waktu'] ?? null;
                $waktu    = trim($item['waktu'] ?? '');
                $hari     = trim($item['hari'] ?? '');

                if (empty($waktu) && empty($hari)) continue;

                $dataWaktu = [
                    'id_checklist_maintance' => $id,
                    'waktu' => $waktu ?: null,
                    'hari'  => $hari ?: null,
                ];

                if (!empty($id_waktu)) {
                    // update data lama
                    $this->db->table('tb_waktu')->where('id_waktu', $id_waktu)->update($dataWaktu);
                    $idWaktuPakai[] = $id_waktu;
                } else {
                    // tambah data baru
                    $this->db->table('tb_waktu')->insert($dataWaktu);
                    $idWaktuPakai[] = $this->db->insertID();
                }
            }
        }

        if (!empty($idWaktuPakai)) {
            $this->db->table('tb_waktu')
                ->where('id_checklist_maintance', $id)
                ->whereNotIn('id_waktu', $idWaktuPakai)
                ->delete();
        }

        return redirect()->to('/pemetaan/detail/' . $id . '/' . $id_ruangan)->with('success', 'Aktivitas berhasil diperbarui.');
    }
}
