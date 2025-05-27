<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$message = '';

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM vendas_itens WHERE produto_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        $message = 'Não é possível excluir o produto pois ele está associado a vendas.';
    } else {
        $stmt = $db->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = 'Produto excluído com sucesso.';
        } else {
            $message = 'Erro ao excluir o produto: ' . $db->error();
        }
    }
}

$result = $db->query("SELECT p.*, c.nome as categoria FROM produtos p 
                     JOIN categorias c ON p.categoria_id = c.id 
                     ORDER BY p.nome");
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<div class="admin-header">
    <h1>Gerenciar Produtos</h1>
    <a href="<?php echo ADMIN_URL; ?>produtos/create.php" class="btn btn-primary">Novo Produto</a>
</div>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="admin-table">
    <?php if (count($products) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['nome']; ?></td>
                        <td><?php echo formatPrice($product['preco']); ?></td>
                        <td><?php echo $product['categoria']; ?></td>
                        <td class="actions">
                            <a href="<?php echo ADMIN_URL; ?>produtos/edit.php?id=<?php echo $product['id']; ?>" class="btn btn-small">Editar</a>
                            <a href="<?php echo ADMIN_URL; ?>produtos/index.php?delete=<?php echo $product['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum produto cadastrado.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>