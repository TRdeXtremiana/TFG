<?php

// Configuración de la base de datos
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'gestorgastos');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

/**
 * Clase para manejar la conexión a la base de datos utilizando PDO.
 */
class Database
{
	private static $pdo = null;

	/**
	 * Establece una conexión a la base de datos y devuelve la instancia PDO.
	 * Muestra mensajes de estado durante la conexión.
	 *
	 * @return PDO|null
	 */
	public static function connect()
	{
		if (self::$pdo === null) {
			// echo "Cargando conexión a la base de datos...<br>";

			try {
				self::$pdo = new PDO(
					"mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
					DB_USER,
					DB_PASS,
					[
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo fetch asociativo
						PDO::ATTR_EMULATE_PREPARES => false, // Deshabilitar emulación de preparaciones
					]
				);
				// echo "¡Conexión exitosa a la base de datos!<br>";
			} catch (PDOException $e) {
				error_log("Error de conexión: " . $e->getMessage(), 3, __DIR__ . '/error_log.log'); // Log del error
				die("Error de conexión a la base de datos. Ver logs para más información.");
			}
		}

		return self::$pdo;
	}
}

$db = Database::connect();