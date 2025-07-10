<?php
include("../../database/connection.php");
include("../../database/configue.php");

$configue= new Configue();

$db = new Database($configue->servername, $configue->database,$configue->username,$configue->password);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $phone    = trim($_POST['phone']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $bio      = trim($_POST['bio']);

    if ($name && $phone && $email && $password) {
        try {
            // Step 1: Insert into users table
            $userData = [
                'shop_id'    => 1,
                'name'       => $name,
                'email'      => $email,
                'phone'      => $phone,
                'facebook_id'=> null,
                'password'   => $password,
                'role'       => 'staff',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->insert('users', $userData);
            $userId = $db->lastInsertId();

            $staffData = [
                'user_id'   => $userId,
                'shop_id'   => 1,
                'bio'       => $bio,
                'is_active' => 1
            ];

            $db->insert('staff', $staffData);

            header("Location: ../../staff.php?success=1");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please fill all required fields.";
    }
} else {
    echo "Invalid request.";
}