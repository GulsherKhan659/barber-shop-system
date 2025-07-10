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
    <div class="w-100 h-100 d-flex justify-content-center align-items-center mb-4">
      <img width="75%" src="../img/logo.png" alt="Logo" />
    </div>

    <form>
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" placeholder="Enter your full name">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email">
      </div>
      <div class="mb-3">
        <label for="number" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="number" placeholder="Enter phone number">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Create a password">
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