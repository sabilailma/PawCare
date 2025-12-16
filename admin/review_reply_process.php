<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/db.php';
if (!$review_id || !$admin_reply) {
    die("Data tidak lengkap.");
}

// Update balasan admin
$stmt = $pdo->prepare("UPDATE service_reviews SET admin_reply = ? WHERE id = ?");
$stmt->execute([$admin_reply, $review_id]);

// Ambil user_id & service_id untuk notifikasi
$stmt2 = $pdo->prepare("SELECT user_id, service_id FROM service_reviews WHERE id = ?");
$stmt2->execute([$review_id]);
$review = $stmt2->fetch();

if ($review) {
    require_once __DIR__ . '/../includes/functions.php';
    notify($review['user_id'], 'Balasan Review Layanan', 'Admin telah membalas review layanan Anda.');
}

// Redirect kembali ke halaman list review admin
header("Location: review_list.php");
exit;
?>
