<?php
include 'includes/admin_header.php';
require '../config/db.php';

$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">

    <h1>Kelola Layanan</h1>
    <p class="welcome">Daftar layanan PawCare</p>

    <a href="add_service.php" class="btn-add">+ Tambah Layanan</a>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th>Durasi (menit)</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php if (!$services): ?>
            <tr><td colspan="5">Belum ada layanan</td></tr>
        <?php endif; ?>

        <?php foreach ($services as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['name'] ?? '') ?></td>
                <td>Rp <?= number_format($s['price'] ?? 0) ?></td>
                <td><?= $s['duration_minutes'] ?? '-' ?></td>
                <td><?= $s['rating'] ?? '0' ?></td>
                <td>
                    <a href="edit_service.php?id=<?= $s['id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_service.php?id=<?= $s['id'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Hapus layanan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</div>

<?php include 'includes/admin_footer.php'; ?>
