<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../backend/auth.php';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Credenciales incorrectas.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/CSS/styles.css">
</head>

<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password">

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>