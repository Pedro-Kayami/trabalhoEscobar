<?php
require_once 'database.php';

function sanitize($input)
{
    $db = Database::getInstance();
    return $db->escapeString(trim($input));
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['admin_id']);
}

function requireAdmin()
{
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . 'login.php');
    }
}

function formatPrice($price)
{
    return 'R$ ' . number_format($price, 2, ',', '.');
}

function getCartItemCount()
{
    if (!isset($_SESSION['cart'])) {
        return 0;
    }

    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantidade'];
    }

    return $count;
}

function getCartTotal()
{
    if (!isset($_SESSION['cart'])) {
        return 0;
    }

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }

    return $total;
}

function getAllCategories()
{
    $db = Database::getInstance();
    $result = $db->query("SELECT * FROM categorias ORDER BY nome");
    $categories = [];

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}

function getCategoryById($id)
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getProducts($categoryId = null)
{
    $db = Database::getInstance();

    if ($categoryId) {
        $stmt = $db->prepare("SELECT p.*, c.nome as categoria FROM produtos p 
                             JOIN categorias c ON p.categoria_id = c.id 
                             WHERE p.categoria_id = ? 
                             ORDER BY p.nome");
        $stmt->bind_param("i", $categoryId);
    } else {
        $stmt = $db->prepare("SELECT p.*, c.nome as categoria FROM produtos p 
                             JOIN categorias c ON p.categoria_id = c.id 
                             ORDER BY p.nome");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}
function getProductById($id)
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT p.*, c.nome as categoria FROM produtos p 
                         JOIN categorias c ON p.categoria_id = c.id 
                         WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    return $result->fetch_assoc();
}
