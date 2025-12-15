<?php
include 'includes/admin_header.php';
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("
        INSERT INTO services (name, price, duration_minutes, description, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $price, $duration, $desc]);

    header("Location:admin/manage_services.php ");
    exit;
}
?>

<div class="form-container">
    <h2>Tambah Layanan</h2>

    <form method="POST">
        <label>Nama Layanan</label>
        <input class="input" name="name" required>

        <label>Harga</label>
        <input class="input" name="price" type="number" required>

        <label>Durasi (menit)</label>
        <input class="input" name="duration" type="number">

        <label>Deskripsi</label>
        <textarea class="input" name="description"></textarea>

        <button class="btn-primary">Simpan</button>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
