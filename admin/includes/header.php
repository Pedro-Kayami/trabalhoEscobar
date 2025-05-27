<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Loja Virtual</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
</head>

<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">
                <h1>Admin</h1>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="<?php echo ADMIN_URL; ?>categorias/index.php">Categorias</a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>produtos/index.php">Produtos</a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>vendas/index.php">Vendas</a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>logout.php">Sair</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">