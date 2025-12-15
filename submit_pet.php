<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = $_POST['name']; $type = $_POST['type']; $age = $_POST['age'];
  $health = $_POST['health_status']; $desc = $_POST['description'];
  $img = upload_image($_FILES['image']);
  $stmt = $pdo->prepare("INSERT INTO pets (name,type,age,health_status,description,image,status) VALUES (?,?,?,?,?,?,?)");
  $stmt->execute([$name,$type,$age,$health,$desc,$img,'available']);
  // notify admins
  $admins = $pdo->query("SELECT id FROM users WHERE role='admin'")->fetchAll();
  foreach($admins as $a) notify($a['id'],'Hewan Diajukan','Ada hewan baru yang disubmit.');
  flash('success','Hewan berhasil disubmit.');
  header('Location: index.php'); exit;
}
require_once 'includes/header.php';
?>
<div class="form">
  <h2>Serahkan Hewan</h2>
  <?php if($err) echo "<div style='color:#b91c1c'>$err</div>"; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Nama</label><input class="input" name="name" required>
    <label>Jenis</label><select class="input" name="type"><option>Dog</option><option>Cat</option><option>Other</option></select>
    <label>Usia</label><input class="input" name="age">
    <label>Kondisi Kesehatan</label><input class="input" name="health_status">
    <label>Deskripsi</label><textarea class="input" name="description"></textarea>
    <label>Foto</label><input type="file" name="image" accept="image/*">
    <button class="btn-primary">Submit</button>
  </form>
</div>
<?php require_once 'includes/footer.php'; ?>
