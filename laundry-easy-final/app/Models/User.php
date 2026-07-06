<?php

class User
{
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function findByCredentials(string $username, string $role)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ? AND role = ?');
        $stmt->execute([$username, $role]);
        return $stmt->fetch();
    }

    public function all(string $cari = '', string $role = ''): array
    {
        $sql = 'SELECT * FROM users WHERE 1=1';
        $params = [];
        if ($cari !== '') { $sql .= ' AND username LIKE :cari'; $params[':cari'] = "%$cari%"; }
        if ($role !== '') { $sql .= ' AND role = :role';       $params[':role'] = $role; }
        $sql .= ' ORDER BY id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function stats()
    {
        return $this->db->query("
            SELECT COUNT(*) AS total,
                   SUM(role = 'Admin') AS total_admin,
                   SUM(role = 'Kasir') AS total_kasir
            FROM users
        ")->fetch();
    }

    public function create(string $username, string $passwordHash, string $role): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        return $stmt->execute([$username, $passwordHash, $role]);
    }

    public function updateWithPassword(int $id, string $username, string $passwordHash, string $role): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?');
        return $stmt->execute([$username, $passwordHash, $role, $id]);
    }

    public function updateWithoutPassword(int $id, string $username, string $role): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET username = ?, role = ? WHERE id = ?');
        return $stmt->execute([$username, $role, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
