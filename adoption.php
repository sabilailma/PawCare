<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// handle adopsi submit
if($_SERVER['REQUEST_METHOD']==='POST' && is_logged()){
  $pet_id = intval($_POST['pet_id']);
  $message = $_POST['message'] ?? '';
  $stmt = $pdo->prepare("INSERT INTO adoption_requests (user_id,pet_id,message) VALUES (?,?,?)");
  $stmt->execute([uid(),$pet_id,$message]);
  $pdo->prepare("UPDATE pets SET status='pending' WHERE id=?")->execute([$pet_id]);
  notify(uid(),'Pengajuan Adopsi Dikirim','Tim akan meninjau permohonan Anda.');
  flash('success','Pengajuan adopsi terkirim!');
  header('Location: profile.php'); exit;
}

// list pets
$stmt = $pdo->query("SELECT * FROM pets WHERE status='available' ORDER BY created_at DESC");
$pets = $stmt->fetchAll();
?>
<h2>Hewan Siap Diadopsi</h2>
<div class="cards">
<?php foreach($pets as $p): ?>
  <div class="card">
    <img src="<?= $p['image'] ?: 'assets/img/pet_placeholder.jpg' ?>" alt="">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="small"><?= htmlspecialchars($p['type']) ?> • <?= htmlspecialchars($p['age']) ?></p>
    <div class="card-actions">
      <a class="btn-primary" href="detail_pet.php?id=<?= $p['id'] ?>">Detail</a>
      <?php if(is_logged()): ?>
        <button onclick="document.getElementById('adopt-<?= $p['id'] ?>').style.display='block'" class="btn">Ajukan</button>
        <div id="adopt-<?= $p['id'] ?>" style="display:none;margin-top:10px">
          <form method="post">
            <input type="hidden" name="pet_id" value="<?= $p['id'] ?>">
            <label>Alasan</label>
            <textarea name="message" class="input" required></textarea>
            <button class="btn-primary">Kirim Pengajuan</button>
          </form>
        </div>
      <?php else: ?>
        <a class="btn" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
