<?php
// Helper URL & icon sorting
$getSortUrl = function(string $col) use ($sort, $order, $keyword): string {
    $nextOrder = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
    $params = ['sort' => $col, 'order' => $nextOrder];
    if (!empty($keyword)) {
        $params['keyword'] = $keyword;
    }
    return base_url('?' . http_build_query($params));
};

$getSortOptionUrl = function(string $col, string $dir) use ($keyword): string {
    $params = ['sort' => $col, 'order' => $dir];
    if (!empty($keyword)) {
        $params['keyword'] = $keyword;
    }
    return base_url('?' . http_build_query($params));
};

$getSortIcon = function(string $col) use ($sort, $order): string {
    if ($sort !== $col) {
        return '<span class="text-secondary opacity-50 ms-1 small">↕</span>';
    }
    return $order === 'asc' 
        ? '<span class="text-warning ms-1 small">▲</span>' 
        : '<span class="text-warning ms-1 small">▼</span>';
};

$sortLabels = [
    'id_asc'     => 'Urutkan: Default',
    'id_desc'    => 'Urutkan: Terbaru',
    'nis_asc'    => 'Urutkan: NIS (0-9)',
    'nis_desc'   => 'Urutkan: NIS (9-0)',
    'nama_asc'   => 'Urutkan: Nama (A-Z)',
    'nama_desc'  => 'Urutkan: Nama (Z-A)',
    'kelas_asc'  => 'Urutkan: Kelas (A-Z)',
    'kelas_desc' => 'Urutkan: Kelas (Z-A)',
];
$currentSortKey = ($sort ?? 'id') . '_' . ($order ?? 'asc');
$currentSortLabel = $sortLabels[$currentSortKey] ?? 'Urutkan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa XI RPL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        .card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .card-header {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            border-bottom: 1px solid #e2e8f0;
            background-color: #ffffff;
        }
        .table thead th {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 600;
            padding: 0.85rem 1rem;
            vertical-align: middle;
        }
        .table tbody td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: #f1f5f9;
        }
        thead th a:hover {
            color: #facc15 !important;
        }
        .btn-sort-toggle {
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .btn-sort-toggle:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #0d6efd;
        }
        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }
        .dropdown-item {
            font-size: 0.9rem;
            padding: 0.45rem 1rem;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover {
            background-color: #eff6ff;
            color: #1d4ed8;
        }
        .dropdown-item.active {
            background-color: #0d6efd;
            color: #ffffff;
        }
        .pagination .page-link {
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            color: #0d6efd;
            padding: 0.5rem 0.85rem;
            border-color: #dee2e6;
        }
        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #0d6efd;
            color: #ffffff !important;
            border-color: #0d6efd;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
            transform: translateY(-2px);
            cursor: pointer;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.25);
        }
        .pagination .page-item.disabled .page-link {
            cursor: not-allowed;
            opacity: 0.55;
            background-color: #f8fafc;
        }
        .modal-content {
            border-radius: 14px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= base_url(); ?>">
                <span>Data Siswa XI RPL</span>
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card">
            <div class="card-header py-3">
                <h5 class="fw-bold m-0 text-primary">Daftar Siswa</h5>
            </div>
            <div class="card-body p-4">
                <!-- Baris Aksi: Tambah Siswa & Form Sortir + Pencarian -->
                <div class="row g-2 mb-3 align-items-center justify-content-between">
                    <div class="col-12 col-md-auto">
                        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span>+</span>
                            <span>Tambah Siswa</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-8 col-lg-7">
                        <div class="d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-md-end">
                            <!-- Tombol Sort di Sebelah Search -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle btn-sort-toggle d-flex align-items-center gap-1 shadow-sm" 
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-down-up me-1" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                    <span><?= esc($currentSortLabel); ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header small text-muted text-uppercase fw-bold">Pilih Urutan</h6></li>
                                    <li><a class="dropdown-item <?= ($sort === 'id' && $order === 'asc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('id', 'asc'); ?>">Default (ID)</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?= ($sort === 'nis' && $order === 'asc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('nis', 'asc'); ?>">NIS: 0 &rarr; 9 (Terkecil)</a></li>
                                    <li><a class="dropdown-item <?= ($sort === 'nis' && $order === 'desc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('nis', 'desc'); ?>">NIS: 9 &rarr; 0 (Terbesar)</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?= ($sort === 'nama' && $order === 'asc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('nama', 'asc'); ?>">Nama: A &rarr; Z</a></li>
                                    <li><a class="dropdown-item <?= ($sort === 'nama' && $order === 'desc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('nama', 'desc'); ?>">Nama: Z &rarr; A</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?= ($sort === 'kelas' && $order === 'asc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('kelas', 'asc'); ?>">Kelas: A &rarr; Z</a></li>
                                    <li><a class="dropdown-item <?= ($sort === 'kelas' && $order === 'desc') ? 'active fw-semibold' : '' ?>" href="<?= $getSortOptionUrl('kelas', 'desc'); ?>">Kelas: Z &rarr; A</a></li>
                                </ul>
                            </div>

                            <!-- Form Pencarian -->
                            <form action="<?= base_url(); ?>" method="GET" class="flex-grow-1">
                                <?php if (!empty($sort) && $sort !== 'id') : ?>
                                    <input type="hidden" name="sort" value="<?= esc($sort); ?>">
                                    <input type="hidden" name="order" value="<?= esc($order); ?>">
                                <?php endif; ?>
                                <div class="input-group shadow-sm">
                                    <input type="text" name="keyword" class="form-control" 
                                           placeholder="Cari NIS, nama, atau kelas..." 
                                           value="<?= esc($keyword ?? ''); ?>">
                                    <button class="btn btn-primary px-3" type="submit">Cari</button>
                                    <?php if (!empty($keyword)) : ?>
                                        <a href="<?= base_url($sort !== 'id' ? "?sort={$sort}&order={$order}" : ''); ?>" class="btn btn-outline-secondary" title="Reset pencarian">Reset</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (!empty($keyword)) : ?>
                    <div class="alert alert-light border py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            Hasil pencarian: "<strong><?= esc($keyword); ?></strong>" (<?= $total; ?> ditemukan)
                        </span>
                        <a href="<?= base_url($sort !== 'id' ? "?sort={$sort}&order={$order}" : ''); ?>" class="btn btn-sm btn-link text-danger text-decoration-none p-0">Hapus Filter</a>
                    </div>
                <?php endif; ?>

                <div class="table-responsive rounded-2 overflow-hidden border">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" width="5%" class="text-center">No</th>
                                <th scope="col">
                                    <a href="<?= $getSortUrl('nis'); ?>" class="text-white text-decoration-none d-flex align-items-center justify-content-between" title="Klik untuk mengurutkan NIS">
                                        <span>NIS</span>
                                        <?= $getSortIcon('nis'); ?>
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="<?= $getSortUrl('nama'); ?>" class="text-white text-decoration-none d-flex align-items-center justify-content-between" title="Klik untuk mengurutkan Nama">
                                        <span>Nama Lengkap</span>
                                        <?= $getSortIcon('nama'); ?>
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="<?= $getSortUrl('kelas'); ?>" class="text-white text-decoration-none d-flex align-items-center justify-content-between" title="Klik untuk mengurutkan Kelas">
                                        <span>Kelas</span>
                                        <?= $getSortIcon('kelas'); ?>
                                    </a>
                                </th>
                                <th scope="col" width="16%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($siswa)) : ?>
                                <?php $no = 1 + (($currentPage - 1) * $perPage); ?>
                                <?php foreach ($siswa as $row) : ?>
                                    <tr>
                                        <td class="text-center fw-medium text-muted"><?= $no++; ?></td>
                                        <td class="fw-semibold text-dark"><?= esc($row['nis']); ?></td>
                                        <td><?= esc($row['nama']); ?></td>
                                        <td><span class="badge bg-secondary-subtle text-secondary-emphasis fw-medium px-2 py-1"><?= esc($row['kelas']); ?></span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm text-white px-2 py-1 me-1 shadow-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">
                                                Edit
                                            </button>
                                            <a href="<?= base_url('home/hapus/' . $row['id']); ?>" 
                                               class="btn btn-danger btn-sm px-2 py-1 shadow-sm" 
                                               onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Siswa -->
                                    <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1"
                                         aria-labelledby="modalEditLabel<?= $row['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-white">
                                                    <h5 class="modal-title fw-bold" id="modalEditLabel<?= $row['id']; ?>">Edit Data Siswa</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="<?= base_url('home/edit/' . $row['id']); ?>" method="POST">
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">NIS</label>
                                                            <input type="text" name="nis" class="form-control"
                                                                   value="<?= esc($row['nis']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">Nama Lengkap</label>
                                                            <input type="text" name="nama" class="form-control"
                                                                   value="<?= esc($row['nama']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">Kelas</label>
                                                            <input type="text" name="kelas" class="form-control" list="listKelas"
                                                                   value="<?= esc($row['kelas']); ?>" placeholder="Pilih atau ketik kelas..." required autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning text-white fw-semibold">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <?php if (!empty($keyword)) : ?>
                                            Data siswa dengan kata kunci "<strong><?= esc($keyword); ?></strong>" tidak ditemukan.
                                            <div class="mt-2">
                                                <a href="<?= base_url(); ?>" class="btn btn-sm btn-outline-primary">Tampilkan Semua Siswa</a>
                                            </div>
                                        <?php else : ?>
                                            Belum ada data siswa.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Navigasi Pagination di Tengah -->
                <div class="d-flex justify-content-center mt-3 pt-3 border-top">
                    <?= $pager->links('default', 'bootstrap_full'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('home/tambah'); ?>" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="nis" class="form-label fw-medium">NIS</label>
                            <input type="text" name="nis" id="nis" class="form-control" placeholder="Masukkan NIS" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label fw-medium">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control" list="listKelas"
                                   placeholder="Pilih atau ketik kelas..." required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Datalist Pilihan Kelas Dinamis -->
    <datalist id="listKelas">
        <?php foreach ($daftarKelas as $k) : ?>
            <option value="<?= esc($k); ?>">
        <?php endforeach; ?>
    </datalist>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
