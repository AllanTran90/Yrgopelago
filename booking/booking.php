<?php
session_start();

require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/centralbank.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$guestName    = trim($_POST['guest_name'] ?? '');
$room_id      = (int)($_POST['room_id'] ?? 0);
$arrival      = $_POST['arrival'] ?? '';
$departure    = $_POST['departure'] ?? '';
$transferCode = trim($_POST['transfer_code'] ?? '');
$features     = $_POST['features'] ?? [];


// validation
if ($guestName === '' || $room_id === 0 || $arrival === '' || $departure === '') {
    $_SESSION['error'] = 'Booking failed.';
    header('Location: ../index.php');
    exit;
}

if ($arrival >= $departure) {
    $_SESSION['error'] = 'Wrong booking date.';
    header('Location: ../index.php');
    exit;
}

if ($arrival < '2026-01-01' || $arrival > '2026-01-31') {
    $_SESSION['error'] = 'Arrival must be within January 2026.';
    header('Location: ../index.php');
    exit;
}

if ($departure < '2026-01-02' || $departure > '2026-02-01') {
    $_SESSION['error'] = 'Departure must be within January 2026.';
    header('Location: ../index.php');
    exit;
}

// availability check
$sql = "
SELECT COUNT(*)
FROM bookings
WHERE room_id = :room_id
AND arrival < :departure
AND departure > :arrival
";

try {
    $statement = $pdo->prepare($sql);
    $statement->execute([
        ':room_id'   => $room_id,
        ':arrival'   => $arrival,
        ':departure' => $departure
    ]);
} catch (PDOException $error) {
    error_log('Availability check failed: ' . $error->getMessage());
    $_SESSION['error'] = 'Booking failed.';
    header('Location: ../index.php');
    exit;
}

if ($statement->fetchColumn() > 0) {
    $_SESSION['error'] = 'This room is not available for selected dates.';
    header('Location: ../index.php');
    exit;
}

// nights
$arrivalDate   = new DateTime($arrival);
$departureDate = new DateTime($departure);
$nights        = $arrivalDate->diff($departureDate)->days;

// room price (DB)
try {
    $statement = $pdo->prepare(
        'SELECT price FROM rooms WHERE id = :id'
    );
    $statement->execute([':id' => $room_id]);
    $room = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Room lookup failed: ' . $e->getMessage());
    $_SESSION['error'] = 'Booking failed.';
    header('Location: ../index.php');
    exit;
}

if ($room === false) {
    $_SESSION['error'] = 'Invalid room selected.';
    header('Location: ../index.php');
    exit;
}

$pricePerNight = (int)$room['price'];

// feature price (DB)
$totalFeaturePrice = 0;

if (!empty($features)) {
    $placeholders = implode(',', array_fill(0, count($features), '?'));

    $statement = $pdo->prepare(
        "SELECT COALESCE(SUM(price), 0)
         FROM features
         WHERE id IN ($placeholders)"
    );

    $statement->execute($features);
    $totalFeaturePrice = (int)$statement->fetchColumn();
}

// total price
$totalPrice = ($pricePerNight * $nights) + $totalFeaturePrice;


// payment validation
if (!validateTransferCode($transferCode, $totalPrice)) {
    $_SESSION['error'] = 'Payment failed. Invalid transfer code.';
    header('Location: ../index.php');
    exit;
}

// insert booking
$sql = "
INSERT INTO bookings (
    guest_name,
    room_id,
    arrival,
    departure,
    total_price
) VALUES (
    :guest_name,
    :room_id,
    :arrival,
    :departure,
    :total_price
)";

try {
    $statement = $pdo->prepare($sql);
    $statement->execute([
        ':guest_name'  => $guestName,
        ':room_id'     => $room_id,
        ':arrival'     => $arrival,
        ':departure'   => $departure,
        ':total_price' => $totalPrice
    ]);
} catch (PDOException $e) {
    error_log('Booking insert failed: ' . $e->getMessage());
    $_SESSION['error'] = 'Booking failed.';
    header('Location: ../index.php');
    exit;
}

$bookingId = $pdo->lastInsertId();

// insert booking features
if (!empty($features)) {
    $statement = $pdo->prepare(
        "INSERT INTO booking_features (booking_id, feature_id)
         VALUES (:booking_id, :feature_id)"
    );

    foreach ($features as $featureId) {
        $statement->execute([
            ':booking_id' => $bookingId,
            ':feature_id' => (int)$featureId
        ]);
    }
}

// success
header('Location: ../index.php?booked=1');
exit;
