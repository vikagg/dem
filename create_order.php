<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_type = $_POST['house_type'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $need_design = isset($_POST['need_design']) ? 1 : 0;
    $phone = trim($_POST['phone'] ?? '');
    
    if (!in_array($house_type, ['Брус', 'Бревно', 'Минибрус'])) {
        $error = 'Выберите тип дома';
    } elseif (empty($location)) {
        $error = 'Укажите населенный пункт';
    } elseif (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = 'Телефон в формате 8(XXX)XXX-XX-XX';
    } else {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, house_type, location, need_design, phone, status) VALUES (?, ?, ?, ?, ?, 'Новый')");
        if ($stmt->execute([$_SESSION['user_id'], $house_type, $location, $need_design, $phone])) {
            $success = 'Заказ отправлен на рассмотрение администратору!';
        } else {
            $error = 'Ошибка при создании заказа';
        }
    }
}
include 'header.php';
?>

<div class="container">
    <div class="card">
        <h2>Оформление заказа</h2>
        <?php if($success): ?>
            <div class="success-msg"><?= $success ?> <a href="orders.php">Перейти к моим заказам</a></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error-msg" style="background:#ffebee; padding:10px; border-radius:20px; margin-bottom:16px;"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Тип дома</label>
            <select name="house_type" required>
                <option value="Брус">Брус</option>
                <option value="Бревно">Бревно</option>
                <option value="Минибрус">Минибрус</option>
            </select>
            <label>Населенный пункт</label>
            <input type="text" name="location" required placeholder="г. Москва, д. Строителей">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="need_design" style="width: auto; margin-bottom: 0;"> Требуется дизайн-проект
            </label>
            <label>Телефон для связи</label>
            <input type="text" name="phone" required placeholder="8(999)123-45-67">
            <button type="submit" class="btn">Отправить заказ</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>