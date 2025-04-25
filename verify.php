<?php
require 'database.php';
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
            
            // Begin transaction
            $connection->begin_transaction();
            
            try {
                // Hash the password before storing
                $fullname = htmlspecialchars($account['firstname']) . ' ' . htmlspecialchars($account['lastname']);
                $hashedPassword = password_hash($account['password'], PASSWORD_DEFAULT);
                
                // Insert into main accounts table with hashed password
                $insertQuery = "INSERT INTO accountstbl (
                    fullname,
                    email, 
                    personal_email, 
                    password,
                    barangay, 
                    city_municipality, 
                    accessrole, 
                    organization,
                    date_registered
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param(
                    "ssssssss",
                    $fullname,  // Use the combined name here
                    htmlspecialchars($account['email']),
                    htmlspecialchars($account['personal_email']),
                    $hashedPassword,
                    htmlspecialchars($account['barangay']),
                    htmlspecialchars($account['city_municipality']),
                    htmlspecialchars($account['accessrole']),
                    htmlspecialchars($account['organization'])
                );
                $stmt->execute();
                
                // Delete from temporary table
                $deleteQuery = "DELETE FROM tempaccstbl WHERE tempacc_id = ?";
                $stmt = $connection->prepare($deleteQuery);
                $stmt->bind_param("i", $account['tempacc_id']);
                $stmt->execute();
                
                $connection->commit();
                
                $_SESSION['response'] = [
                    'status' => 'success',
                    'msg' => "Account verified and activated successfully! You can now login."
                ];
                
            } catch (Exception $e) {
                $connection->rollback();
                throw $e;
            }
            
            header("Location: login.php");
            exit();
            
        } else {
            $_SESSION['response'] = [
                'status' => 'error',
                'msg' => "Invalid verification token or account already verified."
            ];
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['response'] = [
            'status' => 'error',
            'msg' => "Error during verification: " . $e->getMessage()
        ];
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['response'] = [
        'status' => 'error',
        'msg' => "No verification token provided."
    ];
    header("Location: login.php");
    exit();
}
?>