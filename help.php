<!-- Add this in your <head> if not already there -->
<!-- Bootstrap CSS -->
 <!DOCTYPE html>
<html lang="en">
<head>
  <!-- your head content -->
</head>
<body style="background-color:cadetblue;">
  <!-- everything else goes here, including your support form -->
</body>
</html>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<!-- Support Request Form -->
<div class="card mt-5 mx-auto" style="max-width: 600px; background: linear-gradient(135deg, #f8f9fa 0%, #e9f0fb 100%); border-radius: 10px; box-shadow: 0 8px 10px rgba(0,0,0,0.1);">
  <div class="card-body p-4">
    <h3 class="card-title mb-4 fw-bold text-primary text-center">Need Help? Submit a Support Request</h3>

    <form action="../ChapChapPay/support_requests.php" method="POST" novalidate>
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

<!-- Optional: Bootstrap JS Bundle for interactivity (tooltips etc) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
