<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userData = getUserData($_SESSION['user_id'], $pdo);
$pageTitle = 'Página Inicial';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="welcome-message">
        <p>Bem-vindo, <strong><?php echo htmlspecialchars($userData['name']); ?></strong>!</p>
        <p>Seu login: <?php echo htmlspecialchars($userData['login']); ?></p>
    </div>
    
    <div class="quick-actions">
        <a href="profile.php" class="btn">Editar Perfil</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>