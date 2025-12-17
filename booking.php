<?php
declare(strict_types=1);
require __DIR__ . '/includes/db.php';

$guestName = trim($_POST['guest_name'] ?? '' );
$room_id = (int)$_POST['room_id'];
$arrival = $_POST['arrival'];
$departure = $_POST['departure'];

if (empty($guestName) || $room_id === 0 || empty($arrival) || empty($departure)){
    die("You have to fill all the fields. <a href='index.php'>Gå tillbaka</a>");
}

if ($arrival >= $departure) {
    die("Departure has to be after Arrival. <a href='index.php'>Gå tillbaka</a>");
}

if ($arrival < '2026-01-01' || $arrival > '2026-01-31'){
    die("Arrival must be within January 2026.");
}

if ($departure < '2026-01-02' || $departure > '2026-02-01'){
    die("Departure must be within January 2026.");
}     


$sql = "
SELECT COUNT(*)
FROM bookings
WHERE room_id = :room_id
AND arrival < :departure
AND departure > :arrival
";


$statement = $pdo ->prepare($sql);
$statement -> execute([
    ':room_id' => $room_id,
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


$statement = $pdo->prepare($sql);;
$statement->execute([
    ':guest_name' => $guestName,
    ':room_id' => $room_id,
    ':arrival' => $arrival,
    ':departure' => $departure
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking confirmed</title>
</head>
<body>
     <div class="success">
        <h1>Your booking is confirmed!</h1>
        <p><strong>name:</strong> <?= htmlspecialchars($guestName) ?></p>
        <p><strong>Room:</strong> <?= ['','Budget', 'Standard', 'Luxury'][$room_id] ?></p>
        <p><strong>Arrival:</strong> <?= htmlspecialchars($arrival) ?></p>
        <p><strong>Departure:</strong> <?= htmlspecialchars($departure) ?></p>
    </div>
    <p><a href="index.php">Book again</a></p>
    
</body>
</html>