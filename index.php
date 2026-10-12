<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChapChapPay - Home</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background: #8172f8; /* Soft purple outer frame background */
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px;
      color: #ffffff;
    }

    /* Outer Container Wrapper matching the dark card container */
    .app-card {
      width: 100%;
      max-width: 1200px;
      background-color: #061118; /* Dark navy black theme background */
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      overflow: hidden;
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Subtly animated background grid & radial glow */
    .app-card::before {
      content: '';
      position: absolute;
      top: -200px;
      right: -100px;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(111, 76, 255, 0.18) 0%, rgba(6, 17, 24, 0) 70%);
      pointer-events: none;
    }

    /* Navigation Bar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 30px 50px;
      position: relative;
      z-index: 10;
    }

    .logo {
      font-size: 1.4rem;
      font-weight: 800;
      color: #ffffff;
      display: flex;
      align-items: center;
      gap: 10px;
      letter-spacing: -0.5px;
    }

    .logo-icon {
      width: 28px;
      height: 28px;
      background: linear-gradient(135deg, #8172f8, #6f4cff);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .nav-links {
      display: flex;
      gap: 30px;
    }

    .nav-links a {
      color: #a0aec0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    .nav-links a:hover {
      color: #ffffff;
    }

    /* Main Hero Layout */
    .hero-container {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      align-items: center;
      padding: 40px 50px 80px;
      gap: 40px;
      position: relative;
      z-index: 10;
    }

    .hero-title-main {
      font-size: 3.5rem;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -1.5px;
      color: #ffffff;
      margin-bottom: 8px;
    }

    .hero-title-sub {
      font-size: 2.2rem;
      font-weight: 700;
      color: #8172f8; /* Vibrant purple highlight */
      margin-bottom: 20px;
      letter-spacing: -0.5px;
    }

    .hero-description {
      font-size: 1.05rem;
      color: #94a3b8;
      line-height: 1.6;
      margin-bottom: 35px;
      max-width: 480px;
    }

    /* Button Group - Exact buttons retained */
    .btn-group {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .btn-primary {
      background: linear-gradient(135deg, #7c5cff, #6336ff);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.95rem;
      padding: 14px 32px;
      border-radius: 10px;
      text-decoration: none;
      box-shadow: 0 4px 20px rgba(124, 92, 255, 0.35);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: inline-block;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(124, 92, 255, 0.5);
    }

    .btn-outline {
      background: transparent;
      color: #ffffff;
      font-weight: 600;
      font-size: 0.95rem;
      padding: 13px 28px;
      border-radius: 10px;
      text-decoration: none;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: background 0.2s ease, border-color 0.2s ease;
      display: inline-block;
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.08);
      border-color: rgba(255, 255, 255, 0.4);
    }

    /* Graphic Preview Card Element */
    .hero-visual {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 25px;
      backdrop-filter: blur(10px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .visual-header {
      font-size: 0.9rem;
      font-weight: 700;
      color: #e2e8f0;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .bar-chart {
      display: flex;
      align-items: flex-end;
      gap: 12px;
      height: 140px;
      padding-top: 10px;
    }

    .bar {
      flex: 1;
      background: rgba(129, 114, 248, 0.3);
      border-radius: 4px;
      transition: height 0.3s ease;
    }

    .bar.highlight {
      background: linear-gradient(to top, #6336ff, #8172f8);
    }

    @media (max-width: 900px) {
      body {
        padding: 10px;
      }
      .hero-container {
        grid-template-columns: 1fr;
        padding: 30px;
      }
      .hero-title-main {
        font-size: 2.8rem;
      }
      .hero-title-sub {
        font-size: 1.8rem;
      }
      .hero-visual {
        display: none;
      }
    }
  </style>
</head>
<body>

  <div class="app-card">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="logo">
        <div class="logo-icon">⚡</div>
        ChapChapPay
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact_us.php">Contact</a>
      </div>
    </nav>

    <!-- Main Hero Section -->
    <main class="hero-container">
      <div class="hero-text">
        <h1 class="hero-title-main">Welcome</h1>
        <h2 class="hero-title-sub">To ChapChapPay</h2>
        <p class="hero-description">
          Manage your Chama contributions with ease, security, and full transparency.
        </p>

        <!-- Preserved exact button actions -->
        <div class="btn-group">
          <a href="register_login.php" class="btn-primary">Login / Register</a>
          <a href="help.php" class="btn-outline">Help</a>
        </div>
      </div>

      <!-- Dashboard Visual Card Graphic -->
      <div class="hero-visual">
        <div class="visual-header">
          <span>Chama Contribution Overview</span>
          <span style="color: #8172f8; font-size: 0.8rem;">Active</span>
        </div>
        <div class="bar-chart">
          <div class="bar" style="height: 40%;"></div>
          <div class="bar" style="height: 65%;"></div>
          <div class="bar highlight" style="height: 85%;"></div>
          <div class="bar" style="height: 50%;"></div>
          <div class="bar highlight" style="height: 95%;"></div>
          <div class="bar" style="height: 60%;"></div>
          <div class="bar" style="height: 75%;"></div>
        </div>
      </div>
    </main>
  </div>

</body>
</html>