<?php
require_once '../conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get total feedbacks
$total_result = $conn->query("SELECT COUNT(*) as count FROM feedbacks");
$total_feedbacks = $total_result ? $total_result->fetch_assoc()['count'] : 0;

// Get sentiment counts
$positive = 0;
$neutral = 0;
$negative = 0;
$sentiment_result = $conn->query("SELECT sentiment, COUNT(*) as count FROM feedbacks GROUP BY sentiment");
if ($sentiment_result) {
    while($row = $sentiment_result->fetch_assoc()) {
        if ($row['sentiment'] == 'Positive') $positive = $row['count'];
        if ($row['sentiment'] == 'Neutral') $neutral = $row['count'];
        if ($row['sentiment'] == 'Negative') $negative = $row['count'];
    }
}

// Get branches
$branches = [];
$branches_result = $conn->query("SELECT * FROM branches WHERE status = 'active' ORDER BY branch_name");
if ($branches_result) {
    while($row = $branches_result->fetch_assoc()) {
        $branches[] = $row;
    }
}

// Get QR codes
$qrcodes = [];
$qr_result = $conn->query("SELECT q.*, b.branch_name FROM qr_codes q LEFT JOIN branches b ON q.branch_id = b.id WHERE q.is_active = 1");
if ($qr_result) {
    while($row = $qr_result->fetch_assoc()) {
        $qrcodes[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'stats' => [
        'total_feedbacks' => $total_feedbacks,
        'positive' => $positive,
        'neutral' => $neutral,
        'negative' => $negative,
        'total_branches' => count($branches)
    ],
    'branches' => $branches,
    'qrcodes' => $qrcodes
]);

$conn->close();
?>