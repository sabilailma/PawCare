<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Ambil pet_id jika ada (opsional)
$pet_id = intval($_GET['pet_id'] ?? 0);

// ===============================
// CEK DUPLIKASI HANYA JIKA PET_ID DI-SET
// ===============================
if ($pet_id && is_logged()) {
    $check = $pdo->prepare("
      SELECT id FROM adoption_requests
      WHERE user_id=? AND pet_id=?
    ");
    $check->execute([uid(), $pet_id]);

    if ($check->fetch()) {
        flash('error','Kamu sudah pernah mengajukan adopsi hewan ini.');
        header('Location: profile.php');
        exit;
    }

    // Pastikan pet masih available
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE id=? AND status='available'");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch();

    if (!$pet) {
        flash('error', 'Hewan tidak tersedia.');
        header('Location: profile.php');
        exit;
    }

    // Redirect ke form step adopsi
    header("Location: adoption_form.php?pet_id={$pet_id}");
    exit;
}

// ===============================
// LIST SEMUA HEWAN YANG READY
// ===============================
$pets = $pdo->query("
    SELECT * FROM pets
    WHERE status='available'
    ORDER BY created_at DESC
")->fetchAll();
?>

<h2>Hewan Siap Diadopsi 🐾</h2>

<div class="cards">
<?php foreach ($pets as $p): ?>
  <div class="card">

    <img
      src="/pawcare/assets/uploads/<?= htmlspecialchars($p['image']) ?>"
      style="height:180px;width:100%;object-fit:cover;border-radius:12px"
    >

    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="small"><?= htmlspecialchars($p['type']) ?> • <?= htmlspecialchars($p['age']) ?></p>

    <div class="card-actions">
      <a class="btn-primary" href="detail_pet.php?id=<?= $p['id'] ?>">Detail</a>

      <?php if(is_logged()): ?>
        <a class="btn" href="adoption_form.php?pet_id=<?= $p['id'] ?>">
          Ajukan Adopsi
        </a>
      <?php else: ?>
        <a class="btn" href="login.php">Login</a>
      <?php endif; ?>
    </div>

  </div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
