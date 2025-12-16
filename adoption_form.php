<?php
require_once 'includes/functions.php';
if (!is_logged()) header('Location: login.php');
require_once 'includes/header.php';

$step   = intval($_POST['step'] ?? 1);
$pet_id = intval($_GET['pet_id'] ?? $_POST['pet_id'] ?? 0);

// ambil pet
$stmt = $pdo->prepare("SELECT * FROM pets WHERE id=? AND status='available'");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch();

if (!$pet) {
    echo "<p>Hewan tidak ditemukan atau sudah tidak tersedia.</p>";
    require_once 'includes/footer.php';
    exit;
}

/* ===============================
   FINAL SUBMIT (STEP 3)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 3) {

    // 🔒 CEK SUDAH PERNAH AJU ADOPSI PET INI ATAU BELUM
    $check = $pdo->prepare("
        SELECT id 
        FROM adoption_requests 
        WHERE user_id=? 
          AND pet_id=? 
          AND status IN ('pending','approved')
    ");
    $check->execute([uid(), $pet_id]);

    if ($check->fetch()) {
        flash('error', 'Anda sudah mengajukan adopsi untuk hewan ini.');
        header("Location: adoption_form.php?pet_id=$pet_id");
        exit;
    }

    // gabungkan jawaban form
    $data = [
        'Alasan'      => $_POST['reason'],
        'Alamat'      => $_POST['address'],
        'Pekerjaan'   => $_POST['job'],
        'Pengalaman'  => $_POST['experience'],
        'Komitmen'    => $_POST['commitment']
    ];

    $message = "";
    foreach ($data as $k => $v) {
        $message .= "$k: $v\n";
    }

    // simpan ke DB
    $pdo->prepare("
        INSERT INTO adoption_requests (user_id, pet_id, message)
        VALUES (?,?,?)
    ")->execute([uid(), $pet_id, $message]);

    // update status pet
    $pdo->prepare("UPDATE pets SET status='pending' WHERE id=?")
        ->execute([$pet_id]);

    // notifikasi user
    notify(uid(), 'Pengajuan Adopsi Dikirim', 'Form adopsi berhasil dikirim.');

    flash('success', 'Pengajuan adopsi berhasil dikirim!');
    header('Location: profile.php');
    exit;
}
?>

<h2>Form Adopsi Hewan 🐾</h2>

<?php if($err = flash('error')): ?>
  <div class="alert error"><?= $err ?></div>
<?php endif; ?>

<div class="card" style="max-width:650px;margin:auto">

  <img
    src="/pawcare/assets/uploads/<?= htmlspecialchars($pet['image']) ?>"
    style="width:100%;height:220px;object-fit:cover;border-radius:12px"
  >

  <h3><?= htmlspecialchars($pet['name']) ?> (<?= htmlspecialchars($pet['type']) ?>)</h3>

  <p class="small">Step <?= $step ?> dari 3</p>

  <form method="post">
    <input type="hidden" name="pet_id" value="<?= $pet_id ?>">
    <input type="hidden" name="step" value="<?= $step + 1 ?>">

    <?php if ($step === 1): ?>
      <label>Alasan Mengadopsi *</label>
      <textarea name="reason" class="input" required></textarea>

      <label>Alamat Lengkap *</label>
      <textarea name="address" class="input" required></textarea>

      <label>Pekerjaan *</label>
      <input type="text" name="job" class="input" required>

    <?php elseif ($step === 2): ?>
      <label>Pengalaman Merawat Hewan</label>
      <textarea name="experience" class="input"></textarea>

      <label>Komitmen Merawat Hewan *</label>
      <textarea name="commitment" class="input" required></textarea>

    <?php elseif ($step === 3): ?>
      <p class="small">
        Dengan ini saya menyatakan bersedia merawat hewan dengan penuh tanggung jawab.
      </p>
      <button class="btn-primary">Kirim Pengajuan</button>
      <input type="hidden" name="step" value="3">
    <?php endif; ?>

    <?php if ($step < 3): ?>
      <button class="btn-primary" style="margin-top:10px">
        Lanjut →
      </button>
    <?php endif; ?>

  </form>
</div>

<?php require_once 'includes/footer.php'; ?>
