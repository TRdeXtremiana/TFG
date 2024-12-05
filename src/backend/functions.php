<?php
// Función para cerrar sesión
function logout()
{
	// Borrar todas las variables de sesión y destruir la sesión
	session_unset();
	session_destroy();

	// Redirigir al login
	header('Location: login.php');
	exit();
}

// Función para manejar la actualización del perfil
function handleProfileUpdate()
{
	if (isset($_POST['user_name'])) {
		$_SESSION['user_name'] = htmlspecialchars($_POST['user_name']);
	}

	if (isset($_POST['description'])) {
		$_SESSION['description'] = htmlspecialchars($_POST['description']);
	}

	if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
		$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
		$file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);

		if (in_array($file_extension, $allowed_extensions)) {
			// Subir imagen al servidor (en una carpeta 'uploads')
			$upload_dir = 'uploads/';
			$file_path = $upload_dir . basename($_FILES['profile_picture']['name']);
			move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path);
			$_SESSION['profile_picture'] = $file_path;
		} else {
			$error_message = "Solo se permiten imágenes JPG, JPEG, PNG o GIF.";
		}
	}
}
