<?php
require __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$arrival = $_GET['arrival'] ?? null;
$departure = $_GET['departure'] ?? null; 

if (!$arrival || !$departure){
    echo json_encode([]);
    exit;
}

$sql = "
SELECT room_id
FROM bookings
WHERE NOT (
    departure <= :arrival
    OR arrival >= :departure
)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':arrival'   => $arrival,
    ':departure' => $departure
]);

$bookedRooms = $stmt->fetchAll(PDO::FETCH_COLUMN);


$availability = [
    1 => true,
    2 => true,
    3 => true
];


foreach ($bookedRooms as $roomId) {
    $availability[(int)$roomId] = false;
}

echo json_encode($availability);