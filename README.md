# LaundryEasy 🧺

Aplikasi manajemen laundry berbasis web untuk mengelola layanan, transaksi, akun kasir, dan laporan bulanan. Dibangun dengan **PHP native** menggunakan pola arsitektur **MVC** yang rapi (tanpa framework), sehingga mudah dipelajari dan dikembangkan.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi & Hak Akses** — login dengan dua peran: **Admin** dan **Kasir**.
- 📊 **Dashboard** — ringkasan transaksi hari ini, pendapatan, pesanan sedang proses, dan selesai bulan ini.
- 🧾 **Manajemen Transaksi** — tambah, edit, hapus, ubah status (antri → proses → selesai), dan cetak **struk**.
- 🧮 **Perhitungan Biaya Otomatis** — total dihitung dari berat × (tarif per kg + biaya kilat) sesuai durasi pengerjaan.
- 🧴 **Manajemen Layanan** — CRUD daftar layanan beserta tarif per kg.
- 👥 **Kelola Akun** — kelola user (Admin/Kasir), khusus untuk Admin.
- 📈 **Laporan Bulanan** — statistik per bulan, layanan terlaris, dan **export/cetak** laporan.

---

## 🖼️ Tampilan Aplikasi

> Letakkan gambar Anda di folder [`screenshots/`](screenshots) dengan nama berikut (atau sesuaikan nama file di bawah ini).

| Halaman Login | Dashboard |
|:---:|:---:|
| ![Halaman Login](screenshots/login.png) | ![Dashboard](screenshots/dashboard.png) |

| Daftar Transaksi |
|:---:|
| ![Daftar Transaksi](screenshots/transaksi.png) |

---

## 🛠️ Teknologi

| Bagian | Teknologi |
|---|---|
| Bahasa | PHP (native, tanpa framework) |
| Database | MySQL / MariaDB |
| Akses DB | PDO (prepared statement) |
| Styling | Tailwind CSS (CDN) + CSS kustom per halaman |
| Ikon | Tabler Icons (CDN) |
| Arsitektur | MVC (Model - View - Controller) |

---

## 🏗️ Struktur Proyek
