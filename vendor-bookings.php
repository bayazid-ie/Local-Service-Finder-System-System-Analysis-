<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

include 'db_connect.php';
$vendor_id = $_SESSION['vendor_id'];

// Get vendor bookings
$sql = "SELECT b.*, u.name as customer_name, u.phone as customer_phone, u.email as customer_email 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.vendor_id = ? 
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Bookings - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="services-section">
    <h2>My Customer Bookings</h2>
    
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
    
    <?php if(count($bookings) > 0): ?>
      <div class="services-grid">
        <?php foreach($bookings as $booking): ?>
          <div class="service-card">
            <h3 style="color: #2563eb;"><?php echo htmlspecialchars($booking['service_type']); ?> Service</h3>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($booking['customer_name']); ?></p>
            <p><strong>Customer Phone:</strong> <?php echo htmlspecialchars($booking['customer_phone']); ?></p>
            <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($booking['customer_email']); ?></p>
            <p><strong>Service Description:</strong> <?php echo htmlspecialchars($booking['service_description']); ?></p>
            <p><strong>Booking Date:</strong> <?php echo date('M j, Y g:i A', strtotime($booking['booking_date'])); ?></p>
            <p><strong>Customer Address:</strong> <?php echo htmlspecialchars($booking['address']); ?></p>
            
            <div style="margin-top: 15px;">
              <strong>Status:</strong> 
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
            </div>
            
            <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
              <?php if($booking['status'] == 'pending'): ?>
                <form action="update-booking-status.php" method="POST" style="display: inline;">
                  <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                  <input type="hidden" name="status" value="confirmed">
                  <button type="submit" style="background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                    Confirm Booking
                  </button>
                </form>
              <?php endif; ?>
              
              <?php if($booking['status'] == 'confirmed'): ?>
                <form action="update-booking-status.php" method="POST" style="display: inline;">
                  <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                  <input type="hidden" name="status" value="completed">
                  <button type="submit" style="background: #17a2b8; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                    Mark Completed
                  </button>
                </form>
              <?php endif; ?>

             
              <?php if(in_array($booking['status'], ['pending', 'confirmed'])): ?>
                <?php
                // Check if booking can be cancelled 
                $hours_until_booking = 0;
                if ($booking['status'] == 'confirmed') {
                    $booking_time = strtotime($booking['booking_date']);
                    $current_time = time();
                    $hours_until_booking = round(($booking_time - $current_time) / 3600, 1);
                }
                $can_cancel = ($booking['status'] == 'pending') || ($booking['status'] == 'confirmed' && $hours_until_booking > 2);
                ?>
                
                <?php if($can_cancel): ?>
                  <button type="button" onclick="openVendorCancelModal(<?php echo $booking['id']; ?>)" 
                          style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                      <i class="fa-solid fa-times"></i> Cancel Booking
                  </button>
                <?php else: ?>
                  <div style="color: #6c757d; font-size: 0.9em; padding: 8px 0;">
                    <i class="fa-solid fa-info-circle"></i> Cannot cancel within 2 hours of service
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>

            <!-- Cancellation Info -->
            <?php if($booking['status'] == 'cancelled'): ?>
              <div style="margin-top: 10px; padding: 10px; background: #f8d7da; border-radius: 6px; border-left: 4px solid #dc3545;">
                <strong><i class="fa-solid fa-ban"></i> Cancelled by <?php echo $booking['cancelled_by']; ?></strong>
                <?php if(!empty($booking['cancellation_reason'])): ?>
                  <p style="margin: 5px 0 0 0; color: #721c24;">Reason: <?php echo htmlspecialchars($booking['cancellation_reason']); ?></p>
                <?php endif; ?>
                <small style="color: #721c24;">Cancelled on: <?php echo date('M j, Y g:i A', strtotime($booking['cancelled_at'])); ?></small>
              </div>
            <?php endif; ?>
            <!-- End Cancellation Info -->
            
            <p style="margin-top: 10px; color: #666; font-size: 0.9rem;">
              <strong>Booked on:</strong> <?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px;">
        <i class="fa-solid fa-calendar-times" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
        <h3 style="color: #666; margin-bottom: 15px;">No Bookings Yet</h3>
        <p style="color: #888; margin-bottom: 25px;">You haven't received any bookings yet.</p>
      </div>
    <?php endif; ?>
  </section>

  <!-- Vendor Cancel Booking Modal -->
  <div id="vendorCancelModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
      <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 500px;">
          <span class="close" onclick="closeVendorCancelModal()" style="float: right; font-size: 28px; font-weight: bold; cursor: pointer; color: #aaa;">&times;</span>
          
          <h3>Cancel Booking</h3>
          <form id="vendorCancelForm" action="vendorBookingCancel.php" method="POST">
              <input type="hidden" name="booking_id" id="vendor_cancel_booking_id">
              
              <div style="margin-bottom: 15px;">
                  <label style="display: block; margin-bottom: 5px; font-weight: bold;">Cancellation Reason:</label>
                  <select name="cancellation_reason" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                      <option value="">Select a reason</option>
                      <option value="Unavailable">I'm unavailable</option>
                      <option value="Schedule conflict">Schedule conflict</option>
                      <option value="Emergency">Emergency</option>
                      <option value="Customer request">Customer requested cancellation</option>
                      <option value="Other">Other</option>
                  </select>
              </div>
              
              <div style="margin-bottom: 15px;">
                  <label style="display: block; margin-bottom: 5px; font-weight: bold;">Additional Notes (Optional):</label>
                  <textarea name="additional_notes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; height: 80px;" placeholder="Any additional details..."></textarea>
              </div>
              
              <div style="display: flex; gap: 10px; justify-content: flex-end;">
                  <button type="button" onclick="closeVendorCancelModal()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer;">Cancel</button>
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
        <li><a href="#">Plumber</a></li>
        <li><a href="#">Electrician</a></li>
        <li><a href="#">Cleaning</a></li>
        <li><a href="#">Painter</a></li>
      </ul>
    </div>
  </footer>

  <script>
  function openVendorCancelModal(bookingId) {
      document.getElementById('vendor_cancel_booking_id').value = bookingId;
      document.getElementById('vendorCancelModal').style.display = 'block';
  }

  function closeVendorCancelModal() {
      document.getElementById('vendorCancelModal').style.display = 'none';
      document.getElementById('vendorCancelForm').reset();
  }

  window.onclick = function(event) {
      const modal = document.getElementById('vendorCancelModal');
      if (event.target == modal) {
          closeVendorCancelModal();
      }
  }
  </script>
</body>
</html>