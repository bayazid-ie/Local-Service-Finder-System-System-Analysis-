<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vendor_id = $_SESSION['vendor_id'];
    $booking_id = $_POST['booking_id'];
    $cancellation_reason = $_POST['cancellation_reason'];
    $additional_notes = $_POST['additional_notes'];
    
    // Combine reason and notes
    $full_reason = "Vendor: " . $cancellation_reason;
    if (!empty($additional_notes)) {
        $full_reason .= " - " . $additional_notes;
    }
    
    $check_sql = "SELECT b.*, 
                         TIMESTAMPDIFF(HOUR, NOW(), b.booking_date) as hours_until_booking 
                  FROM bookings b 
                  WHERE b.id = ? AND b.vendor_id = ? AND b.status IN ('pending', 'confirmed')";
    $check_stmt = $conn->prepare($check_sql);
    
    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $check_stmt->bind_param("ii", $booking_id, $vendor_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $booking = $check_result->fetch_assoc();
        
        $can_cancel = false;
        $message = "";
        
        if ($booking['status'] == 'pending') {
            $can_cancel = true;
        } elseif ($booking['status'] == 'confirmed' && $booking['hours_until_booking'] > 2) {
            $can_cancel = true;
        } elseif ($booking['status'] == 'confirmed' && $booking['hours_until_booking'] <= 2) {
            $can_cancel = false;
            $message = "Cannot cancel within 2 hours of service time";
        }
        
        if ($can_cancel) {
            $update_sql = "UPDATE bookings SET 
                          status = 'cancelled', 
                          cancellation_reason = ?,
                          cancelled_at = NOW(),
                          cancelled_by = 'vendor'
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            
            if (!$update_stmt) {
                die("Prepare failed: " . $conn->error);
            }
            
            $update_stmt->bind_param("si", $full_reason, $booking_id);
            
            if ($update_stmt->execute()) {
                header("Location: vendor-bookings.php?cancelled=success");
                exit();
            } else {
                echo "Error cancelling booking: " . $update_stmt->error;
            }
        } else {
            header("Location: vendor-bookings.php?error=" . urlencode($message));
            exit();
        }
    } else {
        echo "Booking not found or you don't have permission to cancel it!";
    }
} else {
    header("Location: vendor-bookings.php");
    exit();
}
?>