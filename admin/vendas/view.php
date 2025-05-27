<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$sale = null;
$items = [];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $db->prepare("SELECT * FROM vendas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $sale = $result->fetch_assoc();
        
        $stmt = $db->prepare("SELECT vi.*, p.nome as produto_nome 
                             FROM vendas_itens vi 
                             JOIN produtos p ON vi.produto_id = p.id 
                             WHERE vi.venda_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    } else {
        redirect(ADMIN_URL . 'vendas/index.php');
    }
} else {
    redirect(ADMIN_URL . 'vendas/index.php');
}
?>

<div class="admin-header">
    <h1>Detalhes da Venda #<?php echo $sale['id']; ?></h1>
    <a href="<?php echo ADMIN_URL; ?>vendas/index.php" class="btn">Voltar</a>
</div>

<div class="sale-details">
    <div class="sale-info">
        <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($sale['data_venda'])); ?></p>
        <p><strong>Total:</strong> <?php echo formatPrice($sale['total']); ?></p>
    </div>
    
    <h2>Itens da Venda</h2>
    
    <div class="admin-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['produto_nome']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td><?php echo formatPrice($item['preco_unitario']); ?></td>
                        <td><?php echo formatPrice($item['quantidade'] * $item['preco_unitario']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong><?php echo formatPrice($sale['total']); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>