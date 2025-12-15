<?php
declare (strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel bookings</title>
</head>
<body>
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
        <label> 
            Arrival:
            <input type="date" name="arrival" required>
        </label>
        <br>
        <br>
        <label>
            Departure
            <input type="date" name="departure" required>
        </label>
        <br>
        <br>

        <button type="sumit">Book</button>
    </form>
    
</body>
</html>