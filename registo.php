<?php

$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($username) < 3 || strlen($username) > 50) {
        $mensagem = 'O username deve ter entre 3 e 50 caracteres.';
    } elseif (strlen($password) < 8) {
        $mensagem = 'A password deve ter pelo menos 8 caracteres.';
    } else {
        try {
            require 'mysqlConnection.php';
            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            $sucesso = true;
            $mensagem = 'Conta criada! Já pode iniciar sessão.';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensagem = 'Este username já existe.';
            } else {
                error_log($e->getMessage());
                $mensagem = 'Erro ao criar a conta.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Registo</h1>

    <?php if ($mensagem): ?>
        <p class="<?php echo $sucesso ? 'ok' : 'erro'; ?>"><?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Username <input type="text" name="username" maxlength="50" required></label>
        <label>Password <input type="password" name="password" minlength="8" required></label>
        <button type="submit">Registar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Iniciar sessão</a></p>
</main>
</body>
</html>