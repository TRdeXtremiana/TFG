<?php
session_start();
require_once '../backend/auth.php'; // Ruta al archivo con la lógica

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$nombre = htmlspecialchars($_POST['nombre']);
	$email = htmlspecialchars($_POST['email']);
	$password = $_POST['password'];

	$resultado = registrarUsuario($nombre, $email, $password);
	if ($resultado === true) {
		// Guardar el nombre en la sesión y redirigir
		$_SESSION['user_name'] = $nombre;
		header('Location: gestion_gastos.php');
		exit();
	} else {
		$error = $resultado; // Error devuelto desde la función
	}
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/CSS/styles.css">
	<title>Registro</title>
</head>

<body>
	<div class="login-container">
		<?php if ($error): ?>
			<p class="error"><?= htmlspecialchars($error) ?></p>
		<?php endif; ?>

		<h1 class="centrado">Registro</h1>

		<form method="POST" action="">
			<label for="nombre">Nombre:</label>
			<input type="text" id="nombre" name="nombre" required>

			<label for="email">Correo electrónico:</label>
			<input type="email" id="email" name="email" required>

			<label for="password">Contraseña:</label>
			<input type="password" id="password" name="password" required>

			<button type="submit">Registrarse</button>
		</form>

		<p>¿Ya tienes cuenta? <a href="./login.php">Inicia sesión</a></p>
	</div>
</body>

</html>