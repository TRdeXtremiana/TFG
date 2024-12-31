<?php
// Incluir funciones comunes
include('../backend/functions.php');

// Iniciar sesión
session_start();

// Obtener la información del usuario desde la base de datos
$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
	$userData = getUserData($userId);

	// Si no hay datos, asignar valores predeterminados
	$user_name = $userData['nombre_usuario'] ?? 'Usuario';
	$description = $userData['descripcion'] ?? 'No tienes una descripción configurada.';
	$profile_picture = $userData['foto_perfil'] ?? 'default-profile.png';
} else {
	// Si no hay un usuario identificado
	$user_name = 'Usuario';
	$description = 'No tienes una descripción configurada.';
	$profile_picture = 'default-profile.png';
}

// Si el formulario ha sido enviado, procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
	// Verificar qué campo fue enviado y llamar a la función correspondiente
	if (isset($_POST['user_name'])) {
		$message = updateUserName($userId, $_POST['user_name']);
	}

	if (isset($_POST['description'])) {
		$message = updateDescription($userId, $_POST['description']);
	}

	if (!empty($_FILES['profile_picture']['name'])) {
		$message = updateProfilePicture($userId, $_FILES['profile_picture']);
	}

	// Volver a cargar los datos actualizados del usuario
	$userData = getUserData($userId);
	$user_name = $userData['nombre_usuario'];
	$description = $userData['descripcion'] ?: 'No tienes una descripción configurada.';
	$profile_picture = $userData['foto_perfil'];
}
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

			<div id="perfilContainer">
				<!-- Mostrar la foto de perfil -->
				<div id="fotoPerfil">
					<h3>Foto de Perfil</h3>
					<img src="<?= htmlspecialchars($profile_picture) ?>" alt="Foto de Perfil" width="100" height="100">
				</div>

				<div id="descripcion">
					<!-- Mostrar la descripción -->
					<h3>Descripción:</h3>
					<p><?= htmlspecialchars($description) ?: 'No tienes una descripción configurada.' ?></p>
				</div>
			</div>

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