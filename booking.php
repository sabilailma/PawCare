<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');
$services = $pdo->query("SELECT * FROM services")->fetchAll();
$user_pets = $pdo->query("SELECT * FROM pets")->fetchAll(); // for simplicity include all; you can filter by owner if added
if($_SERVER['REQUEST_METHOD']==='POST'){
  $service_id = $_POST['service_id']; $pet_id = $_POST['pet_id'] ?: null;
  $date = $_POST['date']; $time = $_POST['time']; $notes = $_POST['notes'] ?? '';
  $stmt = $pdo->prepare("INSERT INTO bookings (user_id,service_id,pet_id,booking_date,booking_time,notes) VALUES (?,?,?,?,?,?)");
  $stmt->execute([uid(),$service_id,$pet_id,$date,$time,$notes]);
  notify(uid(),'Booking dibuat','Booking Anda telah berhasil dibuat.');
  header('Location: profile.php'); exit;
}
require_once 'includes/header.php';
?>
<div class="form">
  <h2>Booking Layanan</h2>
  <form method="post">
    <label>Pilih Layanan</label>
    <select class="input" name="service_id">
      <?php foreach($services as $s): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> - Rp <?= number_format($s['price'],0,',','.') ?></option>
      <?php endforeach; ?>
    </select>
    <label>Pilih Hewan (opsional)</label>
    <select class="input" name="pet_id">
      <option value="">-- none --</option>
      <?php foreach($user_pets as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['type']) ?>)</option>
      <?php endforeach; ?>
    </select>
    <label>Tanggal</label><input class="input" type="date" name="date" required>
    <label>Waktu</label><input class="input" type="time" name="time" required>
    <label>Catatan</label><textarea class="input" name="notes"></textarea>
    <button class="btn-primary">Buat Booking</button>
  </form>
</div>
<?php require_once 'includes/footer.php'; ?>
