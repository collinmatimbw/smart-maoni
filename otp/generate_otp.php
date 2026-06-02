<?php
session_start();
require_once 'otp_funct.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];

$otp = generateOTP();
$_SESSION['admin_otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // 5 minutes

if (sendOTPEmail($email, $otp)) {
    echo json_encode(['success' => true, 'otp' => $otp]); // Kwa testing tu
} else {
    echo json_encode(['success' => false]);
}
?>