<?php
include("../../database/configue.php");
include("../../database/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $config = new Configue();
    $db = new Database($config->servername, $config->database, $config->username, $config->password);

    $id = intval($_POST['id']); // Ensure it's an integer
    $result = $db->delete('services', ['id' => $id]);

    if ($result) {
        echo "Service deleted successfully.";
    } else {
        echo "Failed to delete service.";
    }
} else {
    echo "Invalid request.";
}
?>
