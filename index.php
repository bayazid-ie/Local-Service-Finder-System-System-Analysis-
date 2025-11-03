<!DOCTYPE html>
<html>
<head>
  <title>Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Find Trusted Local Services Near You</h1>
    <p>Electricians, Plumbers, Cleaners & More – Just a Click Away</p>

    <div class="search-bar">
  <form action="vendors-list.php" method="GET" style="display: contents;">
    <select name="service">
      <option value="Plumbing">Plumber</option>
      <option value="Electrician">Electrician</option>
      <option value="AC Repair">AC Repair</option>
      <option value="Cleaning">Cleaning</option>
      <option value="Painter">Painter</option>
      <option value="Carpenter">Carpenter</option>
      <option value="Mason">Mason</option>
      <option value="Pest Control">Pest Control</option>
    </select>
    <input type="text" name="location" placeholder="Enter your location">
    <button type="submit">Search</button>
  </form>
</div>
  </section>

  <!-- Categories -->
  <section class="categories">
    <h2>Popular Categories</h2>
    <div class="category-grid">
      <div class="category-card" onclick="window.location.href='vendors-list.php?service=Plumbing'">
        <i class="fa-solid fa-wrench"></i>
        <p>Plumber</p>
      </div>
      <div class="category-card" onclick="window.location.href='vendors-list.php?service=Electrician'">
        <i class="fa-solid fa-lightbulb"></i>
        <p>Electrician</p>
      </div>
      <div class="category-card" onclick="window.location.href='vendors-list.php?service=AC Repair'">
        <i class="fa-solid fa-fan"></i>
        <p>AC Repair</p>
      </div>
      <div class="category-card" onclick="window.location.href='vendors-list.php?service=Cleaning'">
        <i class="fa-solid fa-spray-can"></i>
        <p>Cleaning</p>
      </div>
      <div class="category-card" onclick="window.location.href='vendors-list.php?service=Painter'">
        <i class="fa-solid fa-hammer"></i>
        <p>Painter</p>
      </div>
    </div>
  </section>

  <!-- Vendor Banner -->
  <section class="vendor-banner">
    <p><b>Are you a service provider?</b> Reach thousands of local customers today.</p>
    <a class="vendor-btn" href="vendor.php">Become a Vendor</a>
  </section>

  <!-- Features -->
  <section class="features">
    <div class="feature-card">
      <i class="fa-solid fa-magnifying-glass"></i>
      <h3>Search Service</h3>
      <p>Enter your location, service type, and find providers easily.</p>
    </div>
    <div class="feature-card">
      <i class="fa-solid fa-list-check"></i>
      <h3>Compare Providers</h3>
      <p>View ratings, reviews, and prices instantly.</p>
    </div>
    <div class="feature-card">
      <i class="fa-solid fa-calendar-check"></i>
      <h3>Book & Pay</h3>
      <p>Confirm your booking and pay securely.</p>
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
        <li><a href="vendors-list.php?service=Plumbing">Plumber</a></li>
        <li><a href="vendors-list.php?service=Electrician">Electrician</a></li>
        <li><a href="vendors-list.php?service=Cleaning">Cleaning</a></li>
        <li><a href="vendors-list.php?service=Painter">Painter</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>