<?php

class Transaksi
{
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    // ── Rumus biaya: surcharge per kg berdasar durasi ──
    public static function surchargePerKg(int $durasi): int
    {
        return $durasi === 2 ? 2000 : ($durasi === 1 ? 5000 : 0);
    }

    public function all(string $cari = '', string $status = ''): array
    {
        $sql = "SELECT t.*, l.nama_layanan, l.tarif_per_kg
                FROM transaksi t
                LEFT JOIN layanan l ON t.id_layanan = l.id
                WHERE 1=1";
        $params = [];
        if ($cari !== '')   { $sql .= ' AND t.nama_pelanggan LIKE :cari'; $params[':cari'] = "%$cari%"; }
        if ($status !== '') { $sql .= ' AND t.status = :status';          $params[':status'] = $status; }
        $sql .= ' ORDER BY t.tanggal_transaksi DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function statCards()
    {
        return $this->db->query("
            SELECT COUNT(*) AS total,
                   SUM(status = 'antri')   AS antri,
                   SUM(status = 'proses')  AS proses,
                   SUM(status = 'selesai') AS selesai,
                   SUM(total_biaya)        AS pendapatan
            FROM transaksi
        ")->fetch();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM transaksi WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findWithLayanan(int $id)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, l.nama_layanan, l.tarif_per_kg
            FROM transaksi t
            LEFT JOIN layanan l ON t.id_layanan = l.id
            WHERE t.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function createMany(array $rows): void
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('INSERT INTO transaksi
                (nama_pelanggan, id_layanan, berat_kg, durasi_hari, biaya_tambahan, total_biaya, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)');
            foreach ($rows as $r) {
                $stmt->execute([$r['nama'], $r['id_layanan'], $r['berat'],
                    $r['durasi'], $r['biaya_tambahan'], $r['total'], $r['status']]);
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update(int $id, string $nama, int $idLayanan, float $berat, int $durasi, int $biayaTambahan, int $total, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE transaksi
            SET nama_pelanggan = ?, id_layanan = ?, berat_kg = ?, durasi_hari = ?, biaya_tambahan = ?, total_biaya = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nama, $idLayanan, $berat, $durasi, $biayaTambahan, $total, $status, $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE transaksi SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM transaksi WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // ── Statistik dashboard ──
    public function countToday(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) AS total FROM transaksi WHERE DATE(tanggal_transaksi) = CURRENT_DATE")->fetch()['total'];
    }
    public function omsetToday()
    {
        return $this->db->query("SELECT SUM(total_biaya) AS omset FROM transaksi WHERE DATE(tanggal_transaksi) = CURRENT_DATE")->fetch()['omset'] ?? 0;
    }
    public function countProses(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) AS total FROM transaksi WHERE status = 'proses'")->fetch()['total'];
    }
    public function countSelesaiThisMonth(): int
    {
        return (int) $this->db->query("
            SELECT COUNT(*) AS total FROM transaksi
            WHERE status = 'selesai'
              AND MONTH(tanggal_transaksi) = MONTH(CURRENT_DATE)
              AND YEAR(tanggal_transaksi)  = YEAR(CURRENT_DATE)
        ")->fetch()['total'];
    }
    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, l.nama_layanan
            FROM transaksi t
            LEFT JOIN layanan l ON t.id_layanan = l.id
            ORDER BY t.id DESC LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
