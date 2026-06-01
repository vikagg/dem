<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review']) && isset($_POST['order_id'])) {
    $review = trim($_POST['review']);
    $order_id = (int)$_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET review = ? WHERE id = ? AND user_id = ? AND status = 'Выполнен'");
    $stmt->execute([$review, $order_id, $_SESSION['user_id']]);
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
include 'header.php';
?>

<div class="container">
    <div class="card">
        <h2>Мои заказы</h2>
        <?php if(count($orders) === 0): ?>
            <p>У вас пока нет заказов.</p>
            <a href="create_order.php" class="btn" style="text-align:center; text-decoration:none; display:block;">Создать первый заказ</a>
        <?php else: ?>
            <?php foreach($orders as $order): ?>
                <div class="order-item">
                    <strong><?= htmlspecialchars($order['house_type']) ?></strong> - <?= htmlspecialchars($order['location']) ?><br>
                    Статус: <span class="status-badge status-<?= strtolower($order['status']) === 'новый' ? 'new' : (strtolower($order['status']) === 'согласован' ? 'agreed' : 'done') ?>"><?= $order['status'] ?></span><br>
                    Телефон: <?= htmlspecialchars($order['phone']) ?><br>
                    Дизайн проект: <?= $order['need_design'] ? 'Да' : 'Нет' ?><br>
                    Дата создания: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?><br>
                    <?php if($order['status'] === 'Выполнен'): ?>
                        <form method="POST" style="margin-top: 12px;">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <textarea name="review" class="review-text" placeholder="Ваш отзыв о выполненном заказе..." rows="3"><?= htmlspecialchars($order['review'] ?? '') ?></textarea>
                            <button type="submit" class="btn btn-small" style="margin-top: 8px;">Оставить отзыв</button>
                        </form>
                        <?php if($order['review']): ?>
                            <p style="margin-top: 8px; background: #f5f5f5; padding: 8px; border-radius: 12px;"><strong>Ваш отзыв:</strong> <?= nl2br(htmlspecialchars($order['review'])) ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>