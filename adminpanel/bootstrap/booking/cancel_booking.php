<?php
require_once __DIR__ . '/../../database/configue.php';
require_once __DIR__ . '/../../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];

    $config = new Configue();
    $db = new Database($config->servername, $config->database, $config->username, $config->password);

    $db->update('bookings', ['status' => 'Cancelled'], ['id' => $bookingId]);

    header("Location: ../../clientpanel/service_tab.php"); 
    exit();
}
?>
