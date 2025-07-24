<?php
include("./database/configue.php");
include("./database/connection.php");
include("./partial/header.php");

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

// Get user role and user ID from session
$userRole = $_SESSION['user_role'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

// Initialize staff array
$staff = [];
$staffMap = [];

// Role-based staff list fetch
if ($userRole === 'shop_admin' || $userRole === 'admin') {
    // Fetch all staff
    $query = "
        SELECT s.id AS staff_id, u.name AS staff_name
        FROM staff s
        JOIN users u ON s.user_id = u.id
        WHERE u.role = 'staff'
    ";
    $staffResult = $db->selectJoin($query);
} elseif ($userRole === 'staff') {
    // Fetch only this staff's own data
    $query = "
        SELECT s.id AS staff_id, u.name AS staff_name
        FROM staff s
        JOIN users u ON s.user_id = u.id
        WHERE u.id = :uid
    ";
    $staffResult = $db->selectJoin($query, ['uid' => $userId]);
} else {
    $staffResult = []; // No access
}

// Map staff names
foreach ($staffResult as $row) {
    $staff[] = $row['staff_name'];
    $staffMap[$row['staff_id']] = $row['staff_name'];
}

// Calendar view setup
$view = isset($_GET['view']) && $_GET['view'] === 'month' ? 'month' : 'week';
$start = new DateTime(isset($_GET['date']) ? $_GET['date'] : 'today');
if ($view === 'month') {
    $start->modify('first day of this month');
}

$days = [];
$ref = clone $start;
$range = $view === 'week' ? 7 : (int)$ref->format('t');
for ($i = 0; $i < $range; $i++) {
    $days[] = (clone $ref)->modify("+$i day");
}

// Fetch bookings based on role
if ($userRole === 'shop_admin' || $userRole === 'admin') {
    $bookingQuery = "
        SELECT b.*, u.name AS staff_name
        FROM bookings b
        JOIN staff s ON b.staff_id = s.id
        JOIN users u ON s.user_id = u.id
    ";
    $bookings = $db->selectJoin($bookingQuery);
} elseif ($userRole === 'staff') {
    $bookingQuery = "
        SELECT b.*, u.name AS staff_name
        FROM bookings b
        JOIN staff s ON b.staff_id = s.id
        JOIN users u ON s.user_id = u.id
        WHERE u.id = :uid
    ";
    $bookings = $db->selectJoin($bookingQuery, ['uid' => $userId]);
} else {
    $bookings = [];
}

// Prepare calendar events
$events = [];
foreach ($bookings as $booking) {
    $date = $booking['appointment_date'];
    $staffName = $booking['staff_name'];
    $time = $booking['appointment_time'];
    $duration = $booking['total_duration'];
    $notes = htmlspecialchars($booking['notes']);
    $eventText = "$time<br><small>{$duration} mins - $notes</small>";
    $events[$date][$staffName][] = [$eventText, 'event-green'];
}

// Navigation links
$current = $start->format('Y-m-d');
$prev = (clone $start)->modify($view === 'week' ? '-7 days' : '-1 month')->format('Y-m-d');
$next = (clone $start)->modify($view === 'week' ? '+7 days' : '+1 month')->format('Y-m-d');
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Staff Booking Calendar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .calendar-table { width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0; background: #fff; box-shadow: 0 2px 12px #0001; }
    .calendar-table th, .calendar-table td { border: 1px solid #e2e4e6; text-align: left; vertical-align: top; height: 60px; padding: 2px 6px; background: #fff; position: relative; }
    .calendar-table th { background: #f6f7f9; font-weight: 600; text-align: center; font-size: 1rem; }
    .calendar-date { width: 110px; background: #f6f7f9; }
    .calendar-event { border-radius: 6px; color: #fff; padding: 4px 8px; font-size: 0.95rem; margin-top: 4px; min-height: 32px; }
    .event-green { background: #388e3c; }
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
                      foreach ($events[$dateKey][$name] as [$event, $class]) {
                          echo "<div class='calendar-event $class'>$event</div>";
                      }
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
