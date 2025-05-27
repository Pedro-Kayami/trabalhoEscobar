<?php
require_once 'includes/header.php';

$saleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>

<div class="thank-you">
    <div class="thank-you-content">
        <h1>Obrigado por sua compra!</h1>
        
        <?php if ($saleId > 0): ?>
            <p>Seu pedido #<?php echo $saleId; ?> foi registrado com sucesso.</p>
        <?php else: ?>
            <p>Seu pedido foi registrado com sucesso.</p>
        <?php endif; ?>
        
        <p>Agradecemos por comprar conosco!</p>
        
        <div class="thank-you-actions">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Continuar Comprando</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>