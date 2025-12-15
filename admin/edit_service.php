<?php
include 'includes/admin_header.php';
require '../config/db.php';

$id = $_GET['id'];
$service = $pdo->prepare("SELECT * FROM services WHERE id=?");
$service->execute([$id]);
$s = $service->fetch();

if (!$s) die("Data tidak ditemukan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE services 
        SET name=?, category=?, price=?, description=?, status=?
        WHERE id=?
    ");
    $stmt->execute([
        $_POST['name'],
        $_POST['category'],
        $_POST['price'],
        $_POST['description'],
        $_POST['status'],
        $id
    ]);

    header("Location: manage_services.php");
    exit;
}
?>

<div class="admin-container">
    <h1>Edit Layanan</h1>

    <form method="POST" class="form-card">
        <label>Nama</label>
        <input class="input" name="name" value="<?= htmlspecialchars($s['name']) ?>">

        <label>Kategori</label>
        <input class="input" name="category" value="<?= htmlspecialchars($s['category']) ?>">

        <label>Harga</label>
        <input class="input" type="number" name="price" value="<?= $s['price'] ?>">

        <label>Status</label>
        <select class="input" name="status">
            <option value="active" <?= $s['status']=='active'?'selected':'' ?>>Active</option>
            <option value="inactive" <?= $s['status']=='inactive'?'selected':'' ?>>Inactive</option>
        </select>

        <label>Deskripsi</label>
        <textarea class="input" name="description"><?= htmlspecialchars($s['description']) ?></textarea>

        <button class="btn-primary">Update</button>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
