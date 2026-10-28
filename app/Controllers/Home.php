<?php

namespace App\Controllers;

use App\Models\SiswaModel;

class Home extends BaseController
{
    public function index(): string
    {
        $siswaModel = new SiswaModel();

        // Parameter pencarian & sortir
        $keyword = trim((string) ($this->request->getGet('keyword') ?? ''));
        $sort    = (string) ($this->request->getGet('sort') ?? 'id');
        $order   = strtolower((string) ($this->request->getGet('order') ?? 'asc'));

        // Whitelist kolom sortir dan urutan
        $allowedSorts = ['id', 'nis', 'nama', 'kelas'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }

        // Filter data jika kata kunci pencarian ada
        if ($keyword !== '') {
            $siswaModel->groupStart()
                ->like('nis', $keyword)
                ->orLike('nama', $keyword)
                ->orLike('kelas', $keyword)
            ->groupEnd();
        }

        // Pengurutan data
        $siswaModel->orderBy($sort, $order);

        $perPage = 10;

        $data = [
            'siswa'       => $siswaModel->paginate($perPage),
            'pager'       => $siswaModel->pager,
            'currentPage' => $siswaModel->pager->getCurrentPage(),
            'perPage'     => $perPage,
            'total'       => $siswaModel->pager->getTotal(),
            'totalPages'  => $siswaModel->pager->getPageCount(),
            'keyword'     => $keyword,
            'sort'        => $sort,
            'order'       => $order,
            'daftarKelas' => $siswaModel->getDaftarKelas(),
        ];

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

    // Method untuk memperbarui data siswa
    public function edit($id)
    {
        $siswaModel = new SiswaModel();

        // Memperbarui data berdasarkan ID dan inputan form
        $siswaModel->update($id, [
            'nis'   => $this->request->getPost('nis'),
            'nama'  => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
        ]);

        // Kembali ke halaman utama
        return redirect()->to(base_url());
    }

    public function welcome(): string
    {
        return view('welcome_message');
    }
}
