<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Become a Vendor - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

  <!-- Vendor Section -->
  <section class="login">
    <div class="login-container">
      <h1>Become a Vendor</h1>

      <form class="login-form" action="vendor_register_process.php" method="POST">
        
        <div class="input-group">
          <i class="fa-solid fa-user"></i>
          <input type="text" name="name" placeholder="Your Name" required>
        </div>

        <div class="form-group">
          <i class="fas fa-briefcase"></i>
          <select class="form-control" name="service_type" required>
            <option value="">Select Service Type</option>
            <option value="plumber">Plumbing</option>
            <option value="electrician">Electrician</option>
            <option value="carpenter">Carpenter</option>
            <option value="pestcontrol">Pest Control</option>
            <option value="cleaning">Cleaning</option>
          </select>
        </div>

        <div class="input-group">
          <i class="fa-solid fa-phone"></i>
          <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <div class="input-group">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="input-group">
          <i class="fa-solid fa-location-dot"></i>
          <input type="text" name="location" placeholder="Your Location" required>
        </div>

        <div class="input-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" placeholder="Create Password" required>
        </div>

        <div class="input-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="login-btn">Register as Vendor</button>

        <p class="signup-link">
          Already have an account? <a href="vendorLogin.php">Log In</a>
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
        <li><a href="indexphp">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Popular Services</h4>
      <ul>
         <li><a href="vendors-list.php?service=Plumbing">Plumber</a></li>
        <li><a href="vendors-list.php?service=electrician">Electrician</a></li>
        <li><a href="vendors-list.php?service=cleaning">Cleaning</a></li>
        <li><a href="vendors-list.php?service=Painter">Painter</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>
