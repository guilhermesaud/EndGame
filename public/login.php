<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (login($login, $password, $pdo)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Login ou senha incorretos.';
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <?php if (isset($_GET['registered'])): ?>
        <div class="success">Cadastro realizado com sucesso! Faça login.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
    </form>
    
    <div class="auth-links">
        <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>