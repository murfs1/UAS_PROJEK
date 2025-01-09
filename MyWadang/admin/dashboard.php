<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <a href="manage_restaurants.php">Kelola Restoran</a>
    <a href="logout.php">Logout</a>
</body>
</html>
