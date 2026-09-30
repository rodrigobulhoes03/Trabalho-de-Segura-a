<?php
// login.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            require_once 'mysqlConnection.php';
            $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: home.php');
                exit();
            } else {
                $erro = "Credenciais inválidas. Tente novamente.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erro = "Ocorreu um erro. Tente novamente mais tarde.";
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
        <p style="color: #d9534f;">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="on">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required maxlength="50" autofocus>

        <label for="password">Palavra-passe</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit">Entrar</button>
    </form>

    <p class="sub">
        Ainda não tem conta? <a href="registo.php">Registe-se</a>
    </p>
</main>
</body>
</html>