<?php

namespace App\Modules\Ruangan\Controllers;

use App\Modules\Ruangan\Models\RuanganModel;
use App\Modules\Ruangan\Models\LokasiModel;
use App\Modules\Ruangan\Models\UsersModel;
use App\Modules\Ruangan\Models\RoleModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use CodeIgniter\Controller;

class RuanganController extends Controller
{
    protected $lokasiModel;
    protected $ruanganModel;
    protected $roleModel;

    public function __construct()
    {

        $this->lokasiModel = new LokasiModel();
        $this->ruanganModel = new RuanganModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ruangan');
        $builder->select('tb_ruangan.*, tb_lokasi.nama_lokasi, tb_role.role');
        $builder->join('tb_lokasi', 'tb_lokasi.id_lokasi = tb_ruangan.id_lokasi');
        $builder->join('tb_role', 'tb_role.id_role = tb_ruangan.id_role', 'left');
        $builder->orderBy('tb_ruangan.created_at', 'DESC');

        $query = $builder->get();
        $data['ruangan'] = $query->getResultArray();

        $data['lokasi'] = $this->lokasiModel->findAll();
        $data['tb_role'] = $this->roleModel->where('id_role !=', 5)->findAll();

        return view('\App\Modules\Ruangan\Views\index', $data);
    }


    public function create()
    {

        $data['lokasi'] = $this->lokasiModel->findAll();
        $data['tb_role'] = $this->roleModel->findAll();

        return view('\App\Modules\Ruangan\Views\create', $data);
    }

    public function post()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'ruangan'   => 'required|min_length[3]',
            'id_lokasi' => 'required|is_natural_no_zero',
            'role'      => 'required|is_natural_no_zero',
        ];

        $messages = [
            'ruangan' => [
                'required'    => 'Silakan isi nama ruangan terlebih dahulu.',
                'min_length'  => 'Nama ruangan minimal harus terdiri dari 3 karakter.'
            ],
            'id_lokasi' => [
                'required'           => 'Silakan pilih lokasi terlebih dahulu.',
                'is_natural_no_zero' => 'Data lokasi tidak valid.'
            ],
            'role' => [
                'required'           => 'Silakan pilih role petugas terlebih dahulu.',
                'is_natural_no_zero' => 'Data role tidak valid.'
            ]
        ];


        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }
        $model = new \App\Modules\Ruangan\Models\RuanganModel();

        $uuid       = uniqid();
        $ruangan    = $this->request->getPost('ruangan');
        $id_lokasi  = $this->request->getPost('id_lokasi');
        $id_role    = $this->request->getPost('role');

        $lokasiModel = new \App\Modules\Lokasi\Models\LokasiModel();
        $lokasi      = $lokasiModel->find($id_lokasi);
        $nama_lokasi = $lokasi['nama_lokasi'] ?? 'Unknown';

        $roleToController = [
            1 => 'cs',
            2 => 'mechanical',
            3 => 'gardener',
            4 => 'takmir'
        ];
        $controller = $roleToController[$id_role] ?? 'cs';

        $qrContent = base_url("{$controller}/checklist_task/{$uuid}");

        $qrDir  = FCPATH . 'assets/file/qrcode/';
        $qrName = $uuid . '.png';
        $qrPath = $qrDir . $qrName;

        // dd($qrPath);

        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        try {
            $qrCode = new QrCode($qrContent);
            // $qrCode->withSize(300);
            // $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $result->saveToFile($qrPath);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat QR Code: ' . $e->getMessage()
            ]);
        }

        $data = [
            'uuid_ruangan' => $uuid,
            'id_lokasi'    => $id_lokasi,
            'id_role'      => $id_role,
            'ruangan'      => $ruangan,
            'qrcode'       => base_url("{$controller}/checklist_task/{$uuid}"),
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data ruangan dan QR Code berhasil disimpan.',
            'qrcode_url' => base_url("file/qrcode/" . $qrName)
        ]);
    }


    public function edit($id)
    {
        helper('form');

        $data['ruangan'] = $this->ruanganModel->find($id);
        $data['lokasi'] = $this->lokasiModel->findAll();
        $data['tb_role'] = $this->roleModel->findAll();

        if (!$data['ruangan']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data ruangan dengan ID $id tidak ditemukan");
        }

        return view('\App\Modules\Ruangan\Views\edit', $data);
    }


    public function update($id)
    {
        $rules = [
            'ruangan'   => [
                'label' => 'Nama Ruangan',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Silakan isi nama ruangan terlebih dahulu.',
                    'min_length' => 'Nama ruangan minimal 3 karakter.'
                ]
            ],
            'id_lokasi' => [
                'label' => 'Lokasi',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Silakan pilih lokasi terlebih dahulu.'
                ]
            ],
            'role' => [
                'label' => 'Role Petugas',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Silakan pilih petugas terlebih dahulu.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return view('\App\Modules\Ruangan\Views\edit', [
                'validation' => $this->validator,
                'ruangan' => $this->ruanganModel->find($id),
                'lokasi' => $this->lokasiModel->findAll(),
                'tb_role' => $this->roleModel->findAll(),
                'success' => session()->getFlashdata('success')
            ]);
        }

        $model = new \App\Modules\Ruangan\Models\RuanganModel();

        $uuid       = uniqid();
        $ruangan    = $this->request->getPost('ruangan');
        $id_lokasi  = $this->request->getPost('id_lokasi');
        $id_role    = $this->request->getPost('role');

        $lokasiModel = new \App\Modules\Lokasi\Models\LokasiModel();
        $lokasi      = $lokasiModel->find($id_lokasi);
        $nama_lokasi = $lokasi['nama_lokasi'] ?? 'Unknown';

        $roleToController = [
            1 => 'cs',
            2 => 'mechanical',
            3 => 'gardener',
            4 => 'takmir'
        ];
        $controller = $roleToController[$id_role] ?? 'cs';

        $qrContent = base_url("{$controller}/checklist_task/{$uuid}");

        $qrDir  = FCPATH . 'assets/file/qrcode/';
        $qrName = $uuid . '.png';
        $qrPath = $qrDir . $qrName;

        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        try {
            $qrCode = new QrCode($qrContent);
            // $qrCode->withSize(300);
            // $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $result->saveToFile($qrPath);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat QR Code: ' . $e->getMessage()
            ]);
        }

        $data = [
            'uuid_ruangan' => $uuid,
            'id_lokasi' => $id_lokasi,
            'id_role'   => $id_role,
            'ruangan'   => $ruangan,
            'qrcode' => base_url("{$controller}/checklist_task/{$uuid}"),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->ruanganModel->update($id, $data);

        return redirect()->to('/ruangan')->with('success', 'Data ruangan dan QR Code berhasil diperbarui');
    }

    public function delete($id)
    {
        $model = new RuanganModel();
        $model->where('id_ruangan', $id)->delete();
        return redirect()->to('/ruangan')->with('success', 'Data ruangan berhasil dihapus.');
    }
}
