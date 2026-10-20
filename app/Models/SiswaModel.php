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
}
