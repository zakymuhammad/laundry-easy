<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Layanan — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/layanan-tambah.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-icon"><i class="ti ti-wash"></i></div>
            <span class="sb-brand-name">LaundryEasy</span>
        </div>

        <nav class="sb-nav">
            <div class="sb-section">Menu</div>
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
                <div class="sb-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
                <div>
                    <div class="sb-user-name"><?= htmlspecialchars($username) ?></div>
                    <div class="sb-user-role"><?= htmlspecialchars($role) ?></div>
                </div>
            </div>
            <a href="logout.php" class="sb-link" style="margin-top:4px;">
                <i class="ti ti-logout"></i> Logout
            </a>
        </div>
    </aside>

    <div class="main">

        <div class="topbar">
            <a href="layanan.php" class="topbar-back">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <div class="topbar-divider"></div>
            <span class="topbar-title">Tambah Layanan</span>
            <span class="topbar-breadcrumb" style="margin-left:8px;">
                <a href="dashboard.php">Dashboard</a> /
                <a href="layanan.php">Layanan</a> /
                Tambah
            </span>
        </div>

        <div class="content">

            <div class="page-header">
                <div class="page-title">Tambah Layanan Baru</div>
                <div class="page-sub">Isi detail nama layanan laundry dan tarif per kilogram</div>
            </div>

            <div class="tips-banner">
                <div class="tips-banner-icon"><i class="ti ti-bulb"></i></div>
                <div class="tips-banner-text">
                    <strong>Tips pengisian</strong>
                    Nama layanan harus jelas dan mudah dikenali kasir — contoh: <em>Cuci Kering</em>, <em>Cuci Setrika Express</em>.
                    Tarif diisi dalam Rupiah per kilogram dan akan dihitung otomatis saat kasir input pesanan.
                </div>
            </div>

            <div class="card">

                <div class="card-header">
                    <div class="card-header-icon"><i class="ti ti-plus"></i></div>
                    <div>
                        <div class="card-header-title">Detail Layanan</div>
                        <div class="card-header-sub">Semua field bertanda <span style="color:var(--red-text);">*</span> wajib diisi</div>
                    </div>
                </div>

                <form method="POST" action="Backend/layanan_simpan.php" id="formTambah" novalidate>

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
                                placeholder="Contoh: Cuci Setrika, Cuci Kering, Express 6 Jam..."
                                maxlength="80"
                                autocomplete="off"
                                required>
                            <div class="char-row">
                                <span class="form-hint">Nama yang tampil di form transaksi kasir</span>
                                <span class="char-count" id="nameCount">0 / 80</span>
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
                                    placeholder="0"
                                    min="500"
                                    step="500"
                                    required>
                                <span class="input-suffix">/ kg</span>
                            </div>
                            <span class="form-hint">Minimal Rp 500 · Kelipatan Rp 500 dianjurkan</span>
                        </div>

                        <div class="divider"></div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="display:flex;align-items:center;gap:6px;">
                                <i class="ti ti-eye" style="font-size:14px;color:var(--gold-main);"></i>
                                Preview Kartu Layanan
                            </label>
                            <div class="preview-box">
                                <div class="preview-left">
                                    <div class="preview-name placeholder" id="prevName">Nama layanan...</div>
                                </div>
                                <div class="preview-tarif">
                                    <span class="preview-tarif-label">Tarif</span>
                                    <span class="preview-tarif-val" id="prevTarif">Rp —</span>
                                    <span class="preview-tarif-sub">per kilogram</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <span class="card-footer-left">
                            <i class="ti ti-shield-lock" style="font-size:13px;vertical-align:middle;margin-right:3px;"></i>
                            Hanya Admin yang dapat mengelola layanan
                        </span>
                        <div class="footer-actions">
                            <a href="layanan.php" class="btn btn-ghost">
                                <i class="ti ti-x"></i> Batal
                            </a>
                            <button type="button" class="btn btn-ghost" id="btnReset"
                                style="border-color:var(--cream-border);">
                                <i class="ti ti-refresh"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-gold" id="btnSimpan">
                                <i class="ti ti-device-floppy"></i> Simpan Layanan
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

        const prevName = document.getElementById('prevName');
        const prevTarif = document.getElementById('prevTarif');

        const nameCount = document.getElementById('nameCount');

        // ── Live preview ──
        function updatePreview() {
            const nama = elNama.value.trim();
            const tarif = parseFloat(elTarif.value);

            // Nama
            if (nama) {
                prevName.textContent = nama;
                prevName.classList.remove('placeholder');
            } else {
                prevName.textContent = 'Nama layanan...';
                prevName.classList.add('placeholder');
            }

            // Tarif
            prevTarif.textContent = (!isNaN(tarif) && tarif > 0) ?
                'Rp ' + tarif.toLocaleString('id-ID') :
                'Rp —';
        }

        // ── Char counter ──
        function updateCounts() {
            const nl = elNama.value.length;
            nameCount.textContent = nl + ' / 80';
            nameCount.className = 'char-count' + (nl > 60 ? ' warn' : '');
        }

        elNama.addEventListener('input', () => {
            updatePreview();
            updateCounts();
        });
        elTarif.addEventListener('input', updatePreview);

        // ── Reset ──
        document.getElementById('btnReset').addEventListener('click', function() {
            if (!confirm('Reset semua field?')) return;
            elNama.value = '';
            elTarif.value = '';
            updatePreview();
            updateCounts();
            elNama.focus();
        });

        // ── Validasi submit ──
        document.getElementById('formTambah').addEventListener('submit', function(e) {
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

            // Loading state pada tombol
            const btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2" style="animation:spin 0.8s linear infinite;"></i> Menyimpan...';
        });

        // Spin animation untuk loader
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>

</body>

</html>