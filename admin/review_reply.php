<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM service_reviews WHERE id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch();

if (!$review) {
    die("Review tidak ditemukan.");
}

require_once __DIR__ . '/../includes/header.php';
?>

<h2>Balas Review</h2>

<p><strong>Rating:</strong> <?= str_repeat("⭐", $review['rating']) ?></p>
<p><strong>Review:</strong> <?= htmlspecialchars($review['review_text']) ?></p>

<?php if ($review['photo']): ?>
    <p><img src="../uploads/<?= $review['photo'] ?>" width="150"></p>
<?php endif; ?>

<form action="review_reply_process.php" method="post">
    <input type="hidden" name="id" value="<?= $review['id'] ?>">

    <label>Balasan Admin:</label><br>
    <textarea name="admin_reply" required rows="5" cols="60"><?= htmlspecialchars($review['admin_reply']) ?></textarea><br><br>

    <button type="submit">Kirim Balasan</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
