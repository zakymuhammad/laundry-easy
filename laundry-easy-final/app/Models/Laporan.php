<?php

class Laporan
{
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function statBulanan(int $bulan, int $tahun)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)                      AS total_transaksi,
                   COALESCE(SUM(total_biaya), 0) AS total_pendapatan,
                   SUM(status = 'antri')         AS jml_antri,
                   SUM(status = 'proses')        AS jml_proses,
                   SUM(status = 'selesai')       AS jml_selesai
            FROM transaksi
            WHERE MONTH(tanggal_transaksi) = :bulan AND YEAR(tanggal_transaksi) = :tahun
        ");
        $stmt->execute([':bulan' => $bulan, ':tahun' => $tahun]);
        return $stmt->fetch();
    }

    public function layananTerlaris(int $bulan, int $tahun): array
    {
        $stmt = $this->db->prepare("
            SELECT l.nama_layanan,
                   COUNT(t.id)                    AS jumlah_pesanan,
                   COALESCE(SUM(t.total_biaya),0) AS total_omset
            FROM layanan l
            LEFT JOIN transaksi t
                   ON l.id = t.id_layanan
                  AND MONTH(t.tanggal_transaksi) = :bulan
                  AND YEAR(t.tanggal_transaksi)  = :tahun
            GROUP BY l.id, l.nama_layanan
            ORDER BY jumlah_pesanan DESC
        ");
        $stmt->execute([':bulan' => $bulan, ':tahun' => $tahun]);
        return $stmt->fetchAll();
    }

    public function rincianTransaksi(int $bulan, int $tahun): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, l.nama_layanan
            FROM transaksi t
            LEFT JOIN layanan l ON t.id_layanan = l.id
            WHERE MONTH(t.tanggal_transaksi) = :bulan AND YEAR(t.tanggal_transaksi) = :tahun
            ORDER BY t.tanggal_transaksi DESC
        ");
        $stmt->execute([':bulan' => $bulan, ':tahun' => $tahun]);
        return $stmt->fetchAll();
    }

    public function daftarTahun(): array
    {
        $years = $this->db->query('SELECT DISTINCT YEAR(tanggal_transaksi) AS y FROM transaksi ORDER BY y DESC')
            ->fetchAll(PDO::FETCH_COLUMN);
        return empty($years) ? [(int) date('Y')] : $years;
    }
}
