<?php 
include 'includes/admin_header.php'; 
require_once '../config/db.php'; 

// Ambil ID
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan');window.location='manage_pets.php';</script>";
    exit;
}

$id = $_GET['id'];

// Ambil data pet
$stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ?");
$stmt->execute([$id]);
$pet = $stmt->fetch();

if (!$pet) {
    echo "<script>alert('Data hewan tidak ditemukan');window.location='manage_pets.php';</script>";
    exit;
}

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE pets SET name = ?, type = ?, age = ? WHERE id = ?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['type'],
        $_POST['age'],
        $id
    ]);

    echo "<script>
        alert('Data berhasil diperbarui!');
        window.location='manage_pets.php';
    </script>";
}
?>

<link rel="stylesheet" href="assets/css/admin.css">

<div class="admin-container">
    <h1>Edit Hewan</h1>

    <div class="formeditpet">
        <form method="post">
            
            <label>Pet Name</label>
            <input 
                type="text" 
                name="name" 
                required 
                value="<?= htmlspecialchars($pet['name']); ?>"
            >

            <label>Type</label>
            <select name="type" required>
                <?php
                $types = ['Dog','Cat','Rodent','Bird','Reptile','Aquatic','Other'];
                foreach ($types as $type) {
                    $selected = ($pet['type'] === $type) ? 'selected' : '';
                    echo "<option value=\"$type\" $selected>$type</option>";
                }
                ?>
            </select>

            <label>Age</label>
            <input 
                type="number" 
                name="age" 
                min="0"
                value="<?= htmlspecialchars($pet['age']); ?>"
            >

            <button type="submit" class="btn-primary">
                Update Data
            </button>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
