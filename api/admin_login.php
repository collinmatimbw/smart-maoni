<?php
// api/admin_login.php - Simple login without 2FA
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../conn.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$username = isset($data['username']) ? trim($data['username']) : '';
$password = isset($data['password']) ? $data['password'] : '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password required']);
    exit();
}

// Query to check admin
$sql = "SELECT id, username, password, fullname, email, phone, role, status 
        FROM user_admin WHERE username = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit();
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit();
}

// Start session
session_start();
$_SESSION['admin_logged_in'] = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'fullname' => $user['fullname'],
    'email' => $user['email'],
    'phone' => $user['phone'],
    'role' => $user['role'],
    'login_time' => time()
];

// Update last seen
$update_sql = "UPDATE user_admin SET last_seen = NOW() WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $user['id']);
$update_stmt->execute();

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'username' => $user['username'],
        'fullname' => $user['fullname'],
        'role' => $user['role']
    ]
]);

$conn->close();
?>