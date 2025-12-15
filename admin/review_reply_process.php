<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$id = $_POST['id'];
$reply = $_POST['admin_reply'];

$stmt = $pdo->prepare("UPDATE service_reviews SET admin_reply = ? WHERE id = ?");
$stmt->execute([$reply, $id]);

header("Location: review_list.php");
exit;
?>
