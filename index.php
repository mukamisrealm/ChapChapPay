<?php include 'images.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChapChapPay - Home</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: url('photos\Maori-style corner decoration featuring a turtle in blue and turquoise_.jpeg') no-repeat center center fixed;
      background-color: #66bcb1;
      background-size: cover;
      background-position: left center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      position: relative;
    }

    /* NAVBAR */
    .navbar {
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 0.05); /* subtle overlay */
      position: absolute;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 1.5em;
      font-weight: bold;
      color:  #333;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color:  #333;
      text-decoration: none;
      font-weight: 700;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #f9c63f;
    }

    /* HERO SECTION */
    .center-content {
      position: absolute;
      top: 45%;
      left: 62%;
      transform: translate(-50%, -50%);
      text-align: center;
      max-width: 500px;
    }

    .main-title {
      font-size: 3.5em;
      font-weight: bold;
      color: #fff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 1.8em;
      font-weight: 600;
      color: #fdfd66;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
      margin-bottom: 10px;
    }

    .description {
      font-size: 1em;
      font-weight: 500;
      color: #000;
      text-shadow: 1px 1px 2px rgba(255,255,255,0.4);
      margin-bottom: 20px;
    }

    .btn {
      display: inline-block;
      background-color: white;
      color: #000;
      padding: 10px 20px;
      margin-top: 10px;
      margin-left: 10px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }

    .btn:hover {
      background-color: #f9c63f;
      color: #000;
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <div class="navbar">
    <div class="logo">ChapChapPay</div>
    <div class="nav-links">
      <a href="#">Home</a>
      <a href="#about">About</a>
      <a href="contact_us.php">Contact</a>
      <a href="help.php">Help</a>
    </div>
  </div>

  <!-- Hero Section -->
  <div class="center-content">
    <h1 class="main-title">Welcome</h1>
    <h2 class="subtitle">To ChapChapPay</h2>
    <p class="description">Manage your Chama contributions with ease and transparency.</p>
    <a href="register_login.php" class="btn">Login / Register</a>
    <a href="help.php" class="btn">Help</a>
  </div>

</body>
</html>
