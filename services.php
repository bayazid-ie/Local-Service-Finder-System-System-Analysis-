

<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $check_vendor_sql = "SELECT id FROM vendors WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_vendor_sql);
    
    // Check if prepare was successful
    if ($check_stmt) {
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $vendor_result = $check_stmt->get_result();
        
        
        if ($vendor_result->num_rows > 0) {
            header("Location: vendor-dashboard.php");
            exit();
        }
    }
    
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Services - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .service-card {
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>
 
  <!-- Services Section -->
  <section class="services-section">
    <h2>Our Services</h2>
    <div class="services-grid">

      <?php
      include 'db_connect.php';
      
      $services = [
          'Plumbing' => ['Plumber', 'plumber.png', 'Professional plumbing services for all your home and office needs.'],
          'Electrician' => ['Electrician', 'electrician.png', 'Certified electricians to fix wiring, lighting, and more.'],
          'Cleaning' => ['Cleaning', 'cleaner1.png', 'Reliable home and office cleaning services at your doorstep.'],
          'Painter' => ['Painter', 'painter.png', 'Transform your space with our expert painting services.'],
          'AC Repair' => ['AC Repair', 'acRepair1.png', 'Expert AC repair for comfort you can count on.'],
          'Carpenter' => ['Carpenter', 'carpenter.png', 'Reliable carpentry solutions for every project.'],
          'Mason' => ['Mason', 'mason.png', 'Expert masonry for every corner of your home.'],
          'Pest Control' => ['Pest Control', 'pestControl.jpg', 'Fast, reliable, and professional pest elimination.']
      ];
      
      foreach($services as $service_type => $service_data) {
          $name = $service_data[0];
          $image = $service_data[1];
          $description = $service_data[2];
          
          // Get vendor count for this service
          $sql = "SELECT COUNT(*) as vendor_count FROM vendors WHERE service_type = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $service_type);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          $vendor_count = $row['vendor_count'];
          $stmt->close();
          ?>
          
          <div class="service-card" onclick="viewVendors('<?php echo $service_type; ?>')">
            <img src="images/<?php echo $image; ?>" alt="<?php echo $name; ?> Service">
            <h3><?php echo $name; ?></h3>
            <p><?php echo $description; ?></p>
            <!-- Vendor count - now part of the clickable card -->
            <div style="margin-top: 10px; font-size: 14px; color: #2563eb; font-weight: 500;">
              ✅ <?php echo $vendor_count; ?> vendor<?php echo $vendor_count != 1 ? 's' : ''; ?> available
            </div>
            <div style="margin-top: 5px; font-size: 12px; color: #666;">
            </div>
          </div>
          
      <?php 
      }
      $conn->close();
      ?>

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

<script>
function viewVendors(serviceType) {
   
    window.location.href = 'vendors-list.php?service=' + serviceType;
}
</script>
</body>
</html>