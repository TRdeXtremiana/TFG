<?php
require_once '../models/database.php';
require_once '../utils/utils.php';

try {
    session_start();

    // Confirmar autenticación
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['error' => 'Usuario no autenticado'], 403);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data) {
            jsonResponse(['error' => 'Datos incompletos.'], 400);
            exit();
        }

        // Manejo de eliminación
        if (isset($data['id']) && count($data) === 1) {
            $id = (int)$data['id'];

            if ($id <= 0) {
                jsonResponse(['error' => 'ID inválido.'], 400);
                exit();
            }

            $db = Database::connect();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = 'DELETE FROM gastos WHERE id_gasto = :id_gasto';
            $stmt = $db->prepare($query);
            $stmt->execute([':id_gasto' => $id]);

            if ($stmt->rowCount() > 0) {
                jsonResponse(['success' => true, 'message' => 'Registro eliminado correctamente.']);
            } else {
                jsonResponse(['error' => 'No se encontró el registro.'], 404);
            }
            exit();
        }

        // Validar datos principales para edición
        if (!isset($data['id'], $data['amount'], $data['category'], $data['date'])) {
            jsonResponse(['error' => 'Datos incompletos.'], 400);
            exit();
        }

        $id = (int)$data['id'];
        $amount = (float)$data['amount'];
        $category = (int)$data['category'];
        $description = isset($data['description']) ? trim($data['description']) : '';
        $date = $data['date'];

        // Validaciones específicas
        if ($id <= 0) {
            jsonResponse(['error' => 'ID inválido.'], 400);
            exit();
        }

        if ($amount <= 0 || $amount > 1000000) {
            jsonResponse(['error' => 'El monto debe ser mayor que 0 y menor que 1,000,000.'], 400);
            exit();
        }

        if ($category <= 0) {
            jsonResponse(['error' => 'Categoría inválida.'], 400);
            exit();
        }

        if (strlen($description) > 255) {
            jsonResponse(['error' => 'La descripción no puede superar los 255 caracteres.'], 400);
            exit();
        }

        if (!empty($description) && !preg_match('/^[\w\s.,áéíóúÁÉÍÓÚñÑ!?-]*$/u', $description)) {
            jsonResponse(['error' => 'La descripción contiene caracteres no permitidos.'], 400);
            exit();
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            jsonResponse(['error' => 'La fecha debe estar en el formato YYYY-MM-DD.'], 400);
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

        jsonResponse(['success' => true, 'message' => 'Gasto actualizado correctamente.']);
    } else {
        jsonResponse(['error' => 'Método no permitido.'], 405);
    }
} catch (PDOException $e) {
    error_log('Error en la consulta SQL: ' . $e->getMessage());
    jsonResponse(['error' => 'Error en la consulta SQL.'], 500);
} catch (Exception $e) {
    error_log('Error general: ' . $e->getMessage());
    jsonResponse(['error' => 'Ocurrió un error inesperado.'], 500);
}
