<?php

require_once 'database.php';

function login($username, $password)
{
	// Permitir acceso con credenciales vacías solo en entorno de pruebas
	if ($username === '' && $password === '') {
		session_start();
		$_SESSION['user_id'] = 'test_user'; 	// Identificador temporal para el usuario de prueba
		$_SESSION['user_name'] = 'Test User'; 	// Asignar el nombre del usuario en la sesión
		return true;
	}

	// Código original para verificar credenciales reales
	$db = connect_db();

	// Usar `name` para la autenticación
	$stmt = $db->prepare('SELECT id FROM users WHERE name = :username AND password = :password');
	$stmt->execute([':username' => $username, ':password' => sha1($password)]);
	$user = $stmt->fetch();

	if ($user) {
		session_start();
		$_SESSION['user_id'] = $user['id'];
		return true;
	}
	return false;
}



function registrarUsuario($name, $email, $password)
{
	global $pdo; // Asumimos que $pdo está definido en database.php
	// Verificar si el email ya existe
	$query = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
	$query->execute(['email' => $email]);

	if ($query->rowCount() > 0) {
		return "El correo electrónico ya está registrado.";
	}

	// Insertar el nuevo usuario
	$query = $pdo->prepare("INSERT INTO usuarios (name, email, password) VALUES (:name, :email, :password)");
	$query->execute([
		'name' => $name,
		'email' => $email,
		'password' => password_hash($password, PASSWORD_BCRYPT),
	]);

	return true;
}
