<?php
require_once __DIR__ . '/../database/configue.php'; 
require_once __DIR__ . '/../database/connection.php'; 

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $alert = '<div class="alert alert-danger">Email and password are required.</div>';
    } else {
        try {
            $config = new Configue();
            $db = new Database($config->servername, $config->database, $config->username, $config->password);

            $user = $db->select("users", "*", ["email" => $email]);

            if (empty($user)) {
                $alert = '<div class="alert alert-danger">No user found with this email.</div>';
            } else {
                $user = $user[0]; 

                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'shop_id' => $user['shop_id']
                    ];
                    header("Location: service_tab.php"); 
                    exit;
                } else {
                    $alert = '<div class="alert alert-danger">Incorrect password.</div>';
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
  <title>Dark Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
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
    .btn-facebook {
      background-color: #3b5998;
      color: #fff;
    }
    .btn-google {
      background-color: #db4437;
      color: #fff;
    }
    .btn-guest {
      background-color: #6c757d;
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

  <div class="login-container">
    <div class="w-100 h-100 d-flex justify-content-center align-items-center mb-4">
      <img width="75%" src="../img/logo.png" />
    </div>

    <?= $alert ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
      </div>

      <div class="d-flex justify-content-between mb-3">
        <a href="#">Forget Password?</a>
        <a href="signup.php">Create Account</a>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-success">Login</button>
      </div>

      <div class="d-grid mb-3">
        <button type="button" class="btn btn-google">Login with Google</button>
      </div>

      <div class="d-grid">
        <a href="customer-serivces.php" class="btn btn-guest">Continue as Guest</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
