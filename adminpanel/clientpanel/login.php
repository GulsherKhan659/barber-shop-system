<?php
// require_once __DIR__ . '/../database/configue.php';
// require_once __DIR__ . '/../database/connection.php';

// $alert = '';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $config = new Configue();
//   $db = new Database($config->servername, $config->database, $config->username, $config->password);

//   // Guest Login
//   if (isset($_POST['guest_submit'])) {
//     $guestEmail = trim($_POST['guest_email'] ?? '');
//     $guestPhone = trim($_POST['guest_phone'] ?? '');

//     if (!$guestEmail || !$guestPhone) {
//       $alert = '<div class="alert alert-danger">Guest email and phone are required.</div>';
//     } else {
//       try {
//         // Check if guest email already exists
//         $existingUser = $db->select("users", "*", ["email" => $guestEmail]);

//         if (!$existingUser) {
//           // Insert new guest user
//           $db->insert("users", [
//             "email" => $guestEmail,
//             "phone" => $guestPhone,
//             "role" => "guest",
//             "name" => "Guest User"
//           ]);

//           $userId = $db->lastInsertId(); // if available
//         } else {
//           $userId = $existingUser[0]['id'];
//         }

//         session_start();
//         $_SESSION['user'] = [
//           'id' => $userId,
//           'name' => 'Guest User',
//           'email' => $guestEmail,
//           'role' => 'guest',
//           'shop_id' => 1
//         ];
//         header("Location: service_tab.php");
//         exit;
//       } catch (Exception $e) {
//         $alert = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
//       }
//     }
//   }
//   // Normal Login
//   else {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//       $email = trim($_POST['email'] ?? '');
//       $password = $_POST['password'] ?? '';

//       if (!$email || !$password) {
//         $alert = '<div class="alert alert-danger">Email and password are required.</div>';
//       } else {
//         try {
//           $config = new Configue();
//           $db = new Database($config->servername, $config->database, $config->username, $config->password);

//           $user = $db->select("users", "*", ["email" => $email]);

//           if (empty($user)) {
//             $alert = '<div class="alert alert-danger">No user found with this email.</div>';
//           } else {
//             $user = $user[0];

//             if (password_verify($password, $user['password'])) {
//               session_start();
//               $_SESSION['user'] = [
//                 'id' => $user['id'],
//                 'name' => $user['name'],
//                 'email' => $user['email'],
//                 'role' => $user['role'],
//                 'shop_id' => $user['shop_id']
//               ];
//               header("Location: service_tab.php");
//               exit;
//             } else {
//               $alert = '<div class="alert alert-danger">Incorrect password.</div>';
//             }
//           }
//         } catch (Exception $e) {
//           $alert = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
//         }
//       }
//     }
//   }
// }

session_start();

// Redirect to service_tab.php if already logged in
if (isset($_SESSION['user'])) {
  header("Location: service_tab.php");
  exit;
}

require_once __DIR__ . '/../database/configue.php';
require_once __DIR__ . '/../database/connection.php';

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $config = new Configue();
  $db = new Database($config->servername, $config->database, $config->username, $config->password);

  // Guest Login
  if (isset($_POST['guest_submit'])) {
    $guestEmail = trim($_POST['guest_email'] ?? '');
    $guestPhone = trim($_POST['guest_phone'] ?? '');

    if (!$guestEmail || !$guestPhone) {
      $alert = '<div class="alert alert-danger">Guest email and phone are required.</div>';
    } else {
      try {
        $existingUser = $db->select("users", "*", ["email" => $guestEmail]);

        if (!$existingUser) {
          $db->insert("users", [
            "email" => $guestEmail,
            "phone" => $guestPhone,
            "role" => "guest",
            "name" => "Guest User",
            "shop_id" => 1
          ]);
          $userId = $db->lastInsertId();
        } else {
          $userId = $existingUser[0]['id'];
        }

        $_SESSION['user'] = [
          'id' => $userId,
          'name' => 'Guest User',
          'email' => $guestEmail,
          'role' => 'guest',
          'shop_id' => 1
        ];

        header("Location: service_tab.php");
        exit;
      } catch (Exception $e) {
        $alert = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
      }
    }
  }

  // Customer Login
  else {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
      $alert = '<div class="alert alert-danger">Email and password are required.</div>';
    } else {
      try {
        $user = $db->select("users", "*", ["email" => $email]);

        if (empty($user)) {
          $alert = '<div class="alert alert-danger">No user found with this email.</div>';
        } else {
          $user = $user[0];

          if (password_verify($password, $user['password'])) {
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
      background: #f2f4f7 url('https://www.transparenttextures.com/patterns/symphony.png');
      color: #25304a;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 32px 28px 28px 28px;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 24px rgba(44, 60, 120, 0.12);
      border: 1px solid #e2e8f0;
    }

    .login-container .w-100 {
      margin-bottom: 8px;
    }

    .login-container img {
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
      box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.13);
    }

    .btn-success {

      background: #000 !important;
      color: #fff !important;
      font-weight: 700;
      font-size: 1.08rem;
      border-radius: 8px;
      border: none !important;
      padding: 11px 0;
      transition: background 0.15s;
      box-shadow: 0 1px 10px rgba(44, 204, 113, 0.04);
    }

    .btn-success:hover,
    .btn-success:focus {

      background: rgb(57, 59, 58) !important;
    }

    .btn-google {
      background: #fff !important;
      color: #db4437 !important;
      border: 1.5px solid #db4437;
      font-weight: 600;
      font-size: 1.03rem;
      border-radius: 8px;
      padding: 10px 0;
      transition: background 0.14s, color 0.14s;
      margin-top: 4px;
    }

    .btn-google:hover {
      background: #db4437 !important;
      color: #fff !important;
    }

    .btn-guest {
      background: #f7fafd !important;
      color: #25304a !important;
      font-weight: 600;
      border: 1.5px solid #bfcbdc;
      border-radius: 8px;
      padding: 10px 0;
      font-size: 1.03rem;
      margin-top: 4px;
      transition: background 0.14s, color 0.14s;
    }

    .btn-guest:hover {
      background: #e2e8f0 !important;
      color: #1d3557 !important;
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

    .alert {
      margin-bottom: 18px;
      border-radius: 7px;
      font-size: 0.99rem;
      text-align: center;
    }

    .footer-note,
    .footer-text {
      text-align: center;
      color: #8fa1b4 !important;
      margin: 24px 0 0 0;
      font-size: 1.04rem !important;
      padding-bottom: 10px;
    }

    @media (max-width: 500px) {
      .login-container {
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

  <div class="login-container">
    <div class="w-100 h-100 d-flex justify-content-center align-items-center mb-4">
      <img width="75%" src="assets/shop-logo.png" />
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
        <a href="google_login.php" class="btn btn-google">Login with Google</a>
      </div>

      <div class="d-grid">
        <button type="button" class="btn btn-guest" data-bs-toggle="modal" data-bs-target="#guestModal">Continue as Guest</button>
      </div>
    </form>
  </div>

  <!-- Guest Modal -->
  <div class="modal fade" id="guestModal" tabindex="-1" aria-labelledby="guestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="guestModalLabel">Continue as Guest</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="guest_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="guest_email" name="guest_email" required>
          </div>
          <div class="mb-3">
            <label for="guest_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="guest_phone" name="guest_phone" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="guest_submit" class="btn btn-success">Continue</button>
        </div>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>