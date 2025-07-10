<?php
session_start();
require_once __DIR__ . '/../../model/availability.php';
require_once __DIR__ . '/../../database/configue.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id   = $_POST['staff_id'] ?? null;
    $weekday    = $_POST['weekday'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time   = $_POST['end_time'] ?? null;

    if ($staff_id && $weekday && $start_time && $end_time) {
        try {
            $config = new Configue();
            $availability = new Availibity(
                $config->servername,
                $config->database,
                $config->username,
                $config->password
            );
            //$availability = new Availibity('localhost', 'barberbookingsystem', 'root', '');

            $result = $availability->create($staff_id, $weekday, $start_time, $end_time);

            $_SESSION['notification'] = [
                'type' => $result ? 'success' : 'danger',
                'message' => $result
                    ? 'Availability saved successfully!'
                    : 'Failed to save availability.'
            ];

            header("Location: ../../avalibity.php");
            exit;
        } catch (Exception $e) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Error: ' . $e->getMessage()
            ];
            header("Location: ../../avalibity.php");
            exit;
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'warning',
            'message' => 'All fields are required.'
        ];
        header("Location: ../../avalibity.php");
        exit;
    }
} else {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Invalid request method.'
    ];
    header("Location: ../../avalibity.php");
    exit;
}
