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
    body {
      background-color: cadetblue;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 100px;
    }

    /* Navbar */
    .navbar {
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0, 0, 0, 0.6);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 1.5em;
      font-weight: bold;
      color: #ccc;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #f9c63f;
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

  <!-- Support Request Form -->
  <div class="card mt-5 mx-auto" style="max-width: 450px; background: linear-gradient(135deg, #f8f9fa 0%, #e9f0fb 100%); border-radius: 10px; box-shadow: 0 8px 10px rgba(0,0,0,0.1);">
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
          <button type="submit" class="btn btn-primary btn-lg fw-semibold" style="border-radius: 50px; transition: background-color 0.3s ease;">
            <i class="bi bi-send-fill me-2"></i> Send Request
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
