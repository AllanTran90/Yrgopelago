<?php

declare(strict_types=1);

require __DIR__ . '/includes/db.php';
require __DIR__ . '/assets/availability/availability.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yrgopelag Hotel</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<?php if (isset($_GET['booked'])): ?>
  <p class="success">
    ✅ Booking completed successfully!
  </p>
<?php endif; ?>

<h1>Welcome to The Cozy Maui Retreat ⭐⭐⭐</h1>

<form method="post" action="booking.php">

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

    <input type="hidden" name="room_id" id="roomInput">

    <!-- dates from calendar -->
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
            <?php for ($i = 1; $i <= 31; $i++): ?>
              <li><?= $i ?></li>
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
            <?php for ($i = 1; $i <= 31; $i++): ?>
              <li><?= $i ?></li>
            <?php endfor; ?>
          </ul>
        </div>

      </div>
      
      <div class="room-images">
          <img src="/assets/images/budget-room.png" alt="Budget room">
          <img src="/assets/images/standard-room.jpeg" alt="Standard room">
          <img src="/assets/images/luxury-room.png" alt="Luxury room">
      </div>
    </div>

    <h2>Features</h2>
     <label> 
        <input type="checkbox" name="features[]" value="scuba"> Scuba diving (+5) 
    </label><br> 
    <label> 
        <input type="checkbox" name="features[]" value="pingpong"> Ping pong (+5) 
    </label><br> 
    <label> 
        <input type="checkbox" name="features[]" value="bicykle"> Bicycle (+5) 
    </label><br> 
    <label> 
        <input type="checkbox" name="features[]" value="casino"> Casino (+17) 
    </label> <br><br>

    <h2>Room availability</h2>

<ul id="availability">
  <li data-room="1">
    <label>
      <input type="checkbox" name="room_id" value="1">
      Budget room: <span class="status booked">Booked</span>
    </label>
  </li>

  <li data-room="2">
    <label>
      <input type="checkbox" name="room_id" value="2">
      Standard room: <span class="status available">Available</span>
    </label>
  </li>

  <li data-room="3">
    <label>
      <input type="checkbox" name="room_id" value="3">
      Luxury room: <span class="status available">Available</span>
    </label>
  </li>
</ul>

    <br><br>

    <p>
    To complete your booking, you must <br>generate a transfer code from the
    <a href="http://www.yrgopelag.se/centralbank" target="_blank">
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

<!--------intro, right side------->
<div class="hotel-intro">
    <h2>	The Cozy Maui Retreat </h2>
     <p>
       A beach resort next to the water eith focus of relaxing, comfort and upplevelser.
        Välj mellan budget-, standard- och lyxrum – alla med närhet till havet.
    </p>
</div>

<script src="/assets/js/calendar.js"></script>
</body>
</html>
