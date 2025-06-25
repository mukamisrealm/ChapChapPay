<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChapChapPay - Registration</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      background-color: #87CEEB; /* sky blue */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .register-box {
      background: white;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }

    .register-box h2 {
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <h2>User Registration</h2>
    <form action="submit_registration.php" method="POST">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="role">Select Role:</label>
        <select class="form-control" id="role" name="role" required>
          <option value="member">Member</option>
          <option value="treasurer">Treasurer</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Register</button>
      <p class="mt-3 text-center"><a href="login.php">Already have an account? Login</a></p>
    </form>
  </div>

</body>
</html>
