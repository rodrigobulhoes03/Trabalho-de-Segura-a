<?php
// registo.php
require_once 'mysqlConnection.php';

$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $mensagem = "Por favor, preencha todos os campos.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $mensagem = "O username deve ter entre 3 e 50 caracteres.";
    } elseif (strlen($password) < 8) {
        $mensagem = "A palavra-passe deve ter pelo menos 8 caracteres.";
    } else {
        // Verificar se o username já existe
        $stmt = $pdo->prepare('SELECT id FROM utilizadores WHERE username = :username');
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch()) {
            $mensagem = "Este nome de utilizador já está em uso.";
        } else {
            // Hash seguro da palavra-passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO utilizadores (username, password) VALUES (:username, :password)');
            if ($stmt->execute(['username' => $username, 'password' => $passwordHash])) {
                $sucesso = true;
                $mensagem = "Conta criada com sucesso! Já pode iniciar sessão.";
            } else {
                $mensagem = "Ocorreu um erro ao criar a conta.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Criar conta</h1>
    <p class="sub">Preencha os campos abaixo para se registar.</p>

    <?php if (!empty($mensagem)): ?>
        <p style="color: <?php echo $sucesso ? '#2e7d32' : '#d9534f'; ?>; background: <?php echo $sucesso ? '#e8f5e9' : '#fdf7f7'; ?>; padding: 10px; border: 1px solid <?php echo $sucesso ? '#2e7d32' : '#d9534f'; ?>; border-radius: 4px;">
            <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="registo.php" autocomplete="on">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autocomplete="username" maxlength="50" autofocus>

        <label for="password">Palavra-passe</label>
        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
        
        <button type="submit">Registar</button>
    </form>

    <p class="sub" style="margin-top: 20px; text-align: center;">
        Já tem conta? <a href="login.php" style="color: #2b59c3;">Iniciar sessão</a>
    </p>
</main>
</body>
</html>