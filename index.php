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
      margin: 0;
   position: relative;
    }
   .center-content {
      position: absolute;
      top: 30%;
      left: 55%; /* Moves content closer to turtle side */
      transform: translate(-50%, -50%);
      text-align: right;
    }


    .center-content h1 {
      font-size: 3.2em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
    }

   .center-content p {
      margin-top: 10px;
      font-size: 1.1em;
      font-weight: bold;
      color: #000;
      text-shadow: 1px 1px 2px rgba(255,255,255,0.3);
    }

    .btn {
      display: inline-block;
      background-color: white;
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
      color:rgb(255, 255, 0);
    }
  </style>
</head>
<body>
  <div class="center-content">
    <h1>Welcome to <span style="color:rgb(255, 255, 0);">ChapChapPay</span></h1>
    <p>Manage your Chama contributions with ease and transparency.</p>
    <a href="register_login.php" class="btn">Login / Register</a>
    <a href="help.php" class="btn">Help</a>
  </div>
</body>
</html>
