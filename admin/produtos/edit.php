<?php
require_once '../includes/header.php';

$db = Database::getInstance();
$message = '';
$product = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        redirect(ADMIN_URL . 'produtos/index.php');
    }
} else {
    redirect(ADMIN_URL . 'produtos/index.php');
}

$result = $db->query("SELECT * FROM categorias ORDER BY nome");
$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
    $price = isset($_POST['preco']) ? str_replace(',', '.', $_POST['preco']) : '';
    $description = isset($_POST['descricao']) ? sanitize($_POST['descricao']) : '';
    $categoryId = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : '';
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Por favor, preencha o nome do produto.';
    }
    
    if (empty($price) || !is_numeric($price)) {
        $errors[] = 'Por favor, informe um preço válido.';
    }
    
    if (empty($categoryId)) {
        $errors[] = 'Por favor, selecione uma categoria.';
    }
    
    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE produtos SET nome = ?, preco = ?, descricao = ?, categoria_id = ? WHERE id = ?");
        $stmt->bind_param("sdsii", $name, $price, $description, $categoryId, $id);
        
        if ($stmt->execute()) {
            redirect(ADMIN_URL . 'produtos/index.php');
        } else {
            $message = 'Erro ao atualizar o produto: ' . $db->error();
        }
    } else {
        $message = implode('<br>', $errors);
    }
    
    $product['nome'] = $name;
    $product['preco'] = $price;
    $product['descricao'] = $description;
    $product['categoria_id'] = $categoryId;
}
?>

<div class="admin-header">
    <h1>Editar Produto</h1>
    <a href="<?php echo ADMIN_URL; ?>produtos/index.php" class="btn">Voltar</a>
</div>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo $product['nome']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço</label>
            <input type="text" id="preco" name="preco" value="<?php echo $product['preco']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="categoria_id">Categoria</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $product['categoria_id'] == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo $category['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="5"><?php echo $product['descricao']; ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>