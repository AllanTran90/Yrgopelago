<?php
declare(strict_types=1);
require __DIR__ . '/../../includes/db.php';

$date = $_GET['date'] ?? '' ;

if ($date === ''){
    echo json_encode([]);
    exit;
}

$result = [];

for ($roomId = 1; $roomId <= 3; $roomId++){
    $sql = "
        SELECT COUNT(*) FROM bookings
        WHERE room_id = :room_id
        AND arrival <= :date
        AND departure > :date
    ";
 

$statement = $pdo->prepare($sql);
$statement->execute([
    ':room_id' => $roomId,
    ':date' => $date
]);

    $result[$roomId] = $statement->fetchColumn() == 0;

}

header('Content-Type: application/json');
echo json_encode($result);