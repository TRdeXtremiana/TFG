<?php
// Incluir funciones comunes
include('../backend/functions.php');

// Iniciar sesión
session_start();

// Verificar si el usuario está intentando cerrar sesión
if (isset($_GET['logout'])) {
	logout();
}

// Si el formulario ha sido enviado, guardar los datos en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	handleProfileUpdate();
}

// Obtener la información del usuario desde la sesión
$user_name = $_SESSION['user_name'] ?? 'Usuario';
$description = $_SESSION['description'] ?? '';
$profile_picture = $_SESSION['profile_picture'] ?? 'default-profile.png'; // Imagen por defecto si no tiene foto
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Perfil - Gestión de Gastos</title>
	<link rel="stylesheet" href="../assets/css/styles.css">
	<script src="../assets/JS/script.js" defer></script>
</head>

<body>
	<?php include('header.php'); ?>

	<h1 class="saludo">Hola, <?= htmlspecialchars($user_name) ?></h1>

	<main>
		<section class="profile-section">
			<h2>Editar Perfil</h2>

			<!-- Mostrar la foto de perfil -->
			<h3>Foto de Perfil</h3>
			<img src="<?= htmlspecialchars($profile_picture) ?>" alt="Foto de Perfil" width="100" height="100">

			<!-- Mostrar la descripción -->
			<h3>Descripción:</h3>
			<p><?= htmlspecialchars($description) ?: 'No tienes una descripción configurada.' ?></p>

			<!-- Formulario para editar perfil -->
			<form method="POST" enctype="multipart/form-data">
				<label for="user_name">Nombre:</label>
				<input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>" required>

				<label for="description">Descripción:</label>
				<textarea id="description" name="description"><?= htmlspecialchars($description) ?></textarea>

				<label for="profile_picture">Foto de Perfil:</label>
				<input type="file" id="profile_picture" name="profile_picture" accept="image/*">

				<button type="submit">Guardar Cambios</button>
			</form>
		</section>
	</main>
</body>

</html>