<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Layanan — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/layanan.css">
</head>

<body>
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
            <a href="transaksi.php" class="sb-link">
                <i class="ti ti-receipt"></i> Transaksi
            </a>

            <div class="sb-section">Admin</div>
            <a href="layanan.php" class="sb-link active">
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

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Manajemen Layanan</h1>
                <p>Kelola jenis layanan laundry dan tarif per kilogram</p>
            </div>
            <div class="topbar-right">
                <a href="layanan_tambah.php" class="btn btn-gold">
                    <i class="ti ti-plus"></i> Tambah Layanan
                </a>
            </div>
        </div>

        <div class="content">
            <?php if ($msg): ?>
                <div class="alert alert-<?= $msg_type === 'success' ? 'success' : 'danger' ?>">
                    <i class="ti ti-<?= $msg_type === 'success' ? 'circle-check' : 'alert-circle' ?>"
                        style="font-size: 16px"></i>
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Layanan</span>
                        <div class="stat-icon" style="background: var(--navy-soft)">
                            <i class="ti ti-list-details" style="color: var(--navy)"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $totalLayanan ?></div>
                    <div class="stat-sub">Jenis layanan aktif</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Tarif Termurah</span>
                        <div class="stat-icon" style="background: var(--green-bg)">
                            <i class="ti ti-arrow-down-circle" style="color: var(--green-text)"></i>
                        </div>
                    </div>
                    <div class="stat-val" style="font-size: 18px">
                        Rp <?= number_format($tarifTermurah ?? 0, 0, ',', '.') ?>
                    </div>
                    <div class="stat-sub">Per kilogram</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Tarif Termahal</span>
                        <div class="stat-icon" style="background: var(--crimson-bg)">
                            <i class="ti ti-arrow-up-circle" style="color: var(--crimson)"></i>
                        </div>
                    </div>
                    <div class="stat-val" style="font-size: 18px">
                        Rp <?= number_format($tarifTermahal ?? 0, 0, ',', '.') ?>
                    </div>
                    <div class="stat-sub">Per kilogram</div>
                </div>
            </div>

            <div class="card">
                <div class="card-toolbar">
                    <span class="card-title">Daftar Layanan</span>
                    <div class="toolbar-right">
                        <form method="GET" style="display: flex; gap: 8px; align-items: center">
                            <div class="search-wrap">
                                <i class="ti ti-search"></i>
                                <input type="text" name="cari" placeholder="Cari nama layanan..."
                                    value="<?= htmlspecialchars($cari) ?>" />
                            </div>
                            <?php if ($cari): ?>
                                <a href="layanan.php" class="btn btn-ghost btn-sm">
                                    <i class="ti ti-x" style="font-size: 13px"></i> Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php
                // Ambil max usage untuk skala bar 
                $maxUsage = 1;
                if (!empty($layanans)) {
                    $usageMap = [];
                    $usageRows = $usageRows ?? []; // dikirim dari LayananController

                    foreach ($layanans as $l) {
                        $jml = $usageRows[$l['id']] ?? 0;
                        if ($jml > $maxUsage) {
                            $maxUsage = $jml;
                        }
                    }
                }
                ?>

                <?php if (empty($layanans)): ?>
                    <div class="empty-state">
                        <i class="ti ti-list-details"></i>
                        <p>Belum ada layanan<?= $cari ? ' yang cocok dengan pencarian' : '' ?>.</p>
                        <?php if (!$cari): ?>
                            <a href="layanan_tambah.php" class="btn btn-gold">
                                <i class="ti ti-plus"></i> Tambah Layanan Pertama
                            </a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 48px">#</th>
                                <th>Nama Layanan</th>
                                <th>Tarif / kg</th>
                                <th>Digunakan</th>
                                <th style="text-align: center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($layanans as $i => $l):
                                $jumlahPakai = $usageRows[$l['id']] ?? 0;
                                $pct = $maxUsage > 0 ? round(($jumlahPakai / $maxUsage) * 100) : 0;
                            ?>
                                <tr>
                                    <td>
                                        <div class="no-circle"><?= $i + 1 ?></div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--gray-800)">
                                            <?= htmlspecialchars($l['nama_layanan']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tarif-badge">
                                            <i class="ti ti-coin" style="font-size: 13px"></i>
                                            Rp <?= number_format($l['tarif_per_kg'], 0, ',', '.') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="usage-wrap">
                                            <div class="usage-bar-bg">
                                                <div class="usage-bar-fill" style="width: <?= $pct ?>%"></div>
                                            </div>
                                            <span class="usage-count"><?= $jumlahPakai ?> pesanan</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="aksi-wrap" style="justify-content: center">
                                            <a href="layanan_edit.php?id=<?= $l['id'] ?>" class="btn-icon btn-edit"
                                                title="Edit layanan">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <a href="Backend/layanan_hapus.php?id=<?= $l['id'] ?>" class="btn-icon btn-del"
                                                title="Hapus layanan"
                                                onclick="return confirm('Hapus layanan &quot;<?= htmlspecialchars($l['nama_layanan']) ?>&quot;?\nLayanan yang masih digunakan di transaksi tidak bisa dihapus.')">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="card-footer">
                        <span class="card-footer-text">
                            <?= $total_rows ?> layanan <?= $cari ? "cocok dengan \"" . htmlspecialchars($cari) . "\"" : 'terdaftar' ?>
                        </span>
                        <span class="card-footer-text"><?= date('d M Y, H:i') ?> WIB</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        // Auto-submit search on Enter
        document.querySelector('.search-wrap input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') this.closest('form').submit();
        });
    </script>
</body>

</html>