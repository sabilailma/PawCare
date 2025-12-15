<?php include 'includes/admin_header.php'; ?>
<?php require '../config/db.php'; ?>

<link rel="stylesheet" href="assets/css/admin.css">

<div class="admin-container">

    <h1>Kelola Hewan</h1>
    <p class="welcome">Daftar hewan dalam bentuk card grid.</p>

    <a href="add_pet.php" class="btn-add">+ Tambah Hewan</a>

    <div class="pet-grid">

        <?php
        $pets = $pdo->query("SELECT * FROM pets ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        if (!$pets) {
            echo "<p>Tidak ada data hewan.</p>";
        }

        foreach ($pets as $p):
        ?>

        <div class="pet-card">

            <img 
                src="../assets/uploads/<?= !empty($p['image']) ? htmlspecialchars($p['image']) : 'default.jpg' ?>" 
                class="image-wrapper"
            >

            <div class="pet-info">
                <h3><?= htmlspecialchars($p['name'] ?? '') ?></h3>

            <p class="type">
                <?= htmlspecialchars($p['type'] ?? '-') ?>
            </p>

            <p>
                 <?= htmlspecialchars(substr($p['description'] ?? '', 0, 70)) ?>...
            </p>


                <div class="action-row">
                    <a href="edit_pet.php?id=<?= $p['id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_pet.php?id=<?= $p['id'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Hapus hewan ini?')">Hapus</a>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

</div>

<?php include 'includes/admin_footer.php'; ?>
