<?php
declare(strict_types=1);
require __DIR__ . '/includes/db.php';


$availability = [];

if (isset($_POST['check_availability'])) {
    $arrival = $_POST['arrival'] ?? '';
    $departure = $_POST['departure'] ?? '';
    
    if ($arrival < $departure) {
        for ($room_id = 1; $room_id <= 3; $room_id++) {
            $sql = "
            SELECT COUNT(*)
            FROM bookings
            WHERE room_id = :room_id
            AND arrival < :departure
            AND departure > :arrival
            ";
            
            //$pdo = new PDO('sqlite:database/yrgopelago.db');
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':room_id' => $room_id,
                ':arrival' => $arrival,
                ':departure' => $departure
            ]);
            
            $availability[$room_id] = (int)$statement->fetchColumn() === 0;
        }
    }
}

require __DIR__ . '/startpage.html';