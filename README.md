# Sistem Informasi Data Siswa XI RPL (ci_siswa)

Aplikasi web sederhana berbasis **CodeIgniter 4** dan **Bootstrap 5** untuk mengelola data siswa kelas XI RPL. Aplikasi ini dilengkapi dengan antarmuka yang bersih, modern, dan responsif serta fitur pencarian, pengurutan cerdas (*tri-state*), dan paginasi data.

---

## Fitur Utama

- **CRUD Data Siswa Lengkap:**
  - Menampilkan daftar data siswa (NIS, Nama Lengkap, Kelas).
  - Menambah siswa baru melalui modal dialog interaktif.
  - Mengubah data siswa yang sudah ada.
  - Menghapus data siswa dengan konfirmasi keamanan.
- **Input Kelas Dinamis & Fleksibel:**
  - Menggunakan elemen `<datalist>` yang mengambil daftar opsi kelas unik secara otomatis dari database.
  - Memperbolehkan pengguna memilih kelas yang sudah ada atau mengetik kelas baru secara manual.
- **Pencarian Real-Time (Multi-Kolom):**
  - Mencari data siswa secara instan berdasarkan NIS, Nama Lengkap, maupun Kelas.
  - Dilengkapi tombol reset filter pencarian yang praktis.
- **Pengurutan Fleksibel (*Tri-State Sorting*):**
  - **Klik Header Tabel (NIS, Nama, Kelas):**
    - Klik 1: Mengurutkan dari A $\rightarrow$ Z / terkecil (tanda panah `▲`).
    - Klik 2: Mengurutkan dari Z $\rightarrow$ A / terbesar (tanda panah `▼`).
    - Klik 3: Mematikan sortir (kembali ke default, tanda panah hilang).
    - Klik 4: Mengulang kembali siklus pengurutan.
  - **Dropdown Toolbar:** Pilihan urutan langsung (Default, NIS, Nama, Kelas).
- **Paginasi Data:**
  - Menampilkan 10 data per halaman secara proporsional.
  - Tombol navigasi halaman yang responsif dan terpusat rapi di mobile maupun desktop.
- **Desain UI Modern & Responsif:**
  - Tampilan rapi dengan tipografi jelas, palet warna elegan, dan badge kelas tematik.
  - Teruji optimal pada tampilan layar desktop maupun smartphone.

---

## Kebutuhan Sistem (Prasyarat)

Aplikasi ini dapat dijalankan dengan mudah menggunakan aplikasi **XAMPP**:
- **Web Server:** Apache
- **Database Server:** MySQL / MariaDB
- **PHP:** Versi 8.1 atau 8.2+
- **Ekstensi PHP Aktif:** `intl`, `mbstring`, `mysqli`, `json` (umumnya sudah aktif secara default di XAMPP).

---

## File Database (SQL)

Di dalam repositori ini terdapat 2 file SQL:

1. **`db_rpl_siswa.sql` (File Utama - Direkomendasikan)**
   - Berisi perintah pembuatan database `db_rpl_siswa`, struktur tabel `siswa`, dan **26 data siswa awal** siap pakai.
   - Sangat disarankan digunakan untuk pengujian, penilaian, atau demonstrasi aplikasi.
2. **`db_rpl_siswa_schema.sql` (Skema Kosong - Opsional)**
   - Berisi hanya struktur database dan tabel `siswa` tanpa data awal (kosong).
   - Digunakan jika ingin memulai pengisian data dari nol.

---

## Cara Instalasi dan Menjalankan Aplikasi

Ikuti langkah-langkah mudah berikut untuk menjalankan project di komputer lokal:

### 1. Letakkan Folder Project
Pastikan folder project ini berada di direktori `htdocs` XAMPP Anda:
```text
C:\xampp\htdocs\ci_siswa
```

### 2. Nyalakan XAMPP
Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.

### 3. Impor File Database
Terdapat dua cara untuk mengimpor database:

**Cara A: Melalui phpMyAdmin (Paling Mudah)**
1. Buka browser dan akses `http://localhost/phpmyadmin/`.
2. Klik tab **Import** pada menu atas.
3. Klik tombol **Choose File** / **Pilih File**, lalu pilih file `db_rpl_siswa.sql` dari folder project ini.
4. Gulir ke bawah dan klik tombol **Import** (atau **Kirim**).
5. Database `db_rpl_siswa` dan tabel `siswa` beserta 26 data siswa akan terbuat secara otomatis.

**Cara B: Melalui Terminal / Command Prompt**
```bash
mysql -u root < C:\xampp\htdocs\ci_siswa\db_rpl_siswa.sql
```

### 4. Konfigurasi Environment (`.env`)
1. Di dalam folder root project (`ci_siswa`), pastikan sudah terdapat file `.env`.
2. Jika belum ada, salin file `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   ```
3. Buka file `.env` dan pastikan konfigurasi dasar sudah sesuai:
   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost/ci_siswa/public/'

   database.default.hostname = localhost
   database.default.database = db_rpl_siswa
   database.default.username = root
   database.default.password = ''
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

### 5. Akses Aplikasi di Browser
Buka browser favorit Anda, lalu kunjungi alamat URL berikut:
```text
http://localhost/ci_siswa/public/
```

---

## Bukti Pengerjaan (Evidence / Checkpoint)

Tangkapan layar hasil pengerjaan dan perkembangan fitur tersimpan rapi pada direktori:
- **`evidence/`**: Bukti pemenuhan checkpoint penugasan dari awal hingga implementasi tabel.
- **`bukti_checkpoint/`**: Dokumentasi visual fitur akhir (tampilan desktop, mobile, sorting, search, datalist kelas, dan paginasi).

---

## Struktur Folder Singkat

```text
ci_siswa/
├── app/
│   ├── Config/          # Konfigurasi aplikasi (Routes, Pager, Database, dll.)
│   ├── Controllers/     # Home.php (Logika CRUD, search, filter, sorting)
│   ├── Models/          # SiswaModel.php (Interaksi tabel database)
│   └── Views/           # siswa_view.php (Tampilan antarmuka utama)
├── bukti_checkpoint/    # Dokumentasi visual fitur akhir
├── evidence/            # Tangkapan layar checkpoint penugasan
├── public/              # File index.php dan aset publik yang dapat diakses browser
├── db_rpl_siswa.sql     # File dump database utama (struktur + 26 data siswa)
├── db_rpl_siswa_schema.sql # File skema database kosong
├── .env.example         # Template konfigurasi environment
├── README.md            # Dokumentasi petunjuk penggunaan aplikasi
└── spark                # CLI tool bawaan CodeIgniter 4
```
