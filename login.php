<?php
// login.php
ini_set('session.cookie_httponly', 1); // Impede acesso a cookies via JS (Proteção XSS)
ini_set('session.cookie_samesite', 'Lax'); // Proteção CSRF

session_start();
require_once 'mysqlConnection.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Query segura com Prepared Statement
        $stmt = $pdo->prepare('SELECT id, username, password FROM utilizadores WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Verificação segura da password encriptada
        if ($user && password_verify($password, $user['password'])) {
            // Prevenção contra Session Fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: home.php');
            exit();
        } else {
            // Mensagem genérica para não revelar se o erro foi no username ou na password
            $erro = "Credenciais inválidas. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sessão</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Iniciar sessão</h1>
    <p class="sub">Introduza as suas credenciais para continuar.</p>

    <?php if (!empty($erro)): ?>
        <p style="color: #d9534f; background: #fdf7f7; padding: 10px; border: 1px solid #d9534f; border-radius: 4px;">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="on">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autocomplete="username" maxlength="50" autofocus>

        <label for="password">Palavra-passe</label>
        <input type="password" id="password" name="password" required minlength="8" autocomplete="current-password">
        
        <button type="submit">Entrar</button>
    </form>
    
    <p class="sub" style="margin-top: 20px; text-align: center;">
        Ainda não tem conta? <a href="registo.php" style="color: #2b59c3;">Registe-se</a>
    </p>
</main>
</body>
</html>