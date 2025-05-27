<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    redirect(ADMIN_URL . 'produtos/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['usuario']) ? sanitize($_POST['usuario']) : '';
    $password = isset($_POST['senha']) ? $_POST['senha'] : '';

    if (empty($username) || empty($password)) {
        $error = 'Preencha todos os campos.';
    } else {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, usuario, senha FROM admin_usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['senha'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_usuario'] = $user['usuario'];

                redirect(ADMIN_URL . 'produtos/index.php');
            } else {
                $error = 'Senha incorreta.';
            }
        } else {
            $error = 'Usuário não encontrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Administrativo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Painel Administrativo</h1>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>