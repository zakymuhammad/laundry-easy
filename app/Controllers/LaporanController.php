<?php

class LaporanController extends Controller
{
    private Laporan $laporan;
    private array $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function __construct()
    {
        $this->requireAdmin();
        $this->laporan = new Laporan();
    }

    private function periode(): array
    {
        $bulan = (int) ($_GET['bulan'] ?? date('n'));
        $tahun = (int) ($_GET['tahun'] ?? date('Y'));
        if ($bulan < 1 || $bulan > 12) { $bulan = (int) date('n'); }
        if ($tahun < 2020 || $tahun > 2099) { $tahun = (int) date('Y'); }
        return [$bulan, $tahun];
    }

    private function buildData(int $bulan, int $tahun): array
    {
        $stat = $this->laporan->statBulanan($bulan, $tahun);
        $total_transaksi  = $stat['total_transaksi']  ?? 0;
        $total_pendapatan = $stat['total_pendapatan'] ?? 0;
        $layanan_stats = $this->laporan->layananTerlaris($bulan, $tahun);
        $max_pesanan   = max(array_column($layanan_stats, 'jumlah_pesanan') ?: [1]);
        if ($max_pesanan == 0) { $max_pesanan = 1; }

        return [
            'bulan'            => $bulan,
            'tahun'            => $tahun,
            'bulan_str'        => str_pad((string) $bulan, 2, '0', STR_PAD_LEFT),
            'periode_label'    => date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)),
            'periode_label_id' => $this->namaBulan[$bulan] . ' ' . $tahun,
            'nama_bulan_list'  => $this->namaBulan,
            'stat'             => $stat,
            'total_transaksi'  => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'rata_rata'        => $total_transaksi > 0 ? round($total_pendapatan / $total_transaksi) : 0,
            'jml_antri'        => $stat['jml_antri']   ?? 0,
            'jml_proses'       => $stat['jml_proses']  ?? 0,
            'jml_selesai'      => $stat['jml_selesai'] ?? 0,
            'layanan_stats'    => $layanan_stats,
            'max_pesanan'      => $max_pesanan,
            'transaksis'       => $this->laporan->rincianTransaksi($bulan, $tahun),
            'daftar_tahun'     => $this->laporan->daftarTahun(),
            'current_username' => $_SESSION['username'] ?? 'User',
            'current_role'     => $_SESSION['role'] ?? '',
        ];
    }

    // laporan.php
    public function index(): void
    {
        [$bulan, $tahun] = $this->periode();
        $this->render('laporan/index', $this->buildData($bulan, $tahun));
    }

    // laporan_export.php
    public function export(): void
    {
        [$bulan, $tahun] = $this->periode();
        $data = $this->buildData($bulan, $tahun);
        // Export memakai label periode versi Indonesia
        $data['periode_label'] = $this->namaBulan[$bulan] . ' ' . $tahun;
        $data['dicetak_oleh']  = $_SESSION['username'] ?? 'Admin';
        $data['dicetak_pada']  = date('d ') . $this->namaBulan[(int) date('n')] . date(' Y, H:i') . ' WIB';
        $this->render('laporan/export', $data);
    }
}
