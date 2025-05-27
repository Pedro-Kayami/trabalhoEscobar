<?php
require_once 'database.php';

// Sanitize input
function sanitize($input) {
    $db = Database::getInstance();
    return $db->escapeString(trim($input));
}

// Redirect to a URL
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Verify admin access
function requireAdmin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . 'login.php');
    }
}

// Format price
function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}

// Get cart item count
function getCartItemCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantidade'];
    }
    
    return $count;
}

// Get cart total
function getCartTotal() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
    
    return $total;
}

// Get all categories
function getAllCategories() {
    $db = Database::getInstance();
    $result = $db->query("SELECT * FROM categorias ORDER BY nome");
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// Get category by ID
function getCategoryById($id) {
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

// Get products with optional category filter
function getProducts($categoryId = null) {
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

// Get product by ID
function getProductById($id) {
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