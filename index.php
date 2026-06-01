<?php
session_start();
require_once 'db_config.php';
include 'header.php';
?>

<div class="container">
    <div class="slider-container">
        <div class="slider">
            <img class="slide" src=".\\img/house.png" alt="Дом из бруса">
            <img class="slide" src=".\\img/house2.png" alt="Дом из бревна">
            <img class="slide" src=".\\img/house3.png" alt="Строительство">
        </div>
        <button class="slider-btn prev">&#10094;</button>
        <button class="slider-btn next">&#10095;</button>
        <div class="dots"></div>
    </div>
    
    <div class="card">
        <h2>Строим дома мечты</h2>
        <p>Брус, бревно, минибрус - выберите идеальный дом для вашей семьи. Оставьте заявку и получите лучшее предложение.</p>
        <p style="margin: 16px 0px;">Мы строим качественные дома из экологически чистых материалов. Соблюдаем сроки, гарантируем результат.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <a href="register.php" class="btn" style="text-align:center; text-decoration:none;">Зарегистрироваться</a>
                <a href="login.php" class="btn btn-secondary" style="text-align:center; text-decoration:none;">Войти</a>
            </div>
        <?php else: ?>
            <a href="create_order.php" class="btn" style="text-align:center; text-decoration:none; display:block;">Оставить заявку</a>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h2>Наши преимущества</h2>
        <ul style="list-style: none; padding-left: 0;">
            <li style="margin-bottom: 12px;"><i class="fas fa-check-circle" style="color: #4a6a3b; margin-right: 8px;"></i> Гарантия 5 лет на все работы</li>
            <li style="margin-bottom: 12px;"><i class="fas fa-check-circle" style="color: #4a6a3b; margin-right: 8px;"></i> Собственное производство</li>
            <li style="margin-bottom: 12px;"><i class="fas fa-check-circle" style="color: #4a6a3b; margin-right: 8px;"></i> Бесплатная доставка материалов</li>
            <li style="margin-bottom: 12px;"><i class="fas fa-check-circle" style="color: #4a6a3b; margin-right: 8px;"></i> Фиксированная смета</li>
        </ul>
    </div>
</div>

<?php include 'footer.php'; ?>