<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/transaksi.css">
</head>

<body>

    <!-- ════════════ SIDEBAR ════════════ -->
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-icon"><i class="ti ti-wash"></i></div>
            <span class="sb-brand-name">LaundryEasy</span>
        </div>

        <nav class="sb-nav">
            <div class="sb-section">Utama</div>
            <a href="dashboard.php" class="sb-link">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="transaksi.php" class="sb-link active">
                <i class="ti ti-receipt"></i> Transaksi
            </a>

            <?php if ($role === 'Admin'): ?>
                <div class="sb-section">Admin</div>
                <a href="layanan.php" class="sb-link">
                    <i class="ti ti-list-details"></i> Layanan
                    <span class="sb-admin-badge">Admin</span>
                </a>
                <a href="#" class="sb-link">
                    <i class="ti ti-users"></i> Kelola Akun
                    <span class="sb-admin-badge">Admin</span>
                </a>
                <a href="#" class="sb-link">
                    <i class="ti ti-chart-bar"></i> Laporan
                    <span class="sb-admin-badge">Admin</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="sb-bottom">
            <div class="sb-user">
                <div class="sb-avatar"><?= strtoupper(substr($username, 0, 2)) ?></div>
                <div>
                    <div class="sb-user-name"><?= htmlspecialchars($username) ?></div>
                    <div class="sb-user-role"><?= htmlspecialchars($role) ?></div>
                </div>
            </div>
            <a href="logout.php" class="sb-logout">
                <i class="ti ti-logout"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- ════════════ MAIN ════════════ -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h1>Manajemen Transaksi</h1>
                <p>Daftar seluruh pesanan laundry</p>
            </div>
            <div class="topbar-right">
                <a href="transaksi_tambah.php" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Pesanan Baru
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- Alert flash message -->
            <?php if ($msg): ?>
                <div class="alert alert-<?= $msg_type === 'success' ? 'success' : 'danger' ?>">
                    <i class="ti ti-<?= $msg_type === 'success' ? 'circle-check' : 'alert-circle' ?>"
                        style="font-size:16px;"></i>
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <!-- STAT CARDS -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Pesanan</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-receipt" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['total'] ?></div>
                    <div class="stat-sub">Semua waktu</div>
                </div>
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Antri</span>
                        <div class="stat-icon" style="background:var(--gold-light);">
                            <i class="ti ti-clock" style="color:var(--gold-dark);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['antri'] ?></div>
                    <div class="stat-sub">Menunggu proses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Diproses</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-loader" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['proses'] ?></div>
                    <div class="stat-sub">Sedang dikerjakan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Pendapatan</span>
                        <div class="stat-icon" style="background:var(--green-bg);">
                            <i class="ti ti-coin" style="color:var(--green-text);"></i>
                        </div>
                    </div>
                    <div class="stat-val" style="font-size:17px;">
                        Rp <?= number_format($stat['pendapatan'] ?? 0, 0, ',', '.') ?>
                    </div>
                    <div class="stat-sub"><?= $stat['selesai'] ?> pesanan selesai</div>
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="card">
                <div class="card-toolbar">
                    <span class="card-title">Daftar Pesanan</span>
                    <div class="toolbar-right">
                        <!-- Search -->
                        <form method="GET" style="display:flex;gap:8px;align-items:center;">
                            <div class="search-wrap">
                                <i class="ti ti-search"></i>
                                <input type="text" name="cari" placeholder="Cari nama pelanggan..."
                                    value="<?= htmlspecialchars($cari) ?>">
                            </div>
                            <!-- Filter status -->
                            <select name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">Semua status</option>
                                <option value="antri" <?= $status === 'antri' ? 'selected' : '' ?>>Antri</option>
                                <option value="proses" <?= $status === 'proses' ? 'selected' : '' ?>>Proses</option>
                                <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <?php if ($cari || $status): ?>
                                <a href="transaksi.php" class="btn btn-ghost btn-sm">
                                    <i class="ti ti-x" style="font-size:13px;"></i> Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php if (empty($transaksis)): ?>
                    <!-- Empty state -->
                    <div class="empty-state">
                        <i class="ti ti-inbox"></i>
                        <p>Belum ada transaksi<?= ($cari || $status) ? ' yang cocok dengan filter' : '' ?>.</p>
                        <?php if (!$cari && !$status): ?>
                            <a href="transaksi_tambah.php" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Buat Pesanan Pertama
                            </a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transaksis as $i => $t): ?>
                                <tr>
                                    <td style="color:var(--gray-400);font-size:12px;"><?= $i + 1 ?></td>
                                    <td>
                                        <div style="font-weight:500;"><?= htmlspecialchars($t['nama_pelanggan']) ?></div>
                                    </td>
                                    <td style="color:var(--gray-600);">
                                        <?= htmlspecialchars($t['nama_layanan'] ?? '-') ?>
                                    </td>
                                    <td><?= number_format($t['berat_kg'], 1) ?> kg</td>
                                    <td><?= $t['durasi_hari'] ?> hari</td>
                                    <td style="font-weight:600;color:var(--navy);">
                                        Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <form action="Backend/update_status.php" method="POST" style="margin: 0;">
                                            <input type="hidden" name="id_transaksi" value="<?= $t['id'] ?>">
                                            <select name="status_baru" onchange="this.form.submit()"
                                                class="badge badge-<?= $t['status'] ?>"
                                                style="border: none; outline: none; cursor: pointer; padding: 4px 8px; appearance: none; font-family: inherit;">
                                                <option value="antri" <?= $t['status'] === 'antri' ? 'selected' : '' ?>>Antri
                                                </option>
                                                <option value="proses" <?= $t['status'] === 'proses' ? 'selected' : '' ?>>Proses
                                                </option>
                                                <option value="selesai" <?= $t['status'] === 'selesai' ? 'selected' : '' ?>>Selesai
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td style="color:var(--gray-400);font-size:12px;">
                                        <?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?>
                                    </td>
                                    <td>
                                        <div class="aksi-wrap" style="justify-content:center;">
                                            <!-- Edit status -->
                                            <a href="transaksi_edit.php?id=<?= $t['id'] ?>" class="btn-icon btn-edit"
                                                title="Edit status">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <!-- Cetak struk -->
                                            <a href="struk.php?id=<?= $t['id'] ?>" class="btn-icon btn-struk" target="_blank"
                                                title="Cetak struk">
                                                <i class="ti ti-printer"></i>
                                            </a>
                                            <!-- Hapus (Admin only) -->
                                            <?php if ($role === 'Admin'): ?>
                                                <a href="Backend/transaksi_hapus.php?id=<?= $t['id'] ?>" class="btn-icon btn-del"
                                                    title="Hapus"
                                                    onclick="return confirm('Hapus transaksi <?= htmlspecialchars($t['nama_pelanggan']) ?>? Tindakan ini tidak bisa dibatalkan.')">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="card-footer">
                        <span class="card-footer-text">
                            Menampilkan <?= $total_rows ?> transaksi
                            <?php if ($cari || $status): ?>
                                (difilter dari <?= $stat['total'] ?> total)
                            <?php endif; ?>
                        </span>
                        <span class="card-footer-text">
                            <?= date('d M Y, H:i') ?> WIB
                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script>
        // Auto-submit form saat search di-enter
        document.querySelector('.search-wrap input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') this.closest('form').submit();
        });
    </script>

</body>

</html>