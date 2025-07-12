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
  background: #f2f4f7 url('https://www.transparenttextures.com/patterns/symphony.png');
  color: #25304a;
  font-family: 'Segoe UI', sans-serif;
}
.signup-container {
  max-width: 400px;
  margin: 80px auto;
  padding: 32px 28px 28px 28px;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 4px 24px rgba(44, 60, 120, 0.12);
  border: 1px solid #e2e8f0;
}
.signup-container .w-100 {
  margin-bottom: 8px;
}
.signup-container img {
  max-width: 72%;
  display: block;
  margin: 0 auto 10px auto;
}
.form-label {
  color: #506080;
  font-weight: 500;
  font-size: 1rem;
}
.form-control {
  background: #f7fafd;
  border: 1px solid #e2e8f0;
  color: #232c3a;
  border-radius: 7px;
  font-size: 1.02rem;
  box-shadow: none;
  transition: border-color 0.13s;
}
.form-control:focus {
  border-color: #2ecc71;
  background: #f7fafd;
  color: #232c3a;
  box-shadow: 0 0 0 2px rgba(46,204,113,0.13);
}
.btn-signup {
  background: #2ecc71 !important;
  color: #fff !important;
  font-weight: 700;
  font-size: 1.08rem;
  border-radius: 8px;
  border: none !important;
  padding: 11px 0;
  transition: background 0.15s;
  box-shadow: 0 1px 10px rgba(44,204,113,0.04);
}
.btn-signup:hover, .btn-signup:focus {
  background: #27ae60 !important;
}
a {
  color: #0077b6;
  font-weight: 500;
  font-size: 0.97rem;
  text-decoration: none;
  transition: color 0.13s;
}
a:hover {
  color: #2ecc71;
  text-decoration: underline;
}
@media (max-width: 500px) {
  .signup-container {
    max-width: 98vw;
    margin: 32px auto 0 auto;
    border-radius: 0;
    padding-left: 8px;
    padding-right: 8px;
  }
}

 </style>
</head>
<body>

  <div class="signup-container">
    <div class="w-100 d-flex justify-content-center align-items-center mb-4">
      <img width="75%" src="assets/shop-logo.png" alt="Logo" />
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
