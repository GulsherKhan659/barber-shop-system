<?php
include("../../database/configue.php");
include("../../database/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['is_active'])) {
    $config = new Configue();
    $db = new Database($config->servername, $config->database, $config->username, $config->password);

    $id = $_POST['id'];
    $isActive = $_POST['is_active'] ? 1 : 0;

    $result = $db->update('services', ['is_active' => $isActive], ['id' => $id]);

    echo $result ? "Status updated" : "Failed to update status";
}
?>
