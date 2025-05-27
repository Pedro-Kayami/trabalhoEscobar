<?php
require_once 'includes/header.php';

$db = Database::getInstance();

$productCount = $db->query("SELECT COUNT(*) as count FROM produtos")->fetch_assoc()['count'];
$categoryCount = $db->query("SELECT COUNT(*) as count FROM categorias")->fetch_assoc()['count'];
$salesCount = $db->query("SELECT COUNT(*) as count FROM vendas")->fetch_assoc()['count'];
$totalSales = $db->query("SELECT SUM(total) as total FROM vendas")->fetch_assoc()['total'] ?: 0;

$recentSales = [];
$result = $db->query("SELECT * FROM vendas ORDER BY data_venda DESC LIMIT 5");

while ($row = $result->fetch_assoc()) {
    $recentSales[] = $row;
}
?>

<div class="dashboard">
    <h1>Dashboard</h1>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-value"><?php echo $productCount; ?></div>
            <div class="stat-label">Produtos</div>
        </div>

        <div class="stat-card">
            <div class="stat-value"><?php echo $categoryCount; ?></div>
            <div class="stat-label">Categorias</div>
        </div>

        <div class="stat-card">
            <div class="stat-value"><?php echo $salesCount; ?></div>
            <div class="stat-label">Vendas</div>
        </div>

        <div class="stat-card">
            <div class="stat-value"><?php echo formatPrice($totalSales); ?></div>
            <div class="stat-label">Total de Vendas</div>
        </div>
    </div>

    <div class="recent-sales">
        <h2>Vendas Recentes</h2>

        <?php if (count($recentSales) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentSales as $sale): ?>
                        <tr>
                            <td><?php echo $sale['id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($sale['data_venda'])); ?></td>
                            <td><?php echo formatPrice($sale['total']); ?></td>
                            <td>
                                <a href="<?php echo ADMIN_URL; ?>vendas/view.php?id=<?php echo $sale['id']; ?>" class="btn btn-small">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma venda registrada ainda.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>