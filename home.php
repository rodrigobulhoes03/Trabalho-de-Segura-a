<?php

session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Área reservada</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h1>

    <form method="post">
        <button type="submit" name="logout">Terminar sessão</button>
    </form>
</main>
</body>
</html>