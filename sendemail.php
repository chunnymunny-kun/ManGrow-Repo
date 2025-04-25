<?php
session_start();

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['sendEmail'])) {
    $date = $_POST['date'];
    $formattedDate = (new DateTime($date))->format('m-d-Y');
    
    // Database connection
    require 'database.php';
    
    $query = "SELECT * FROM tempaccstbl WHERE import_date = ? AND (is_verified != 'Verified' OR is_verified IS NULL)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $formattedDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $accountCount = $result->num_rows;
    
    if($accountCount > 0){
        while($account = $result->fetch_assoc()) {
            sendVerificationEmail($account);
        }
        
        $_SESSION['response'] = [
            'status' => 'success',
            'msg' => "Verification emails sent successfully to $accountCount accounts!"
        ];
    }
    else {
        $_SESSION['response'] = [
            'status' => 'error',
            'msg' => 'There were no accounts detected sharing the same date from the selection. Please try again.'
        ];
    }
    
    header("Location: adminpage.php");
    exit();
}

function sendVerificationEmail($account) {
    $to = $account['personal_email'];
    $subject = "Account Verification - Mangrove Website";
    $verificationToken = bin2hex(random_bytes(32));
    
    // Store the token first
    storeVerificationToken($account['tempacc_id'], $verificationToken);
    //Get user account details
    


    // Email content
    $message = "
    <html>
    <head>
        <title>Account Verification - ManGrow</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { color: #2E8B57; text-align: center; }
            .logo { max-width: 150px; margin-bottom: 20px; }
            .button { 
                display: inline-block; 
                background-color: #2E8B57; 
                color: white !important; 
                padding: 12px 24px; 
                text-decoration: none; 
                border-radius: 4px; 
                font-weight: bold; 
                margin: 20px 0;
            }
            .footer { 
                margin-top: 30px; 
                font-size: 12px; 
                color: #777; 
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1 class='header'>Welcome to ManGrow!</h1>
        
        <p>Dear User,</p>
        
        <p>Thank you for registering with <strong>ManGrow: Mangrove Conservation and Eco Tracking System</strong>. 
        To complete your registration and activate your account, please verify your email address.</p>
        
        <div class='account-details'>
            <h3>Your Account Details:</h3>
            <p><strong>First Name:</strong> ".htmlspecialchars($account['firstname'])."</p>
            <p><strong>Last Name:</strong> ".htmlspecialchars($account['lastname'])."</p>
            <p><strong>Email:</strong> ".htmlspecialchars($account['email'])."</p>
            <p><strong>Temporary Password:</strong> ".htmlspecialchars($account['password'])."</p>
        </div>

        <div style='text-align: center;'>
            <a href='http://localhost:3000/verify.php?token=$verificationToken' class='button'>
                Verify My Account
            </a>
        </div>
        
        <p>If the button above doesn't work, copy and paste this link into your browser:</p>
        <p><small>http://localhost:3000/verify.php?token=$verificationToken</small></p>
        
        <p>This verification link will expire in 72 hours.</p>
        
        <div class='footer'>
            <p>If you didn't request this account, please ignore this email.</p>
            <p>&copy; ".date('Y')." ManGrow System. All rights reserved.</p>
        </div>
    </body>
    </html>
    ";

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'jetrimentimentalistically@gmail.com';                     //SMTP username
        $mail->Password   = 'eywquyertetbgyhf';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption ENCRYPTION_SMTPS
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('jetrimentimentalistically@gmail.com', 'ManGrow');
        $mail->addAddress($to);               //Name is optional

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    =  $message;

        $mail->send();
        // echo For debugging
        // echo 'Message has been sent';
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}        

function storeVerificationToken($accountId, $token) {
    global $connection;
    
    $query = "UPDATE tempaccstbl SET verification_token = ?, is_verified = 'Pending' WHERE tempacc_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("si", $token, $accountId);
    $stmt->execute();
}
?>

