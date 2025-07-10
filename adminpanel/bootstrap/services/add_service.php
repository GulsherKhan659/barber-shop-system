<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../../database/configue.php");
include("../../database/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $config = new Configue();
        $db = new Database($config->servername, $config->database, $config->username, $config->password);

        $data = [
            'shop_id' => 1,
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'duration_minutes' => $_POST['duration_minutes'],
            'description' => $_POST['description'],
            'is_active' => 1
        ];

        $inserted = $db->insert('services', $data);

        if ($inserted) {
            echo "Service added successfully!";
        } else {
            echo "Database insert failed!";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
