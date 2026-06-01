<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, maximum-scale=1.0">
    <title>Бобр Строитель</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="container header-container">
        <div class="logo">
            <img src=".\\img/logo.png">
            <a href="index.php">Бобр <span>Строитель</span></a>
        </div>
        <nav class="main-nav">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Главная</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="orders.php"><i class="fas fa-list"></i> Мои заказы</a></li>
                    <li><a href="create_order.php"><i class="fas fa-plus-circle"></i> Новый заказ</a></li>
                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <li><a href="admin.php"><i class="fas fa-user-shield"></i> Админ панель</a></li>
                    <?php endif; ?>
                    <li class="user-info">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_login'] ?? 'Пользователь') ?></span>
                    </li>
                    <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                <?php else: ?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Вход</a></li>
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>