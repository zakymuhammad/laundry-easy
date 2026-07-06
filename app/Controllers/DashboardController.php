<?php

class DashboardController extends Controller
{
    private Transaksi $trx;
    public function __construct()
    {
        $this->requireLogin();
        $this->trx = new Transaksi();
    }

    public function index(): void
    {
        $this->render('dashboard/index', [
            'current_username'    => $_SESSION['username'] ?? 'User',
            'current_role'        => $_SESSION['role'] ?? '',
            'total_hari_ini'      => $this->trx->countToday(),
            'pendapatan_hari_ini' => $this->trx->omsetToday(),
            'sedang_proses'       => $this->trx->countProses(),
            'selesai_bulan_ini'   => $this->trx->countSelesaiThisMonth(),
            'transaksi_terbaru'   => $this->trx->latest(5),
        ]);
    }
}
