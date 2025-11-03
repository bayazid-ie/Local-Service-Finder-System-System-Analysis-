<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $vendor_id = $_SESSION['vendor_id'];
    
    $check_sql = "SELECT id FROM bookings WHERE id = ? AND vendor_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $booking_id, $vendor_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update booking status
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        
        if ($stmt->execute()) {
            header("Location: vendor-bookings.php?updated=success");
        } else {
            echo "Error updating booking: " . $stmt->error;
        }
    } else {
        echo "Unauthorized access!";
    }
}
?>