<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id'];
    $cancellation_reason = $_POST['cancellation_reason'];
    $additional_notes = $_POST['additional_notes'];
    

    $full_reason = $cancellation_reason;
    if (!empty($additional_notes)) {
        $full_reason .= " - " . $additional_notes;
    }
    
    // Verify user for this booking and can cancel 
    $check_sql = "SELECT b.*, 
                         TIMESTAMPDIFF(HOUR, NOW(), b.booking_date) as hours_until_booking 
                  FROM bookings b 
                  WHERE b.id = ? AND b.user_id = ? AND b.status IN ('pending', 'confirmed')";
    $check_stmt = $conn->prepare($check_sql);
    
    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $booking = $check_result->fetch_assoc();
        
        // Check cancellation rules
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
            // Update booking status to cancelled
            $update_sql = "UPDATE bookings SET 
                          status = 'cancelled', 
                          cancellation_reason = ?,
                          cancelled_at = NOW(),
                          cancelled_by = 'user'
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            
            if (!$update_stmt) {
                die("Prepare failed: " . $conn->error);
            }
            
            $update_stmt->bind_param("si", $full_reason, $booking_id);
            
            if ($update_stmt->execute()) {
                
                $vendor_id = $booking['vendor_id'];
                $vendor_notification_sql = "INSERT INTO notifications (vendor_id, title, message, type) 
                                           VALUES (?, 'Booking Cancelled', 
                                           CONCAT('Booking #', ?, ' has been cancelled by customer. Reason: ', ?), 
                                           'system')";
                $vendor_notification_stmt = $conn->prepare($vendor_notification_sql);
                
                if ($vendor_notification_stmt) {
                    $vendor_notification_stmt->bind_param("iis", $vendor_id, $booking_id, $full_reason);
                    $vendor_notification_stmt->execute();
                }
                
                header("Location: book-service.php?cancelled=success");
                exit();
            } else {
                echo "Error cancelling booking: " . $update_stmt->error;
            }
        } else {
            header("Location: book-service.php?error=" . urlencode($message));
            exit();
        }
    } else {
        echo "Booking not found or you don't have permission to cancel it!";
    }
} else {
    header("Location: book-service.php");
    exit();
}
?>