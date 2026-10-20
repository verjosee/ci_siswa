<?php

namespace App\Controllers;

use App\Models\SiswaModel;

class Home extends BaseController
{
    public function index(): string
    {
        $siswaModel = new SiswaModel();
        $data['siswa'] = $siswaModel->getSiswa();
        return view('siswa_view', $data);
    }

    // Method untuk menyimpan data siswa baru (Create)
    public function tambah()
    {
        $siswaModel = new SiswaModel();

        $siswaModel->save([
            'nis'   => $this->request->getPost('nis'),
            'nama'  => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
        ]);

        return redirect()->to(base_url());
    }

    // Method untuk menghapus data siswa (Delete)
    public function hapus($id)
    {
        $siswaModel = new SiswaModel();

        // Hapus data berdasarkan ID
        $siswaModel->delete($id);

        // Kembali ke halaman utama
        return redirect()->to(base_url());
    }

    public function welcome(): string
    {
        return view('welcome_message');
    }
}
