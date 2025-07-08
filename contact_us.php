<?php
// Handle form submission (if any)
$success_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';

    // Simple validation
    if (!empty($email) && !empty($name)) {
        // You can send an email or save to DB here
        $success_message = "Thank you, $name! Your message has been received.";
    } else {
        $success_message = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #ffffff;
    }

    /* Navbar Styles */
    .navbar {
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0, 0, 0, 0.03);
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .logo {
      font-size: 1.5em;
      font-weight: bold;
      color: #333;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: #333;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color:darkorange;
    }

    /* Contact Page Content */
    .contact-section {
      text-align: center;
      padding: 50px 20px 30px;
    }

    .contact-section h2 {
      font-size: 36px;
      color: #000;
    }

    .contact-section p {
      font-size: 18px;
      color: #444;
    }

    form {
      margin-top: 30px;
    }

    input[type="email"],
    input[type="text"] {
      padding: 10px 15px;
      margin: 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
      width: 250px;
      max-width: 90%;
    }

    .submit-btn {
      display: inline-block;
      margin-top: 20px;
      background-color: #2dcac1;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 10px 40px;
      font-size: 16px;
      cursor: pointer;
    }

    .info-section {
      background-color: #e8e8e8;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      gap: 60px;
      flex-wrap: wrap;
    }

    .info-box {
      text-align: center;
      max-width: 300px;
    }

    .info-icon {
      font-size: 40px;
      background-color: #2dcac1;
      color: white;
      border-radius: 50%;
      padding: 15px;
      display: inline-block;
      margin-bottom: 15px;
    }

    .success {
      margin-top: 15px;
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <div class="navbar">
    <div class="logo">ChapChapPay</div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="#about">About</a>
      <a href="contact_us.php">Contact</a>
      <a href="help.php">Help</a>
    </div>
  </div>

  <!-- Contact Section -->
  <div class="contact-section">
    <h2>Contact Us</h2>
    <p>Any questions or remarks? Just write us a message!</p>

    <?php if (!empty($success_message)) : ?>
      <div class="success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Enter a valid email address" required>
      <input type="text" name="name" placeholder="Enter your Name" required><br>
      <button type="submit" class="submit-btn">SUBMIT</button>
    </form>
  </div>

  <!-- Info Boxes -->
  <div class="info-section" id="about">
    <div class="info-box">
      <div class="info-icon">💵</div>
      <h3>ABOUT US</h3>
      <p>Transparency<br>Contributions</p>
    </div>

    <div class="info-box">
      <div class="info-icon">📞</div>
      <h3>PHONE (LANDLINE)</h3>
      <p>+254 723 246354<br>+254 722 397643</p>
    </div>
  </div>

</body>
</html>
