<?php

// Koneksi PDO tunggal (singleton)
class Database
{
    private static ?PDO $instance = null;

    private const HOST = 'localhost';
    private const NAME = 'laundry_easy';
    private const USER = 'root';
    private const PASS = '';

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::NAME . ';charset=utf8mb4';
            try {
                self::$instance = new PDO($dsn, self::USER, self::PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('Koneksi ke sistem gagal.');
            }
        }
        return self::$instance;
    }
}
