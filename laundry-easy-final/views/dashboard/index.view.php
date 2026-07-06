<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/dashboard.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-icon"><i class="ti ti-wash"></i></div>
            <span class="sb-brand-name">LaundryEasy</span>
        </div>

        <nav class="sb-nav">
            <div class="sb-section">Utama</div>
            <a href="dashboard.php" class="sb-link active">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="transaksi.php" class="sb-link">
                <i class="ti ti-receipt"></i> Transaksi
            </a>

            <?php if ($current_role === 'Admin'): ?>
                <div class="sb-section">Admin</div>
                <a href="layanan.php" class="sb-link">
                    <i class="ti ti-list-details"></i> Layanan
                    <span class="sb-admin-badge">Admin</span>
                </a>
                <a href="kelola_akun.php" class="sb-link">
                    <i class="ti ti-users"></i> Kelola Akun
                    <span class="sb-admin-badge">Admin</span>
                </a>
                <a href="laporan.php" class="sb-link">
                    <i class="ti ti-chart-bar"></i> Laporan
                    <span class="sb-admin-badge">Admin</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="sb-bottom">
            <div class="sb-user">
                <div class="sb-avatar"><?= strtoupper(substr($current_username, 0, 2)) ?></div>
                <div>
                    <div class="sb-user-name"><?= htmlspecialchars($current_username) ?></div>
                    <div class="sb-user-role"><?= htmlspecialchars($current_role) ?></div>
                </div>
            </div>
            <a href="logout.php" class="sb-logout">
                <i class="ti ti-logout"></i> Keluar
            </a>
        </div>
    </aside>

    <div class="main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Dashboard Utama</h1>
                <p><?= date('l, d F Y') ?></p>
            </div>
            <div class="topbar-right">
                <a href="transaksi_tambah.php" class="btn btn-gold">
                    <i class="ti ti-plus"></i> Pesanan Baru
                </a>
            </div>
        </div>

        <div class="content">

            <div class="page-header">
                <div class="page-title">Selamat datang, <?= htmlspecialchars($current_username) ?> 👋</div>
                <div class="page-sub">Ringkasan aktivitas laundry hari ini · <?= $current_role ?></div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Pesanan Hari Ini</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-receipt" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $total_hari_ini ?></div>
                    <div class="stat-sub">Transaksi masuk</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Pendapatan Hari Ini</span>
                        <div class="stat-icon" style="background:var(--gold-light);">
                            <i class="ti ti-coin" style="color:var(--gold-dark);"></i>
                        </div>
                    </div>
                    <div class="stat-val money">Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.') ?></div>
                    <div class="stat-sub">Total omset harian</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Sedang Diproses</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-loader" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $sedang_proses ?></div>
                    <div class="stat-sub">Pesanan aktif</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Selesai Hari Ini</span>
                        <div class="stat-icon" style="background:var(--green-bg);">
                            <i class="ti ti-circle-check" style="color:var(--green-text);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $selesai_bulan_ini ?></div>
                    <div class="stat-sub">Selesai dicuci</div>
                </div>
            </div>

            <div class="card">
                <div class="card-toolbar">
                    <div class="card-title-wrap">
                        <div class="card-title-icon"><i class="ti ti-clock-hour-4"></i></div>
                        <span class="card-title">Pesanan Terbaru</span>
                    </div>
                    <a href="transaksi.php" class="btn btn-gold" style="padding:7px 14px;font-size:12px;">
                        <i class="ti ti-arrow-right" style="font-size:13px;"></i> Lihat Semua
                    </a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transaksi_terbaru)): ?>
                                <tr>
                                    <td colspan="8" style="text-align:center;color:var(--gray-400);padding:32px;">
                                        Belum ada data transaksi masuk.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transaksi_terbaru as $trx): ?>
                                    <tr>
                                        <td class="trx-id">#TRX-<?= str_pad($trx['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                        <td style="font-weight:500;"><?= htmlspecialchars($trx['nama_pelanggan']) ?></td>
                                        <td style="color:var(--gray-600);"><?= htmlspecialchars($trx['nama_layanan'] ?? 'Layanan Dihapus') ?></td>
                                        <td><?= number_format($trx['berat_kg'], 1) ?> kg</td>
                                        <td><?= $trx['durasi_hari'] ?> hari</td>
                                        <td style="font-weight:600;color:var(--navy);">
                                            Rp <?= number_format($trx['total_biaya'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $trx['status'] ?>">
                                                <span class="badge-dot"></span>
                                                <?= ucfirst($trx['status']) ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--gray-400);font-size:12px;">
                                            <?= date('d M Y', strtotime($trx['tanggal_transaksi'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <span class="card-footer-text">Menampilkan 5 transaksi terbaru</span>
                    <span class="card-footer-text"><?= date('d M Y, H:i') ?> WIB</span>
                </div>
            </div>

        </div>
    </div>
</body>

</html>