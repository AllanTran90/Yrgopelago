<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/includes/db.php';

$rooms = $pdo
    ->query('SELECT id, name, price, description FROM rooms')
    ->fetchAll(PDO::FETCH_ASSOC);

$features = $pdo
    ->query('SELECT id, name, price FROM features')
    ->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yrgopelag Hotel</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="page">

<section class="hero">
  <div class="hero-content">
    <h1>The Cozy Maui Retreat</h1>
    <p>
      A relaxing ocean-side retreat on the Yrgopelag islands.
      Choose between budget, standard or luxury rooms – all just steps from the sea.
    </p>

    <a href="#booking" class="cta-button">
      Book your stay
    </a>
  </div>
</section>

<?php if (!empty($_SESSION['error'])): ?>
  <p class="error">
    <?= htmlspecialchars($_SESSION['error']) ?>
  </p>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_GET['booked'])): ?>
  <p class="success">
    ✅ Booking completed successfully!
  </p>
<?php endif; ?>

<h1>Welcome to The Cozy Maui Retreat ⭐⭐⭐</h1>

<form id="booking" method="post" action="booking.php">

    <label>
        Name:
        <input type="text" name="guest_name" required>
    </label>
    <br><br>

    <label>
        Email:
        <input type="email" name="email" required>
    </label>
    <br><br>
    
    
    <!-- dates from calendar -->
    <input type="hidden" name="room_id" id="roomInput">
    <input type="hidden" name="arrival" id="arrivalInput">
    <input type="hidden" name="departure" id="departureInput">

    <!-- CALENDAR -->
    <div class="layout">
      <div class="calendar-stack">

        <h2 class="calendar-title">Arrival</h2>
        <div class="calendar">
          <div class="month">
            <ul>
              <li>
                January<br>
                <span style="font-size:18px">2026</span>
              </li>
            </ul>
          </div>

          <ul class="days arrival-days">

          <!-- Empty days (Mon–Wed) -->
          <?php for ($i = 0; $i < 3; $i++): ?>
            <li class="empty"></li>
          <?php endfor; ?>

          <!-- Days 1–31 -->
          <?php for ($day = 1; $day <= 31; $day++): ?>
            <li><?= $day ?></li>
          <?php endfor; ?>
        </ul>

        </div>

        <h2 class="calendar-title">Departure</h2>
        <div class="calendar">
          <div class="month">
            <ul>
              <li>
                January<br>
                <span style="font-size:18px">2026</span>
              </li>
            </ul>
          </div>

          <ul class="days departure-days">

          <!-- Empty days (Mon–Wed) -->
          <?php for ($i = 0; $i < 3; $i++): ?>
            <li class="empty"></li>
          <?php endfor; ?>

          <!-- Days 1–31 -->
          <?php for ($day = 1; $day <= 31; $day++): ?>
            <li><?= $day ?></li>
          <?php endfor; ?>

          </ul>

        </div>

      </div>
      
      <div class="room-images">
          <img src="assets/images/budget-room.png" alt="Budget room">
          <img src="assets/images/standard-room.jpeg" alt="Standard room">
          <img src="assets/images/luxury-room.png" alt="Luxury room">
      </div>
    </div>

    <h2>Features</h2>
    <?php foreach($features as $feature):?>
      <label>
        <input type="checkbox" name="features[]" value="<?= $feature['id'] ?>">

        <?= htmlspecialchars($feature['name']) ?>
        (+<?= $feature['price'] ?>)
      </label><br>
      <?php endforeach; ?>
    

<h2>Rooms</h2>

<ul id="rooms">
<?php foreach ($rooms as $room): ?>
    <li data-room="<?= $room['id'] ?>">
        <label>
            <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
            <?= htmlspecialchars($room['name']) ?>
              <strong>Price: <?= $room['price'] ?> kr / night</strong>
            <span class="status"></span>
        </label>
    </li>
<?php endforeach; ?>
</ul>

    <br><br>
<p id="totalPrice">
  <strong>Total amount: 0 kr</strong>
</p>

    <p>
    To complete your booking, you must <br>generate a transfer code from the
    <a href="http://www.yrgopelag.se/centralbank" target="_blank" class="action-link">>
        Central Bank of Yrgopelag
    </a>.
    </p><br><br>


    <label>
        Transfer code:
        <input type="text" name="transfer_code" required>
    </label>
    <br><br>
    <button type="submit">Book room</button>

</form>

<!---------intro, rightside----->
<div class="hotel-intro">
    <h2>	The Cozy Maui Retreat </h2>
    <p>
       A beach resort next to the water with the focus of relaxing, comfort and treat.
       Chose between budget-, standard- or luxuryroom. All of these just a step away from the ocean.
    </p>
</div>

<script src="assets/js/calendar.js"></script>
</div>
</body>
</html>
