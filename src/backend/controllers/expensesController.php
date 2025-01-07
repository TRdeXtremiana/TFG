<?php
require_once '../models/database.php';
require_once '../utils/utils.php';

try {
    // Configuración de errores para facilitar la depuración
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start(); // Iniciar la sesión
    if (!isset($_SESSION['user_id'])) {
        // Comprobar si el usuario está autenticado
        jsonResponse(['error' => 'Usuario no autenticado'], 403);
    }

    // Coger el ID del usuario autenticado
    $id_usuario = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Solo se permiten solicitudes POST
        $input = file_get_contents('php://input'); // Coger los datos recibidos
        error_log("Datos recibidos en POST: " . $input);
        $data = json_decode($input, true);

        // Comprobar que los datos sean válidos y existan.
        if (!$data) {
            error_log('Error al decodificar JSON: ' . json_last_error_msg());
            jsonResponse(['error' => 'Datos inválidos.'], 400);
            exit();
        }

        // Coger y comprobar los datos del cuerpo de la solicitud
        $cantidad = isset($data['amount']) ? (float)$data['amount'] : 0;
        $id_etiqueta = isset($data['category']) ? (int)$data['category'] : 0;
        $descripcion = !empty($data['description']) ? htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8') : null;
        $fecha_gasto = $data['date'];

        // Validaciones para asegurar datos correctos
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $cantidad) || $cantidad <= 0) {
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar una cantidad válida.'], 400);
            exit();
        }

        if (!is_null($descripcion) && strlen($descripcion) > 255) {
            jsonResponse(['error' => 'Descripción demasiado larga. Máximo 255 caracteres.'], 400);
            exit();
        }

        if ($id_etiqueta < 0) {
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar una categoría válida.'], 400);
            exit();
        }

        if (empty($fecha_gasto) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_gasto)) {
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar fecha válida.'], 400);
            exit();
        }

        // Conexión a la base de datos
        $db = Database::connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Mete el gasto en la base de datos
        $query = 'INSERT INTO gastos (id_usuario, id_etiqueta, cantidad, descripcion, fecha_gasto, fecha_creacion, eliminado) 
                  VALUES (:id_usuario, :id_etiqueta, :cantidad, :descripcion, :fecha_gasto, NOW(), 0)';
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_etiqueta' => $id_etiqueta,
            ':cantidad' => $cantidad,
            ':descripcion' => $descripcion,
            ':fecha_gasto' => $fecha_gasto
        ]);

        // Responde con éxito si la operación se realiza correctamente
        jsonResponse(['success' => true, 'message' => 'Gasto añadido con éxito']);
    } else {
        // Responde con un error si el método no es POST
        jsonResponse(['error' => 'Método no permitido'], 405);
    }
} catch (PDOException $e) {
    // Manejar errores específicos de la base de datos
    error_log('Error en la consulta SQL: ' . $e->getMessage());
    jsonResponse(['error' => 'Error en la consulta SQL: ' . $e->getMessage()], 500);
} catch (Exception $e) {
    // Manejar errores generales
    error_log('Error general: ' . $e->getMessage());
    jsonResponse(['error' => 'Ocurrió un error inesperado.'], 500);
}