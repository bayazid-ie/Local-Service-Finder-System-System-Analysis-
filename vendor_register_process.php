<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $service_type = $_POST['service_type'];
    $location = $_POST['location'];
    
    // Check if email already exists
    $check_sql = "SELECT id FROM vendors WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        echo "Email already registered!";
        exit();
    }
    
    // Insert new vendor
    $sql = "INSERT INTO vendors (name, email, password, phone, service_type, location) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $password, $phone, $service_type, $location);
    
    if ($stmt->execute()) {
        // Get the new vendor ID
        $vendor_id = $stmt->insert_id;
        $_SESSION['vendor_id'] = $vendor_id;
        $_SESSION['vendor_name'] = $name;
        $_SESSION['vendor_email'] = $email;
        
        header("Location: vendor-dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>