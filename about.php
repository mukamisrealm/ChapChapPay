<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background: #8172f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .app-card {
      width: 100%;
      max-width: 1000px;
      background-color: #061118;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    .app-card::before {
      content: '';
      position: absolute;
      top: -150px;
      right: -100px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(111, 76, 255, 0.2) 0%, rgba(6, 17, 24, 0) 70%);
      pointer-events: none;
    }

    .navbar-custom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 40px;
    }

    .logo {
      font-size: 1.4rem;
      font-weight: 800;
      color: #ffffff;
    }

    .nav-links a {
      color: #a0aec0;
      text-decoration: none;
      font-weight: 500;
      margin-left: 20px;
    }

    .nav-links a:hover {
      color: #ffffff;
    }

    .content-area {
      padding: 20px 40px 40px;
      position: relative;
      z-index: 10;
    }

    .info-card {
      background: #fffffff7;
      border-radius: 12px;
      padding: 30px;
      color: #2d3748;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Navbar -->
  <nav class="navbar-custom">
    <div class="logo">ChapChapPay</div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php" style="color: #fffffff7; font-weight: 700;">About</a>
      <a href="contact_us.php">Contact</a>
      <a href="help.php">Help</a>
    </div>
  </nav>

  <!-- Content -->
  <div class="content-area">
    <div class="info-card">
      <h2 class="fw-bold text-dark mb-3">About ChapChapPay</h2>
      <p class="lead text-secondary">
        ChapChapPay is a modern financial tracking and management system designed specifically for Chama groups.
      </p>
      <hr class="my-4">
      <p>
        Our platform simplifies contribution tracking, automated fine calculations, payout schedules, and member requests with total transparency and security.
      </p>
      <div class="mt-4">
        <a href="register_login.php" class="btn btn-primary fw-bold px-4">Get Started</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>