<?php

namespace App\Modules\Lokasi\Controllers;

use App\Modules\Lokasi\Models\LokasiModel;
use CodeIgniter\Controller;

class LokasiController extends Controller
{
    public function index()
    {
        $model = new LokasiModel();
        $data['lokasi'] = $model->orderBy('created_at', 'DESC')->findAll();

        return view('\App\Modules\Lokasi\Views\index', $data);
    }


    public function create()
    {
        return view('\App\Modules\Lokasi\Views\create');
    }

    public function post()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_lokasi' => [
                'label' => 'Nama Lokasi',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama lokasi wajib diisi.',
                    'min_length' => 'Nama lokasi minimal 3 karakter.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new LokasiModel();
        $data = [
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $model->insert($data);


        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data berhasil disimpan!'
        ]);
    }


    public function edit($id)
    {
        $model = new LokasiModel();
        $data['lokasi'] = $model->find($id);

        if (!$data['lokasi']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data ruangan dengan ID $id tidak ditemukan");
        }

        return view('\App\Modules\Lokasi\Views\edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_lokasi' => [
                'label' => 'Nama Lokasi',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama lokasi wajib diisi.',
                    'min_length' => 'Nama lokasi minimal 3 karakter.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new LokasiModel();
        $model->update($id, [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data lokasi berhasil diperbarui'
        ]);
    }

    // public function update()
    // {
    //     $model = new LokasiModel();
    //     $id = $this->request->getPost('id_lokasi');

    //     $data = [
    //         'nama_lokasi' => $this->request->getPost('nama_lokasi'),
    //         'updated_at' => date('Y-m-d H:i:s'),
    //     ];

    //     try {
    //         $model->update($id, $data);
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Data lokasi berhasil diperbarui'
    //         ]);
    //     } catch (\Exception $e) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Gagal memperbarui data lokasi'
    //         ]);
    //     }
    // }

    public function delete($id)
    {
        $model = new LokasiModel();
        // dd($id);
        $model->where('id_lokasi', $id)->delete();
        return redirect()->to('/lokasi')->with('success', 'Data berhasil dihapus.');
    }
}
