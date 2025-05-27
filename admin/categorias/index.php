<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$message = '';

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM produtos WHERE categoria_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        $message = 'Não é possível excluir a categoria pois existem produtos vinculados a ela.';
    } else {
        $stmt = $db->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = 'Categoria excluída com sucesso.';
        } else {
            $message = 'Erro ao excluir a categoria: ' . $db->error();
        }
    }
}

$result = $db->query("SELECT * FROM categorias ORDER BY nome");
$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>

<div class="admin-header">
    <h1>Gerenciar Categorias</h1>
    <a href="<?php echo ADMIN_URL; ?>categorias/create.php" class="btn btn-primary">Nova Categoria</a>
</div>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="admin-table">
    <?php if (count($categories) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo $category['nome']; ?></td>
                        <td class="actions">
                            <a href="<?php echo ADMIN_URL; ?>categorias/edit.php?id=<?php echo $category['id']; ?>" class="btn btn-small">Editar</a>
                            <a href="<?php echo ADMIN_URL; ?>categorias/index.php?delete=<?php echo $category['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma categoria cadastrada.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>