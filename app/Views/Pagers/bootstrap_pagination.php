<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * Custom Bootstrap 5 Pager Template for CI4
 *
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Navigasi Halaman Siswa">
    <ul class="pagination justify-content-center mb-0 gap-1">
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Sebelumnya">
                    <span aria-hidden="true">&laquo;</span> Sebelumnya
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link"><span aria-hidden="true">&laquo;</span> Sebelumnya</span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>" <?= $link['active'] ? 'aria-current="page"' : '' ?>>
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Berikutnya">
                    Berikutnya <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link">Berikutnya <span aria-hidden="true">&raquo;</span></span>
            </li>
        <?php endif ?>
    </ul>
</nav>
