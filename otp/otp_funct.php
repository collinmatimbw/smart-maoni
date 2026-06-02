<?php
// send_otp_email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendOTPEmail($recipient_email, $otp_code) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Badilisha na email yako
        $mail->Password   = 'your-app-password';    // Badilisha na app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Sender and recipient
        $mail->setFrom('your-email@gmail.com', 'Admin System');
        $mail->addAddress($recipient_email);
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Admin Login OTP Code';
        $mail->Body    = "Your OTP code for admin login is: <b>$otp_code</b><br>This code expires in 5 minutes.";
        $mail->AltBody = "Your OTP code for admin login is: $otp_code";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Generate random 6-digit OTP
function generateOTP() {
    return rand(100000, 999999);
}
?>