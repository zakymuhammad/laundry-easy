<?php

class TransaksiController extends Controller
{
    private Transaksi $trx;
    private Layanan $layanan;
    public function __construct()
    {
        $this->requireLogin();
        $this->trx     = new Transaksi();
        $this->layanan = new Layanan();
    }

    // transaksi.php
    public function index(): void
    {
        $flash  = $this->takeFlash();
        $cari   = trim($_GET['cari'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $transaksis = $this->trx->all($cari, $status);

        $this->render('transaksi/index', [
            'role'       => $_SESSION['role'] ?? '',
            'username'   => $_SESSION['username'] ?? 'User',
            'msg'        => $flash['msg'],
            'msg_type'   => $flash['msg_type'],
            'cari'       => $cari,
            'status'     => $status,
            'transaksis' => $transaksis,
            'total_rows' => count($transaksis),
            'stat'       => $this->trx->statCards(),
        ]);
    }

    // transaksi_tambah.php
    public function tambah(): void
    {
        $this->render('transaksi/tambah', [
            'data_layanan' => $this->layanan->all(),
            'role'         => $_SESSION['role'] ?? 'User',
            'username'     => $_SESSION['username'] ?? 'User',
        ]);
    }

    // Backend/proses_transaksi.php
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['items'])) { $this->redirect('transaksi_tambah.php'); }
        try {
            $rows = [];
            foreach ($_POST['items'] as $item) {
                $nama      = htmlspecialchars($item['nama_pelanggan'] ?? '');
                $idLayanan = intval($item['id_layanan'] ?? 0);
                $berat     = floatval($item['berat_kg'] ?? 0);
                $durasi    = intval($item['durasi_hari'] ?? 0);
                if ($nama === '' || $berat <= 0 || $idLayanan <= 0 || !in_array($durasi, [1, 2, 3])) {
                    throw new Exception('Input data tidak valid.');
                }
                $l = $this->layanan->find($idLayanan);
                if (!$l) { throw new Exception('Layanan tidak ada.'); }
                $s = Transaksi::surchargePerKg($durasi);
                $rows[] = [
                    'nama'           => $nama,
                    'id_layanan'     => $idLayanan,
                    'berat'          => $berat,
                    'durasi'         => $durasi,
                    'biaya_tambahan' => $s * $berat,
                    'total'          => $berat * ($l['tarif_per_kg'] + $s),
                    'status'         => 'antri',
                ];
            }
            $this->trx->createMany($rows);
            $this->redirect('dashboard.php?status=sukses');
        } catch (Exception $e) {
            $this->redirect('dashboard.php?status=gagal&pesan=' . urlencode($e->getMessage()));
        }
    }

    // transaksi_edit.php
    public function edit(): void
    {
        $id = intval($_GET['id'] ?? 0);
        $t = $this->trx->find($id);
        if (!$t) {
            $this->setFlash('Data transaksi tidak ditemukan!', 'danger');
            $this->redirect('transaksi.php');
        }
        $this->render('transaksi/edit', [
            't'             => $t,
            'layananList'   => $this->layanan->all(),
            'statusOptions' => ['antri', 'proses', 'selesai'],
            'role'          => $_SESSION['role'] ?? 'User',
            'username'      => $_SESSION['username'] ?? 'User',
        ]);
    }

    // transaksi_update.php
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('transaksi.php'); }
        $id            = intval($_POST['id'] ?? 0);
        $nama          = htmlspecialchars(trim($_POST['nama_pelanggan'] ?? ''));
        $idLayanan     = intval($_POST['id_layanan'] ?? 0);
        $berat         = floatval($_POST['berat_kg'] ?? 0);
        $durasi        = intval($_POST['durasi_hari'] ?? 0);
        $biayaTambahan = intval($_POST['biaya_tambahan'] ?? 0);
        $status        = $_POST['status'] ?? '';
        if ($nama === '' || $idLayanan <= 0 || $berat <= 0 || !in_array($status, ['antri', 'proses', 'selesai'])) {
            die('Data yang diinput tidak valid.');
        }
        $l = $this->layanan->find($idLayanan);
        if (!$l) { die('Jenis layanan tidak ditemukan.'); }
        $s = Transaksi::surchargePerKg($durasi);
        $total = ($berat * ($l['tarif_per_kg'] + $s)) + $biayaTambahan;
        $this->trx->update($id, $nama, $idLayanan, $berat, $durasi, $biayaTambahan, $total, $status);
        $this->setFlash('Pesanan atas nama ' . $nama . ' berhasil diperbarui.', 'success');
        $this->redirect('transaksi.php');
    }

    // Backend/update_status.php
    public function updateStatus(): void
    {
        if (!isset($_SESSION['user_id'])) { die('Akses ditolak.'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = intval($_POST['id_transaksi'] ?? 0);
            $status = htmlspecialchars(trim($_POST['status_baru'] ?? ''));
            if ($id > 0 && in_array($status, ['antri', 'proses', 'selesai'])) {
                $this->trx->updateStatus($id, $status);
            }
            $asal = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/dashboard.php');
            header('Location: ' . $asal);
            exit;
        }
    }

    // Backend/transaksi_hapus.php
    public function hapus(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->trx->delete($id);
            $this->setFlash('Transaksi #TRX-' . str_pad((string) $id, 3, '0', STR_PAD_LEFT) . ' berhasil dihapus.', 'success');
        }
        $this->redirect('transaksi.php');
    }

    // struk.php
    public function struk(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) { die('ID transaksi tidak valid.'); }
        $t = $this->trx->findWithLayanan($id);
        if (!$t) { die('Transaksi tidak ditemukan.'); }
        $no_struk = 'LDRY-' . date('Ymd', strtotime($t['tanggal_transaksi'])) . '-' . str_pad((string) $t['id'], 4, '0', STR_PAD_LEFT);
        $this->render('transaksi/struk', ['id' => $id, 't' => $t, 'no_struk' => $no_struk]);
    }
}
