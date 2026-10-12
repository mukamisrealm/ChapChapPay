<?php session_start(); $name = $_SESSION['user_id'] ?? 'Guest'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Help - ChapChapPay</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

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
      min-height: 600px;
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

    /* Form Card */
    .content-area {
      padding: 20px 40px 40px;
      position: relative;
      z-index: 10;
    }

    .support-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      border: none;
    }

    .btn-primary-custom {
      background-color: #0d6efd;
      border-color: #0d6efd;
      color: #ffffff;
      border-radius: 50px;
      transition: background-color 0.3s ease;
    }

    .btn-primary-custom:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
      color: #ffffff;
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
      <a href="contact_us.php">Contact</a>
      <a href="help.php" class="active">Help</a>
    </div>
  </nav>

  <!-- Support Request Form Container -->
  <div class="content-area">
    <div class="card support-card mx-auto" style="max-width: 500px;">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 fw-bold text-primary text-center">Need Help? Submit a Support Request</h3>

        <form action="support_requests.php" method="POST" novalidate>
          <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">

          <!-- Subject -->
          <div class="form-floating mb-4 position-relative">
            <input 
              type="text" 
              class="form-control ps-5" 
              id="subject" 
              name="subject" 
              placeholder="Subject" 
              required 
              minlength="3" 
              maxlength="100"
            >
            <label for="subject" class="text-secondary">Subject</label>
            <i class="bi bi-card-text position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: #6c757d;"></i>
            <div class="form-text">Briefly summarize your request (3-100 characters).</div>
          </div>

          <!-- Message -->
          <div class="form-floating mb-4 position-relative">
            <textarea 
              class="form-control ps-5" 
              placeholder="Message" 
              id="message" 
              name="message" 
              style="height: 120px;" 
              required 
              minlength="10" 
              maxlength="1000"
            ></textarea>
            <label for="message" class="text-secondary">Message</label>
            <i class="bi bi-chat-left-text position-absolute" style="top: 20px; left: 15px; color: #6c757d;"></i>
            <div class="form-text">Provide details to help us assist you better (10-1000 characters).</div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary-custom btn-lg fw-semibold">
              <i class="bi bi-send-fill me-2"></i> Send Request
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>