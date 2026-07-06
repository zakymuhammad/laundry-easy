<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — LaundryEasy</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/auth-login.css">
</head>

<body>
  <main class="page-wrapper">
    <section class="left-panel">
      <div class="brand">
        <div class="brand-icon">
          <i class="ti ti-wash"></i>
        </div>
        <span class="brand-name">LaundryEasy</span>
      </div>

      <div class="left-copy">
        <h1>Sistem Manajemen Layanan Laundry</h1>
        <p>
          Kelola pesanan, hitung biaya otomatis, dan cetak struk transaksi — semua
          dalam satu platform yang presisi.
        </p>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="stat-val">2</div>
          <div class="stat-label">Peran<br />Pengguna</div>
        </div>
        <div class="stat">
          <div class="stat-val">8</div>
          <div class="stat-label">Fitur<br />Utama</div>
        </div>
        <div class="stat">
          <div class="stat-val">100%</div>
          <div class="stat-label">Berbasis<br />Web</div>
        </div>
      </div>
    </section>

    <section class="right-panel">
      <div class="login-box">
        <div class="page-label">
          <i class="ti ti-login"></i>
          <span>Form Login</span>
        </div>

        <h2 class="login-title">Selamat datang kembali</h2>
        <p class="login-sub">Silakan masuk untuk mengelola sistem</p>

        <?php if (isset($_GET['error'])): ?>
          <div class="error-msg" id="errorMsg">
            <i class="ti ti-alert-circle"></i>
            <span>Username, password, atau role tidak sesuai.</span>
          </div>
        <?php endif; ?>

        <form action="Backend/login_process.php" method="POST">

          <div class="role-select">
            <label>Masuk sebagai</label>
            <div class="role-buttons">
              <button
                type="button"
                class="role-btn active"
                onclick="selectRole(this, 'Admin')">
                <i class="ti ti-shield"></i>
                Admin
              </button>

              <button type="button" class="role-btn" onclick="selectRole(this, 'Kasir')">
                <i class="ti ti-user"></i>
                Kasir
              </button>
            </div>
          </div>

          <input type="hidden" name="role" id="selected_role" value="Admin">

          <div class="field">
            <label for="username">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Masukkan username"
              autocomplete="off"
              required />
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password"
              required />
          </div>

          <button type="submit" class="btn-login">
            Masuk ke Sistem
            <i class="ti ti-arrow-right"></i>
          </button>
        </form>

      </div>
    </section>
  </main>

  <script>
    function selectRole(el, roleName) {
      document.querySelectorAll('.role-btn').forEach((btn) => {
        btn.classList.remove('active');
      });

      el.classList.add('active');

      // Update nilai input hidden agar terkirim ke sistem PHP
      document.getElementById('selected_role').value = roleName;
    }
  </script>
</body>

</html>