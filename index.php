<?php
declare(strict_types=1);

$availability = [];

if (isset($_POST['check_availability'])) {
    $arrival = $_POST['arrival'] ?? '';
    $departure = $_POST['departure'] ?? '';

    if ($arrival < $departure) {
        for ($roomId = 1; $roomId <= 3; $roomId++) {
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
    <title>Hotel Bookings</title>
</head>
<body>
    <h1>Welcome to Yrgopelag Hotel</h1>

    <form method="post">
        <label>
            Arrival:
            <input type="date" name="arrival" required 
                   min="2026-01-01" max="2026-01-31">
        </label>
        <br><br>
        
        <label>
            Departure:
            <input type="date" name="departure" required 
                   min="2026-01-02" max="2026-02-01">
        </label>
        <br><br>
        <h2>Check Room Availability</h2>
        <button type="submit" name="check_availability">Check Availability</button>
    </form>

    
    <?php if (!empty($availability)) : ?>
        <h3>Availability Results:</h3>
        <ul>
            <li>Budget: <?= $availability[1] ? '✅ Available' : '❌ Booked' ?></li>
            <li>Standard: <?= $availability[2] ? '✅ Available' : '❌ Booked' ?></li>
            <li>Luxury: <?= $availability[3] ? '✅ Available' : '❌ Booked' ?></li>
        </ul>
    <?php endif; ?>

    <br><br>

   
    <h2>Book a Room</h2>
    <form action="booking.php" method="post">
        <label>
            Name:
            <input type="text" name="guest_name" required>
        </label>
        <br><br>
        
        <label>
            Room:
            <select name="room_id" required>
                <option value="1">Budget</option>
                <option value="2">Standard</option>
                <option value="3">Luxury</option>
            </select>
        </label>
        <br><br>
        
        <label>
            Arrival:
            <input type="date" name="arrival" required 
                   min="2026-01-01" max="2026-01-31">
        </label>
        <br><br>
        
        <label>
            Departure:
            <input type="date" name="departure" required 
                   min="2026-01-02" max="2026-02-01">
        </label>
        <br><br>
        
        <button type="submit">Book</button>
    </form>
</body>
</html>