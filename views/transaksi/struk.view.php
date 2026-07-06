<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #<?= $no_struk ?> — LaundryEasy</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/struk.css">
</head>

<body>

    <div class="toolbar">
        <a href="transaksi.php" class="btn btn-ghost">← Kembali</a>
        <button onclick="window.print()" class="btn btn-gold">🖨 Cetak Struk</button>
    </div>

    <div class="struk">

        <div class="struk-header">
            <div class="struk-brand">🧺 LaundryEasy</div>
            <div class="struk-tagline">Struk Pembayaran</div>
            <div class="struk-divider-dots">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
            <div class="struk-no"><?= htmlspecialchars($no_struk) ?></div>
        </div>

        <div class="struk-body">

            <div class="status-row">
                <span class="status-label">Status Pesanan</span>
                <span class="badge badge-<?= $t['status'] ?>">
                    <span class="badge-dot"></span>
                    <?= ucfirst($t['status']) ?>
                </span>
            </div>

            <div class="section-label">Informasi Pelanggan</div>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">Nama</span>
                    <span class="info-val"><?= htmlspecialchars($t['nama_pelanggan']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Tanggal Masuk</span>
                    <span class="info-val"><?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Estimasi Selesai</span>
                    <span class="info-val">
                        <?= date('d M Y', strtotime($t['tanggal_transaksi'] . ' +' . $t['durasi_hari'] . ' days')) ?>
                    </span>
                </div>
            </div>

            <hr class="divider">

            <div class="section-label">Detail Layanan</div>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">Jenis Layanan</span>
                    <span class="info-val"><?= htmlspecialchars($t['nama_layanan'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Berat Cucian</span>
                    <span class="info-val"><?= number_format($t['berat_kg'], 1) ?> kg</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Durasi</span>
                    <span class="info-val"><?= $t['durasi_hari'] ?> hari</span>
                </div>
            </div>

            <hr class="divider">

            <div class="section-label">Rincian Biaya</div>
            <div class="calc-list">
                <div class="calc-row">
                    <span class="calc-key">Tarif per kg</span>
                    <span class="calc-val">Rp <?= number_format($t['tarif_per_kg'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="calc-row">
                    <span class="calc-key">Berat</span>
                    <span class="calc-val"><?= number_format($t['berat_kg'], 1) ?> kg</span>
                </div>
                <div class="calc-row">
                    <span class="calc-key">Subtotal</span>
                    <span class="calc-val">
                        Rp <?= number_format(($t['tarif_per_kg'] ?? 0) * $t['berat_kg'], 0, ',', '.') ?>
                    </span>
                </div>
            </div>

            <div class="total-box">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-val">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></span>
            </div>

        </div>
        <div class="struk-footer">
            <?php if ($t['status'] === 'selesai'): ?>
                <div class="watermark">✓ Lunas</div><br><br>
            <?php endif; ?>
            <p>Terima kasih telah mempercayakan cucian Anda<br>kepada <strong>LaundryEasy</strong>. 🧺</p>
            <p style="margin-top:8px;">Dicetak: <?= date('d M Y, H:i') ?> WIB</p>
        </div>

    </div>
</body>

</html>