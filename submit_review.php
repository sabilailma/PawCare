<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged()) {
    header("Location: login.php");
    exit;
}

// Ambil data user
$user_id = uid();
$service_id = intval($_POST['service_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$review_text = trim($_POST['review_text'] ?? '');
$tag = trim($_POST['tag'] ?? null);

// Validasi
if (!$service_id || $rating < 1 || $rating > 5 || !$review_text) {
    flash('error', 'Data review tidak lengkap atau tidak valid.');
    header("Location: service_detail.php?id=" . $service_id);
    exit;
}

// Upload foto jika ada
$photoFile = null;
if (!empty($_FILES['photo']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($_FILES['photo']['type'], $allowedTypes)) {
        $targetDir = "uploads/reviews/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('rev_', true) . "." . $ext;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photoFile = $fileName; // simpan nama file saja, folder sudah fix
        }
    }
}

// Insert review ke database
$stmt = $pdo->prepare("
    INSERT INTO service_reviews (user_id, service_id, rating, review_text, tag, photo) 
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([$user_id, $service_id, $rating, $review_text, $tag, $photoFile]);

// Update rata-rata rating di tabel services
$stmt2 = $pdo->prepare("
    UPDATE services SET rating = (
        SELECT AVG(rating) FROM service_reviews WHERE service_id = ?
    ) WHERE id = ?
");
$stmt2->execute([$service_id, $service_id]);

flash('success', 'Review berhasil dikirim!');
header("Location: service_detail.php?id=" . $service_id);
exit;
