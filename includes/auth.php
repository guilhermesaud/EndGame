<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($login, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT id, password FROM user WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function register($name, $login, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetchColumn() > 0) {
        return false;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO user (name, login, password) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $login, $hashedPassword]);
}

function getUserData($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT name, login FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function updateUserData($userId, $name, $password = null, $pdo) {
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET name = ?, password = ? WHERE id = ?");
        return $stmt->execute([$name, $hashedPassword, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE user SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $userId]);
    }
}
?>