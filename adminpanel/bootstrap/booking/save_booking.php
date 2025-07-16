<?php
require_once __DIR__ . '/../../database/configue.php';
require_once __DIR__ . '/../../database/connection.php';

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => false, "message" => "No input received."]);
    exit;
}

try {
    $now = date("Y-m-d H:i:s");
    $booking = [
        "shop_id" => 1,
        "staff_id" => $data['staff']['id'],
        "appointment_date" => $data['appointment_date'],
        "appointment_time" => $data['appointment_time'],
        "total_price" => $data['service']['price'],
        "total_duration" => $data['service']['duration'],
        "status" => "pending",
        "payment_status" => "unpaid",
        "notes" => $data['service']['name'] . " with " . $data['staff']['name'] . " (" . $data['service']['duration'] . " mins)",
        "created_at" => $now,
        "service_id" => $data['service']['id']
    ];

    $success = $db->insert("bookings", $booking);
    if ($success) {
        echo json_encode(["status" => true, "message" => "Booking saved successfully."]);
    } else {
        echo json_encode(["status" => false, "message" => "Failed to save booking."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => "Error: " . $e->getMessage()]);
}
