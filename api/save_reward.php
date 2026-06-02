<?php
require_once '../conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['promo_code'])) {
    echo json_encode(['success' => false, 'message' => 'Promo code required']);
    exit();
}

$promo_code = $conn->real_escape_string($data['promo_code']);
$is_used = isset($data['is_used']) ? (int)$data['is_used'] : 0;

$sql = "UPDATE rewards SET is_used = ?, used_at = NOW() WHERE promo_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $is_used, $promo_code);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Reward updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$conn->close();
?>