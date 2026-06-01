<?php
session_start();
require_once 'db_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $login)) {
        $error = 'Логин должен содержать латиницу и цифры, минимум 6 символов';
    } elseif (strlen($password) < 8) {
        $error = 'Пароль должен быть не менее 8 символов';
    } elseif (!preg_match('/^[А-Яа-яЁё\s]+$/u', $fullname)) {
        $error = 'ФИО должно содержать только кириллицу и пробелы';
    } elseif (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = 'Телефон в формате 8(XXX)XXX-XX-XX';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким логином уже существует';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$login, $hashed, $fullname, $phone, $email])) {
                $success = 'Регистрация успешна! Теперь вы можете войти.';
            } else {
                $error = 'Ошибка базы данных';
            }
        }
    }
}
include 'header.php';
?>

<div class="container">
    <div class="card">
        <h2>Регистрация</h2>
        <?php if($error): ?>
            <div class="error-msg" style="background:#ffebee; padding:10px; border-radius:20px; margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="success-msg"><?= htmlspecialchars($success) ?> <a href="login.php">Войти</a></div>
        <?php endif; ?>
        <form id="registerForm" method="POST">
            <label>Логин (латиница, цифры, мин 6)</label>
            <input type="text" name="login" id="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            
            <label>Пароль (мин 8 символов)</label>
            <input type="password" name="password" id="password" required>
            
            <label>ФИО (кириллица)</label>
            <input type="text" name="fullname" id="fullname" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
            
            <label>Телефон (8(XXX)XXX-XX-XX)</label>
            <input type="text" name="phone" id="phone" placeholder="8(999)123-45-67" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            
            <label>Email</label>
            <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="login.php">Уже есть аккаунт? Войти</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>