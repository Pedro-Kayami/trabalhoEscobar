<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$message = '';
$category = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $category = $result->fetch_assoc();
    } else {
        redirect(ADMIN_URL . 'categorias/index.php');
    }
} else {
    redirect(ADMIN_URL . 'categorias/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
    
    if (empty($name)) {
        $message = 'Por favor, preencha o nome da categoria.';
    } else {
        $stmt = $db->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        
        if ($stmt->execute()) {
            redirect(ADMIN_URL . 'categorias/index.php');
        } else {
            $message = 'Erro ao atualizar a categoria: ' . $db->error();
        }
    }
    
    $category['nome'] = $name;
}
?>

<div class="admin-header">
    <h1>Editar Categoria</h1>
    <a href="<?php echo ADMIN_URL; ?>categorias/index.php" class="btn">Voltar</a>
</div>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo $category['nome']; ?>" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>