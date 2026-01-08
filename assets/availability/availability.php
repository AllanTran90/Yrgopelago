<?php
declare(strict_types=1);

function getAvailabilityForDate(PDO $pdo, string $date): array
{
    $availability = [];

    for ($roomId = 1; $roomId <= 3; $roomId++) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM bookings
            WHERE room_id = :room_id
              AND arrival <= :date
              AND departure > :date
        ");

        
        $stmt->execute([
            ':room_id' => $roomId,
            ':date'    => $date,
        ]);

        $availability[$roomId] = ((int)$stmt->fetchColumn() === 0);
   
    }

    return $availability;
}
