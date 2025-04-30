<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Initialize response
    $_SESSION['response'] = ['status' => 'error', 'msg' => ''];

    // Get user ID from session
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        $_SESSION['response']['msg'] = 'User not logged in';
        header("Location: profileform.php");
        exit();
    }

    $organization = htmlspecialchars(trim($_POST['organization'] ?? ''));
    $bio = htmlspecialchars(trim($_POST['bio'] ?? ''));
    
    // Process password to be updated if provided
    $password_update = '';
    $password_param = null;
    $types = 'ssi';
    
    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['confirm-password']) {
            $_SESSION['response']['msg'] = "Password and confirmation don't match";
            header("Location: profileform.php");
            exit();
        }
        //only requires letter and numbers 
        if (strlen($_POST['password']) < 8 || !preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])) {
            $_SESSION['response']['msg'] = "Password must be 8-20 chars with letters and numbers";
            header("Location: profileform.php");
            exit();
        }
        
        $password_update = ', password = ?';
        $password_param = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $types = 'sssi';
    }

    // Build query and parameters
    $query = "UPDATE accountstbl SET organization = ?, bio = ? $password_update WHERE account_id = ?";
    
    try {
        $stmt = $connection->prepare($query);
        
        if ($password_param !== null) {
            $stmt->bind_param($types, $organization, $bio, $password_param, $user_id);
        } else {
            $stmt->bind_param($types, $organization, $bio, $user_id);
        }
        
        if ($stmt->execute()) {
            // Update session variables
            $_SESSION['organization'] = $organization;
            $_SESSION['bio'] = $bio;
            
            $_SESSION['response'] = [
                'status' => 'success',
                'msg' => 'Profile updated successfully!'
            ];
        } else {
            $_SESSION['response']['msg'] = 'Failed to update profile';
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['response']['msg'] = 'Database error: ' . $e->getMessage();
    }

    header("Location: profileform.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>