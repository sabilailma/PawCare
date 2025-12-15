<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$service_id = $_POST['service_id'];
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];
$tag = $_POST['tag'] ?? null;

// UPLOAD FOTO
$photoFile = null;
if (!empty($_FILES['photo']['name'])) {
    $targetDir = "uploads/reviews/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . "_" . basename($_FILES['photo']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $photoFile = $targetFile;
    }
}

// Insert review
$stmt = $pdo->prepare("INSERT INTO service_reviews (user_id, service_id, rating, review_text, tag, photo) 
                       VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $service_id, $rating, $review_text, $tag, $photoFile]);

// Update average rating
$stmt2 = $pdo->prepare("
    UPDATE services SET rating = (
        SELECT AVG(rating) FROM service_reviews WHERE service_id = ?
    ) WHERE id = ?
");
$stmt2->execute([$service_id, $service_id]);

header("Location: service_detail.php?id=" . $service_id);
exit;
