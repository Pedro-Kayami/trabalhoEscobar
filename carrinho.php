<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$message = '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add']) && !empty($_GET['add'])) {
    $id = (int)$_GET['add'];
    $product = getProductById($id);
    
    if ($product) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantidade']++;
            $message = 'Quantidade do produto atualizada no carrinho.';
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'nome' => $product['nome'],
                'preco' => $product['preco'],
                'quantidade' => 1
            ];
            $message = 'Produto adicionado ao carrinho.';
        }
    }
}

if (isset($_GET['remove']) && !empty($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        $message = 'Produto removido do carrinho.';
    }
}

if (isset($_POST['update'])) {
    foreach ($_POST['quantidade'] as $id => $quantidade) {
        $id = (int)$id;
        $quantidade = (int)$quantidade;
        
        if (isset($_SESSION['cart'][$id])) {
            if ($quantidade > 0) {
                $_SESSION['cart'][$id]['quantidade'] = $quantidade;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
    
    $message = 'Carrinho atualizado com sucesso.';
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    $message = 'Carrinho limpo com sucesso.';
}
?>

<div class="page-header">
    <h1>Carrinho de Compras</h1>
</div>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (count($_SESSION['cart']) > 0): ?>
    <form method="POST" action="carrinho.php" class="carrinho-form">
        <table class="carrinho-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['nome']; ?></td>
                        <td><?php echo formatPrice($item['preco']); ?></td>
                        <td>
                            <input type="number" name="quantidade[<?php echo $item['id']; ?>]" value="<?php echo $item['quantidade']; ?>" min="1" class="quantidade-input">
                        </td>
                        <td><?php echo formatPrice($item['preco'] * $item['quantidade']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>carrinho.php?remove=<?php echo $item['id']; ?>" class="btn btn-small btn-danger">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td colspan="2"><strong><?php echo formatPrice(getCartTotal()); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="carrinho-acoes">
            <a href="<?php echo BASE_URL; ?>" class="btn">Continuar Comprando</a>
            <a href="<?php echo BASE_URL; ?>carrinho.php?clear" class="btn btn-danger">Limpar Carrinho</a>
            <button type="submit" name="update" class="btn">Atualizar Carrinho</button>
            <a href="<?php echo BASE_URL; ?>finalizar.php" class="btn btn-primary">Finalizar Compra</a>
        </div>
    </form>
<?php else: ?>
    <div class="carrinho-vazio">
        <p>Seu carrinho está vazio.</p>
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Continuar Comprando</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>