<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$date = $_GET['arrival'] ?? null;

if (!$date) {
    echo json_encode([]);
    exit;
}

function getAvailabilityForDate(PDO $pdo, string $date): array
{
    $availability = [];

    for ($roomId = 1; $roomId <= 3; $roomId++) {
        $sql = "
            SELECT COUNT(*)
            FROM bookings
            WHERE room_id = :room_id
              AND arrival <= :date
              AND departure > :date
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':room_id' => $roomId,
            ':date'    => $date,
        ]);

        $availability[$roomId] = ((int)$stmt->fetchColumn() === 0);
   
    }

    return $availability;
}

echo json_encode(getAvailabilityForDate($pdo, $date));