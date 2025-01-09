<?php
// File: add_restaurant.php

require_once(__DIR__ . '/../includes/auth.php'); // Pastikan admin sudah login
require_once(__DIR__ . '/../includes/db.php');  // Pastikan path benar

$pdo = connectDatabase();  // Mendapatkan koneksi ke database

// Proses tambah restoran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO restaurants (name, lat, lng, rating, promo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['lat'],
            $_POST['lng'],
            $_POST['rating'],
            $_POST['promo']
        ]);
        header('Location: manage_restaurants.php?success=added');
        exit();
    } catch (PDOException $e) {
        $error = "Gagal menambah restoran: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Restoran</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <h1>Tambah Restoran Baru</h1>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="add_restaurant.php" method="POST">
            <input type="text" name="name" placeholder="Nama Restoran" required>
            <input type="text" name="lat" placeholder="Latitude" required>
            <input type="text" name="lng" placeholder="Longitude" required>
            <input type="number" name="rating" placeholder="Rating (1-5)" step="0.1">
            <input type="text" name="promo" placeholder="Promo">
            <button type="submit" name="add_restaurant">Tambah Restoran</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
