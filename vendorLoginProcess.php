<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM vendors WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $vendor = $result->fetch_assoc();
        
        if (password_verify($password, $vendor['password'])) {
            $_SESSION['vendor_id'] = $vendor['id'];
            $_SESSION['vendor_name'] = $vendor['name'];
            $_SESSION['vendor_email'] = $vendor['email'];
            $_SESSION['vendor_service'] = $vendor['service_type'];
            
            header("Location: vendor-dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Vendor not found!";
    }
}
?>