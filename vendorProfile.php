<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

include 'db_connect.php';
$vendor_id = $_SESSION['vendor_id'];

// Get current vendor data
$sql = "SELECT * FROM vendors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$vendor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hourly_rate = $_POST['hourly_rate'];
    $min_charge = $_POST['min_charge'];
    $service_description = $_POST['service_description'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    
    $update_sql = "UPDATE vendors SET hourly_rate = ?, min_charge = ?, service_description = ?, phone = ?, location = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ddsssi", $hourly_rate, $min_charge, $service_description, $phone, $location, $vendor_id);
    
    if ($update_stmt->execute()) {
        $success = "Profile updated successfully!";

        $vendor['hourly_rate'] = $hourly_rate;
        $vendor['min_charge'] = $min_charge;
        $vendor['service_description'] = $service_description;
        $vendor['phone'] = $phone;
        $vendor['location'] = $location;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .profile-container {
      max-width: 600px;
      margin: 100px auto 50px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .profile-header h1 {
      color: #2563eb;
      margin-bottom: 10px;
    }
    
    .profile-header p {
      color: #666;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
    }
    
    .input-with-icon {
      position: relative;
    }
    
    .input-with-icon i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #2563eb;
    }
    
    .input-with-icon input,
    .input-with-icon textarea {
      width: 100%;
      padding: 12px 15px 12px 45px;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .input-with-icon input:focus,
    .input-with-icon textarea:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      outline: none;
    }
    
    .input-with-icon textarea {
      min-height: 120px;
      resize: vertical;
    }
    
    .pricing-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .pricing-card {
      background: #f8fafc;
      padding: 20px;
      border-radius: 8px;
      border: 2px solid #e2e8f0;
      text-align: center;
    }
    
    .pricing-card label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 10px;
      color: #475569;
    }
    
    .pricing-card input {
      width: 100%;
      padding: 10px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      text-align: center;
      font-size: 16px;
      font-weight: 600;
    }
    
    .currency {
      color: #059669;
      font-weight: 700;
    }
    
    .btn-primary {
      width: 100%;
      background: #2563eb;
      color: white;
      border: none;
      padding: 15px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    
    .btn-primary:hover {
      background: #1e40af;
    }
    
    .success-message {
      background: #d1fae5;
      color: #065f46;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border-left: 4px solid #10b981;
    }
    
    @media (max-width: 768px) {
      .profile-container {
        margin: 100px 20px 50px;
        padding: 20px;
      }
      
      .pricing-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="profile-container">
    <div class="profile-header">
      <h1><i class="fa-solid fa-user-edit"></i> Edit Your Profile</h1>
      <p>Update your business information and pricing</p>
    </div>
    
    <?php if(isset($success)): ?>
      <div class="success-message">
        <i class="fa-solid fa-check-circle"></i> <?php echo $success; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST">
      <!-- Contact Information -->
      <div class="form-group">
        <label>Contact Information</label>
        <div class="input-with-icon">
          <i class="fa-solid fa-phone"></i>
          <input type="text" name="phone" value="<?php echo htmlspecialchars($vendor['phone']); ?>" placeholder="Phone Number" required>
        </div>
      </div>
      
      <div class="form-group">
        <div class="input-with-icon">
          <i class="fa-solid fa-location-dot"></i>
          <input type="text" name="location" value="<?php echo htmlspecialchars($vendor['location']); ?>" placeholder="Your Service Area/Location" required>
        </div>
      </div>
      
      <!-- Pricing Information -->
      <div class="form-group">
        <label>Pricing Information</label>
        <div class="pricing-grid">
          <div class="pricing-card">
            <label>
              <i class="fa-solid fa-clock"></i>
              Hourly Rate
            </label>
            <div style="position: relative;">
              <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #059669; font-weight: 600;">TK</span>
              <input type="number" name="hourly_rate" value="<?php echo $vendor['hourly_rate']; ?>" step="0.01" min="0" placeholder="0.00" style="padding-left: 40px;" required>
            </div>
          </div>
          
          <div class="pricing-card">
            <label>
              <i class="fa-solid fa-tag"></i>
              Minimum Charge
            </label>
            <div style="position: relative;">
              <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #059669; font-weight: 600;">TK</span>
              <input type="number" name="min_charge" value="<?php echo $vendor['min_charge']; ?>" step="0.01" min="0" placeholder="0.00" style="padding-left: 40px;" required>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Service Description -->
      <div class="form-group">
        <label>
          <i class="fa-solid fa-file-alt"></i>
          Service Description
        </label>
        <div class="input-with-icon">
          <i class="fa-solid fa-pen"></i>
          <textarea name="service_description" placeholder="Describe your services, expertise, experience, and what makes you unique..." required><?php echo htmlspecialchars($vendor['service_description']); ?></textarea>
        </div>
        <small style="color: #666; margin-top: 5px; display: block;">
          This will help customers understand your services better
        </small>
      </div>
      
      <button type="submit" class="btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> Update Profile
      </button>
    </form>
  </div>
</body>
</html>