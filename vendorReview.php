<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

include 'db_connect.php';
$vendor_id = $_SESSION['vendor_id'];

// Get vendor reviews
$sql = "SELECT r.*, u.name as user_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.vendor_id = ? 
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reviews - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="services-section">
    <h2>Customer Reviews</h2>
    
    <?php if(count($reviews) > 0): ?>
      <div class="services-grid">
        <?php foreach($reviews as $review): ?>
          <div class="service-card" style="text-align: left;">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
              <h4 style="color: #2563eb; margin: 0;"><?php echo htmlspecialchars($review['user_name']); ?></h4>
              <div style="color: #ffc107;">
                <?php
                for($i = 1; $i <= 5; $i++) {
                    echo $i <= $review['rating'] ? '⭐' : '☆';
                }
                ?>
              </div>
            </div>
            
            <?php if(!empty($review['comment'])): ?>
              <p style="color: #555; font-style: italic;">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
            <?php endif; ?>
            
            <p style="color: #666; font-size: 0.9rem; margin-top: 10px;">
              <i class="fa-solid fa-clock"></i> 
              <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px;">
        <i class="fa-solid fa-comment-dots" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
        <h3 style="color: #666; margin-bottom: 15px;">No Reviews Yet</h3>
        <p style="color: #888;">You haven't received any reviews from customers yet.</p>
      </div>
    <?php endif; ?>
  </section>
</body>
</html>