<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/transaksi-edit.css">
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
            <a href="transaksi.php" class="sb-link active">
                <i class="ti ti-receipt"></i> Transaksi
            </a>

            <?php if ($role === 'Admin'): ?>
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
                <div class="sb-avatar"><?= strtoupper(substr($username, 0, 2)) ?></div>
                <div class="sb-user-info">
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
            <a href="transaksi.php" class="topbar-back">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <div class="topbar-divider"></div>
            <span class="topbar-title">Edit Transaksi</span>
            <span class="topbar-sub">#<?= $t['id'] ?></span>
        </div>

        <div class="content">

            <div class="page-header">
                <div class="page-title">Edit Pesanan</div>
                <div class="page-sub">
                    Ubah detail pesanan atau perbarui status pengerjaan
                </div>
            </div>

            <div class="info-card">
                <div class="info-item">
                    <label>ID Transaksi</label>
                    <span>#<?= $t['id'] ?></span>
                </div>
                <div class="info-item">
                    <label>Tanggal Masuk</label>
                    <span><?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Status Saat Ini</label>
                    <span>
                        <span class="badge badge-<?= $t['status'] ?>">
                            <span class="badge-dot"></span>
                            <?= ucfirst($t['status']) ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <label>Total Biaya</label>
                    <span>Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="ti ti-edit"></i>
                    </div>
                    <div>
                        <div class="card-header-title">Detail Pesanan</div>
                        <div class="card-header-sub">
                            <?= htmlspecialchars($t['nama_pelanggan']) ?>
                        </div>
                    </div>
                </div>

                <form method="POST" action="transaksi_update.php" id="formEdit">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">

                    <div class="card-body">
                        <div class="form-grid">

                            <div class="form-group">
                                <label class="form-label" for="nama_pelanggan">
                                    Nama Pelanggan <span class="req">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nama_pelanggan"
                                    name="nama_pelanggan"
                                    class="form-control"
                                    value="<?= htmlspecialchars($t['nama_pelanggan']) ?>"
                                    placeholder="Masukkan nama pelanggan"
                                    autocomplete="off"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="id_layanan">
                                    Jenis Layanan <span class="req">*</span>
                                </label>
                                <div class="select-wrap">
                                    <select
                                        id="id_layanan"
                                        name="id_layanan"
                                        class="form-control"
                                        required>
                                        <option value="">-- Pilih Layanan --</option>
                                        <?php foreach ($layananList as $lay): ?>
                                            <option
                                                value="<?= $lay['id'] ?>"
                                                data-tarif="<?= $lay['tarif_per_kg'] ?>"
                                                <?= $t['id_layanan'] == $lay['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($lay['nama_layanan']) ?>
                                                (Rp <?= number_format($lay['tarif_per_kg'], 0, ',', '.') ?>/kg)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="berat_kg">
                                    Berat Cucian (kg) <span class="req">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="berat_kg"
                                    name="berat_kg"
                                    class="form-control"
                                    value="<?= $t['berat_kg'] ?>"
                                    min="0.1"
                                    step="0.1"
                                    placeholder="0.0"
                                    required>
                                <span class="form-hint">Minimal 0.1 kg</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="durasi_hari">
                                    Durasi Pengerjaan (hari) <span class="req">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="durasi_hari"
                                    name="durasi_hari"
                                    class="form-control"
                                    value="<?= $t['durasi_hari'] ?>"
                                    min="1"
                                    step="1"
                                    placeholder="1"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="biaya_tambahan">
                                    Biaya Tambahan (Rp)
                                </label>
                                <input
                                    type="number"
                                    id="biaya_tambahan"
                                    name="biaya_tambahan"
                                    class="form-control"
                                    value="<?= $t['biaya_tambahan'] ?? 0 ?>"
                                    min="0"
                                    step="500"
                                    placeholder="0">
                                <span class="form-hint">Opsional — antar/jemput, dll.</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="catatan">Catatan</label>
                                <input
                                    type="text"
                                    id="catatan"
                                    name="catatan"
                                    class="form-control"
                                    value="<?= htmlspecialchars($t['catatan'] ?? '') ?>"
                                    autocomplete="off"
                                    placeholder="Parfum khusus, warna terpisah, dll.">
                            </div>

                            <div class="form-group full">
                                <label class="form-label">
                                    Status Pengerjaan <span class="req">*</span>
                                </label>
                                <div class="status-group">
                                    <?php foreach ($statusOptions as $opt): ?>
                                        <div class="status-pill">
                                            <input
                                                type="radio"
                                                id="s-<?= $opt ?>"
                                                name="status"
                                                value="<?= $opt ?>"
                                                <?= $t['status'] === $opt ? 'checked' : '' ?>>
                                            <label for="s-<?= $opt ?>">
                                                <span class="dot"></span>
                                                <?= ucfirst($opt) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <span class="form-hint">
                                    antri → proses → selesai
                                </span>
                            </div>

                        </div>
                        <div class="total-preview">
                            <span class="total-preview-label">
                                <i class="ti ti-calculator" style="font-size:15px;vertical-align:middle;margin-right:4px;"></i>
                                Total Biaya (preview)
                            </span>
                            <span class="total-preview-val" id="totalPreview">
                                Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?>
                            </span>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="transaksi.php" class="btn btn-ghost">
                            <i class="ti ti-x"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-gold">
                            <i class="ti ti-device-floppy"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        // ── Kalkulasi total realtime ──
        const elLayanan = document.getElementById('id_layanan');
        const elBerat = document.getElementById('berat_kg');
        const elTambah = document.getElementById('biaya_tambahan');
        const elDurasi = document.getElementById('durasi_hari');
        const elPreview = document.getElementById('totalPreview');

        function hitungTotal() {
            const opt = elLayanan.options[elLayanan.selectedIndex];
            const tarifDasar = parseFloat(opt?.dataset.tarif ?? 0);
            const berat = parseFloat(elBerat.value) || 0;
            const tambah = parseFloat(elTambah.value) || 0;
            const durasi = parseInt(elDurasi.value) || 3;

            // Logika Surcharge (Sama dengan backend)
            let surcharge = 0;
            if (durasi === 2) surcharge = 2000;
            if (durasi === 1) surcharge = 5000;

            const tarifEfektif = tarifDasar + surcharge;
            const total = (berat * tarifEfektif) + tambah;

            elPreview.textContent = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Pasang trigger event ke semua input
        elLayanan.addEventListener('change', hitungTotal);
        elBerat.addEventListener('input', hitungTotal);
        elTambah.addEventListener('input', hitungTotal);
        elDurasi.addEventListener('input', hitungTotal);

        // ── Validasi sebelum submit ──
        document.getElementById('formEdit').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama_pelanggan').value.trim();
            const lay = elLayanan.value;
            const berat = parseFloat(elBerat.value);

            if (!nama) {
                alert('Nama pelanggan wajib diisi.');
                e.preventDefault();
                return;
            }
            if (!lay) {
                alert('Pilih jenis layanan terlebih dahulu.');
                e.preventDefault();
                return;
            }
            if (!berat || berat <= 0) {
                alert('Berat cucian harus lebih dari 0 kg.');
                e.preventDefault();
                return;
            }
        });
    </script>

</body>

</html>