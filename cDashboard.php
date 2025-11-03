<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - Local Service Finder</title>
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

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 50px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 10;
    }
    .navbar .logoLink {
      text-decoration: none;
    }

    .navbar .logo {
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 1.2rem;
      color: #2563eb;
    }
    
    .navbar .logoTxt {
      color: black;
    }
      
    .navbar .logo i {
      margin-right: 8px;
      color: #2563eb;
      font-size: 1.3rem;
    }

    .navbar nav a {
      margin: 0 12px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      position: relative;
      transition: color 0.3s ease;
    }

    .navbar nav a::after {
      content: "";
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0%;
      height: 2px;
      background: #2563eb;
      transition: width 0.3s ease;
    }

    .navbar nav a:hover {
      color: #2563eb;
    }

    .navbar nav a:hover::after {
      width: 100%;
    }

    .navbar .login-btn {
      color: #fff !important;
      background: #2563eb;
      padding: 8px 18px;
      border-radius: 6px;
      transition: background 0.3s;
    }

    .navbar .login-btn:hover {
      background: #1e40af;
    }

    /* Main Content - No sidebar */
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

    .card-link {
      text-decoration: none;
      color: inherit;
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

    /* Responsive */
    @media (max-width: 700px) {
      .navbar {
        flex-direction: column;
        height: auto;
        padding: 10px 20px;
      }
      .main-content {
        padding: 100px 20px;
      }
    }
  </style>
</head>
<body>
  
  <?php include 'navbar.php'; ?>

 
  <div class="main-content">
    <div class="dashboard-header">
      <h2>Welcome back, <?php echo htmlspecialchars($name); ?> 👋</h2>
      <div class="user-info">
        <i class="fa-solid fa-user-circle"></i>
        <span><?php echo htmlspecialchars($email); ?></span>
      </div>
    </div>

    <div class="card-container">
      <a href="book-service.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-clipboard-list"></i>
          <h3>My Bookings</h3>
          <p>View and manage all your service bookings</p>
        </div>
      </a>
      

      
      <a href="profile.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-user"></i>
          <h3>Profile</h3>
          <p>Update your account info and password</p>
        </div>
      </a>
      
      
      <a href="cLogout.php" class="card-link">
        <div class="card">
          <i class="fa-solid fa-right-from-bracket"></i>
          <h3>Logout</h3>
          <p>Securely logout from your account</p>
        </div>
      </a>
    </div>
  </div>
</body>
</html>