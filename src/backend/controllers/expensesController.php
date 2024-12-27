<?php
require_once '../models/database.php';
require_once '../utils/utils.php';

try {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['error' => 'Usuario no autenticado'], 403);
    }

    $id_usuario = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        error_log("Datos recibidos en POST: " . $input);
        $data = json_decode($input, true);

        if (!$data) {
            error_log('Error al decodificar JSON: ' . json_last_error_msg());
            jsonResponse(['error' => 'Datos inválidos.'], 400);
            exit();
        }

        $cantidad = isset($data['amount']) ? (float)$data['amount'] : 0;
        $id_etiqueta = isset($data['category']) ? (int)$data['category'] : 0;
        $descripcion = !empty($data['description']) ? htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8') : null;
        $fecha_gasto = $data['date'];

        if ($cantidad <= 0) {
            error_log('Validación fallida: ' . print_r($data, true));
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar cantidad válida.'], 400);
            exit();
        }

        if ($id_etiqueta < 0) {
            //TODO preguntar si quieres crear una etiqueta nueva
            error_log('Validación fallida: ' . print_r($data, true));
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar una categoría válida.'], 400);
            exit();
        }

        if (empty($fecha_gasto) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_gasto)) {
            error_log('Validación fallida: ' . print_r($data, true));
            jsonResponse(['error' => 'Datos inválidos. Asegúrate de enviar fecha válida.'], 400);
            exit();
        }

        $db = Database::connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

        jsonResponse(['success' => true, 'message' => 'Gasto añadido con éxito']);
    } else {
        jsonResponse(['error' => 'Método no permitido'], 405);
    }
} catch (PDOException $e) {
    error_log('Error en la consulta SQL: ' . $e->getMessage());
    jsonResponse(['error' => 'Error en la consulta SQL: ' . $e->getMessage()], 500);
} catch (Exception $e) {
    error_log('Error general: ' . $e->getMessage());
    jsonResponse(['error' => 'Ocurrió un error inesperado.'], 500);
}