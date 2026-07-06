<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun — LaundryEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/akun.css">
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
            <a href="layanan.php" class="sb-link">
                <i class="ti ti-list-details"></i> Layanan
                <span class="sb-admin-badge">Admin</span>
            </a>
            <a href="kelola_akun.php" class="sb-link active">
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
                <h1>Kelola Akun</h1>
                <p>Manajemen pengguna sistem LaundryEasy</p>
            </div>
            <div class="topbar-right">
                <button class="btn btn-gold" onclick="openModal('tambah')">
                    <i class="ti ti-user-plus" style="font-size:15px;"></i> Tambah Akun
                </button>
            </div>
        </div>

        <div class="content">

            <?php if ($msg): ?>
                <div class="alert alert-<?= $msg_type === 'success' ? 'success' : 'danger' ?>">
                    <i class="ti ti-<?= $msg_type === 'success' ? 'circle-check' : 'alert-circle' ?>"
                        style="font-size:16px;"></i>
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Total Akun</span>
                        <div class="stat-icon" style="background:var(--navy-soft);">
                            <i class="ti ti-users" style="color:var(--navy);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['total'] ?></div>
                    <div class="stat-sub">Terdaftar di sistem</div>
                </div>
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Admin</span>
                        <div class="stat-icon" style="background:var(--gold-light);">
                            <i class="ti ti-shield-check" style="color:var(--gold-dark);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['total_admin'] ?></div>
                    <div class="stat-sub">Akses penuh</div>
                </div>
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-label">Kasir</span>
                        <div class="stat-icon" style="background:var(--green-bg);">
                            <i class="ti ti-user" style="color:var(--green-text);"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stat['total_kasir'] ?></div>
                    <div class="stat-sub">Akses transaksi</div>
                </div>
            </div>

            <div class="card">
                <div class="card-toolbar">
                    <span class="card-title">Daftar Pengguna</span>
                    <div class="toolbar-right">
                        <form method="GET" style="display:flex;gap:8px;align-items:center;">
                            <div class="search-wrap">
                                <i class="ti ti-search"></i>
                                <input
                                    type="text"
                                    name="cari"
                                    placeholder="Cari username..."
                                    value="<?= htmlspecialchars($cari) ?>">
                            </div>
                            <select name="role" class="filter-select" onchange="this.form.submit()">
                                <option value="">Semua role</option>
                                <option value="Admin" <?= $filter_role === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Kasir" <?= $filter_role === 'Kasir' ? 'selected' : '' ?>>Kasir</option>
                            </select>
                            <?php if ($cari || $filter_role): ?>
                                <a href="kelola_akun.php" class="btn btn-ghost btn-sm">
                                    <i class="ti ti-x" style="font-size:13px;"></i> Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php if (empty($users)): ?>
                    <div class="empty-state">
                        <i class="ti ti-users-off"></i>
                        <p>Belum ada akun<?= ($cari || $filter_role) ? ' yang cocok dengan filter' : '' ?>.</p>
                        <?php if (!$cari && !$filter_role): ?>
                            <button class="btn btn-gold" onclick="openModal('tambah')">
                                <i class="ti ti-user-plus"></i> Tambah Akun Pertama
                            </button>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pengguna</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $u): ?>
                                <tr>
                                    <td style="color:var(--gray-400);font-size:12px;"><?= $i + 1 ?></td>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar <?= $u['role'] === 'Admin' ? 'avatar-admin' : 'avatar-kasir' ?>">
                                                <?= strtoupper(substr($u['username'], 0, 2)) ?>
                                            </div>
                                            <div style="font-weight:500;"><?= htmlspecialchars($u['username']) ?></div>
                                        </div>
                                    </td>
                                    <td style="color:var(--gray-600);font-family:monospace;font-size:12px;">
                                        <?= htmlspecialchars($u['username']) ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= strtolower($u['role']) ?>">
                                            <span class="badge-dot"></span>
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="aksi-wrap" style="justify-content:center;">
                                            <button
                                                class="btn-icon btn-edit"
                                                title="Edit akun"
                                                onclick="openModalEdit(<?= $u['id'] ?>, '<?= htmlspecialchars($u['username'], ENT_QUOTES) ?>', '<?= $u['role'] ?>')">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                                <a href="Backend/proses_akun.php?aksi=hapus&id=<?= $u['id'] ?>"
                                                    class="btn-icon btn-del"
                                                    title="Hapus akun"
                                                    onclick="return confirm('Hapus akun <?= htmlspecialchars($u['username'], ENT_QUOTES) ?>? Tindakan ini tidak bisa dibatalkan.')">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="btn-icon btn-icon-disabled" title="Tidak bisa hapus akun sendiri">
                                                    <i class="ti ti-trash"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="card-footer">
                        <span class="card-footer-text">
                            Menampilkan <?= $total_rows ?> akun
                            <?php if ($cari || $filter_role): ?>
                                (difilter dari <?= $stat['total'] ?> total)
                            <?php endif; ?>
                        </span>
                        <span class="card-footer-text"><?= date('d M Y, H:i') ?> WIB</span>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <div class="modal-overlay" id="modalTambah">
        <div class="modal">
            <div class="modal-accent"></div>
            <div class="modal-header">
                <span class="modal-title">Tambah Akun Baru</span>
                <button class="modal-close" onclick="closeModal('tambah')">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form method="POST" action="Backend/proses_akun.php?aksi=tambah">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input"
                        placeholder="Masukkan username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input"
                        placeholder="Minimal 6 karakter" required>
                    <div class="form-hint">Password akan di-hash dengan bcrypt sebelum disimpan.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">— Pilih role —</option>
                        <option value="Admin">Admin</option>
                        <option value="Kasir">Kasir</option>
                    </select>
                </div>
                <div class="form-footer">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('tambah')">Batal</button>
                    <button type="submit" class="btn btn-gold">
                        <i class="ti ti-user-plus"></i> Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalEdit">
        <div class="modal">
            <div class="modal-accent"></div>
            <div class="modal-header">
                <span class="modal-title">Edit Akun</span>
                <button class="modal-close" onclick="closeModal('edit')">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form method="POST" action="Backend/proses_akun.php?aksi=edit">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="edit_username" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    <div class="form-hint">Isi hanya jika ingin mengganti password.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" id="edit_role" class="form-select" required>
                        <option value="Admin">Admin</option>
                        <option value="Kasir">Kasir</option>
                    </select>
                </div>
                <div class="form-footer">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('edit')">Batal</button>
                    <button type="submit" class="btn btn-gold">
                        <i class="ti ti-device-floppy"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Modal helpers ──
        function openModal(type) {
            document.getElementById('modal' + capitalize(type)).classList.add('open');
        }

        function closeModal(type) {
            document.getElementById('modal' + capitalize(type)).classList.remove('open');
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        // ── Buka modal edit + isi data ──
        function openModalEdit(id, username, role) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_role').value = role;
            openModal('edit');
        }

        // ── Tutup modal klik luar ──
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        });

        // ── Auto-submit search saat Enter ──
        document.querySelector('.search-wrap input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') this.closest('form').submit();
        });
    </script>

</body>

</html>