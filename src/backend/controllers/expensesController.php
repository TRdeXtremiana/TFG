<?php
// Incluir la conexión a la base de datos
require_once '../backend/database.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el contenido del cuerpo de la solicitud
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validar los datos recibidos
    if (
        isset($data['cantidad'], $data['fecha_gasto'], $data['descripcion'], $data['id_etiqueta']) &&
        is_numeric($data['cantidad']) &&
        !empty($data['fecha_gasto'])
    ) {
        $cantidad = (float) $data['cantidad'];
        $fecha_gasto = $data['fecha_gasto'];
        $descripcion = !empty($data['descripcion']) ? htmlspecialchars($data['descripcion']) : null;
        $id_etiqueta = !empty($data['id_etiqueta']) ? (int) $data['id_etiqueta'] : null;

        // Obtener el ID del usuario desde la sesión
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit();
        }

        $id_usuario = $_SESSION['user_id'];

        // Insertar el gasto en la base de datos
        try {
            $db = Database::connect(); // Obtener conexión a la base de datos
            $query = 'INSERT INTO gastos (id_usuario, id_etiqueta, cantidad, descripcion, fecha_gasto, eliminado) 
                      VALUES (:id_usuario, :id_etiqueta, :cantidad, :descripcion, :fecha_gasto, 0)';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_etiqueta', $id_etiqueta, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_gasto', $fecha_gasto, PDO::PARAM_STR);
            $stmt->execute();

            // Responder con éxito
            echo json_encode(['success' => true, 'message' => 'Gasto añadido con éxito']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el gasto: ' . $e->getMessage()]);
        }
    } else {
        // Responder con error si los datos son inválidos
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
    }
} else {
    // Responder con error si no es una solicitud POST
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}