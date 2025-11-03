<?php
// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db_connect.php';
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
   
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (empty($errors)) {
        // Insert into database
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);
        
        if ($stmt->execute()) {
            $success = "Thank you for your message! We'll get back to you soon.";
            
            
            $to = "support@localservice.com"; // Your email
            $subject = "New Contact Form Message - Local Service Finder";
            $email_message = "Name: $name\nEmail: $email\n\nMessage:\n$message";
            $headers = "From: $email";
            
            mail($to, $subject, $email_message, $headers);
            
    
            $name = $email = $message = '';
        } else {
            $errors[] = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <!-- Contact Section -->
  <section class="contact">
    <h1>Contact Us</h1>
    <p>If you have any questions or need help, feel free to reach out to us.</p>

    <!-- Display Messages -->
    <?php if(isset($success)): ?>
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin: 20px auto; max-width: 600px; border: 1px solid #c3e6cb;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($errors)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin: 20px auto; max-width: 600px; border: 1px solid #f5c6cb;">
            <?php foreach($errors as $error): ?>
                <p style="margin: 5px 0;"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="contact-container">
      <!-- Contact Info -->
      <div class="contact-info">
        <div class="info-box">
          <i class="fa-solid fa-phone"></i>
          <h3>Phone</h3>
          <p>+880 1234-567890</p>
        </div>
        <div class="info-box">
          <i class="fa-solid fa-envelope"></i>
          <h3>Email</h3>
          <p>support@localservice.com</p>
        </div>
        <div class="info-box">
          <i class="fa-solid fa-location-dot"></i>
          <h3>Address</h3>
          <p>Mirpur, Dhaka, Bangladesh</p>
        </div>
      </div>

      <!-- Contact Form -->
      <form class="contact-form" method="POST" action="">
        <input type="text" name="name" placeholder="Your Name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
        <input type="email" name="email" placeholder="Your Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        <textarea name="message" placeholder="Your Message" rows="6" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
        <button type="submit">Send Message</button>
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
</body>
</html>