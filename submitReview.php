<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $vendor_id = $_POST['vendor_id'];
    $booking_id = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Verify user owns this booking and it's completed
    $check_sql = "SELECT id FROM bookings WHERE id = ? AND user_id = ? AND status = 'completed'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Insert review
        $sql = "INSERT INTO reviews (user_id, vendor_id, booking_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $user_id, $vendor_id, $booking_id, $rating, $comment);
        
        if ($stmt->execute()) {
            
            // Update vendor rating and review 
            updateVendorRating($conn, $vendor_id);
            
            header("Location: book-service.php?review=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid booking or booking not completed!";
    }
}

function updateVendorRating($conn, $vendor_id) {
    // Calculate new average rating
    $avg_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM reviews WHERE vendor_id = ?";
    $avg_stmt = $conn->prepare($avg_sql);
    $avg_stmt->bind_param("i", $vendor_id);
    $avg_stmt->execute();
    $avg_result = $avg_stmt->get_result();
    $rating_data = $avg_result->fetch_assoc();
    
    // Update vendor table
    $update_sql = "UPDATE vendors SET avg_rating = ?, total_reviews = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dii", $rating_data['avg_rating'], $rating_data['total_reviews'], $vendor_id);
    $update_stmt->execute();
}
?>