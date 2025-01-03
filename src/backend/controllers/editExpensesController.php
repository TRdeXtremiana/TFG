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
        if (!$data || !isset($data['id'], $data['amount'], $data['category'], $data['date'])) {
            jsonResponse(['error' => 'Datos inválidos.'], 400);
            exit();
        }

        $id = (int)$data['id'];
        $amount = (float)$data['amount'];
        $category = (int)$data['category'];
        $description = isset($data['description']) ? trim($data['description']) : null;
        $date = $data['date'];

        // Validar campos
        if ($id <= 0 || $amount <= 0 || $category <= 0 || empty($date)) {
            jsonResponse(['error' => 'Campos inválidos.'], 400);
            exit();
        }

        // Conectar a la base de datos
        $db = Database::connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Actualizar el gasto
        $query = 'UPDATE gastos 
                  SET cantidad = :amount, 
                      id_etiqueta = :category, 
                      descripcion = :description, 
                      fecha_gasto = :date, 
                      fecha_actualizacion = NOW() 
                  WHERE id_gasto = :id_gasto';
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':amount' => $amount,
            ':category' => $category,
            ':description' => $description,
            ':date' => $date,
            ':id_gasto' => $id
        ]);

        // Respuesta exitosa
        jsonResponse(['success' => true, 'message' => 'Gasto actualizado correctamente.']);
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