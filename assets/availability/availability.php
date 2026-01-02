<?php
declare(strict_types=1);

require __DIR__ . '/includes/db.php';

function getAvailabilityForDate(PDO $pdo, string $date): array
{
    $availability = [];

    for ($roomId = 1; $roomId <= 3; $roomId++) {
        $sql = "
            SELECT COUNT(*) FROM bookings
            WHERE room_id = :room_id
              AND arrival <= :date
              AND departure > :date
        ";

        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':room_id' => $roomId,
            ':date' => $date,
        ]);

        $availability[$roomId] = $statement->fetchColumn() === 0;
    }

    return $availability;
}
