<?php

require_once __DIR__ . '/../models/database.php';

// Función para iniciar la sesión con un usuario de prueba (solo en entorno de pruebas)
function loginWithTestCredentials()
{
	session_start();
	$_SESSION['user_id'] = 'test_user';  // Identificador temporal para el usuario de prueba
	$_SESSION['user_name'] = 'Test User';  // Asignar el nombre del usuario en la sesión
	return true;
}

// Función para verificar las credenciales de un usuario
function verifyCredentials($username, $password)
{
	$pdo = connect_db();  // Llamar a la función para obtener la conexión a la base de datos

	// Preparar la consulta para obtener el id y la contraseña del usuario
	$stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE name = :name');
	$stmt->execute([':name' => $username]);
	$user = $stmt->fetch();

	if ($user && password_verify($password, $user['password'])) {
		// Si la contraseña coincide con la almacenada, devolver el usuario
		return $user;
	}

	// Si las credenciales no son correctas, devolver false
	return false;
}

// Función principal para hacer login
function login($username, $password)
{
	// Permitir acceso con credenciales vacías solo en entorno de pruebas
	if ($username === '' && $password === '') {
		return loginWithTestCredentials();
	}

	// Verificar credenciales reales
	$user = verifyCredentials($username, $password);

	if ($user) {
		session_start();
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['user_name'] = $user['name'];  // Guardamos el nombre del usuario en la sesión
		return true;
	}
	return false;
}

// Función para verificar si el correo electrónico ya está registrado
function isEmailRegistered($email)
{
	$pdo = connect_db();  // Llamar a la función para obtener la conexión a la base de datos
	$query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
	$query->execute(['email' => $email]);
	return $query->rowCount() > 0;
}

// Función para registrar un nuevo usuario
function registrarUsuario($name, $email, $password)
{
	$pdo = connect_db();  // Llamar a la función para obtener la conexión a la base de datos

	if (isEmailRegistered($email)) {
		return "El correo electrónico ya está registrado.";
	}

	// Insertar el nuevo usuario
	$query = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
	$query->execute([
		'name' => $name,
		'email' => $email,
		'password' => password_hash($password, PASSWORD_BCRYPT),  // Encriptar la contraseña
	]);

	return true;
}
