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
     <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                                    <img width="75%" src="../img/logo.png" />
                                </div>
    <!-- <h3 class="text-center mb-4">Login</h3> -->
    
    <form>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Password">
      </div>

      <div class="d-flex justify-content-between mb-3">
        <a href="#">Forget Password?</a>
        <a href="signup.php">Create Account</a>
      </div>

      <div class="d-grid mb-3">
        <button type="button" class="btn btn-success">Login</button>
      </div>
      <!-- <div class="d-grid mb-3">
        <button type="button" class="btn btn-facebook">Login with Facebook</button>
      </div> -->

      <div class="d-grid mb-3">
        <button type="button" class="btn btn-google">Login with Google</button>
      </div>

      <div class="d-grid">
        <a href="customer-serivces.php"  class="btn btn-guest">Continue as Guest</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
