<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    // Menghubungkan Model ke tabel 'siswa'
    protected $table = 'siswa';

    // Menentukan primary key
    protected $primaryKey = 'id';

    // Kolom yang diizinkan untuk dikelola
    protected $allowedFields = ['nis', 'nama', 'kelas'];

    // Method untuk mengambil seluruh data siswa
    public function getSiswa()
    {
        return $this->findAll();
    }

    // Mengambil daftar kelas unik secara dinamis dari database
    public function getDaftarKelas(): array
    {
        $rows = $this->builder()
            ->select('kelas')
            ->distinct()
            ->where('kelas IS NOT NULL')
            ->where("kelas != ''")
            ->orderBy('kelas', 'ASC')
            ->get()
            ->getResultArray();

        $list = array_column($rows, 'kelas');

        return !empty($list) ? $list : ['XI RPL 1', 'XI RPL 2', 'XI RPL 3'];
    }
}
