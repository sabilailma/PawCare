<?php include 'includes/admin_header.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pets WHERE id=?");
$stmt->execute([$id]);
$pet = $stmt->fetch();
?>

<h1>Edit Hewan</h1>

<form method="post">
    <label>Pet Name</label>
        <input class="input" name="name" required>

        <label>Type</label>
        <select class="input" name="type">
            <option>Dog</option>
            <option>Cat</option>
            <option>Rodent</option>
            <option>Bird</option>
            <option>Reptile</option>
            <option>Aquatic</option>
            <option>Other</option>
        </select>

        <label>Age</label>
        <input class="input" name="age">

    <button type="submit" style="padding:10px;background:#22c55e;color:white;">Update</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $pdo->prepare("UPDATE pets SET name=?, type=?, age=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['type'], $_POST['age'], $id]);
    echo "<script>alert('Data diperbarui!');window.location='manage_pets.php';</script>";
}
?>

<?php include 'includes/admin_footer.php'; ?>
