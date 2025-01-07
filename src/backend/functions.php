<?php
include('models/database.php'); // Incluye el archivo que permite conectarse a la base de datos

class UserManager
{
    // Función privada que ejecuta consultas en la base de datos
    private static function executeQuery($query, $params)
    {
        $pdo = Database::connect(); // Conecta a la base de datos
        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            return null; // Devuelve null si hay un error
        }
    }

    // Función para obtener los datos básicos de un usuario por su ID.
    public static function getUserData($userId)
    {
        $query = "SELECT nombre_usuario, descripcion, foto_perfil FROM usuarios WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [':id' => $userId]); // Ejecuta la consulta.
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null; // Devuelve los datos si hay resultado.
    }

    // Función para actualizar el nombre de usuario
    public static function updateUserName($userId, $newUserName)
    {
        if (empty($newUserName)) { // Comprueba que el nuevo nombre no esté vacío
            return ['message' => "El nombre no puede estar vacío.", 'class' => 'error'];
        }

        $currentData = self::getUserData($userId); // Obtiene los datos actuales del usuario.
        if ($currentData && $currentData['nombre_usuario'] === $newUserName) {
            return null; // No hace nada si el nombre ya es el mismo.
        }

        // Comprueba si el nuevo nombre ya lo usa otro usuario.
        $queryCheck = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = :nombre AND id_usuario != :id";
        $stmtCheck = self::executeQuery($queryCheck, [
            ':nombre' => $newUserName,
            ':id' => $userId
        ]);

        if ($stmtCheck && $stmtCheck->rowCount() > 0) {
            return ['message' => "El nombre de usuario ya está en uso.", 'class' => 'error'];
        }

        // Actualiza el nombre en la base de datos.
        $queryUpdate = "UPDATE usuarios SET nombre_usuario = :nombre WHERE id_usuario = :id";
        $stmtUpdate = self::executeQuery($queryUpdate, [
            ':nombre' => $newUserName,
            ':id' => $userId
        ]);

        if ($stmtUpdate) {
            $_SESSION['user_name'] = $newUserName; // Actualiza el nombre en la sesión.
            return ['message' => "Nombre de usuario actualizado correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar el nombre de usuario.", 'class' => 'error'];
    }

    // Función pública para actualizar la descripción del usuario.
    public static function updateDescription($userId, $newDescription)
    {
        if (empty($newDescription)) { // Comprueba que la descripción no esté vacía.
            return ['message' => "La descripción no puede estar vacía.", 'class' => 'error'];
        }

        if (strlen($newDescription) > 150) { // Comprueba que no exceda 150 caracteres.
            return ['message' => "La descripción no puede tener más de 150 caracteres.", 'class' => 'error'];
        }

        $currentData = self::getUserData($userId); // Obtiene los datos actuales del usuario.
        if ($currentData && $currentData['descripcion'] === $newDescription) {
            return null; // No hace nada si la descripción ya es la misma.
        }

        // Actualiza la descripción en la base de datos.
        $query = "UPDATE usuarios SET descripcion = :descripcion WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [
            ':descripcion' => $newDescription,
            ':id' => $userId
        ]);

        if ($stmt) {
            $_SESSION['description'] = $newDescription; // Actualiza la descripción en la sesión.
            return ['message' => "Descripción actualizada correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar la descripción.", 'class' => 'error'];
    }

    // Función pública para actualizar la foto de perfil del usuario.
    public static function updateProfilePicture($userId, $file)
    {
        $baseDir = __DIR__ . '/../assets/images/profileImages/'; // Directorio base de imágenes.
        $userDir = $baseDir . $userId . '/'; // Carpeta específica de cada usuario.

        // Crea la carpeta del usuario si no existe.
        if (!file_exists($userDir) && !mkdir($userDir, 0777, true)) {
            return ['message' => "Error: No se pudo crear el directorio del usuario.", 'class' => 'error'];
        }

        $fileName = basename($file['name']); // Obtiene el nombre del archivo subido.
        $targetFile = $userDir . $fileName; // Ruta completa para guardar el archivo.

        if ($file['error'] !== UPLOAD_ERR_OK) { // Comprueba si hubo errores al subir el archivo.
            return ['message' => "Error al subir el archivo: Código de error " . $file['error'], 'class' => 'error'];
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Tipos permitidos de imagen.
        $fileType = mime_content_type($file['tmp_name']); // Obtiene el tipo de archivo subido.
        if (!in_array($fileType, $allowedTypes)) {
            return ['message' => "Error: El archivo debe ser una imagen válida (JPEG, PNG, GIF).", 'class' => 'error'];
        }

        // Comprueba si ya existe una imagen con el mismo nombre.
        $currentData = self::getUserData($userId);
        $currentProfilePicture = $currentData['foto_perfil'] ?? null;

        if ($currentProfilePicture && $currentProfilePicture === 'assets/images/profileImages/' . $userId . '/' . $fileName) {
            return ['message' => "Error: Ya existe una foto de perfil con ese nombre. Cambie el nombre del archivo e intente nuevamente.", 'class' => 'error'];
        }

        // Mueve el archivo a la carpeta del usuario.
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['message' => "Error al mover el archivo a la carpeta del usuario.", 'class' => 'error'];
        }

        // Guarda la nueva ruta de la foto en la base de datos.
        $relativePath = 'assets/images/profileImages/' . $userId . '/' . $fileName;
        $query = "UPDATE usuarios SET foto_perfil = :foto WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [
            ':foto' => $relativePath,
            ':id' => $userId
        ]);

        if ($stmt) {
            $_SESSION['profile_picture'] = $relativePath; // Actualiza la foto en la sesión.
            return ['message' => "Foto de perfil actualizada correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar la foto de perfil.", 'class' => 'error'];
    }
}