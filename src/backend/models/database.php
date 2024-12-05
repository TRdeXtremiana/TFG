<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestiongastos');
define('DB_USER', 'root');
define('DB_PASS', '');  // Cambia esta contraseña si es necesario

// Conexión a la base de datos utilizando PDO
function connect_db()
{
	// Usar variable global de PDO
	static $pdo = null;

	// Si la conexión ya está establecida, no se hace de nuevo
	if ($pdo === null) {
		try {
			// Establecer las opciones de la conexión PDO para mejorar la seguridad y el manejo de errores
			$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Configuración para manejo de errores
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Por defecto, el PDO devolverá resultados como arrays asociativos
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // Desactivar emulación de preparaciones para evitar ataques de inyección SQL
		} catch (PDOException $e) {
			// Manejar errores de conexión
			die("Error de conexión: " . $e->getMessage());
		}
	}

	return $pdo;
}
