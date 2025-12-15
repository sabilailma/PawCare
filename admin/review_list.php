<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

// FILTER RATING
$ratingFilter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;

if ($ratingFilter > 0) {
    $stmt = $pdo->prepare("SELECT * FROM service_reviews WHERE rating = ? ORDER BY created_at DESC");
    $stmt->execute([$ratingFilter]);
} else {
    $stmt = $pdo->query("SELECT * FROM service_reviews ORDER BY created_at DESC");
}

$reviews = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<div class="admin-table-wrapper">

    <h2>Daftar Review Layanan</h2>

    <!-- FILTER -->
    <form method="get" class="filter-form">
        <label>Filter Rating:</label>
        <select name="rating" onchange="this.form.submit()">
            <option value="0">Semua</option>
            <option value="5" <?= $ratingFilter == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐</option>
            <option value="4" <?= $ratingFilter == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐</option>
            <option value="3" <?= $ratingFilter == 3 ? 'selected' : '' ?>>⭐⭐⭐</option>
            <option value="2" <?= $ratingFilter == 2 ? 'selected' : '' ?>>⭐⭐</option>
            <option value="1" <?= $ratingFilter == 1 ? 'selected' : '' ?>>⭐</option>
        </select>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Service ID</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Foto</th>
                <th>Tag</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reviews as $r): ?>
            <tr>
                <td><?= $r['user_id'] ?></td>
                <td><?= $r['service_id'] ?></td>
                <td><?= str_repeat("⭐", $r['rating']) ?></td>
                <td><?= htmlspecialchars($r['review_text']) ?></td>

                <td>
                    <?php if ($r['photo']): ?>
                        <img src="../uploads/<?= $r['photo'] ?>" width="70">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($r['tag']) ?></td>

                <td>
                    <?php if ($r['admin_reply']): ?>
                        <span class="status-done">Sudah dibalas</span>
                    <?php else: ?>
                        <span class="status-pending">Belum</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="review_reply.php?id=<?= $r['id'] ?>" class="action-link">
                        Balas
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
