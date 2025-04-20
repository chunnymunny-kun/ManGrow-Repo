<?php

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
    require 'database.php'; // Your database connection file
    
    // 1. Get all accounts with matching import date
    $query = "SELECT * FROM tempaccstbl WHERE import_date = ? AND (is_verified != 'Verified' OR is_verified IS NULL)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $formattedDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 2. Process each account
    while($account = $result->fetch_assoc()) {
        sendVerificationEmail($account);
    }
    
    echo "Verification emails sent successfully!";
}

function sendVerificationEmail($account) {
    $to = $account['personal_email'];
    $subject = "Account Verification - Mangrove Website";
    $verificationToken = bin2hex(random_bytes(32));
    
    // Store the token first
    storeVerificationToken($account['tempacc_id'], $verificationToken);
    
    // Email content
    $message = "
    <html>
    <head>
        <title>Account Verification</title>
    </head>
    <body>
        <!-- Your email HTML content here -->
        <a href='http://localhost:3000/verify.php?token=$verificationToken'>
            Verify My Account
        </a>
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
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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