<?php

require_once __DIR__ . '/../models/database.php';

/**
 * Confirmar las credenciales del usuario.
 */
function verifyCredentials($identifier, $contrasena)
{
	$pdo = Database::connect();

	// Usar el nombre correcto de la columna: correo
	$stmt = $pdo->prepare('SELECT id_usuario, nombre_usuario, correo, contrasena 
                           FROM usuarios 
                           WHERE nombre_usuario = :username OR correo = :correo');
	$stmt->execute([
		':username' => $identifier,
		':correo' => $identifier
	]);

	$usuario = $stmt->fetch();

	// Confirmar si la contraseña coincide
	if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
		return $usuario;
	}
	return false;
}


/**
 * Iniciar sesión con credenciales reales.
 */
function login($identifier, $contrasena)
{
	$usuario = verifyCredentials($identifier, $contrasena);

	if ($usuario) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		// Almacenar los datos del usuario en la sesión
		$_SESSION['user_id'] = $usuario['id_usuario'];
		$_SESSION['user_name'] = $usuario['nombre_usuario'];
		return true;
	}
	return false;
}
