<?php
// Smart Feedback - Setup Helper
// Run once after importing the SQL and placing files in htdocs
// Access: http://localhost/smart_feedback/setup.php

$step = isset($_GET['step']) ? $_GET['step'] : 'welcome';
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'smart_maoni';

// Try to connect
$conn = @new mysqli($db_host, $db_user, $db_password, $db_name);
$db_ok = !$conn->connect_error;

// Handle password reset
$reset_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $new_pass = $_POST['new_password'] ?? 'admin123';
    $hash = password_hash($new_pass, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE user_admin SET password = ? WHERE username = 'admin'");
    $stmt->bind_param("s", $hash);
    if ($stmt->execute()) {
        $reset_msg = "Password reset to: <strong>$new_pass</strong>";
    } else {
        $reset_msg = "Error resetting password: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Feedback - Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6"><i class="fas fa-database mr-2"></i> Smart Feedback Setup</h1>

        <?php if ($reset_msg): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg mb-4"><?= $reset_msg ?></div>
        <?php endif; ?>

        <!-- Database Status -->
        <div class="bg-slate-800 p-6 rounded-2xl mb-6 border border-slate-700">
            <h2 class="text-xl font-bold mb-4">1. Database Connection</h2>
            <?php if ($db_ok): ?>
                <div class="bg-green-500/20 text-green-400 p-3 rounded-lg font-bold">
                    Connected to <strong><?= $db_name ?></strong>
                </div>
                <?php
                $tables = $conn->query("SHOW TABLES");
                $count = $tables ? $tables->num_rows : 0;
                ?>
                <p class="text-slate-400 mt-2">Tables found: <strong><?= $count ?></strong></p>
            <?php else: ?>
                <div class="bg-red-500/20 text-red-400 p-3 rounded-lg font-bold">
                    Connection failed: <?= $conn->connect_error ?>
                </div>
                <p class="text-slate-400 mt-2">Make sure XAMPP is running and the database has been imported.</p>
            <?php endif; ?>
        </div>

        <?php if ($db_ok): ?>
        <!-- Admin Password Reset -->
        <div class="bg-slate-800 p-6 rounded-2xl mb-6 border border-slate-700">
            <h2 class="text-xl font-bold mb-4">2. Reset Admin Password</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">New Password</label>
                    <input type="text" name="new_password" value="admin123" 
                           class="w-full bg-slate-900 border border-slate-700 text-white p-3 rounded-xl">
                </div>
                <button type="submit" name="reset_password" 
                        class="bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition">
                    Reset Password
                </button>
            </form>
        </div>

        <!-- Quick Info -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
            <h2 class="text-xl font-bold mb-4">3. Quick Info</h2>
            <?php
            $admin = $conn->query("SELECT username, fullname, role, email FROM user_admin WHERE username='admin'")->fetch_assoc();
            $feedbacks = $conn->query("SELECT COUNT(*) as c FROM feedbacks")->fetch_assoc()['c'];
            ?>
            <ul class="space-y-2 text-slate-300">
                <li><strong>Admin:</strong> <?= $admin['fullname'] ?> (<?= $admin['username'] ?>)</li>
                <li><strong>Role:</strong> <?= $admin['role'] ?></li>
                <li><strong>Total Feedbacks:</strong> <?= $feedbacks ?></li>
                <li><strong>Site URL:</strong> <a href="index.php" class="text-blue-400 underline">http://localhost/smart_feedback/</a></li>
            </ul>
        </div>
        <?php endif; ?>

        <p class="text-slate-500 text-sm mt-8">
            <i class="fas fa-info-circle mr-1"></i> 
            After setup, delete this file or move it outside the web root for security.
        </p>
    </div>
</body>
</html>
<?php if ($conn && !$conn->connect_error) $conn->close(); ?>