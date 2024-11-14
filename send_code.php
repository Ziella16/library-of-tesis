<?php
// Include PHPMailer files
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rest of your PHP code

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = rand(100000, 999999);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'userregisteration');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Save code to database
    $stmt = $conn->prepare('INSERT INTO verification_codes (email, code, expires_at) VALUES (?, ?, ?)');
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $stmt->bind_param('sss', $email, $code, $expires_at);

    if ($stmt->execute()) {
        // PHPMailer setup
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'zulkiflieverdeen@gmail.com'; // SMTP username
            $mail->Password = 'jetwuarqchcxxfhy'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            $mail->setFrom('zulkiflieverdeen@gmail.com', 'Mailer');
            $mail->addAddress($email); // Add a recipient
            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = 'Your verification code is: ' . $code;

            $mail->send();
            echo 'Verification code sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Failed to save verification code.';
    }

    $stmt->close();
    $conn->close();
}

