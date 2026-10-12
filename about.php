<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - ChapChapPay</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #1e1e1e;
      color: #fff;
    }

    .navbar {
      background-color: #2c2c2c;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
    }

    .navbar .btn {
      background-color: #ffc107;
      color: #000;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .about-section {
      padding: 50px 80px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: url('photos/Teal.jpeg') no-repeat center center fixed;
      background-size: cover;
    }

    .about-text {
      max-width: 50%;
    }

    .about-text h1 {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .about-text h3 {
      color: #00bcd4;
      margin-bottom: 15px;
    }

    .about-text p {
      font-size: 16px;
      line-height: 1.8;
    }

    .about-image img {
      width: 350px;
      border-radius: 10px;
      border: 2px solid #ffc107;
    }

    .stats {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 30px;
      display: flex;
      justify-content: space-around;
      color: #fff;
    }

    .stat-box {
      text-align: center;
    }

    .stat-box h2 {
      color: #ffc107;
      font-size: 36px;
      margin: 5px 0;
    }

    .stat-box small {
      color: #bbb;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div>
        <div class="logo">ChapChapPay</div>
       
      <a href="index.php"><strong>Home</strong></a>
      <a href="contact_us.php">Contact</a>
      <a href="about.php">About</a>
    </div>
    <div>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </div>

  <div class="about-section">
    <div class="about-text">
      <h1>About Us</h1>
      <h3>Empowering Chamas Through Simple Technology</h3>
      <p>
        ChapChapPay is a fast, efficient, and transparent financial management system built specifically for Chamas and small community groups. We believe that group savings and investments should be simple, secure, and accessible. 
        Our platform enables members to track contributions, manage expenses, schedule payouts, and generate reports seamlessly.
        With ChapChapPay, we’re bringing trust, order, and convenience to group finances.
      </p>
    </div>
    <div class="about-image">
      <div class="about-icon"></div>
      <large>🪙💸</large>
    </div>
  </div>

  <div class="stats">
    <div class="stat-box">
      <small>Number of Transactions</small>
      <h2>5</h2>
    </div>
    <div class="stat-box">
      <small>Established Year</small>
      <h2>2025</h2>
    </div>
    <div class="stat-box">
      <small>Number of Users</small>
      <h2>6</h2>
    </div>
  </div>

</body>
</html>
