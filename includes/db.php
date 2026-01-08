<?php
declare(strict_types=1);

try {
    $dbPath = dirname(__DIR__) . '/database/yrgopelago.db';

    if (!file_exists($dbPath)) {
        throw new Exception('Database file not found: ' . $dbPath);
    }

    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON');

} catch (Throwable $error) {
    die('Database error: ' . $error->getMessage());
}