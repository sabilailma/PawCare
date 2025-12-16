<?php
include 'includes/admin_header.php';
require_once '../config/db.php';

$status = $_GET['status'] ?? 'all';

$sql = "
SELECT 
    b.*,
    u.name AS user_name,
    s.name AS service_name
FROM bookings b
LEFT JOIN users u ON b.user_id = u.id
LEFT JOIN services s ON b.service_id = s.id
";

$params = [];

if ($status !== 'all') {
    $sql .= " WHERE b.status = ?";
    $params[] = $status;
}

$sql .= " ORDER BY b.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>

<link rel="stylesheet" href="assets/css/admin.css">

<div class="admin-container">
    <h1>Data Booking</h1>

    <!-- FILTER BUTTON -->
    <div class="filter-bar">
        <a href="bookings.php" class="<?= $status=='all'?'active':'' ?>">Semua</a>
        <a href="?status=pending" class="<?= $status=='pending'?'active':'' ?>">Pending</a>
        <a href="?status=confirmed" class="<?= $status=='confirmed'?'active':'' ?>">Confirmed</a>
        <a href="?status=completed" class="<?= $status=='completed'?'active':'' ?>">Completed</a>
        <a href="?status=cancelled" class="<?= $status=='cancelled'?'active':'' ?>">Cancelled</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Layanan</th>
                <th>Type</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['user_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($b['service_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($b['type'] ?? '-') ?></td>
                <td><?= $b['booking_date'] ?></td>
                <td><?= $b['booking_time'] ?></td>
                <td>
                    <span class="status <?= $b['status'] ?>">
                        <?= ucfirst($b['status']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($b['notes'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin_footer.php'; ?>
