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
      background-size: cover;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      position: relative;
    }

    .top-right {
      position: absolute;
      top: 20px;
      right: 30px;
      text-align: right;
    }

    .top-right h1 {
      font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
    }

    .top-right p {
      margin-top: 5px;
      font-size: 1.1em;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .btn {
      display: inline-block;
      background-color: aquamarine;
      color: #000;
      padding: 10px 20px;
      margin-top: 15px;
      margin-left: 10px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }

    .btn:hover {
      background-color:#f9c63f;
      color: #000;
    }

    h1 span {
      color: darkorange;
    }
  </style>
</head>
<body>

  <div class="top-right">
    <h1>Welcome to <span>ChapChapPay</span></h1>
    <p><span style="color:#000;font-weight: bold;">Manage your Chama contributions with ease and transparency.</span></p>

    <a href="register_login.php" class="btn">Login / Register</a>
    <a href="support_requests.php" class="btn">Help</a>
  </div>

</body>
</html>
