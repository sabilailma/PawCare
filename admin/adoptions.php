<?php
include 'includes/admin_header.php';
require_once '../config/db.php';

/* ===============================
   HANDLE ACTION
================================ */
if (isset($_GET['action'], $_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // ambil data request
    $stmt = $pdo->prepare("
        SELECT a.*, p.id AS pet_id, p.name AS pet_name
        FROM adoption_requests a
        JOIN pets p ON p.id = a.pet_id
        WHERE a.id=?
    ");
    $stmt->execute([$id]);
    $req = $stmt->fetch();

    if ($req) {

        if ($action === 'approve') {

            $pdo->prepare("
                UPDATE adoption_requests SET status='approved' WHERE id=?
            ")->execute([$id]);

            $pdo->prepare("
                UPDATE pets SET status='adopted' WHERE id=?
            ")->execute([$req['pet_id']]);

            notify(
                $req['user_id'],
                'Adopsi Disetujui 🎉',
                'Selamat! Pengajuan adopsi Anda untuk '.$req['pet_name'].' disetujui.'
            );

        } elseif ($action === 'reject') {

            $pdo->prepare("
                UPDATE adoption_requests SET status='rejected' WHERE id=?
            ")->execute([$id]);

            $pdo->prepare("
                UPDATE pets SET status='available' WHERE id=?
            ")->execute([$req['pet_id']]);

            notify(
                $req['user_id'],
                'Adopsi Ditolak',
                'Maaf, pengajuan adopsi Anda belum dapat disetujui.'
            );
        }
    }

    header('Location: adoptions.php');
    exit;
}

/* ===============================
   FILTER STATUS
================================ */
$status = $_GET['status'] ?? 'pending';

$stmt = $pdo->prepare("
    SELECT a.*, u.name AS user_name, p.name AS pet_name
    FROM adoption_requests a
    JOIN users u ON u.id = a.user_id
    JOIN pets p ON p.id = a.pet_id
    WHERE a.status=?
    ORDER BY a.created_at DESC
");
$stmt->execute([$status]);
$adoptions = $stmt->fetchAll();
?>

<h1>Manajemen Adopsi</h1>

<div style="margin-bottom:20px">
  <a class="btn <?= $status==='pending'?'btn-primary':'' ?>" href="?status=pending">Pending</a>
  <a class="btn <?= $status==='approved'?'btn-primary':'' ?>" href="?status=approved">Approved</a>
  <a class="btn <?= $status==='rejected'?'btn-primary':'' ?>" href="?status=rejected">Rejected</a>
</div>

<?php foreach($adoptions as $a): ?>
  <div class="card" style="margin-bottom:15px">

    <h3><?= htmlspecialchars($a['pet_name']) ?></h3>
    <p class="small">
      Oleh: <?= htmlspecialchars($a['user_name']) ?> • <?= $a['created_at'] ?>
    </p>

    <pre style="white-space:pre-wrap;background:#f8f9fa;padding:10px;border-radius:8px">
<?= htmlspecialchars($a['message']) ?>
    </pre>

    <?php if ($a['status'] === 'pending'): ?>
      <a class="btn-primary" href="?action=approve&id=<?= $a['id'] ?>">Approve</a>
      <a class="btn" href="?action=reject&id=<?= $a['id'] ?>">Reject</a>
    <?php else: ?>
      <span class="badge"><?= strtoupper($a['status']) ?></span>
    <?php endif; ?>

  </div>
<?php endforeach; ?>

<?php include 'includes/admin_footer.php'; ?>
