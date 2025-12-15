<?php
include 'includes/admin_header.php';
require '../config/db.php';

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name']);
    $type   = $_POST['type'];
    $age    = $_POST['age'];
    $health = $_POST['health_status'];
    $desc   = $_POST['description'];

    // ===== UPLOAD IMAGE =====
    $imageName = 'default.jpg';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $imageName = uniqid('pet_') . '.' . $ext;

            // PATH KE assets/uploads
            $uploadPath = __DIR__ . '/../assets/uploads/' . $imageName;

            move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
        }
    }

    // ===== INSERT DATABASE =====
    $stmt = $pdo->prepare("
        INSERT INTO pets 
        (name, type, age, health_status, description, image, status)
        VALUES (?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $name,
        $type,
        $age,
        $health,
        $desc,
        $imageName,
        'available'
    ]);

    header("Location: manage_pets.php");
    exit;
}
?>

<?php include 'includes/admin_header.php'; ?>
<link rel="stylesheet" href="assets/css/admin.css">
<div class="form-container">
    <h2>Add New Pet</h2>

    <form method="POST" enctype="multipart/form-data">

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

        <label>Health Status</label>
        <input class="input" name="health_status">

        <label>Description</label>
        <textarea class="input" name="description"></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">

        <button class="btn-primary">Add Pet</button>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
