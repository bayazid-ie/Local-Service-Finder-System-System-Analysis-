<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
 
  <?php include 'navbar.php'; ?>
  
  <!-- Signup Section -->
  <section class="login">
    <div class="login-container">
      <h1>Create Account</h1>
      <form class="login-form" action="signup_process.php" method="POST">
        <div class="input-group">
          <i class="fa-solid fa-user"></i>
          <input type="text" name="name" placeholder="Full Name" required>
        </div>
        <div class="input-group">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="input-group">
          <i class="fa-solid fa-phone"></i>
          <input type="text" name="phone" placeholder="Phone Number" required>
        </div>
        <div class="input-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="login-btn">Sign Up</button>
        <p class="signup-link">Already have an account? <a href="login.php">Log In</a></p>
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
        <li><a href="vendor.php">Become a Vendor</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Log In</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Popular Services</h4>
      <ul>
        <li><a href="#">Plumber</a></li>
        <li><a href="#">Electrician</a></li>
        <li><a href="#">Cleaning</a></li>
        <li><a href="#">Painter</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>
