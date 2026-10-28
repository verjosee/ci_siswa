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
        return '<span class="text-secondary opacity-50 small ms-1" style="font-size: 0.75rem;">↕</span>';
    }
    return $order === 'asc' 
        ? '<span class="text-warning small ms-1" style="font-size: 0.75rem;">▲</span>' 
        : '<span class="text-warning small ms-1" style="font-size: 0.75rem;">▼</span>';
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

$getBadgeClass = function(string $kelas): string {
    if (stripos($kelas, 'RPL 1') !== false) {
        return 'badge-rpl1';
    } elseif (stripos($kelas, 'RPL 2') !== false) {
        return 'badge-rpl2';
    } elseif (stripos($kelas, 'RPL 3') !== false) {
        return 'badge-rpl3';
    } elseif (stripos($kelas, 'ANIMASI') !== false) {
        return 'badge-animasi';
    }
    return 'badge-other';
};
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
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.15);
        }
        .card-main {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            background-color: #ffffff;
        }
        .card-header-custom {
            border-top-left-radius: 16px !important;
            border-top-right-radius: 16px !important;
            border-bottom: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 1.15rem 1.5rem;
        }
        .table-wrapper {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background-color: #ffffff;
        }
        .table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 0.95rem 1rem;
            vertical-align: middle;
            border: none;
            background-color: #0f172a;
            color: #ffffff;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
            font-size: 0.925rem;
        }
        .table tbody tr {
            transition: background-color 0.15s ease;
        }
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .nis-cell {
            font-family: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace;
            font-weight: 600;
            color: #0f172a;
            font-size: 0.9rem;
        }
        thead th a:hover {
            color: #facc15 !important;
        }
        .badge-kelas {
            font-size: 0.775rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            letter-spacing: 0.02em;
            display: inline-block;
            white-space: nowrap;
        }
        .nama-cell {
            white-space: nowrap;
            font-weight: 500;
            color: #1e293b;
        }
        .badge-rpl1 {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }
        .badge-rpl2 {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
        }
        .badge-rpl3 {
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ede9fe;
        }
        .badge-animasi {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
        }
        .badge-other {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-action-edit {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
            font-weight: 500;
            transition: all 0.15s ease;
            font-size: 0.825rem;
        }
        .btn-action-edit:hover {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
            box-shadow: 0 2px 5px rgba(217, 119, 6, 0.15);
        }
        .btn-action-delete {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-weight: 500;
            transition: all 0.15s ease;
            font-size: 0.825rem;
        }
        .btn-action-delete:hover {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.15);
        }
        .btn-sort-toggle {
            transition: all 0.2s ease;
            font-weight: 500;
            border-color: #cbd5e1;
        }
        .btn-sort-toggle:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
            color: #0d6efd;
        }
        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            padding: 0.5rem;
        }
        .dropdown-item {
            font-size: 0.875rem;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover {
            background-color: #eff6ff;
            color: #1d4ed8;
        }
        .dropdown-item.active {
            background-color: #2563eb;
            color: #ffffff;
        }
        .pagination .page-link {
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            color: #2563eb;
            padding: 0.45rem 0.85rem;
            border-color: #e2e8f0;
            border-radius: 8px !important;
        }
        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #2563eb;
            color: #ffffff !important;
            border-color: #2563eb;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transform: translateY(-2px);
            cursor: pointer;
        }
        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }
        .pagination .page-item.disabled .page-link {
            cursor: not-allowed;
            opacity: 0.5;
            background-color: #f8fafc;
        }
        .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2.5">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2.5" href="<?= base_url(); ?>">
                <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-2 p-1.5" style="width: 32px; height: 32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-mortarboard-fill text-white" viewBox="0 0 16 16">
                        <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917z"/>
                        <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466z"/>
                    </svg>
                </div>
                <span>Data Siswa XI RPL</span>
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card card-main">
            <!-- Header Kartu -->
            <div class="card-header-custom">
                <h5 class="fw-bold m-0 text-primary">Daftar Siswa</h5>
                <small class="text-muted">Kelola data induk dan inventaris siswa XI RPL</small>
            </div>

            <div class="card-body p-3 p-md-4">
                <!-- Toolbar: Tombol Tambah & Form Sortir + Cari -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2.5 mb-3">
                    <div>
                        <button type="button" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 shadow-sm px-3 py-2 fw-medium w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            <span>Tambah Siswa</span>
                        </button>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1 justify-content-md-end" style="max-width: 600px;">
                        <!-- Dropdown Sort -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sort-toggle d-inline-flex align-items-center justify-content-between justify-content-sm-center gap-1.5 shadow-sm px-3 py-2 fw-medium bg-white w-100 w-sm-auto" 
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-down-up text-primary me-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                                <span><?= esc($currentSortLabel); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
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
                                <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </span>
                                <input type="text" name="keyword" class="form-control border-start-0 ps-1 py-2" 
                                       placeholder="Cari NIS, nama, atau kelas..." 
                                       value="<?= esc($keyword ?? ''); ?>" autocomplete="off">
                                <button class="btn btn-primary px-3 fw-medium" type="submit">Cari</button>
                                <?php if (!empty($keyword)) : ?>
                                    <a href="<?= base_url($sort !== 'id' ? "?sort={$sort}&order={$order}" : ''); ?>" class="btn btn-outline-secondary" title="Reset pencarian">Reset</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifikasi Filter Pencarian -->
                <?php if (!empty($keyword)) : ?>
                    <div class="alert alert-light border py-2 px-3 mb-3 d-flex justify-content-between align-items-center rounded-3">
                        <span class="small text-muted">
                            Hasil pencarian untuk: "<strong><?= esc($keyword); ?></strong>" (<strong><?= $total; ?></strong> data ditemukan)
                        </span>
                        <a href="<?= base_url($sort !== 'id' ? "?sort={$sort}&order={$order}" : ''); ?>" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-medium">Hapus Filter</a>
                    </div>
                <?php endif; ?>

                <!-- Tabel Data Siswa -->
                <div class="table-wrapper">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" width="6%" class="text-center">No</th>
                                    <th scope="col" width="16%">
                                        <a href="<?= $getSortUrl('nis'); ?>" class="text-white text-decoration-none d-inline-flex align-items-center gap-1.5" title="Klik untuk mengurutkan NIS">
                                            <span>NIS</span>
                                            <?= $getSortIcon('nis'); ?>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="<?= $getSortUrl('nama'); ?>" class="text-white text-decoration-none d-inline-flex align-items-center gap-1.5" title="Klik untuk mengurutkan Nama">
                                            <span>Nama Lengkap</span>
                                            <?= $getSortIcon('nama'); ?>
                                        </a>
                                    </th>
                                    <th scope="col" width="18%">
                                        <a href="<?= $getSortUrl('kelas'); ?>" class="text-white text-decoration-none d-inline-flex align-items-center gap-1.5" title="Klik untuk mengurutkan Kelas">
                                            <span>Kelas</span>
                                            <?= $getSortIcon('kelas'); ?>
                                        </a>
                                    </th>
                                    <th scope="col" width="18%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($siswa)) : ?>
                                    <?php $no = 1 + (($currentPage - 1) * $perPage); ?>
                                    <?php foreach ($siswa as $row) : ?>
                                        <tr>
                                            <td class="text-center text-muted fw-medium"><?= $no++; ?></td>
                                            <td class="nis-cell"><?= esc($row['nis']); ?></td>
                                            <td class="nama-cell"><?= esc($row['nama']); ?></td>
                                            <td>
                                                <span class="badge-kelas <?= $getBadgeClass($row['kelas']); ?>">
                                                    <?= esc($row['kelas']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <button type="button" class="btn btn-action-edit btn-sm px-2.5 py-1 me-1 rounded-2 shadow-2xs"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>" title="Edit data siswa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-pencil-square me-1" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <a href="<?= base_url('home/hapus/' . $row['id']); ?>" 
                                                   class="btn btn-action-delete btn-sm px-2.5 py-1 rounded-2 shadow-2xs" 
                                                   onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')" title="Hapus data siswa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-trash3 me-1" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5a.5.5 0 0 1 .47-.5m5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>
                                                    Hapus
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Siswa -->
                                        <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1"
                                             aria-labelledby="modalEditLabel<?= $row['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning-subtle px-4 py-3">
                                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalEditLabel<?= $row['id']; ?>">
                                                            <span class="badge bg-warning text-dark p-1.5 rounded-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                                                </svg>
                                                            </span>
                                                            <span>Edit Data Siswa</span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= base_url('home/edit/' . $row['id']); ?>" method="POST">
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-secondary small text-uppercase">NIS</label>
                                                                <input type="text" name="nis" class="form-control"
                                                                       value="<?= esc($row['nis']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-secondary small text-uppercase">Nama Lengkap</label>
                                                                <input type="text" name="nama" class="form-control"
                                                                       value="<?= esc($row['nama']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-secondary small text-uppercase">Kelas</label>
                                                                <input type="text" name="kelas" class="form-control" list="listKelas"
                                                                       value="<?= esc($row['kelas']); ?>" placeholder="Pilih atau ketik kelas..." required autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light px-4 py-3 border-top">
                                                            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning text-dark fw-semibold px-3 shadow-sm">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-search text-secondary opacity-50 mb-2" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                            </svg>
                                            <div class="fw-semibold">Data siswa tidak ditemukan</div>
                                            <?php if (!empty($keyword)) : ?>
                                                <div class="small text-muted mt-1">Tidak ada siswa yang cocok dengan kata kunci "<strong><?= esc($keyword); ?></strong>"</div>
                                                <a href="<?= base_url(); ?>" class="btn btn-sm btn-outline-primary mt-3 px-3">Tampilkan Semua Siswa</a>
                                            <?php else : ?>
                                                <div class="small text-muted mt-1">Belum ada data siswa yang tersimpan di sistem.</div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-10 border-bottom border-primary-subtle px-4 py-3">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2" id="modalTambahLabel">
                        <span class="badge bg-primary text-white p-1.5 rounded-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        </span>
                        <span>Tambah Data Siswa</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('home/tambah'); ?>" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="nis" class="form-label fw-semibold text-secondary small text-uppercase">NIS</label>
                            <input type="text" name="nis" id="nis" class="form-control" placeholder="Contoh: 1027" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold text-secondary small text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap siswa" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label fw-semibold text-secondary small text-uppercase">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control" list="listKelas"
                                   placeholder="Pilih atau ketik kelas..." required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold px-3 shadow-sm">Simpan Data</button>
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
