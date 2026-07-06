<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Transaksi — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/transaksi-tambah.css">
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
            <a href="transaksi.php" class="topbar-back">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <div class="topbar-divider"></div>
            <span class="topbar-title">Pesanan Baru</span>
        </div>

        <div class="content">
            <form action="Backend/proses_transaksi.php" method="POST" id="formTransaksi">

                <div class="page-header">
                    <div class="page-title">Form Input Transaksi</div>
                    <div class="page-sub">Buat pesanan laundry multi-baris secara dinamis</div>
                </div>

                <div class="grid-2">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-head-icon"><i class="ti ti-user"></i></div>
                            <span class="card-head-title">Data Pelanggan</span>
                        </div>
                        <div class="card-body">
                            <div class="field">
                                <label>Nama Pelanggan <span style="color:var(--crimson);">*</span></label>
                                <input type="text" placeholder="Contoh: Budi Santoso" id="f-nama" autocomplete="off" required />
                            </div>
                            <div class="field">
                                <label>Nomor HP</label>
                                <input type="text" placeholder="Opsional (08xx-xxxx-xxxx)" autocomplete="off" />
                            </div>
                            <div class="field">
                                <label>Catatan Khusus</label>
                                <input type="text" placeholder="Misal: jangan diperas, pisahkan warna..." autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">
                            <div class="card-head-icon"><i class="ti ti-calendar"></i></div>
                            <span class="card-head-title">Info Transaksi</span>
                        </div>
                        <div class="card-body">
                            <div class="field">
                                <label>Tanggal Masuk</label>
                                <input type="text" value="<?= date('d F Y'); ?>" readonly />
                            </div>
                            <div class="field">
                                <label>Kasir Bertugas</label>
                                <input type="text" value="<?= htmlspecialchars($username); ?>" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card items-section">
                    <div class="card-head">
                        <div class="items-title-wrap">
                            <div class="card-head-icon"><i class="ti ti-basket"></i></div>
                            <span class="card-head-title">Item Pesanan</span>
                        </div>
                        <button type="button" class="btn-add-row" onclick="addRow()">
                            <i class="ti ti-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="items-table-wrap">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align:center;">#</th>
                                        <th style="width: 220px">Jenis Layanan</th>
                                        <th style="width: 140px">Durasi</th>
                                        <th style="width: 90px">Berat (kg)</th>
                                        <th style="width: 120px; text-align:right;">Tarif Dasar</th>
                                        <th style="width: 130px">Tambahan (Rp)</th>
                                        <th style="width: 130px; text-align:right;">Subtotal</th>
                                        <th style="width: 44px; text-align:center;"><i class="ti ti-settings"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body"></tbody>
                            </table>
                        </div>

                        <div class="summary-card">
                            <div class="summary-row">
                                <span class="summary-label">Total Sub. Layanan</span>
                                <span class="summary-val" id="subtotal">Rp 0</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Total B. Tambahan</span>
                                <span class="summary-val" id="biaya-tambahan">Rp 0</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span class="summary-label">Grand Total</span>
                                <span class="summary-val" id="grand-total">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-row">
                    <a href="dashboard.php" class="btn-action btn-cancel">
                        <i class="ti ti-arrow-back-up"></i> Batalkan
                    </a>
                    <button type="button" class="btn-action btn-save" onclick="validateAndSave()">
                        <i class="ti ti-device-floppy"></i> Simpan Pesanan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Data dari PHP
        const LAYANAN = [
            <?php foreach ($data_layanan as $layanan): ?> {
                    id: <?= $layanan['id']; ?>,
                    nama: '<?= addslashes($layanan['nama_layanan']); ?>',
                    tarif: <?= $layanan['tarif_per_kg']; ?>
                },
            <?php endforeach; ?>
        ];

        let rowCount = 0;

        function fmt(n) {
            return 'Rp ' + Math.round(n).toLocaleString('id-ID');
        }

        function addRow() {
            if (LAYANAN.length === 0) {
                alert("Data layanan kosong! Tambahkan layanan di menu Admin terlebih dahulu.");
                return;
            }

            rowCount++;
            const tbody = document.getElementById('items-body');
            const tr = document.createElement('tr');
            const id = rowCount;
            const indexArray = id - 1;

            tr.id = 'row-' + id;

            const optsLayanan = LAYANAN.map((layanan) => {
                return `<option value="${layanan.id}" data-tarif="${layanan.tarif}">${layanan.nama}</option>`;
            }).join('');

            tr.innerHTML = `
                    <td class="row-number">${id}</td>

                    <td>
                        <input type="hidden" name="items[${indexArray}][nama_pelanggan]" class="hidden-nama" value="">
                        <select name="items[${indexArray}][id_layanan]" class="sel-layanan" onchange="recalcRow(${id})">
                            ${optsLayanan}
                        </select>
                    </td>

                    <td>
                        <select name="items[${indexArray}][durasi_hari]" class="sel-durasi" onchange="recalcRow(${id})">
                            <option value="3">Reguler (3 Hari)</option>
                            <option value="2">Cepat (+2k/kg)</option>
                            <option value="1">Express (+5k/kg)</option>
                        </select>
                    </td>

                    <td>
                        <input type="number" name="items[${indexArray}][berat_kg]" step="0.1" min="0.1" value="1" id="berat-${id}" oninput="recalcRow(${id})" required />
                    </td>

                    <td>
                        <input type="text" id="tarif-${id}" value="${fmt(LAYANAN[0].tarif)}" readonly />
                    </td>

                    <td>
                        <input type="number" name="items[${indexArray}][biaya_tambahan]" min="0" value="0" id="tambahan-${id}" oninput="recalcRow(${id})" placeholder="0" />
                    </td>

                    <td class="total-col" id="total-${id}">
                        ${fmt(LAYANAN[0].tarif)}
                    </td>

                    <td style="text-align:center;">
                        <button type="button" class="btn-remove" onclick="removeRow(${id})" title="Hapus baris">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                `;

            tbody.appendChild(tr);
            recalcRow(id);
        }

        function recalcRow(id) {
            const row = document.getElementById('row-' + id);
            if (!row) return;

            const selectLayanan = row.querySelector('.sel-layanan');
            const selectDurasi = row.querySelector('.sel-durasi');

            const tarifDasar = parseInt(selectLayanan.options[selectLayanan.selectedIndex].getAttribute('data-tarif'));
            const durasi = parseInt(selectDurasi.value);

            let surcharge = 0;
            if (durasi === 2) surcharge = 2000;
            if (durasi === 1) surcharge = 5000;

            const tarifEfektif = tarifDasar + surcharge;
            const berat = parseFloat(document.getElementById('berat-' + id).value) || 0;
            const tambahanLain = parseInt(document.getElementById('tambahan-' + id).value) || 0;

            const total = (berat * tarifEfektif) + tambahanLain;

            document.getElementById('tarif-' + id).value = fmt(tarifDasar);
            document.getElementById('total-' + id).textContent = fmt(total);

            recalcAll();
        }

        function recalcAll() {
            let subtotal = 0;
            let biayaTambahan = 0;

            for (let i = 1; i <= rowCount; i++) {
                const row = document.getElementById('row-' + i);
                if (!row) continue;

                const selectLayanan = row.querySelector('.sel-layanan');
                const selectDurasi = row.querySelector('.sel-durasi');

                const tarifDasar = parseInt(selectLayanan.options[selectLayanan.selectedIndex].getAttribute('data-tarif'));
                const durasi = parseInt(selectDurasi.value);

                let surcharge = 0;
                if (durasi === 2) surcharge = 2000;
                if (durasi === 1) surcharge = 5000;

                const berat = parseFloat(document.getElementById('berat-' + i).value) || 0;
                const tambahanLain = parseInt(document.getElementById('tambahan-' + i).value) || 0;

                subtotal += berat * tarifDasar;
                biayaTambahan += (berat * surcharge) + tambahanLain;
            }

            document.getElementById('subtotal').textContent = fmt(subtotal);
            document.getElementById('biaya-tambahan').textContent = fmt(biayaTambahan);
            document.getElementById('grand-total').textContent = fmt(subtotal + biayaTambahan);
        }

        function removeRow(id) {
            const row = document.getElementById('row-' + id);
            if (row) row.remove();
            recalcAll();
        }

        function validateAndSave() {
            const namaInput = document.getElementById('f-nama');
            const namaVal = namaInput.value.trim();

            if (!namaVal) {
                namaInput.classList.add('val-error');
                namaInput.focus();
                setTimeout(() => namaInput.classList.remove('val-error'), 2000);
                return;
            }

            const items = document.querySelectorAll('.hidden-nama');
            if (items.length === 0) {
                alert('Tambahkan minimal 1 item pesanan.');
                return;
            }

            // Injeksi nama pelanggan
            items.forEach(input => {
                input.value = namaVal;
            });

            const btn = document.querySelector('.btn-save');
            btn.innerHTML = '<i class="ti ti-loader-2" style="animation:spin 0.8s linear infinite;"></i> Menyimpan...';
            btn.style.pointerEvents = 'none';

            document.getElementById('formTransaksi').submit();
        }

        window.onload = () => {
            if (LAYANAN.length > 0) addRow();
        };

        // Spin animation
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>

</html>