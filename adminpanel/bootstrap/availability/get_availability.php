<?php
require_once __DIR__ . '/../../database/configue.php';
require_once __DIR__ . '/../../database/connection.php';

header('Content-Type: application/json');

if (!isset($_GET['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing staff_id']);
    exit;
}

$staffId = $_GET['staff_id'];

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

// 👇 Fetch using your Database class's `select` method
$availability = $db->select('staff_available_slots', '*', ['staff_id' => $staffId]);

echo json_encode([
    'success' => true,
    'data' => $availability
]);

?>