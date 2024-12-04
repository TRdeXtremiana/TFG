<?php
session_start();

// Redirigir al inicio si ya está logueado
if (isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once '../backend/auth.php'; // Ruta al archivo backend

	// Obtener y limpiar datos del formulario
	$username = htmlspecialchars($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';

	// Validar el login
	$error = login($username, $password) ? '' : 'Credenciales incorrectas.';
	if (!$error) {
		header('Location: index.php');
		exit();
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
		<h1 class="centrado">Iniciar Sesión</h1>

		<form action="login.php" method="POST">
			<label for="username">Usuario o Correo</label>
			<input type="text" id="username" name="username">

			<label for="password">Contraseña</label>
			<input type="password" id="password" name="password">

			<button type="submit">Entrar</button>
		</form>

		<p>¿No tienes cuenta? <a href="./registro.php">Regístrate</a></p>

		<?php if ($error): ?>
			<p class="error"><?= htmlspecialchars($error) ?></p>
		<?php endif; ?>
	</div>
</body>

</html>