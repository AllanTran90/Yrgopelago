<?php
session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/centralbank.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$guestName = trim(($_POST['guest_name']) ?? '' );
$room_id = ((int)$_POST['room_id']?? 0);
$arrival = ($_POST['arrival'] ?? '');
$departure = ($_POST['departure'] ?? '');
$transferCode = trim($_POST['transfer_code'] ?? '');

$selectFeatures = $_POST['features'] ?? [];
$featurePrices =[
    'scuba' => 5,
    'pingpong' => 5,
    'bicykle' => 5,
    'casino' => 17,
];

$featureCost = 0;
foreach ($selectFeatures as $feature){
    if (isset($featurePrices[$feature])){
        $featureCost += $featurePrices[$feature];
    }
}

if ($guestName === '' || $room_id === 0 || $arrival === '' || $departure === ''){
    echo "Booking failed. <a href='index.php'>Gå tillbaka</a>";
    exit;
}

if ($arrival >= $departure) {
   echo "Wrong booking date. <a href='index.php'>Gå tillbaka</a>";
   exit;
}

if ($arrival < '2026-01-01' || $arrival > '2026-01-31'){
    echo "Arrival must be within January 2026.";
    exit;
}

if ($departure < '2026-01-02' || $departure > '2026-02-01'){
    echo "Departure must be within January 2026.";
    exit;
}     

$sql = "
SELECT COUNT(*)
FROM bookings
WHERE room_id = :room_id
AND arrival < :departure
AND departure > :arrival
";

try{
$statement = $pdo ->prepare($sql);
$statement -> execute([
    ':room_id' => $room_id,
    ':arrival' => $arrival,
    ':departure' => $departure
]);
}catch(PDOException $error){
    error_log('Availability check failed: ' . $error->getMessage());
    echo "Booking failed";
    exit;
}

$booked = $statement-> fetchColumn();

if ($booked > 0) {
    echo "This room is not available these date.";
    exit;
}

//coiunting nights
$arrivalDate = new DateTime($arrival);
$departureDate = new DateTime($departure);
$nights = $arrivalDate->diff($departureDate)->days;

//Get room price
try{
$statement = $pdo->prepare('SELECT price FROM rooms WHERE id = :id');
$statement-> execute([':id' => $room_id]);
$room = $statement->fetch();

} catch (PDOException $e) {
    error_log('Room lookup failed: ' . $e->getMessage());
    echo "Booking failed.";
    exit;
}

if ($room === false){
      error_log('Room not found. room_id=' . $room_id);
    echo "Booking failed. <a href='index.php'>Go back</a>";
    exit;
}

$pricePernight = (int)$room['price'];
$roomCost = $pricePernight * $nights;
$totalCost = $roomCost + $featureCost;

if (!validateTransferCode($transferCode, $totalCost)) {
    echo "Payment failed. Invalid transfer code.";
    exit;
}


$sql = "
    INSERT INTO bookings (
    guest_name, room_id, arrival, departure)
    VALUES (
    :guest_name, :room_id, :arrival, :departure)";

try {
$statement = $pdo->prepare($sql);
$statement->execute([
    ':guest_name' => $guestName,
    ':room_id' => $room_id,
    ':arrival' => $arrival,
    ':departure' => $departure
]);}
     catch (PDOException $e) {
    error_log('Booking insert failed: ' . $e->getMessage());
    echo "Booking failed.";
    exit;
};

$_SESSION['confirmation'] = [
    'guest_name'   => $guestName,
    'room_name'    => ['','Budget','Standard','Luxury'][$room_id],
    'arrival'      => $arrival,
    'departure'    => $departure,
    'nights'       => $nights,
    'room_cost'    => $roomCost,
    'feature_cost' => $featureCost,
    'total_cost'   => $totalCost,
    'transfer_code'=> $transferCode,
];

header('Location: confirmation.php');
exit;
