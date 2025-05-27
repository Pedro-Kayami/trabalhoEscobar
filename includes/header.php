<?php 
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">Loja Virtual</a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <?php
                        $categories = getAllCategories();
                        foreach ($categories as $category) {
                            echo '<li><a href="' . BASE_URL . 'categoria.php?id=' . $category['id'] . '">' . $category['nome'] . '</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
                <div class="cart-icon">
                    <a href="<?php echo BASE_URL; ?>carrinho.php">
                        Carrinho (<?php echo getCartItemCount(); ?>)
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main class="container">