<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan <?= $periode_label ?> — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/laporan-export.css">
</head>

<body>

    <!-- ══ ACTION BAR (hanya tampil di layar, hilang saat print) ══ -->
    <div class="action-bar">
        <div class="action-bar-left">
            <i class="ti ti-file-text" style="font-size:16px;"></i>
            Laporan Bulanan · <strong><?= htmlspecialchars($periode_label) ?></strong>
        </div>
        <div class="action-bar-right">
            <a href="laporan.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn btn-outline">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-gold">
                <i class="ti ti-printer"></i> Cetak / Save PDF
            </button>
        </div>
    </div>

    <!-- ══ KERTAS A4 ══ -->
    <div class="page-wrap">

        <!-- KOP -->
        <div class="kop">
            <div>
                <div class="kop-brand">
                    LaundryEasy
                    <span>Sistem Manajemen Laundry</span>
                </div>
            </div>
            <div class="kop-right">
                <div class="doc-title">Laporan Bulanan</div>
                <div class="doc-sub"><?= htmlspecialchars($periode_label) ?></div>
            </div>
        </div>
        <div class="accent-bar"></div>

        <div class="doc-body">

            <!-- META INFO -->
            <div class="meta-grid">
                <div class="meta-item">
                    <label>Periode</label>
                    <strong><?= htmlspecialchars($periode_label) ?></strong>
                </div>
                <div class="meta-item">
                    <label>Dicetak Oleh</label>
                    <strong><?= htmlspecialchars($dicetak_oleh) ?></strong>
                </div>
                <div class="meta-item">
                    <label>Dicetak Pada</label>
                    <strong><?= $dicetak_pada ?></strong>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="section-title">Ringkasan Periode</div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">
                        <span class="stat-dot" style="background:var(--navy);"></span>
                        Total Transaksi
                    </div>
                    <div class="stat-val"><?= $total_transaksi ?></div>
                    <div class="stat-sub">Pesanan masuk bulan ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">
                        <span class="stat-dot" style="background:var(--gold-main);"></span>
                        Total Pendapatan
                    </div>
                    <div class="stat-val money">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                    <div class="stat-sub">Omset bulan ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">
                        <span class="stat-dot" style="background:var(--gold-mid);"></span>
                        Rata-rata / Transaksi
                    </div>
                    <div class="stat-val money">Rp <?= number_format($rata_rata, 0, ',', '.') ?></div>
                    <div class="stat-sub">Per pesanan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">
                        <span class="stat-dot" style="background:var(--green-text);"></span>
                        Pesanan Selesai
                    </div>
                    <div class="stat-val"><?= $jml_selesai ?></div>
                    <div class="stat-sub">Dari <?= $total_transaksi ?> transaksi</div>
                </div>
            </div>

            <!-- ANALISIS -->
            <div class="section-title">Analisis Transaksi</div>

            <div class="two-col">

                <!-- Layanan Terlaris -->
                <div class="mini-card">
                    <div class="mini-card-head">
                        <i class="ti ti-chart-bar"></i> Layanan Terlaris
                    </div>
                    <div class="mini-card-body">
                        <?php if (empty($layanan_stats)): ?>
                            <p style="font-size:11px;color:var(--gray-400);text-align:center;">Belum ada data.</p>
                        <?php else: ?>
                            <?php foreach ($layanan_stats as $lyr):
                                $pct = round(($lyr['jumlah_pesanan'] / $max_pesanan) * 100);
                            ?>
                                <div class="bar-row">
                                    <div class="bar-info">
                                        <span class="bar-name"><?= htmlspecialchars($lyr['nama_layanan']) ?></span>
                                        <span class="bar-count"><?= $lyr['jumlah_pesanan'] ?> pesanan</span>
                                    </div>
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width:<?= $pct ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Pesanan -->
                <div class="mini-card">
                    <div class="mini-card-head">
                        <i class="ti ti-chart-pie"></i> Status Pesanan
                    </div>
                    <div class="mini-card-body">
                        <?php
                        $max_s = max($jml_antri, $jml_proses, $jml_selesai, 1);
                        $status_items = [
                            ['label' => 'Antri',   'count' => $jml_antri,  'dot' => '#8a6a1f', 'fill' => '#CC9E4A'],
                            ['label' => 'Proses',  'count' => $jml_proses, 'dot' => '#0A1D4B', 'fill' => '#0A1D4B'],
                            ['label' => 'Selesai', 'count' => $jml_selesai, 'dot' => '#085041', 'fill' => '#085041'],
                        ];
                        foreach ($status_items as $si):
                            $pct_s = round(($si['count'] / $max_s) * 100);
                        ?>
                            <div class="status-row">
                                <div class="status-left">
                                    <span class="status-dot" style="background:<?= $si['dot'] ?>;"></span>
                                    <?= $si['label'] ?>
                                </div>
                                <div class="status-right">
                                    <div class="status-bar-bg">
                                        <div class="status-bar-fill" style="width:<?= $pct_s ?>%;background:<?= $si['fill'] ?>;"></div>
                                    </div>
                                    <span class="status-count"><?= $si['count'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="status-total-note">
                            Total <?= $total_transaksi ?> transaksi · <?= htmlspecialchars($periode_label) ?>
                        </div>
                    </div>
                </div>

            </div><!-- /two-col -->

            <!-- RINCIAN TRANSAKSI -->
            <div class="section-title">Rincian Semua Transaksi</div>

            <div class="trx-card">
                <div class="trx-card-head">
                    <div class="trx-card-head-left">
                        <i class="ti ti-list"></i>
                        Rincian Transaksi — <?= htmlspecialchars($periode_label) ?>
                    </div>
                    <div class="trx-card-head-right"><?= $total_transaksi ?> transaksi</div>
                </div>

                <?php if (empty($transaksis)): ?>
                    <div style="padding:32px;text-align:center;color:var(--gray-400);font-size:12px;">
                        Tidak ada transaksi pada <?= htmlspecialchars($periode_label) ?>.
                    </div>
                <?php else: ?>
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
                                    <td class="trx-biaya">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $t['status'] ?>">
                                            <span class="badge-dot"></span>
                                            <?= ucfirst($t['status']) ?>
                                        </span>
                                    </td>
                                    <td style="color:var(--gray-400);">
                                        <?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="trx-footer">
                        <span class="trx-footer-note">
                            <?= $total_transaksi ?> transaksi · <?= htmlspecialchars($periode_label) ?>
                        </span>
                        <span class="trx-footer-total">
                            Total: Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TANDA TANGAN -->
            <div class="ttd-wrap">
                <div class="ttd-box">
                    <div class="ttd-label">Mengetahui,<br>Pemilik / Manager</div>
                    <div class="ttd-line">( _________________________ )</div>
                </div>
                <div class="ttd-box">
                    <div class="ttd-label">Dibuat oleh,<br>Admin Sistem</div>
                    <div class="ttd-line">( <?= htmlspecialchars($dicetak_oleh) ?> )</div>
                </div>
            </div>

            <!-- FOOTER DOKUMEN -->
            <div class="doc-footer">
                <span>LaundryEasy · Laporan Bulanan <?= htmlspecialchars($periode_label) ?></span>
                <span>Dokumen ini digenerate otomatis oleh sistem</span>
            </div>

        </div><!-- /doc-body -->
    </div><!-- /page-wrap -->

    <script>
        // Langsung buka dialog print saat halaman selesai load
        window.addEventListener('load', function() {
            // Delay sedikit supaya font & warna sempat render
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>

</body>

</html>