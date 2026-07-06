<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/laporan.css">
</head>

<body>

    <!-- ════ SIDEBAR ════ -->
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
            <a href="layanan.php" class="sb-link">
                <i class="ti ti-list-details"></i> Layanan
                <span class="sb-admin-badge">Admin</span>
            </a>
            <a href="kelola_akun.php" class="sb-link">
                <i class="ti ti-users"></i> Kelola Akun
                <span class="sb-admin-badge">Admin</span>
            </a>
            <a href="laporan.php" class="sb-link active">
                <i class="ti ti-chart-bar"></i> Laporan
                <span class="sb-admin-badge">Admin</span>
            </a>
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

    <!-- ════ MAIN ════ -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h1>Laporan Bulanan</h1>
                <p>Ringkasan transaksi & pendapatan · <?= $periode_label_id ?></p>
            </div>
            <div class="topbar-right no-print">
                <!-- <button onclick="window.print()" class="btn btn-outline">
                    <i class="ti ti-printer"></i> Cetak
                </button> -->
                <a href="laporan_export.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn btn-gold">
                    <i class="ti ti-download"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="content">

            <!-- ── FILTER ── -->
            <div class="filter-bar no-print">
                <span class="filter-label"><i class="ti ti-calendar" style="color:var(--gold-main);margin-right:4px;font-size:15px;vertical-align:middle;"></i> Periode:</span>

                <form method="GET" style="display:flex;gap:10px;align-items:center;">
                    <select name="bulan" class="filter-select" onchange="this.form.submit()">
                        <?php
                        $nama_bulan = [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ];
                        foreach ($nama_bulan as $i => $nb):
                            $val = $i + 1;
                        ?>
                            <option value="<?= $val ?>" <?= $val == $bulan ? 'selected' : '' ?>>
                                <?= $nb ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="tahun" class="filter-select" onchange="this.form.submit()">
                        <?php foreach ($daftar_tahun as $ty): ?>
                            <option value="<?= $ty ?>" <?= $ty == $tahun ? 'selected' : '' ?>><?= $ty ?></option>
                        <?php endforeach; ?>
                        <?php if (!in_array((int)date('Y'), $daftar_tahun)): ?>
                            <option value="<?= date('Y') ?>" <?= date('Y') == $tahun ? 'selected' : '' ?>><?= date('Y') ?></option>
                        <?php endif; ?>
                    </select>
                </form>

                <span class="filter-hint">Data ditampilkan otomatis sesuai periode yang dipilih</span>
            </div>

            <!-- ── STAT CARDS ── -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Transaksi</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-receipt" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $total_transaksi ?></div>
                    <div class="stat-sub">Pesanan masuk bulan ini</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Pendapatan</span>
                        <div class="stat-icon" style="background:var(--gold-light);">
                            <i class="ti ti-coin" style="color:var(--gold-dark);"></i>
                        </div>
                    </div>
                    <div class="stat-val money">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                    <div class="stat-sub">Omset bulan ini</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Rata-rata / Transaksi</span>
                        <div class="stat-icon" style="background:var(--gold-light);">
                            <i class="ti ti-chart-line" style="color:var(--gold-dark);"></i>
                        </div>
                    </div>
                    <div class="stat-val money">Rp <?= number_format($rata_rata, 0, ',', '.') ?></div>
                    <div class="stat-sub">Per pesanan</div>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Pesanan Selesai</span>
                        <div class="stat-icon" style="background:var(--green-bg);">
                            <i class="ti ti-circle-check" style="color:var(--green-text);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $jml_selesai ?></div>
                    <div class="stat-sub">Dari <?= $total_transaksi ?> transaksi</div>
                </div>
            </div>

            <!-- ── GRAFIK 2 KOLOM ── -->
            <div class="grid-2">

                <!-- Layanan Terlaris -->
                <div class="card">
                    <div class="card-toolbar">
                        <div class="card-title-wrap">
                            <div class="card-title-icon"><i class="ti ti-chart-bar"></i></div>
                            <span class="card-title">Layanan Terlaris</span>
                        </div>
                        <span style="font-size:11px;color:var(--gray-400);"><?= $periode_label_id ?></span>
                    </div>

                    <div class="bar-wrap">
                        <?php if (empty($layanan_stats)): ?>
                            <p style="font-size:12px;color:var(--gray-400);text-align:center;padding:16px 0;">Belum ada data layanan.</p>
                        <?php else: ?>
                            <?php foreach ($layanan_stats as $lyr):
                                $pct = $max_pesanan > 0 ? round(($lyr['jumlah_pesanan'] / $max_pesanan) * 100) : 0;
                            ?>
                                <div class="bar-row">
                                    <div class="bar-info">
                                        <span class="bar-name"><?= htmlspecialchars($lyr['nama_layanan']) ?></span>
                                        <span class="bar-meta"><?= $lyr['jumlah_pesanan'] ?> pesanan</span>
                                    </div>
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Pesanan -->
                <div class="card">
                    <div class="card-toolbar">
                        <div class="card-title-wrap">
                            <div class="card-title-icon"><i class="ti ti-chart-pie"></i></div>
                            <span class="card-title">Status Pesanan</span>
                        </div>
                        <span style="font-size:11px;color:var(--gray-400);"><?= $periode_label_id ?></span>
                    </div>

                    <div class="status-wrap">
                        <?php
                        $status_items = [
                            ['label' => 'Antri',  'count' => $jml_antri,  'dot' => 'var(--amber-text)', 'fill' => 'var(--gold-main)'],
                            ['label' => 'Proses', 'count' => $jml_proses, 'dot' => 'var(--navy)',       'fill' => 'var(--navy-soft)'],
                            ['label' => 'Selesai', 'count' => $jml_selesai, 'dot' => 'var(--green-text)', 'fill' => 'var(--green-bg)'],
                        ];
                        $max_status = max($jml_antri, $jml_proses, $jml_selesai, 1);
                        foreach ($status_items as $si):
                            $pct_s = round(($si['count'] / $max_status) * 100);
                        ?>
                            <div class="status-row">
                                <div class="status-left">
                                    <span class="status-dot" style="background:<?= $si['dot'] ?>;"></span>
                                    <span class="status-label"><?= $si['label'] ?></span>
                                </div>
                                <div class="status-right">
                                    <div class="status-bar-bg">
                                        <div class="status-bar-fill" style="width:<?= $pct_s ?>%;background:<?= $si['fill'] ?>;"></div>
                                    </div>
                                    <span class="status-count"><?= $si['count'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <hr class="status-divider">
                        <div class="status-total-note">
                            Total <?= $total_transaksi ?> transaksi · <?= $periode_label_id ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── TABEL RINCIAN TRANSAKSI ── -->
            <div class="card">
                <div class="card-toolbar">
                    <div class="card-title-wrap">
                        <div class="card-title-icon"><i class="ti ti-list"></i></div>
                        <span class="card-title">Rincian Semua Transaksi — <?= $periode_label_id ?></span>
                    </div>
                    <span style="font-size:12px;color:var(--gray-400);"><?= $total_transaksi ?> transaksi ditemukan</span>
                </div>

                <?php if (empty($transaksis)): ?>
                    <div class="empty-state">
                        <i class="ti ti-inbox"></i>
                        <p>Belum ada transaksi di <?= $periode_label_id ?>.</p>
                    </div>
                <?php else: ?>
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
                                <?php foreach ($transaksis as $t): ?>
                                    <tr>
                                        <td class="trx-id">#TRX-<?= str_pad($t['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                        <td style="font-weight:500;"><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                                        <td style="color:var(--gray-600);"><?= htmlspecialchars($t['nama_layanan'] ?? 'Layanan Dihapus') ?></td>
                                        <td><?= number_format($t['berat_kg'], 1) ?> kg</td>
                                        <td><?= $t['durasi_hari'] ?> hari</td>
                                        <td style="font-weight:600;color:var(--navy);">
                                            Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $t['status'] ?>">
                                                <span class="badge-dot"></span>
                                                <?= ucfirst($t['status']) ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--gray-400);font-size:12px;">
                                            <?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <span class="card-footer-text">
                            Menampilkan <?= $total_transaksi ?> transaksi · <?= $periode_label_id ?>
                        </span>
                        <span class="card-footer-text" style="font-weight:600;color:var(--navy);">
                            Total: Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script>
        // Auto-submit filter saat ganti dropdown (sudah handled via onchange, ini backup)
        document.querySelectorAll('.filter-select').forEach(function(sel) {
            sel.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>

</body>

</html>