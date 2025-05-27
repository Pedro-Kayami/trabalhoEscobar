<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$message = '';
$category = ['nome' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
    
    if (empty($name)) {
        $message = 'Por favor, preencha o nome da categoria.';
    } else {
        $stmt = $db->prepare("INSERT INTO categorias (nome) VALUES (?)");
        $stmt->bind_param("s", $name);
        
        if ($stmt->execute()) {
            redirect(ADMIN_URL . 'categorias/index.php');
        } else {
            $message = 'Erro ao cadastrar a categoria: ' . $db->error();
        }
    }
    
    $category['nome'] = $name;
}
?>

<div class="admin-header">
    <h1>Nova Categoria</h1>
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