<?php
require_once '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $qrcodes = [];
    $qr_result = $conn->query("SELECT q.*, b.branch_name FROM qr_codes q LEFT JOIN branches b ON q.branch_id = b.id ORDER BY q.created_at DESC");
    if ($qr_result) {
        while($row = $qr_result->fetch_assoc()) {
            $qrcodes[] = $row;
        }
    }
    echo json_encode(['success' => true, 'data' => $qrcodes]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create new QR code
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['branch_id'])) {
        echo json_encode(['success' => false, 'message' => 'Branch ID required']);
        exit();
    }
    
    $branch_id = (int)$data['branch_id'];
    $qr_data = "https://smartfeedback.com/branch/" . $branch_id;
    $qr_image = "qr_branch_" . $branch_id . ".png";
    
    $sql = "INSERT INTO qr_codes (branch_id, qr_code_data, qr_code_image, scan_count) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $branch_id, $qr_data, $qr_image);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'QR code created', 'qr_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Creation failed']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update QR code scan count
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['qr_id'])) {
        echo json_encode(['success' => false, 'message' => 'QR ID required']);
        exit();
    }
    
    $qr_id = (int)$data['qr_id'];
    $sql = "UPDATE qr_codes SET scan_count = scan_count + 1, last_scanned_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $qr_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Scan count updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }
}

$conn->close();
?>