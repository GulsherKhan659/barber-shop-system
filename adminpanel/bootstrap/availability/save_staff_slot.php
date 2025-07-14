<?php
session_start();

require_once __DIR__ . '/../../database/connection.php';

// Validate input
if (
    empty($_POST['staff_id']) ||
    empty($_POST['date']) ||
    empty($_POST['start_time']) ||
    empty($_POST['end_time'])
) {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'All fields are required.'
    ];
    header("Location: ../../avalibity.php");
    exit;
}

$staff_id   = intval($_POST['staff_id']);
$date       = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time   = $_POST['end_time'];

if ($start_time >= $end_time) {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Start time must be earlier than end time.'
    ];
    header("Location: ../../avalibity.php");
    exit;
}

try {
    $db = new Database('localhost', 'barberbookingsystem', 'root', '');

    // Check for overlapping slots for this staff and date
    $overlap_sql = "
        SELECT * FROM staff_available_slots
        WHERE staff_id = :staff_id
          AND date = :date
          AND (start_time < :end_time AND end_time > :start_time)
        LIMIT 1
    ";
    $params = [
        'staff_id'   => $staff_id,
        'date'       => $date,
        'start_time' => $start_time,
        'end_time'   => $end_time
    ];
    $overlap = $db->query($overlap_sql, $params);

    if ($overlap && count($overlap) > 0) {
        $_SESSION['notification'] = [
            'type' => 'warning',
            'message' => 'This time overlaps with another slot already added for this staff member.'
        ];
        header("Location: ../../avalibity.php");
        exit;
    }

    // Insert the slot
    $db->insert(
        'staff_available_slots',
        [
            'staff_id'   => $staff_id,
            'date'       => $date,
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'is_available' => 1
        ]
    );

    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Slot added successfully.'
    ];
    header("Location: ../../avalibity.php");
    exit;

} catch (Exception $e) {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Error: ' . $e->getMessage()
    ];
    header("Location: ../../avalibity.php");
    exit;
}



?>
