<?php
require_once __DIR__ . '/../../database/configue.php';
require_once __DIR__ . '/../../database/connection.php';

header('Content-Type: application/json');

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

$staff_id = $_GET['staff_id'] ?? null;
$date = $_GET['date'] ?? null;
$duration = $_GET['duration'] ?? 30;

if (!$staff_id || !$date) {
    echo json_encode(['status' => false, 'message' => 'Missing required parameters.']);
    exit;
}

// Step 1: Get staff available working hours
$available = $db->select('staff_available_slots', '*', [
    'staff_id' => $staff_id,
    'date' => $date,
    'is_available' => 1
]);

if (!$available || count($available) === 0) {
    echo json_encode(['status' => false, 'message' => 'No availability for this date.']);
    exit;
}

$startTime = $available[0]['start_time']; // e.g., 09:00:00
$endTime = $available[0]['end_time'];     // e.g., 12:00:00

// Step 2: Get booked slots for the day
$bookings = $db->select('bookings', ['appointment_time', 'total_duration'], [
    'staff_id' => $staff_id,
    'appointment_date' => $date
]);

$bookedSlots = [];
foreach ($bookings as $b) {
    $start = strtotime($b['appointment_time']);
    $end = $start + ($b['total_duration'] * 60);
    $bookedSlots[] = [$start, $end];
}

// Step 3: Generate available time slots
function generateSlots($start, $end, $duration, $bookedSlots) {
    $slots = [];
    $current = strtotime($start);
    $endTime = strtotime($end);

    while (($current + $duration * 60) <= $endTime) {
        $slotStart = $current;
        $slotEnd = $current + $duration * 60;

        $isBooked = false;
        foreach ($bookedSlots as $booked) {
            if ($slotStart < $booked[1] && $slotEnd > $booked[0]) {
                $isBooked = true;
                break;
            }
        }

        $slots[] = [
            'start' => date('H:i', $slotStart),
            'end' => date('H:i', $slotEnd),
            'disabled' => $isBooked
        ];

        $current += 5 * 60; // increment 5 minutes
    }

    return $slots;
}

$slots = generateSlots($startTime, $endTime, $duration, $bookedSlots);

echo json_encode([
    'status' => true,
    'slots' => $slots
]);
