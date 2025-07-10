<?php include("./database/configue.php"); ?>
<?php include("./database/connection.php"); ?>
<?php include("./partial/header.php"); ?>

<?php
// Connect
$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

// Fetch staff (role='staff')
$staffUsers = $db->select('users', '*', ['role' => 'staff']);
$staff = [];
foreach ($staffUsers as $user) {
    $staff[] = $user['name'];
}

// Handle view mode and navigation
$view = isset($_GET['view']) && $_GET['view'] == 'month' ? 'month' : 'week';

// When clicking WEEK button (no date), always use today. When navigating, use GET['date']
if ($view == 'week') {
    if (isset($_GET['date'])) {
        $start = new DateTime($_GET['date']);
    } else {
        $start = new DateTime(); // today
    }
} else { // month view
    if (isset($_GET['date'])) {
        $start = new DateTime($_GET['date']);
    } else {
        $start = new DateTime(); // today
    }
    $start->modify('first day of this month');
}

// Build days array
$days = [];
if ($view == 'week') {
    $ref = clone $start;
    for ($i = 0; $i < 7; $i++) {
        $days[] = (clone $ref)->modify("+$i day");
    }
} else {
    $ref = clone $start;
    $daysInMonth = (int)$ref->format('t');
    for ($i = 0; $i < $daysInMonth; $i++) {
        $days[] = (clone $ref)->modify("+$i day");
    }
}

// Example events (replace with DB fetch as needed)
$events = [
  '2024-07-08' => [
    'Ali' => ['Sales Meeting<br><small>Conf Room 1</small>', 'event-blue'],
    'Sara' => ['Weekly Call<br><small>Online</small>', 'event-yellow'],
  ],
  '2024-07-09' => [
    'Ayesha' => ['Project Review<br><small>Lunch</small>', 'event-blue'],
  ],
  '2024-07-10' => [
    'Fahad' => ['Northwind Project<br><small>Conf Room 2</small>', 'event-red'],
  ],
];

// Navigation URLs
if ($view == 'week') {
    $prev = (clone $start)->modify('-7 days')->format('Y-m-d');
    $next = (clone $start)->modify('+7 days')->format('Y-m-d');
} else {
    $prev = (clone $start)->modify('-1 month')->format('Y-m-d');
    $next = (clone $start)->modify('+1 month')->format('Y-m-d');
}
$current = $start->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Booking Calendar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .calendar-table { width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0; background: #fff; box-shadow: 0 2px 12px #0001;}
    .calendar-table th, .calendar-table td { border: 1px solid #e2e4e6; text-align: left; vertical-align: top; height: 60px; padding: 2px 6px; background: #fff; position: relative;}
    .calendar-table th { background: #f6f7f9; font-weight: 600; text-align: center; font-size: 1rem;}
    .calendar-date { width: 110px; background: #f6f7f9; }
    .calendar-event { border-radius: 6px; color: #fff; padding: 4px 8px; font-size: 0.95rem; margin-top: 2px; min-height:32px;}
    .event-blue { background: #1374d7; }
    .event-yellow { background: #fbc02d; color: #222; }
    .event-red { background: #c62828; }
    .event-green { background: #388e3c; }
    .event-border { border: 2px solid #fff; }
    .active { font-weight: bold !important; }
  </style>
</head>
<body class="bg-light">
<div class="container-fluid my-4">
  <div class="mb-3 d-flex align-items-center justify-content-between">
    <h2 class="mb-0">Staff Booking Calendar</h2>
    <div>
      <a href="?view=month&date=<?= $current ?>" class="btn btn-outline-primary btn-sm<?= $view=='month'?' active':'' ?>">MONTH</a>
      <a href="?view=week" class="btn btn-outline-primary btn-sm<?= $view=='week'?' active':'' ?>">WEEK</a>
      <a href="?view=<?= $view ?>&date=<?= $prev ?>" class="btn btn-outline-secondary btn-sm">&lt;</a>
      <a href="?view=<?= $view ?>&date=<?= $next ?>" class="btn btn-outline-secondary btn-sm">&gt;</a>
      <a href="?view=<?= $view ?>" class="btn btn-outline-success btn-sm">Today</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="calendar-table">
      <thead>
        <tr>
          <th>Date</th>
          <?php foreach ($staff as $name): ?>
            <th><?= htmlspecialchars($name) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($days as $date): ?>
          <tr>
            <td class="calendar-date fw-bold text-center">
              <?= $date->format('d-M') ?><br>
              <small><?= $date->format('D') ?></small>
            </td>
            <?php foreach ($staff as $name): ?>
              <td>
                <?php
                  $dateKey = $date->format('Y-m-d');
                  if (isset($events[$dateKey][$name])) {
                      list($event, $class) = $events[$dateKey][$name];
                      echo "<div class='calendar-event $class'>$event</div>";
                  }
                ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

<?php include("./partial/footer.php"); ?>
