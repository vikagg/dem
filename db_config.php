<?php
$host = 'localhost';
$dbname = 'bobr_stroitel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `login` VARCHAR(50) UNIQUE NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `fullname` VARCHAR(100) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `role` ENUM('user', 'admin') DEFAULT 'user',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `orders` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `house_type` ENUM('Брус', 'Бревно', 'Минибрус') NOT NULL,
            `location` VARCHAR(150) NOT NULL,
            `need_design` BOOLEAN DEFAULT FALSE,
            `phone` VARCHAR(20) NOT NULL,
            `status` ENUM('Новый', 'Согласован', 'Выполнен') DEFAULT 'Новый',
            `review` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        )
    ");
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = 'Admin'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $adminPass = password_hash('Brevno', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (login, password, fullname, phone, email, role) VALUES (?, ?, ?, ?, ?, 'admin')")
            ->execute(['Admin', $adminPass, 'Администратор', '8(999)999-99-99', 'admin@bobr.ru']);
    }
    
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>