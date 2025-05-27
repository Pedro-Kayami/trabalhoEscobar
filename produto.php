<?php
require_once 'includes/header.php';

$product = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $product = getProductById($id);
    
    if (!$product) {
        redirect(BASE_URL);
    }
} else {
    redirect(BASE_URL);
}
?>

<div class="produto-detalhes">
    <div class="produto-info">
        <h1><?php echo $product['nome']; ?></h1>
        <div class="produto-meta">
            <div class="produto-categoria">Categoria: <?php echo $product['categoria']; ?></div>
            <div class="produto-preco"><?php echo formatPrice($product['preco']); ?></div>
        </div>
        
        <?php if (!empty($product['descricao'])): ?>
            <div class="produto-descricao">
                <h2>Descrição</h2>
                <p><?php echo nl2br($product['descricao']); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="produto-acoes">
            <a href="<?php echo BASE_URL; ?>carrinho.php?add=<?php echo $product['id']; ?>" class="btn btn-primary btn-large">Adicionar ao Carrinho</a>
            <a href="<?php echo BASE_URL; ?>" class="btn">Continuar Comprando</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>