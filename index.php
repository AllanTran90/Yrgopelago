


// $availability = [];

// if (isset($_POST['check_availability'])) {
//     $arrival = $_POST['arrival'] ?? '';
//     $departure = $_POST['departure'] ?? '';
    
//     if ($arrival < $departure) {
//         for ($room_id = 1; $room_id <= 3; $room_id++) {
//             $sql = "
//             SELECT COUNT(*)
//             FROM bookings
//             WHERE room_id = :room_id
//             AND arrival < :departure
//             AND departure > :arrival
//             ";
            
           
//             $statement = $pdo->prepare($sql);
//             $statement->execute([
//                 ':room_id' => $room_id,
//                 ':arrival' => $arrival,
//                 ':departure' => $departure
//             ]);
            
//             $availability[$room_id] = (int)$statement->fetchColumn() === 0;
//         }
//     }
// }

// require __DIR__ . '/startpage.html';

<?php
declare(strict_type=1);
require __DIR__ . '/includes/db.php';

$totalCost = null;
$error = null;


//calc price if user press calculate price.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])){

    $roomId = (int)($_POST['room_id'] ?? 0);
    $arrival = $_POST['arrival'] ?? '';
    $departure = $_POST['departure'] ?? '';
    $features = $_POST['features'] ?? [];

    if ($roomId === 0 || $arrival === '' || $departure === ''){
        $error = 'You have to choose room and date.';
    }
    else{
        //X many nights
        $arrivalDate = new DateTime($arrival);
        $departureDate = new DateTime($departure);
        $nights = $arrivalDate-> diff($departureDate)->days;

        $statement = $pdo->prepare('SELECT price FROM room WHERE id = :id');
        $statement->execute([':id' => $roomId]);
        $room = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$room){
            $error = 'Cant find room.';
        }
        else{
            $roomCost = (int)$room['price'] * $nights;

            //feature price
            $featurePrices = [
                'scuba' => '5',
                'pingpong' => '5',
                'bicykle' => '5',
                'casino' => '17', 
            ];

            $featureCost = 0;
            foreach($features as $feature){
                if (isset($featurePrices[$feature])){
                    $featureCost += $featurePrices[$feature];
                }
            }
            $totalCost = $roomCost + $featureCost;
        }
    }
}
?>

