<?php

session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        require 'mysqlConnection.php';
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: home.php');
            exit;
        }
        $erro = 'Username ou password incorretos.';
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $erro = 'Erro no servidor. Tente mais tarde.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Login</h1>

    <?php if ($erro): ?>
        <p class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Username <input type="text" name="username" maxlength="50" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não tem conta? <a href="registo.php">Registe-se</a></p>
</main>
</body>
</html>