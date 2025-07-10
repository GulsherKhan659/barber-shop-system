<?php
include("../../database/connection.php");
include("../../database/configue.php");

$config = new Configue(); 
$db = new Database($config->servername, $config->database, $config->username, $config->password);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $deleted = $db->delete("users", ["id" => $id]); 

    if ($deleted) {
        header("Location: ../../staff.php?deleted=1"); 
        exit;
    } else {
        echo "Failed to delete staff member.";
    }
} else {
    echo "Invalid request.";
}
