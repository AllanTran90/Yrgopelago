<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/availability.php';

header('Content-Type: application/json');

$date = $_GET['arrival'] ?? null;

if ($date === null) {
    echo json_encode([]);
    exit;
}

echo json_encode(getAvailabilityForDate($pdo, $date));