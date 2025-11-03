<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
$user_id = $_SESSION['user_id'];

// PROCESS NEW BOOKINGS 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vendor_id'])) {
    $vendor_id = $_POST['vendor_id'];
    $service_type = $_POST['service_type'];
    $service_description = $_POST['service_description'];
    $booking_date = $_POST['booking_date'];
    $address = $_POST['address'];
    
    $sql = "INSERT INTO bookings (user_id, vendor_id, service_type, service_description, booking_date, address, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $user_id, $vendor_id, $service_type, $service_description, $booking_date, $address);
    
    if ($stmt->execute()) {
        // Refresh to show new booking
        header("Location: book-service.php?success=1");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Get user bookings
$sql = "SELECT b.*, v.name as vendor_name, v.phone as vendor_phone, v.email as vendor_email 
        FROM bookings b 
        JOIN vendors v ON b.vendor_id = v.id 
        WHERE b.user_id = ? 
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="services-section">
    <h2>My Bookings</h2>
    
    <!-- Success/Error Messages -->
    <?php if(isset($_GET['cancelled']) && $_GET['cancelled'] == 'success'): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            Booking cancelled successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            Booking created successfully!
        </div>
    <?php endif; ?>
    
    <?php if(count($bookings) > 0): ?>
      <div class="services-grid">
        <?php foreach($bookings as $booking): ?>
          <div class="service-card">
            <h3 style="color: #2563eb;"><?php echo htmlspecialchars($booking['service_type']); ?></h3>
            <p><strong>Vendor:</strong> <?php echo htmlspecialchars($booking['vendor_name']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($booking['service_description']); ?></p>
            <p><strong>Booking Date:</strong> <?php echo date('M j, Y g:i A', strtotime($booking['booking_date'])); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($booking['address']); ?></p>
            <p><strong>Status:</strong> 
              <span style="padding: 4px 8px; border-radius: 4px; 
                background: <?php 
                  if($booking['status'] == 'confirmed') echo '#d4edda';
                  elseif($booking['status'] == 'pending') echo '#fff3cd';
                  elseif($booking['status'] == 'completed') echo '#d1ecf1';
                  else echo '#f8d7da';
                ?>; 
                color: <?php 
                  if($booking['status'] == 'confirmed') echo '#155724';
                  elseif($booking['status'] == 'pending') echo '#856404';
                  elseif($booking['status'] == 'completed') echo '#0c5460';
                  else echo '#721c24';
                ?>;">
                <?php echo ucfirst($booking['status']); ?>
              </span>
            </p>
            <p><strong>Booked on:</strong> <?php echo date('M j, Y', strtotime($booking['created_at'])); ?></p>

            <!-- Cancel Button for Pending/Confirmed Bookings -->
            <?php if(in_array($booking['status'], ['pending', 'confirmed'])): ?>
                <?php
                // Check if booking can be cancelled (more than 2 hours before)
                $hours_until_booking = 0;
                if ($booking['status'] == 'confirmed') {
                    $booking_time = strtotime($booking['booking_date']);
                    $current_time = time();
                    $hours_until_booking = round(($booking_time - $current_time) / 3600, 1);
                }
                $can_cancel = ($booking['status'] == 'pending') || ($booking['status'] == 'confirmed' && $hours_until_booking > 2);
                ?>
                
                <?php if($can_cancel): ?>
                    <div style="margin-top: 15px;">
                        <button type="button" onclick="openCancelModal(<?php echo $booking['id']; ?>)" 
                                style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                            <i class="fa-solid fa-times"></i> Cancel Booking
                        </button>
                    </div>
                <?php else: ?>
                    <div style="margin-top: 10px; color: #6c757d; font-size: 0.9em;">
                        <i class="fa-solid fa-info-circle"></i> Cannot cancel within 2 hours of service
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Review Form for Completed Bookings -->
            <?php if($booking['status'] == 'completed'): ?>
              <?php
              
             // Check if user already reviewed 
              
              $review_check_sql = "SELECT id FROM reviews WHERE booking_id = ? AND user_id = ?";
              $review_check_stmt = $conn->prepare($review_check_sql);
              $review_check_stmt->bind_param("ii", $booking['id'], $user_id);
              $review_check_stmt->execute();
              $review_check_result = $review_check_stmt->get_result();
              $has_reviewed = $review_check_result->num_rows > 0;
              ?>
              
              <?php if(!$has_reviewed): ?>
                <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                  <h4>Rate this service:</h4>
                  <form action="submitReview.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                    <input type="hidden" name="vendor_id" value="<?php echo $booking['vendor_id']; ?>">
                    
                    <div>
                      <label>Rating:</label>
                      <div style="display: flex; gap: 5px;">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                          <label>
                            <input type="radio" name="rating" value="<?php echo $i; ?>" required>
                            ⭐
                          </label>
                        <?php endfor; ?>
                      </div>
                    </div>
                    
                    <div>
                      <label>Comment:</label>
                      <textarea name="comment" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Share your experience..."></textarea>
                    </div>
                    
                    <button type="submit" style="background: #2563eb; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; align-self: flex-start;">
                      Submit Review
                    </button>
                  </form>
                </div>
              <?php else: ?>
                <div style="margin-top: 10px; color: #28a745;">
                  <i class="fa-solid fa-check"></i> You've already reviewed this service
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <!-- End Review Form -->

          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px;">
        <i class="fa-solid fa-calendar-times" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
        <h3 style="color: #666; margin-bottom: 15px;">No Bookings Yet</h3>
        <p style="color: #888; margin-bottom: 25px;">You haven't booked any services yet.</p>
        <a href="services.php" class="login-btn" style="display: inline-block; margin-top: 10px; text-decoration: none;">Book a Service</a>
      </div>
    <?php endif; ?>
  </section>

  <!-- Cancel Booking Modal -->
  <div id="cancelModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
      <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 500px;">
          <span class="close" onclick="closeCancelModal()" style="float: right; font-size: 28px; font-weight: bold; cursor: pointer; color: #aaa;">&times;</span>
          
          <h3>Cancel Booking</h3>
          <form id="cancelForm" action="cancelBooking.php" method="POST">
              <input type="hidden" name="booking_id" id="cancel_booking_id">
              
              <div style="margin-bottom: 15px;">
                  <label style="display: block; margin-bottom: 5px; font-weight: bold;">Cancellation Reason:</label>
                  <select name="cancellation_reason" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                      <option value="">Select a reason</option>
                      <option value="Change of plans">Change of plans</option>
                      <option value="Found another provider">Found another provider</option>
                      <option value="Price issue">Price issue</option>
                      <option value="Service no longer needed">Service no longer needed</option>
                      <option value="Scheduling conflict">Scheduling conflict</option>
                      <option value="Other">Other</option>
                  </select>
              </div>
              
              <div style="margin-bottom: 15px;">
                  <label style="display: block; margin-bottom: 5px; font-weight: bold;">Additional Notes (Optional):</label>
                  <textarea name="additional_notes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; height: 80px;" placeholder="Any additional details..."></textarea>
              </div>
              
              <div style="display: flex; gap: 10px; justify-content: flex-end;">
                  <button type="button" onclick="closeCancelModal()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer;">Cancel</button>
                  <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Confirm Cancellation</button>
              </div>
          </form>
      </div>
  </div>

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
  function openCancelModal(bookingId) {
      document.getElementById('cancel_booking_id').value = bookingId;
      document.getElementById('cancelModal').style.display = 'block';
  }

  function closeCancelModal() {
      document.getElementById('cancelModal').style.display = 'none';
      document.getElementById('cancelForm').reset();
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
      const modal = document.getElementById('cancelModal');
      if (event.target == modal) {
          closeCancelModal();
      }
  }
  </script>
</body>
</html>