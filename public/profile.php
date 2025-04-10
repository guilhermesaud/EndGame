<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$userData = getUserData($userId, $pdo);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $currentPassword = !empty($_POST['current_password']) ? $_POST['current_password'] : null;
    
    if ($password) {
        $stmt = $pdo->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->execute([$userId]);
        $dbPassword = $stmt->fetchColumn();
        
        if (!password_verify($currentPassword, $dbPassword)) {
            $error = 'Senha atual incorreta.';
        }
    }
    
    if (!$error) {
        if (updateUserData($userId, $name, $password, $pdo)) {
            $success = 'Dados atualizados com sucesso!';
            $userData = getUserData($userId, $pdo);
        } else {
            $error = 'Erro ao atualizar dados.';
        }
    }
}

$pageTitle = 'Meu Perfil';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="profile-container">
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form id="profileForm" method="post" class="profile-form">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($userData['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" id="login" class="form-control" 
                   value="<?php echo htmlspecialchars($userData['login']); ?>" 
                   readonly tabindex="-1">
        </div>
        
        <div class="form-group">
            <label for="password">Nova Senha (deixe em branco para não alterar):</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="current_password">Senha Atual (necessária para alterar senha):</label>
            <input type="password" id="current_password" name="current_password" class="form-control">
        </div>
        
        <button type="submit" class="btn" id="submitBtn" disabled>Atualizar</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const nameInput = document.getElementById('name');
    const passwordInput = document.getElementById('password');
    const currentPasswordInput = document.getElementById('current_password');
    const submitBtn = document.getElementById('submitBtn');
    
    // Valores iniciais para comparação
    const initialValues = {
        name: nameInput.value,
        password: '',
        current_password: ''
    };
    
    // Verifica alterações nos campos
    function checkForChanges() {
        const hasNameChanged = nameInput.value !== initialValues.name;
        const hasPasswordChanged = passwordInput.value !== initialValues.password;
        const needsCurrentPassword = hasPasswordChanged && currentPasswordInput.value === '';
        
        // Habilita o botão se houver mudanças válidas
        submitBtn.disabled = !(hasNameChanged || hasPasswordChanged) || needsCurrentPassword;
    }
    
    // Monitora alterações em todos os campos
    [nameInput, passwordInput, currentPasswordInput].forEach(input => {
        input.addEventListener('input', checkForChanges);
    });
    
    // Verifica ao carregar a página
    checkForChanges();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>