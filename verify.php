<?php
// verify.php
require 'database.php';

// Start session if you want to show success messages
session_start();

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Find account with this token
        $query = "SELECT * FROM tempaccstbl WHERE verification_token = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $account = $result->fetch_assoc();
            
            // Mark as verified
            $update = "UPDATE tempaccstbl SET is_verified = 'Verified', verification_token = NULL WHERE tempacc_id = ?";
            $stmt = $connection->prepare($update);
            $stmt->bind_param("i", $account['tempacc_id']);
            $stmt->execute();
            
            // Success message
            $_SESSION['verification_message'] = "Account verified successfully! You can now login.";
            
            // Redirect to login page or show success page
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['verification_error'] = "Invalid verification token or account already verified.";
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['verification_error'] = "Error during verification: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['verification_error'] = "No verification token provided.";
    header("Location: login.php");
    exit();
}
?>