<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

  
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = ?, phone = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $phone, $hashedPassword, $user_id);
    } else {
        $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $phone, $user_id);
    }

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
    $stmt->close();
}

// Fetch user data
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
    <title>User Profile - Local Service Finder</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: #f7f8fa;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .profile-container {
            width: 60%;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 {
            color: #2563eb;
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .profile-container input {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .profile-container button {
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-size: 1rem;
        }
        .profile-container button:hover {
            background: #1e40af;
        }
        .alert {
            padding: 10px;
            color: #fff;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
        .success { background: #38a169; }
        .error { background: #e53e3e; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="profile-container">
    <h2><i class="fa-solid fa-user-circle"></i> Edit Profile</h2>

    <?php if (isset($success)): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Full Name" required>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" disabled>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Phone" required>
        <input type="password" name="password" placeholder="New Password (leave empty if not changing)">
        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
