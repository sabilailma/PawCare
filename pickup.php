<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
  $addr = $_POST['pickup_address']; $date = $_POST['pickup_date']; $time = $_POST['pickup_time']; $stype = $_POST['service_type'];
  $stmt = $pdo->prepare("INSERT INTO pickup_requests (user_id,pickup_address,pickup_date,pickup_time,service_type) VALUES (?,?,?,?,?)");
  $stmt->execute([uid(),$addr,$date,$time,$stype]);
  notify(uid(),'Request Antar/Jemput','Permintaan Anda telah diterima.');
  header('Location: profile.php'); exit;
}
require_once 'includes/header.php';
?>
<div class="form">
  <h2>Request Antar/Jemput</h2>
  <form method="post">
    <label>Alamat Pengambilan</label><textarea class="input" name="pickup_address" required></textarea>
    <label>Tanggal</label><input class="input" type="date" name="pickup_date" required>
    <label>Waktu</label><input class="input" type="time" name="pickup_time" required>
    <label>Jenis Layanan</label>
    <select class="input" name="service_type">
      <option>Pickup for Grooming</option>
      <option>Pickup for Vaccination</option>
      <option>Delivery after service</option>
    </select>
    <button class="btn-primary">Kirim Request</button>
  </form>
</div>
<?php require_once 'includes/footer.php'; ?>
