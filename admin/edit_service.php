<?php
include 'includes/admin_header.php';
require '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_services.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$s) {
    die("Data tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE services
        SET name = ?, price = ?, duration_minutes = ?, description = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['name'],
        $_POST['price'],
        $_POST['duration_minutes'],
        $_POST['description'],
        $id
    ]);

    header("Location: manage_services.php");
    exit;
}
?>

<link rel="stylesheet" href="assets/css/admin.css">
<div class="admin-content">
  <div class="admin-container-edit">
    <h1>Edit Layanan</h1>

    <form method="POST" class="form-card">
        <label>Nama</label>
        <input class="input" name="name"
               value="<?= htmlspecialchars($s['name'] ?? '') ?>" required>

        <label>Harga</label>
        <input class="input" type="number" step="0.01" name="price"
               value="<?= htmlspecialchars($s['price'] ?? '') ?>" required>

        <label>Durasi (menit)</label>
        <input class="input" type="number" name="duration_minutes"
               value="<?= htmlspecialchars($s['duration_minutes'] ?? '') ?>" required>

        <label>Deskripsi</label>
        <textarea class="input" name="description"><?= htmlspecialchars($s['description'] ?? '') ?></textarea>

        <button class="btn-primary">Update</button>
    </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
