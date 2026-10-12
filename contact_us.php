<?php
// Handle form submission
$success_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';

    // Simple validation
    if (!empty($email) && !empty($name)) {
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
  <title>Contact Us - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* Soft purple outer frame background matching system theme */
    body {
      background: #8172f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Dark inner card container wrapper */
    .app-card {
      width: 100%;
      max-width: 980px;
      min-height: 620px;
      background-color: #061118;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    /* Ambient background radial glow */
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

    /* Top Navigation Bar */
    .navbar-custom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 40px;
      position: relative;
      z-index: 10;
    }

    .logo {
      font-size: 1.4rem;
      font-weight: 800;
      color: #ffffff;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: #a0aec0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: #ffffff;
    }

    /* Content Layout */
    .content-area {
      padding: 10px 40px 40px;
      position: relative;
      z-index: 10;
    }

    .contact-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.3);
      border: none;
    }

    /* Blue Submit Button */
    .btn-primary-custom {
      background-color: #0d6efd;
      border-color: #0d6efd;
      color: #ffffff;
      border-radius: 50px;
      padding: 10px 40px;
      font-weight: 700;
      transition: background-color 0.3s ease;
    }

    .btn-primary-custom:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
      color: #ffffff;
    }

    /* Info Boxes */
    .info-box {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 20px;
      color: #ffffff;
      text-align: center;
    }

    .info-icon {
      font-size: 2rem;
      background-color: #0d6efd;
      color: white;
      border-radius: 50%;
      width: 55px;
      height: 55px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
    }
  </style>
</head>

<body>

<div class="app-card">
  <!-- Navigation Bar -->
  <nav class="navbar-custom">
    <div class="logo">ChapChapPay</div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="contact_us.php" class="active">Contact</a>
      <a href="help.php">Help</a>
    </div>
  </nav>

  <!-- Main Content Area -->
  <div class="content-area">
    <!-- Contact Form Card -->
    <div class="card contact-card mx-auto mb-4" style="max-width: 600px;">
      <div class="card-body p-4 text-center">
        <h3 class="fw-bold text-dark mb-2">Contact Us</h3>
        <p class="text-secondary mb-4">Any questions or remarks? Just write us a message!</p>

        <?php if (!empty($success_message)) : ?>
          <div class="alert alert-info py-2"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <input type="email" name="email" class="form-control rounded-pill px-3" placeholder="Enter a valid email address" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="name" class="form-control rounded-pill px-3" placeholder="Enter your Name" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary-custom">SUBMIT</button>
        </form>
      </div>
    </div>

    <!-- Info Boxes Section -->
    <div class="row g-3 justify-content-center" style="max-width: 600px; margin: 0 auto;">
      <div class="col-md-6">
        <div class="info-box h-100">
          <div class="info-icon">💵</div>
          <h5 class="fw-bold mb-1">ABOUT US</h5>
          <p class="text-secondary small mb-0">Transparency<br>Contributions</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box h-100">
          <div class="info-icon">📞</div>
          <h5 class="fw-bold mb-1">PHONE (LANDLINE)</h5>
          <p class="text-secondary small mb-0">+254 723 246354<br>+254 722 397643</p>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>