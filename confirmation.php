<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['confirmation'])) {
    header('Location: index.php');
    exit;
}

$data = $_SESSION['confirmation'];
unset($_SESSION['confirmation']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking confirmed</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<h1>✅ Booking confirmed</h1>

<p>
    Thank you <strong><?= htmlspecialchars($data['guest_name']) ?></strong>
    for booking with Yrgopelag Hotel!
</p>

<h2>📅 Stay details</h2>
<ul>
    <li><strong>Room:</strong> <?= htmlspecialchars($data['room_name']) ?></li>
    <li><strong>Arrival:</strong> <?= htmlspecialchars($data['arrival']) ?></li>
    <li><strong>Departure:</strong> <?= htmlspecialchars($data['departure']) ?></li>
    <li><strong>Nights:</strong> <?= (int)$data['nights'] ?></li>
</ul>

<h2>💰 Cost</h2>
<ul>
    <li>Room cost: <?= (int)$data['room_cost'] ?> credits</li>
    <li>Features cost: <?= (int)$data['feature_cost'] ?> credits</li>
    <li><strong>Total cost: <?= (int)$data['total_cost'] ?> credits</strong></li>
</ul>

<h2>🏦 Payment (Central Bank of Yrgopelag)</h2>
<p>
    Payment was successfully processed by
    <strong>Turisten </strong> 🧙‍♂️
</p>

<p>
    <strong>Transfer code:</strong><br>
    <code><?= htmlspecialchars($data['transfer_code']) ?></code>
</p>

<a href="index.php">⬅ Book another stay</a>

</body>
</html>
