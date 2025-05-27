<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$result = $db->query("SELECT p.*, c.nome as categoria FROM produtos p 
                     JOIN categorias c ON p.categoria_id = c.id 
                     ORDER BY p.id DESC LIMIT 8");
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<div class="hero">
    <div class="hero-content">
        <h1>Bem-vindo à Loja Virtual</h1>
        <p>Encontre os melhores produtos com os melhores preços!</p>
        <a href="#produtos" class="btn btn-primary">Ver Produtos</a>
    </div>
</div>

<section id="produtos" class="produtos-section">
    <h2>Produtos em Destaque</h2>
    
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
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </div>
</section>

<section class="categorias-section">
    <h2>Categorias</h2>
    
    <div class="categorias-grid">
        <?php
        $categories = getAllCategories();
        
        if (count($categories) > 0):
            foreach ($categories as $category):
        ?>
            <a href="<?php echo BASE_URL; ?>categoria.php?id=<?php echo $category['id']; ?>" class="categoria-card">
                <h3><?php echo $category['nome']; ?></h3>
            </a>
        <?php
            endforeach;
        else:
        ?>
            <p>Nenhuma categoria cadastrada.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>