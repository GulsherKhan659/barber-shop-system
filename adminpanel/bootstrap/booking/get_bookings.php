<?php
require_once __DIR__ . '/../../database/configue.php';
require_once __DIR__ . '/../../database/connection.php';

header('Content-Type: application/json');

if (!isset($_GET['staff_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing staff_id or service_id'
    ]);
    exit;
}

$staffId = $_GET['staff_id'];

$config = new Configue();
$db = new Database(
    $config->servername,
    $config->database,
    $config->username,
    $config->password
);

$bookings = $db->select('bookings', '*', [
    'staff_id' => $staffId
]);

echo json_encode([
    'success' => true,
    'data' => $bookings
]);
?>
