<?php
session_start();
require_once "../includes/db.php"; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $error = null;

    try {
        // Validasi input
        if (empty($username) || empty($password)) {
            $error = "Username dan password wajib diisi.";
        } elseif ($password !== $confirm_password) {
            $error = "Password dan konfirmasi password tidak cocok.";
        } else {
            // Koneksi ke database
            $pdo = connectDatabase();

            // Periksa apakah username sudah ada
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Username sudah digunakan.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Masukkan data ke database
                $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $username, 'password' => $hashed_password]);

                // Redirect ke halaman login
                $_SESSION['success_message'] = "Registrasi berhasil. Silakan login.";
                header("Location: login.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        error_log("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .register-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Register Admin</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
