<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

include 'db_connect.php';

$vendor_id = $_SESSION['vendor_id'];
$sql = "SELECT * FROM vendors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$vendor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
  <title>Vendor Dashboard - Local Service Finder</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      color: #333;
      min-height: 100vh;
    }

    /* Main Content */
    .main-content {
      padding: 100px 40px 50px;
    }

    .dashboard-header {
      background: #fff;
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
    }

    .dashboard-header h2 {
      color: #111;
      font-weight: 600;
    }

    .dashboard-header .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
      color: #333;
    }

    .dashboard-header .user-info i {
      color: #2563eb;
      font-size: 1.8rem;
    }

    /* Cards */
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 3px 12px rgba(0,0,0,0.06);
      transition: all 0.3s ease;
      cursor: pointer;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
      
    .card-link {
      text-decoration: none;
      color: inherit;
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .card i {
      font-size: 2.4rem;
      color: #2563eb;
      margin-bottom: 15px;
    }

    .card h3 {
      font-size: 1.2rem;
      color: #222;
      margin-bottom: 8px;
    }
    
    

    .card p {
      color: #666;
      font-size: 0.95rem;
    }

    .vendor-stats {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      margin-bottom: 30px;
    }

    .vendor-stats h3 {
      color: #2563eb;
      margin-bottom: 20px;
    }

    .stat-item {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .stat-item:last-child {
      border-bottom: none;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="dashboard-header">
      <h2>Welcome, <?php echo $_SESSION['vendor_name']; ?>! 👋</h2>
      <div class="user-info">
        <i class="fa-solid fa-user-circle"></i>
        <span><?php echo $_SESSION['vendor_email']; ?></span>
      </div>
    </div>

    <div class="vendor-stats">
      <h3>Your Profile Information</h3>
      <div class="stat-item">
        <strong>Service Type:</strong>
        <span><?php echo ucfirst($vendor['service_type']); ?></span>
      </div>
      <div class="stat-item">
        <strong>Phone:</strong>
        <span><?php echo $vendor['phone']; ?></span>
      </div>
      <div class="stat-item">
        <strong>Location:</strong>
        <span><?php echo $vendor['location']; ?></span>
      </div>
      <div class="stat-item">
        <strong>Member Since:</strong>
        <span><?php echo date('F Y', strtotime($vendor['created_at'])); ?></span>
      </div>
    </div>

    <div class="card-container">
      <a href="vendor-bookings.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-calendar-check"></i>
          <h3>My Bookings</h3>
          <p>View and manage customer bookings</p>
        </div>
      </a>
      
      <!-- Reviews Card -->
      <a href="vendorReview.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-star"></i>
          <h3>My Reviews</h3>
          <p>View customer ratings and feedback</p>
          <?php
            
          $review_count_sql = "SELECT COUNT(*) as count FROM reviews WHERE vendor_id = ?";
          $review_count_stmt = $conn->prepare($review_count_sql);
          $review_count_stmt->bind_param("i", $vendor_id);
          $review_count_stmt->execute();
          $review_count_result = $review_count_stmt->get_result();
          $review_count = $review_count_result->fetch_assoc()['count'];
          ?>
          <div style="margin-top: 10px; color: #666;">
            <?php if($vendor['avg_rating'] > 0): ?>
              ⭐ <?php echo number_format($vendor['avg_rating'], 1); ?> (<?php echo $review_count; ?> reviews)
            <?php else: ?>
              No ratings yet
            <?php endif; ?>
          </div>
        </div>
      </a>
      <!-- End Reviews Card -->
      
      <a href="vendorProfile.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-user-edit"></i>
          <h3>Edit Profile</h3>
          <p>Update your business information</p>
        </div>
      </a>
      
      
    </div>
  </div>
</body>
</html>