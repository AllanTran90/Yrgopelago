<?php
declare(strict_types=1);

require __DIR__ . "/sql-database/db.php";

$guestname = $_POST['guest_name'];
$roomId = (int)$_POST['room_id'];
$arrival = $_POST['arrival'];
$departure = $_POST['departure'];

$sql = "
SELECT COUNT(*)
FROM bookings
WHERE room_id = :room_id
AND arrival < :departure
AND departure > :arrival
";

$statement = $pdo ->prepare($sql);
$statement -> execute([
    ':room_id' => $roomId,
    ':arrival' => $arrival,
    ':departure' => $departure
]);

$booked = $statement-> fetchColumn();

if ($booked > 0) {
    echo "This room is not available these date.";
    exit;
}

$sql = "
    INSERT INTO bookings (
    guest_name, room_id, arrival, departure)
    VALUES (
    :guest_name, :room_id, :arrival, :departure)";

$statement = $pdo->prepare($sql);
$statement->execute([
    ':guest_name' => $guestName,
    ':room_id' => $roomId,
    ':arrival' => $arrival,
    ':departure' => $departure
]);

echo"Your booking is confirmed!";