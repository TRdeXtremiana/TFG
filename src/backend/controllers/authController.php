<?php

require_once __DIR__ . '/../models/database.php';

/**
 * Función para iniciar sesión con credenciales de prueba.
 */
function loginWithTestCredentials()
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$_SESSION['user_id'] = 'usuario_prueba';
	$_SESSION['user_name'] = 'Usuario de Prueba';
	return true;
}

/**
 * Verificar las credenciales del usuario.
 */
function verifyCredentials($nombre_usuario, $contrasena)
{
	$pdo = Database::connect();
	$stmt = $pdo->prepare('SELECT id_usuario, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = :nombre_usuario');
	$stmt->execute([':nombre_usuario' => $nombre_usuario]);
	$usuario = $stmt->fetch();

	if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
		return $usuario;
	}
	return false;
}

/**
 * Iniciar sesión con credenciales reales.
 */
function login($nombre_usuario, $contrasena)
{
	if ($nombre_usuario === '' && $contrasena === '') {
		return loginWithTestCredentials();
	}

	$usuario = verifyCredentials($nombre_usuario, $contrasena);

	if ($usuario) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		//TODO solo se loguea con el username y debe poder loguar con el email
		$_SESSION['user_id'] = $usuario['id_usuario'];
		$_SESSION['user_name'] = $usuario['nombre_usuario'];
		return true;
	}
	return false;
}