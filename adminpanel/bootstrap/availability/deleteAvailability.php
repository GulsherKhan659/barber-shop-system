<?php
include '../../model/availability.php';
include '../../database/configue.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Invalid availability ID!'
    ];
    header('Location: ../../availability.php');
    exit();
}

$availabilityId = $_GET['id'];

// Setup DB connection via your config class
$config = new Configue();
$availability = new Availibity(
    $config->servername,
    $config->database,
    $config->username,
    $config->password
);

// Attempt deletion
$deleted = $availability->deleteAvalibity(['id' => $availabilityId]);

if ($deleted) {
    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Availability deleted successfully!'
    ];
} else {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Failed to delete availability.'
    ];
}

// Redirect back to main page
header("Location: ../../avalibity.php");
exit();
