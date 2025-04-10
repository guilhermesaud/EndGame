<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$fieldErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    // Validação no servidor
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetchColumn() > 0) {
        $fieldErrors['login'] = 'Este login já está em uso';
    }
    
    if (empty($fieldErrors)) {
        if (register($name, $login, $password, $pdo)) {
            $_SESSION['message'] = 'Cadastro realizado com sucesso! Faça login.';
            $_SESSION['message_type'] = 'success';
            header('Location: login.php');
            exit;
        } else {
            $error = 'Erro ao registrar. Tente novamente.';
        }
    }
}

$pageTitle = 'Registro';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form id="registerForm" method="post" novalidate>
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" class="form-control" 
                   value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>" 
                   required autocomplete="username">
            <div id="loginError" class="error-text">
                <?php echo $fieldErrors['login'] ?? ''; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn" id="submitBtn">Cadastrar</button>
    </form>
    
    <div class="auth-links">
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginInput = document.getElementById('login');
    const loginError = document.getElementById('loginError');
    const registerForm = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Validação on blur (quando sai do campo)
    loginInput.addEventListener('blur', function() {
        validateLogin(this.value);
    });
    
    // Validação antes do envio
    registerForm.addEventListener('submit', function(e) {
        if (!validateLogin(loginInput.value)) {
            e.preventDefault();
        }
    });
    
    function validateLogin(login) {
        if (login.length < 8) {
            loginError.textContent = 'O login deve ter pelo menos 8 caracteres';
            return false;
        }
        
        // Mostra loading
        loginError.textContent = 'Verificando...';
        loginError.style.color = '#666';
        
        fetch('../includes/check_login.php?login=' + encodeURIComponent(login))
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    loginError.textContent = 'Este login já está em uso';
                    loginError.style.color = '#dc3545';
                    submitBtn.disabled = true;
                    return false;
                } else {
                    loginError.textContent = 'Login disponível';
                    loginError.style.color = '#28a745';
                    submitBtn.disabled = false;
                    return true;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                loginError.textContent = 'Erro ao verificar login';
                return false;
            });
        
        return true;
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>