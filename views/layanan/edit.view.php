<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Layanan — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/layanan-edit.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-icon"><i class="ti ti-wash"></i></div>
            <span class="sb-brand-name">LaundryEasy</span>
        </div>
        <nav class="sb-nav">
            <div class="sb-section">Menu</div>
            <a href="dashboard.php" class="sb-link"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
            <a href="transaksi.php" class="sb-link"><i class="ti ti-receipt"></i> Transaksi</a>
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
                <div class="sb-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
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
            <a href="layanan.php" class="topbar-back">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <div class="topbar-divider"></div>
            <span class="topbar-title">Edit Layanan</span>
            <span class="topbar-sub">#<?= $l['id'] ?></span>
            <span class="topbar-breadcrumb">
                <a href="dashboard.php">Dashboard</a> /
                <a href="layanan.php">Layanan</a> /
                Edit
            </span>
        </div>

        <div class="content">

            <div class="page-header">
                <div class="page-title">Edit Layanan</div>
                <div class="page-sub">Ubah nama atau tarif layanan laundry</div>
            </div>

            <div class="stat-strip">
                <div class="stat-chip">
                    <div class="stat-chip-label">ID Layanan</div>
                    <div class="stat-chip-val">#<?= $l['id'] ?></div>
                    <div class="stat-chip-sub">Identifikasi sistem</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-label">Digunakan</div>
                    <div class="stat-chip-val"><?= $stat['total_pakai'] ?? 0 ?></div>
                    <div class="stat-chip-sub">total pesanan</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-label">Total Omzet</div>
                    <div class="stat-chip-val" style="font-size:15px;">
                        Rp <?= number_format($stat['total_omzet'] ?? 0, 0, ',', '.') ?>
                    </div>
                    <div class="stat-chip-sub">dari layanan ini</div>
                </div>
            </div>

            <?php if (($stat['total_pakai'] ?? 0) > 0): ?>
                <div class="warn-banner">
                    <i class="ti ti-alert-triangle"></i>
                    <div class="warn-banner-text">
                        <strong>Layanan ini sudah digunakan di <?= $stat['total_pakai'] ?> transaksi</strong>
                        Perubahan nama dan tarif tidak akan mempengaruhi transaksi yang sudah tersimpan —
                        hanya berlaku untuk pesanan baru ke depannya.
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">

                <div class="card-header">
                    <div class="card-header-icon"><i class="ti ti-edit"></i></div>
                    <div>
                        <div class="card-header-title">Detail Layanan</div>
                        <div class="card-header-sub"><?= htmlspecialchars($l['nama_layanan']) ?></div>
                    </div>
                    <span class="id-pill"># <?= $l['id'] ?></span>
                </div>

                <form method="POST" action="Backend/layanan_update.php" id="formEdit" novalidate>
                    <input type="hidden" name="id" value="<?= $l['id'] ?>">

                    <div class="card-body">

                        <div class="form-group">
                            <label class="form-label" for="nama_layanan">
                                Nama Layanan <span class="req">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama_layanan"
                                name="nama_layanan"
                                class="form-control"
                                value="<?= htmlspecialchars($l['nama_layanan']) ?>"
                                data-original="<?= htmlspecialchars($l['nama_layanan']) ?>"
                                maxlength="80"
                                autocomplete="off"
                                required>
                            <div class="char-row">
                                <div>
                                    <span class="form-hint">Nama yang tampil di form transaksi kasir</span>
                                </div>
                                <span class="char-count" id="nameCount">
                                    <?= strlen($l['nama_layanan']) ?> / 80
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="tarif_per_kg">
                                Tarif per Kilogram <span class="req">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-prefix">Rp</span>
                                <input
                                    type="number"
                                    id="tarif_per_kg"
                                    name="tarif_per_kg"
                                    class="form-control has-suffix"
                                    value="<?= $l['tarif_per_kg'] ?>"
                                    data-original="<?= $l['tarif_per_kg'] ?>"
                                    min="500"
                                    step="500"
                                    required>
                                <span class="input-suffix">/ kg</span>
                            </div>
                            <div class="char-row" style="margin-top:5px;">
                                <span class="form-hint">Minimal Rp 500 · Kelipatan Rp 500 dianjurkan</span>
                                <span class="old-val" id="oldTarifWrap" style="display:none;">
                                    <i class="ti ti-arrow-right"></i>
                                    Tarif lama: <span id="oldTarifVal">
                                        Rp <?= number_format($l['tarif_per_kg'], 0, ',', '.') ?>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="change-indicator" id="changeIndicator">
                            <i class="ti ti-pencil" style="font-size:13px;"></i>
                            Ada perubahan yang belum disimpan
                        </div>

                        <div class="divider"></div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="display:flex;align-items:center;gap:6px;">
                                <i class="ti ti-eye" style="font-size:14px;color:var(--gold-main);"></i>
                                Preview Kartu Layanan
                            </label>
                            <div class="preview-box">
                                <div style="flex:1;">
                                    <div class="preview-name" id="prevName">
                                        <?= htmlspecialchars($l['nama_layanan']) ?>
                                    </div>
                                </div>
                                <div class="preview-tarif">
                                    <span class="preview-tarif-label">Tarif</span>
                                    <span class="preview-tarif-val" id="prevTarif">
                                        Rp <?= number_format($l['tarif_per_kg'], 0, ',', '.') ?>
                                    </span>
                                    <span class="preview-tarif-sub">per kilogram</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <span class="card-footer-left">
                            <i class="ti ti-shield-lock" style="font-size:13px;vertical-align:middle;margin-right:3px;"></i>
                            Hanya Admin yang dapat mengedit layanan
                        </span>
                        <div class="footer-actions">
                            <a href="layanan.php" class="btn btn-ghost">
                                <i class="ti ti-x"></i> Batal
                            </a>
                            <button type="button" class="btn btn-ghost" id="btnUndoAll">
                                <i class="ti ti-rotate-2"></i> Kembalikan
                            </button>
                            <button type="submit" class="btn btn-gold" id="btnSimpan">
                                <i class="ti ti-device-floppy"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script>
        const elNama = document.getElementById('nama_layanan');
        const elTarif = document.getElementById('tarif_per_kg');

        const origNama = elNama.dataset.original;
        const origTarif = elTarif.dataset.original;

        const prevName = document.getElementById('prevName');
        const prevTarif = document.getElementById('prevTarif');

        const nameCount = document.getElementById('nameCount');
        const oldTarifWrap = document.getElementById('oldTarifWrap');
        const changeIndicator = document.getElementById('changeIndicator');

        // ── Cek apakah ada perubahan ──
        function hasChanged() {
            return elNama.value !== origNama || elTarif.value !== origTarif;
        }

        // ── Update semua UI ──
        function update() {
            const nama = elNama.value.trim();
            const tarif = parseFloat(elTarif.value);

            // Preview nama
            if (nama) {
                prevName.textContent = nama;
                prevName.classList.remove('placeholder');
            } else {
                prevName.textContent = 'Nama layanan...';
                prevName.classList.add('placeholder');
            }

            // Preview tarif
            prevTarif.textContent = (!isNaN(tarif) && tarif > 0) ?
                'Rp ' + tarif.toLocaleString('id-ID') :
                'Rp —';

            // Char counts
            nameCount.textContent = elNama.value.length + ' / 80';
            nameCount.className = 'char-count' + (elNama.value.length > 60 ? ' warn' : '');

            // Changed highlight
            elNama.classList.toggle('changed', elNama.value !== origNama);
            elTarif.classList.toggle('changed', elTarif.value !== origTarif);

            // Tarif lama badge
            const tarifChanged = elTarif.value !== origTarif;
            oldTarifWrap.style.display = tarifChanged ? 'inline-flex' : 'none';

            // Change indicator
            changeIndicator.classList.toggle('show', hasChanged());
        }

        elNama.addEventListener('input', update);
        elTarif.addEventListener('input', update);

        // ── Kembalikan ke nilai semula ──
        document.getElementById('btnUndoAll').addEventListener('click', function() {
            if (!hasChanged()) return;
            if (!confirm('Kembalikan semua field ke nilai semula?')) return;
            elNama.value = origNama;
            elTarif.value = origTarif;
            update();
            elNama.focus();
        });

        // ── Validasi submit ──
        document.getElementById('formEdit').addEventListener('submit', function(e) {
            const nama = elNama.value.trim();
            const tarif = parseFloat(elTarif.value);

            if (!nama) {
                alert('Nama layanan wajib diisi.');
                elNama.focus();
                e.preventDefault();
                return;
            }
            if (isNaN(tarif) || tarif < 500) {
                alert('Tarif per kg minimal Rp 500.');
                elTarif.focus();
                e.preventDefault();
                return;
            }

            const btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2" style="animation:spin 0.8s linear infinite;"></i> Menyimpan...';
        });

        // ── Warn sebelum navigasi kalau ada perubahan belum disimpan ──
        window.addEventListener('beforeunload', function(e) {
            if (hasChanged()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Jangan warn saat submit
        document.getElementById('formEdit').addEventListener('submit', function() {
            window.onbeforeunload = null;
        });
    </script>

</body>

</html>