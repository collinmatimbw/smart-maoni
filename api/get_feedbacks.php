<?php
require_once '../conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$sql = "SELECT f.*, b.branch_name, b.branch_location 
        FROM feedbacks f 
        LEFT JOIN branches b ON f.branch_id = b.id 
        ORDER BY f.created_at DESC";

$result = $conn->query($sql);
$feedbacks = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'data' => $feedbacks,
    'total' => count($feedbacks)
]);

$conn->close();
?>