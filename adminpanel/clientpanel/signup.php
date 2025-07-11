<?php
require_once __DIR__ . '/../database/configue.php'; 
require_once __DIR__ . '/../database/connection.php'; 

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['number'] ?? '');
    $password = $_POST['password'] ?? '';

    $role = 'customer';
    $shop_id = 1;

    if (!$name || !$email || !$phone || !$password) {
        $alert = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = '<div class="alert alert-danger">Invalid email format.</div>';
    } else {
        try {
            $config = new Configue();
            $db = new Database($config->servername, $config->database, $config->username, $config->password);

            $existing = $db->select("users", "*", ["email" => $email]);
            if (!empty($existing)) {
                $alert = '<div class="alert alert-warning">Email already exists. Try logging in.</div>';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $inserted = $db->insert("users", [
                    "shop_id" => $shop_id,
                    "name" => $name,
                    "email" => $email,
                    "phone" => $phone,
                    "password" => $hashedPassword,
                    "role" => $role
                ]);

                if ($inserted) {
                    $alert = '<div class="alert alert-success">Signup successful! <a href="login.php">Login here</a>.</div>';
                } else {
                    $alert = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                }
            }
        } catch (Exception $e) {
            $alert = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dark Sign Up Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .signup-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background-color: #1e1e1e;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    .form-control {
      background-color: #2c2c2c;
      border: none;
      color: #fff;
    }
    .form-control:focus {
      background-color: #2c2c2c;
      color: #fff;
      box-shadow: none;
      border: 1px solid #555;
    }
    .btn-signup {
      background-color: #0d6efd;
      color: #fff;
    }
    a {
      color: #0d6efd;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="signup-container">
    <div class="w-100 d-flex justify-content-center align-items-center mb-4">
      <img width="75%" src="../img/logo.png" alt="Logo" />
    </div>

    <?= $alert ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="number" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="number" name="number" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-signup">Sign Up</button>
      </div>

      <div class="text-center">
        Already have an account? <a href="login.php">Login here</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
