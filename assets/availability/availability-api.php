<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/availability.php';


$date = $_GET['date'] ?? '';

if ($date === '') {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');
echo json_encode(getAvailabilityForDate($pdo, $date));