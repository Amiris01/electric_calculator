<?php
function calculateElectricityRates($voltage, $current, $currentRate)
{
    // Calculate power in watts
    $power = $voltage * $current;

    // Calculate energy in watt-hours per hour
    $kwh = $power / 1000;

    // Calculate electricity rates per hour
    $rates = array();
    for ($hour = 1; $hour <= 24; $hour++) {
        $rate = $kwh * ($currentRate / 100) * $hour;
        $rates[$hour] = $rate;
    }

    return $rates;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user input from the form
    $voltage = $_POST["voltage"];
    $current = $_POST["current"];
    $currentRate = $_POST["rate"];

    // Calculate the electricity rates
    $rates = calculateElectricityRates($voltage, $current, $currentRate);
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

<body>
  <h1>Electricity Rate Calculator</h1>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <div class="form-group">
      <label for="voltage">Voltage (V):</label>
      <input type="number" name="voltage" step="any" value="<?= $_POST['voltage'] ?? '' ?>">
    </div>
    <div class="form-group">
      <label for="current">Current (A):</label>
      <input type="number" name="current" step="any" value="<?= $_POST['current'] ?? '' ?>">
    </div>
    <div class="form-group">
      <label for="rate">Current Rate (RM):</label>
      <input type="number" name="rate" step="any" value="<?= $_POST['rate'] ?? '' ?>">
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>

  <?php
  // Display the electricity rates if they are calculated
  if (isset($rates)) {
    echo "<h2>Electricity Rates:</h2>";
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Index</th>';
    echo '<th>Hour</th>';
    echo '<th>kWh</th>';
    echo '<th>Rate</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($rates as $index => $rate) {
      echo '<tr>';
      echo '<td>' . ($index + 1) . '</td>';
      echo '<td>' . $index . '</td>';
      echo '<td>' . $kwh . '</td>';
      echo '<td>RM' . $rate . '</td>';
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
  }
  ?>
</body>

</html>