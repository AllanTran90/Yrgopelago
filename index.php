<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/assets/availability/availability.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //get input
    $guestName = trim($_POST['guest_name'] ?? '' );
    $email = trim($_POST['email'] ?? '');
    $roomId = (int)($_POST['room_id'] ?? 0);
    $arrival = $_POST['arrival'] ?? '';
    $departure = $_POST['departure'] ?? '';
    $features = $_POST['features'] ?? [];

    if ($guestName === ''){
        $errors[]= 'Name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    }

    if ($roomId === 0) {
        $errors[] = 'Room must be selected.';
    }

    if ($arrival === '' || $departure === '') {
        $errors[] = 'Arrival and departure dates are required.';
    }

    if ($arrival !== '' && $departure !== '' && $arrival >= $departure) {
        $errors[] = 'Departure must be after arrival.';
    }

    //availabilitycheck
    if(empty($errors)){
        $availability = getAvailabilityForDate($pdo, $arrival);

        if(!isset($availability[$roomId]) || $availability[$roomId] === false){
            $errors[]= 'Selected room is not available for the chosen date.';
        }
    }

    //redirect to booking if everything is ok
    if (empty($errors)){
        $_SESSION['booking'] = [
            'guest_name' => $guestName,
            'email' => $email,
            'room_id' => $roomId,
            'arrival' => $arrival,
            'departure' => $departure,
            'features' => $features,
        ];

        header('Location: booking.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yrgopelag Hotel</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<h1>Welcome to Yrgopelag Hotel ⭐⭐⭐</h1>

<?php if(!empty($errors)):?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li>
                <?= htmlspecialchars($error) ?>
            </li>
        <?php endforeach;?>
    </ul>
    <?php endif; ?>

<form method="post">

    <label>
        Name:
        <input type="text" name="guest_name" required>
    </label>
    <br><br>

    <label>
        Email:
        <input type="email" name= "email" required>
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
        <li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li>
        <li>8</li><li>9</li><li>10</li><li>11</li><li>12</li><li>13</li><li>14</li>
        <li>15</li><li>16</li><li>17</li><li>18</li><li>19</li><li>20</li><li>21</li>
        <li>22</li><li>23</li><li>24</li><li>25</li><li>26</li><li>27</li><li>28</li>
        <li>29</li><li>30</li><li>31</li>
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
        <li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li>
        <li>8</li><li>9</li><li>10</li><li>11</li><li>12</li><li>13</li><li>14</li>
        <li>15</li><li>16</li><li>17</li><li>18</li><li>19</li><li>20</li><li>21</li>
        <li>22</li><li>23</li><li>24</li><li>25</li><li>26</li><li>27</li><li>28</li>
        <li>29</li><li>30</li><li>31</li>
      </ul>
    </div>

  </div>
</div>


    <h3>Features</h3>
    <label><input type="checkbox" name="features[]" value="scuba"> Scuba diving (+5)
    </label><br>
    <label><input type="checkbox" name="features[]" value="pingpong"> Ping pong (+5)
    </label><br>
    <label><input type="checkbox" name="features[]" value="bicykle"> Bicycle (+5)
    </label><br>
    <label><input type="checkbox" name="features[]" value="casino"> Casino (+17)
    </label>

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
