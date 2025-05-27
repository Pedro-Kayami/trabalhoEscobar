<?php
require_once 'includes/header.php';

$category = null;
$products = [];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $category = getCategoryById($id);
    
    if ($category) {
        $products = getProducts($id);
    } else {
        redirect(BASE_URL);
    }
} else {
    redirect(BASE_URL);
}
?>

<div class="page-header">
    <h1>Categoria: <?php echo $category['nome']; ?></h1>
</div>

<div class="produtos-grid">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="produto-card">
                <h3><?php echo $product['nome']; ?></h3>
                <div class="produto-categoria"><?php echo $product['categoria']; ?></div>
                <div class="produto-preco"><?php echo formatPrice($product['preco']); ?></div>
                <div class="produto-acoes">
                    <a href="<?php echo BASE_URL; ?>produto.php?id=<?php echo $product['id']; ?>" class="btn">Ver Detalhes</a>
                    <a href="<?php echo BASE_URL; ?>carrinho.php?add=<?php echo $product['id']; ?>" class="btn btn-primary">Adicionar ao Carrinho</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum produto encontrado nesta categoria.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>