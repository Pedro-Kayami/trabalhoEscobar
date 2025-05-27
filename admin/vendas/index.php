<?php
require_once '../includes/header.php';

$db = Database::getInstance();

$result = $db->query("SELECT * FROM vendas ORDER BY data_venda DESC");
$sales = [];

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}
?>

<div class="admin-header">
    <h1>Gerenciar Vendas</h1>
</div>

<div class="admin-table">
    <?php if (count($sales) > 0): ?>
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
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?php echo $sale['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($sale['data_venda'])); ?></td>
                        <td><?php echo formatPrice($sale['total']); ?></td>
                        <td class="actions">
                            <a href="<?php echo ADMIN_URL; ?>vendas/view.php?id=<?php echo $sale['id']; ?>" class="btn btn-small">Ver Detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma venda registrada.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>