<?php
// home.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

// Proteger a página contra acessos não autorizados
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Lógica de terminação de sessão (Logout)
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Área Reservada</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <!-- htmlspecialchars previne vulnerabilidades de XSS -->
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
    <p class="sub">Autenticação efetuada com sucesso.</p>
    
    <a href="home.php?logout=1" style="display: block; text-align: center; margin-top: 20px; color: #d9534f;">Sair da Conta</a>
</main>
</body>
</html>