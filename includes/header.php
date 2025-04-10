<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obter nome do usuário se estiver logado
$userName = '';
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/auth.php';
    $userData = getUserData($_SESSION['user_id'], $pdo);
    $userName = htmlspecialchars($userData['name']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'END GAME'; ?></title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="../public/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="../public/favicons/favicon.svg" />
    <link rel="shortcut icon" href="../public/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../public/favicons/apple-touch-icon.png" />
    <link rel="manifest" href="../public/favicons/site.webmanifest" />

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <?php if (basename($_SERVER['PHP_SELF']) === 'login.php' || basename($_SERVER['PHP_SELF']) === 'register.php'): ?>
        <link rel="stylesheet" href="../assets/css/auth.css">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="profile.php">Perfil</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <h1 class="page-title"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'END GAME'; ?></h1>
        </header>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>