<?php
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;
$filter_status = $_GET['status'] ?? '';

$query = "SELECT o.*, u.fullname, u.login FROM orders o JOIN users u ON o.user_id = u.id";
$params = [];
if ($filter_status && in_array($filter_status, ['Новый', 'Согласован', 'Выполнен'])) {
    $query .= " WHERE o.status = ?";
    $params[] = $filter_status;
}
$query .= " ORDER BY o.created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

$countQuery = "SELECT COUNT(*) FROM orders o";
if ($filter_status) $countQuery .= " WHERE o.status = ?";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $limit);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header('Location: admin.php?status=' . urlencode($filter_status) . '&page=' . $page);
    exit;
}
include 'header.php';
?>

<div class="container">
    <div class="card">
        <h2>Панель администратора</h2>
        <form method="GET" class="admin-filters">
            <select name="status">
                <option value="">Все статусы</option>
                <option value="Новый" <?= $filter_status == 'Новый' ? 'selected' : '' ?>>Новый</option>
                <option value="Согласован" <?= $filter_status == 'Согласован' ? 'selected' : '' ?>>Согласован</option>
                <option value="Выполнен" <?= $filter_status == 'Выполнен' ? 'selected' : '' ?>>Выполнен</option>
            </select>
            <button type="submit" class="btn" style="width: auto; padding: 0 20px;">Применить фильтр</button>
        </form>
        
        <?php if(count($orders) === 0): ?>
            <p>Заказов не найдено.</p>
        <?php else: ?>
            <?php foreach($orders as $order): ?>
                <div style="border-bottom: 1px solid #e0d6c5; padding: 16px 0;">
                    <strong>Заказ #<?= $order['id'] ?></strong> - <?= htmlspecialchars($order['fullname']) ?> (<?= htmlspecialchars($order['login']) ?>)<br>
                    Дом: <?= $order['house_type'] ?>, Локация: <?= htmlspecialchars($order['location']) ?><br>
                    Телефон: <?= htmlspecialchars($order['phone']) ?><br>
                    Дизайн проект: <?= $order['need_design'] ? 'Да' : 'Нет' ?><br>
                    Статус: <span class="status-badge status-<?= strtolower($order['status']) === 'новый' ? 'new' : (strtolower($order['status']) === 'согласован' ? 'agreed' : 'done') ?>"><?= $order['status'] ?></span><br>
                    <form method="POST" style="margin-top: 12px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="new_status" style="margin-bottom: 0; flex: 1;">
                            <option value="Новый" <?= $order['status'] == 'Новый' ? 'selected' : '' ?>>Новый</option>
                            <option value="Согласован" <?= $order['status'] == 'Согласован' ? 'selected' : '' ?>>Согласован</option>
                            <option value="Выполнен" <?= $order['status'] == 'Выполнен' ? 'selected' : '' ?>>Выполнен</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-small" style="width: auto;">Изменить статус</button>
                    </form>
                    <?php if($order['review']): ?>
                        <div style="margin-top: 12px; background:#f9f6ef; padding: 10px; border-radius: 16px;">
                            <strong>Отзыв пользователя:</strong><br>
                            <?= nl2br(htmlspecialchars($order['review'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if($totalPages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?status=<?= urlencode($filter_status) ?>&page=<?= $i ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>