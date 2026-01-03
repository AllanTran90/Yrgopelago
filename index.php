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

<h1>Welcome to Yrgopelag Hotel ⭐⭐⭐</h1>

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

    <label>
        Room:
        <select name="room_id" required>
            <option value="">Select room</option>
            <option value="1">Budget</option>
            <option value="2">Standard</option>
            <option value="3">Luxury</option>
        </select>
    </label>

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
    </div>

    <h3>Features</h3>
    <label><input type="checkbox" name="features[]" value="scuba"> Scuba diving (+5)</label><br>
    <label><input type="checkbox" name="features[]" value="pingpong"> Ping pong (+5)</label><br>
    <label><input type="checkbox" name="features[]" value="bicykle"> Bicycle (+5)</label><br>
    <label><input type="checkbox" name="features[]" value="casino"> Casino (+17)</label>

    <br><br>

    <h2>Room availability</h2>
    <ul id="availability">
      <li data-room="1">Budget room: <span></span></li>
      <li data-room="2">Standard room: <span></span></li>
      <li data-room="3">Luxury room: <span></span></li>
    </ul>

    <br><br>
    <button type="submit">Book room</button>

</form>

<script src="/assets/js/calendar.js"></script>
</body>
</html>
