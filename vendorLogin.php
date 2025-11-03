<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Login - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <!-- Vendor Login Section -->
  <section class="login">
    <div class="login-container">
      <h1>Vendor Login</h1>
      <form class="login-form" action="vendorLoginProcess.php" method="POST">
        <div class="input-group">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="input-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="login-btn">Login as Vendor</button>

        <div class="login-links">
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <p class="signup-link">
          Don't have an account? <a href="vendor.php">Become a Vendor</a>
        </p>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-column">
      <h4>About Us</h4>
      <p>Local Service helps you connect with trusted providers in your area quickly and securely.</p>
    </div>
    <div class="footer-column">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Popular Services</h4>
      <ul>
        <li><a href="vendors-list.php?service=plumbing">Plumber</a></li>
        <li><a href="vendors-list.php?service=electrician">Electrician</a></li>
        <li><a href="vendors-list.php?service=cleaning">Cleaning</a></li>
        <li><a href="vendors-list.php?service=Painter">Painter</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>