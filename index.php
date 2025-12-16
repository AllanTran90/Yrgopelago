<?php
declare (strict_types=1);
require __DIR__ . '/includes/db.php';

$availability = [];

if(isset($_POST['arrival'], $_POST['departure'])){
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
if ($arrival < $departure){
    for($roomId = 1; $roomId <= 3; $roomId++){
        $sql = "
        SELECT COUNT(*)
        FROM bookings
        WHERE room_id = :room_id
        AND arrival < :departure
        AND departure > :arrival
        ";

        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':room_id' => $roomId,
            ':arrival' => $arrival,
            ':departure' => $departure
        ]);
        $availability[$roomId] = (int)$statement->fetchColumn() === 0;
    }
}    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel bookings</title>
</head>
<body>
    <?php if (!empty($availability)) : ?>
        <h2>Availability</h2>
        <ul>
            <li>Budget:<?= $availability[1] ? 'Available' : 'Booked' ?></li>
            <li>Standard<?= $availability[2] ? 'Available' : 'Booked' ?></li>
            <li>Luxury<?= $availability[3] ? 'Available' : 'Booked' ?></li>
        </ul>
    <?php endif; ?>    
    <h1>Book a room</h1>
    <form action="booking.php" method="post">
        <label>
            Name:
            <input type="text" name="guest_name" required>
        </label>
        <br>
        <br>
        <label>
            Room:
            <select name="room_id">
                <option value="1">Budget</option>
                <option value="2">Standard</option>
                <option value="3">Luxury</option>
            </select>
        </label>
        <br>
        <br>
        <h1>Check room availability</h1>
        <label> 
            Arrival:
            <input type="date" name="arrival" required min="2026-01-01" max="2026-01-31">
        </label>
        <br>
        <br>
        <label>
            Departure
            <input type="date" name="departure" required min="2026-01-02" max="2026-02-01">
        </label>
        <button type="submit">Check availability</button>
        <br>
        <br>
        <button type="sumit">Book</button>
    </form>
    
</body>
</html>