<?php

class Layanan
{
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array
    {
        return $this->db->query('SELECT id, nama_layanan, tarif_per_kg FROM layanan')->fetchAll();
    }

    public function search(string $cari = ''): array
    {
        $sql = 'SELECT * FROM layanan WHERE 1=1';
        $params = [];
        if ($cari !== '') { $sql .= ' AND nama_layanan LIKE :cari'; $params[':cari'] = "%$cari%"; }
        $sql .= ' ORDER BY nama_layanan ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM layanan WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $nama, int $tarif): bool
    {
        $stmt = $this->db->prepare('INSERT INTO layanan (nama_layanan, tarif_per_kg) VALUES (?, ?)');
        return $stmt->execute([$nama, $tarif]);
    }

    public function update(int $id, string $nama, int $tarif): bool
    {
        $stmt = $this->db->prepare('UPDATE layanan SET nama_layanan = ?, tarif_per_kg = ? WHERE id = ?');
        return $stmt->execute([$nama, $tarif, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM layanan WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function usageCount(int $id): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM transaksi WHERE id_layanan = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetch()['total'];
    }

    public function usageMap(): array
    {
        return $this->db->query('SELECT id_layanan, COUNT(*) AS jml FROM transaksi GROUP BY id_layanan')
            ->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function topUsage()
    {
        return $this->db->query("
            SELECT l.nama_layanan, COUNT(t.id) AS jml, SUM(t.total_biaya) AS omzet
            FROM layanan l
            LEFT JOIN transaksi t ON t.id_layanan = l.id
            GROUP BY l.id ORDER BY jml DESC LIMIT 1
        ")->fetch();
    }

    public function countAll(): int { return (int) $this->db->query('SELECT COUNT(*) FROM layanan')->fetchColumn(); }
    public function minTarif()       { return $this->db->query('SELECT MIN(tarif_per_kg) FROM layanan')->fetchColumn(); }
    public function maxTarif()       { return $this->db->query('SELECT MAX(tarif_per_kg) FROM layanan')->fetchColumn(); }

    public function usageStat(int $id)
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) AS total_pakai, SUM(total_biaya) AS total_omzet
            FROM transaksi WHERE id_layanan = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
