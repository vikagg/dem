<?php
session_start();
require_once 'db_config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['is_admin'] = ($user['role'] === 'admin');
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
include 'header.php';
?>

<div class="container">
    <div class="card">
        <h2>Вход в систему</h2>
        <?php if($error): ?>
            <div class="error-msg" style="background:#ffebee; padding:10px; border-radius:20px; margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Логин</label>
            <input type="text" name="login" required>
            <label>Пароль</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn">Войти</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="register.php">Ещё не зарегистрированы? Регистрация</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>