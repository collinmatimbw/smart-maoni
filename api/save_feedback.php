<?php
// save_feedback.php - FIXED VERSION
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in output

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if it's POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Method not allowed. Use POST"]);
    exit();
}

// Include database connection
require_once '../conn.php';

// Check if conn.php worked
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

// Get POST data
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON data received"]);
    exit();
}

// Validate required fields
if (!isset($data['rating']) || $data['rating'] == 0) {
    echo json_encode(["success" => false, "message" => "Rating is required"]);
    exit();
}

// Prepare data
$branch_id = isset($data['branch_id']) ? (int)$data['branch_id'] : 1;
$customer_name = isset($data['customer_name']) && !empty($data['customer_name']) ? $data['customer_name'] : 'Anonymous';
$customer_email = isset($data['customer_email']) ? $data['customer_email'] : '';
$rating = (int)$data['rating'];
$category = isset($data['category']) ? $data['category'] : 'General';
$comment = isset($data['comment']) ? $data['comment'] : '';

// Determine sentiment
if ($rating >= 4) {
    $sentiment = 'Positive';
} elseif ($rating <= 2) {
    $sentiment = 'Negative';
} else {
    $sentiment = 'Neutral';
}

// Generate promo code
$promo_code = 'SMART-' . strtoupper(substr(uniqid(), -6) . rand(100, 999));

// Start transaction
$conn->begin_transaction();

try {
    // Insert feedback
    $sql = "INSERT INTO feedbacks (branch_id, customer_name, customer_email, rating, category, comment, sentiment, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ississs", $branch_id, $customer_name, $customer_email, $rating, $category, $comment, $sentiment);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $feedback_id = $stmt->insert_id;
    $stmt->close();
    
    // Insert reward
    $reward_sql = "INSERT INTO rewards (promo_code, discount_percent, feedback_id, customer_name, customer_email, created_at) 
                   VALUES (?, 15, ?, ?, ?, NOW())";
    
    $reward_stmt = $conn->prepare($reward_sql);
    if (!$reward_stmt) {
        throw new Exception("Reward prepare failed: " . $conn->error);
    }
    
    $reward_stmt->bind_param("siss", $promo_code, $feedback_id, $customer_name, $customer_email);
    
    if (!$reward_stmt->execute()) {
        throw new Exception("Reward execute failed: " . $reward_stmt->error);
    }
    
    $reward_stmt->close();
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        "success" => true,
        "message" => "Feedback saved successfully!",
        "promo_code" => $promo_code,
        "feedback_id" => $feedback_id,
        "sentiment" => $sentiment
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

$conn->close();
?>