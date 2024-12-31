<?php
require_once '../models/database.php';
require_once '../utils/utils.php';

try {
    // Iniciar sesión
    session_start();

    // Verificar que el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['error' => 'Usuario no autenticado'], 403);
        exit();
    }

    // Manejar solicitud POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leer datos enviados en JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Validar datos
        if (!$data || !isset($data['id'])) {
            jsonResponse(['error' => 'Datos inválidos.'], 400);
            exit();
        }

        $id = (int)$data['id'];

        if ($id <= 0) {
            jsonResponse(['error' => 'ID de gasto inválido.'], 400);
            exit();
        }

        // Conectar a la base de datos
        $db = Database::connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Marcar como eliminado
        $query = 'UPDATE gastos SET eliminado = 1, fecha_actualizacion = NOW() WHERE id_gasto = :id_gasto';
        $stmt = $db->prepare($query);
        $stmt->execute([':id_gasto' => $id]);

        // Respuesta exitosa
        jsonResponse(['success' => true, 'message' => 'Gasto marcado como eliminado.']);
    } else {
        // Método no permitido
        jsonResponse(['error' => 'Método no permitido'], 405);
    }
} catch (PDOException $e) {
    // Manejo de errores de base de datos
    error_log('Error en la consulta SQL: ' . $e->getMessage());
    jsonResponse(['error' => 'Error en la consulta SQL: ' . $e->getMessage()], 500);
} catch (Exception $e) {
    // Manejo de errores generales
    error_log('Error general: ' . $e->getMessage());
    jsonResponse(['error' => 'Ocurrió un error inesperado.'], 500);
}
