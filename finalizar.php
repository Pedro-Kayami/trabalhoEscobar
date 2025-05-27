<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$error = '';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    redirect(BASE_URL . 'carrinho.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = getCartTotal();
    
    $db->query("START TRANSACTION");
    
    try {
        $stmt = $db->prepare("INSERT INTO vendas (total) VALUES (?)");
        $stmt->bind_param("d", $total);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao registrar a venda: " . $db->error());
        }
        
        $saleId = $db->lastInsertId();
        
        foreach ($_SESSION['cart'] as $item) {
            $stmt = $db->prepare("INSERT INTO vendas_itens (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $saleId, $item['id'], $item['quantidade'], $item['preco']);
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao registrar os itens da venda: " . $db->error());
            }
        }
        
        $db->query("COMMIT");
        
        $_SESSION['cart'] = [];
        
        redirect(BASE_URL . 'agradecimento.php?id=' . $saleId);
    } catch (Exception $e) {
        $db->query("ROLLBACK");
        $error = $e->getMessage();
    }
}
?>

<div class="page-header">
    <h1>Finalizar Compra</h1>
</div>

<?php if (!empty($error)): ?>
    <div class="error-message"><?php echo $error; ?></div>
<?php endif; ?>

<div class="checkout-container">
    <div class="checkout-resumo">
        <h2>Resumo do Pedido</h2>
        
        <table class="checkout-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['nome']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td><?php echo formatPrice($item['preco']); ?></td>
                        <td><?php echo formatPrice($item['preco'] * $item['quantidade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong><?php echo formatPrice(getCartTotal()); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="checkout-form">
        <h2>Confirmar Pedido</h2>
        <p>Para concluir sua compra, clique no botão abaixo:</p>
        
        <form method="POST" action="">
            <button type="submit" class="btn btn-primary btn-large">Confirmar Compra</button>
            <a href="<?php echo BASE_URL; ?>carrinho.php" class="btn">Voltar ao Carrinho</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>