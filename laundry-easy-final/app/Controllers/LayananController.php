<?php

class LayananController extends Controller
{
    private Layanan $layanan;
    public function __construct()
    {
        $this->requireAdmin();
        $this->layanan = new Layanan();
    }

    // layanan.php
    public function index(): void
    {
        $flash = $this->takeFlash();
        $cari  = trim($_GET['cari'] ?? '');
        $layanans = $this->layanan->search($cari);
        $this->render('layanan/index', [
            'username'      => $_SESSION['username'] ?? 'Admin',
            'role'          => $_SESSION['role'] ?? 'Admin',
            'msg'           => $flash['msg'],
            'msg_type'      => $flash['msg_type'],
            'cari'          => $cari,
            'layanans'      => $layanans,
            'total_rows'    => count($layanans),
            'statUsage'     => $this->layanan->topUsage(),
            'totalLayanan'  => $this->layanan->countAll(),
            'tarifTermurah' => $this->layanan->minTarif(),
            'tarifTermahal' => $this->layanan->maxTarif(),
            'usageRows'     => $this->layanan->usageMap(),
        ]);
    }

    // layanan_tambah.php
    public function tambah(): void
    {
        $this->render('layanan/tambah', [
            'username' => $_SESSION['username'] ?? 'Admin',
            'role'     => $_SESSION['role'] ?? 'Admin',
        ]);
    }

    // layanan_edit.php
    public function edit(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) { $this->redirect('layanan.php'); }
        $l = $this->layanan->find($id);
        if (!$l) {
            $this->setFlash('Layanan tidak ditemukan.', 'error');
            $this->redirect('layanan.php');
        }
        $this->render('layanan/edit', [
            'username' => $_SESSION['username'] ?? 'Admin',
            'role'     => $_SESSION['role'] ?? 'Admin',
            'id'       => $id,
            'l'        => $l,
            'stat'     => $this->layanan->usageStat($id),
        ]);
    }

    // Backend/layanan_simpan.php
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('layanan.php'); }
        $nama  = htmlspecialchars(trim($_POST['nama_layanan'] ?? ''));
        $tarif = intval($_POST['tarif_per_kg'] ?? 0);
        if ($nama !== '' && $tarif >= 500) {
            $this->layanan->create($nama, $tarif);
            $this->setFlash("Layanan $nama berhasil ditambahkan!", 'success');
        } else {
            $this->setFlash('Gagal: Data tidak valid atau tarif di bawah Rp 500.', 'error');
        }
        $this->redirect('layanan.php');
    }

    // Backend/layanan_update.php
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('layanan.php'); }
        $id    = intval($_POST['id'] ?? 0);
        $nama  = htmlspecialchars(trim($_POST['nama_layanan'] ?? ''));
        $tarif = intval($_POST['tarif_per_kg'] ?? 0);
        if ($id > 0 && $nama !== '' && $tarif >= 500) {
            $this->layanan->update($id, $nama, $tarif);
            $this->setFlash('Perubahan harga layanan berhasil disimpan.', 'success');
        } else {
            $this->setFlash('Gagal: Data tidak valid atau tarif di bawah Rp 500.', 'error');
        }
        $this->redirect('layanan.php');
    }

    // Backend/layanan_hapus.php
    public function hapus(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            if ($this->layanan->usageCount($id) > 0) {
                $this->setFlash('Gagal! Layanan tidak bisa dihapus karena sedang digunakan dalam riwayat transaksi.', 'error');
            } else {
                $this->layanan->delete($id);
                $this->setFlash('Layanan berhasil dihapus dari sistem.', 'success');
            }
        }
        $this->redirect('layanan.php');
    }
}
