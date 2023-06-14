<?php

function calculateEnergyPerHour($voltage, $current)
{
  $power = $voltage * $current;
  $energy = $power / 1000;
  $kwPerHour = array();
  for ($hour = 1; $hour <= 24; $hour++) {
    $kwPerHour[$hour] = $energy * $hour;
  }
  return $kwPerHour;
}

function calculateElectricityRatesPerHour($currentRate, $kwhPerHour)
{
  $rates = array();
  for ($hour = 1; $hour <= 24; $hour++) {
    $rate = $kwhPerHour[$hour] * ($currentRate / 100);
    $rates[$hour] = $rate;
  }
  return $rates;
}

function calculateEnergyPerDay($voltage, $current)
{
  $power = $voltage * $current;
  $energyPerHour = $power / 1000;
  $energyPerDay = array();
  for ($day = 1; $day <= 31; $day++) {
    $energyPerDay[$day] = $energyPerHour * 24 * $day;
  }
  return $energyPerDay;
}

function CalculateElectricityRatesPerDay($voltage, $current, $currentRate)
{
  $energyPerDay = calculateEnergyPerHour($voltage, $current);

  $totalPerDay = array();
  for ($day = 1; $day <= 31; $day++) {
    $totalPerDay[$day] = $energyPerDay[24] * ($currentRate / 100) * $day;
  }

  return $totalPerDay;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $voltage = $_POST["voltage"];
  $current = $_POST["current"];
  $currentRate = $_POST["rate"];

  $kwhPerHour = calculateEnergyPerHour($voltage, $current);
  $rates = calculateElectricityRatesPerHour($currentRate, $kwhPerHour);
  $kwhPerDay = calculateEnergyPerDay($voltage, $current);
  $ratesPerDay = CalculateElectricityRatesPerDay($voltage, $current, $currentRate);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Electricity Rate Calculator</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>

<body class="d-flex justify-content-center align-items-center">
  <div class="container">
    <h1 class="text-center">Electricity Rate Calculator</h1>
    <p class="text-center small">by Amir Iskandar</p>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
      <div class="form-group">
        <label for="voltage">Voltage (V):</label>
        <input type="number" name="voltage" step="any" class="form-control" value="<?= $_POST['voltage'] ?? '' ?>">
      </div>
      <div class="form-group">
        <label for="current">Current (A):</label>
        <input type="number" name="current" step="any" class="form-control" value="<?= $_POST['current'] ?? '' ?>">
      </div>
      <div class="form-group">
        <label for="rate">Current Rate (RM):</label>
        <select name="rate" class="form-control">
          <option value="" <?= !isset($_POST['rate']) ? 'selected' : '' ?> disabled></option>
          <option value="21.8" <?= isset($_POST['rate']) && $_POST['rate'] == '21.8' ? 'selected' : '' ?>>21.80</option>
          <option value="33.4" <?= isset($_POST['rate']) && $_POST['rate'] == '33.4' ? 'selected' : '' ?>>33.40</option>
          <option value="51.6" <?= isset($_POST['rate']) && $_POST['rate'] == '51.6' ? 'selected' : '' ?>>51.60</option>
          <option value="54.6" <?= isset($_POST['rate']) && $_POST['rate'] == '54.6' ? 'selected' : '' ?>>54.60</option>
          <option value="57.1" <?= isset($_POST['rate']) && $_POST['rate'] == '57.1' ? 'selected' : '' ?>>57.10</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
    <br>
    <h2>Power: <?php $power = $voltage * $current / 1000;
                echo $power; ?>kW</h2>
    <h2>Rate: RM<?php $rm = $_POST['rate'] / 100;
                echo $rm; ?></h2>

    <?php
    if (isset($rates)) {
      echo "<h2 class='mt-5'>Electricity Rates Per Hour:</h2>";
      echo '<table class="table">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Index</th>';
      echo '<th>Hour</th>';
      echo '<th>Energy (kWh)</th>';
      echo '<th>Rate (RM)</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
      foreach ($rates as $index => $rate) {
        echo '<tr>';
        echo '<td>' . $index . '</td>';
        echo '<td>' . $index . '</td>';
        echo '<td>' . $kwhPerHour[$index] . '</td>';
        echo '<td>RM' . number_format($rate, 2) . '</td>';
        echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
    }
    if (isset($ratesPerDay)) {
      echo "<h2 class='mt-5'>Electricity Rates Per Day:</h2>";
      echo '<table class="table">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Index</th>';
      echo '<th>Day</th>';
      echo '<th>Energy (kWd)</th>';
      echo '<th>Rate (RM)</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
      foreach ($ratesPerDay as $index => $rate) {
        echo '<tr>';
        echo '<td>' . $index . '</td>';
        echo '<td>' . $index . '</td>';
        echo '<td>' . $kwhPerDay[$index] . '</td>';
        echo '<td>RM' . number_format($rate, 2) . '</td>';
        echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
    }
    ?>
  </div>
</body>

</html>