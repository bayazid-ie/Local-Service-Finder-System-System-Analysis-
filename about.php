<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
    <?php include 'navbar.php'; ?>

  <!-- About Section -->
  <section class="about">
    <div class="about-container">
      <h1>About Local Service Finder</h1>
      <p>
        Local Service Finder is your trusted platform to connect with verified service providers in your area.  
        From electricians and plumbers to cleaners and painters, we make it easy to find reliable services near you.
      </p>
      <p>
        Our mission is to save your time and effort by bringing all essential services under one roof.  
        Whether you need urgent repairs or regular maintenance, we’re here to connect you with the right people.
      </p>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="mission">
    <div class="mission-card">
      <i class="fa-solid fa-bullseye"></i>
      <h2>Our Mission</h2>
      <p>To make hiring local services quick, simple, and reliable for every household and business.</p>
    </div>
    <div class="mission-card">
      <i class="fa-solid fa-eye"></i>
      <h2>Our Vision</h2>
      <p>To become the most trusted local service platform that empowers both customers and service providers.</p>
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
