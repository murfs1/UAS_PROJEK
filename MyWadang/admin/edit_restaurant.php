<?php
// File: edit_restaurant.php

require_once(__DIR__ . '/../includes/auth.php'); // Pastikan admin sudah login
require_once(__DIR__ . '/../includes/db.php');  // Pastikan path benar

$pdo = connectDatabase();  // Mendapatkan koneksi ke database

// Jika ID restoran tidak ada dalam URL, redirect ke halaman daftar restoran
if (!isset($_GET['id'])) {
    header('Location: manage_restaurants.php');
    exit();
}

$id = $_GET['id'];

// Ambil data restoran yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika restoran tidak ditemukan, redirect
if (!$restaurant) {
    header('Location: manage_restaurants.php');
    exit();
}

// Proses edit restoran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_restaurant'])) {
    try {
        $stmt = $pdo->prepare("UPDATE restaurants SET name = ?, lat = ?, lng = ?, rating = ?, promo = ? WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['lat'],
            $_POST['lng'],
            $_POST['rating'],
            $_POST['promo'],
            $id
        ]);
        header('Location: manage_restaurants.php?success=updated');
        exit();
    } catch (PDOException $e) {
        $error = "Gagal mengedit restoran: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restoran</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <h1>Edit Restoran</h1>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="edit_restaurant.php?id=<?= $id; ?>" method="POST">
            <input type="text" name="name" value="<?= htmlspecialchars($restaurant['name']); ?>" required>
            <input type="text" name="lat" value="<?= htmlspecialchars($restaurant['lat']); ?>" required>
            <input type="text" name="lng" value="<?= htmlspecialchars($restaurant['lng']); ?>" required>
            <input type="number" name="rating" value="<?= htmlspecialchars($restaurant['rating']); ?>" step="0.1">
            <input type="text" name="promo" value="<?= htmlspecialchars($restaurant['promo']); ?>">
            <button type="submit" name="edit_restaurant">Simpan Perubahan</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
