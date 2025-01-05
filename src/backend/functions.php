<?php
include('models/database.php');

class UserManager
{
    private static function executeQuery($query, $params)
    {
        $pdo = Database::connect();
        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function getUserData($userId)
    {
        $query = "SELECT nombre_usuario, descripcion, foto_perfil FROM usuarios WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [':id' => $userId]);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }

    public static function updateUserName($userId, $newUserName)
    {
        if (empty($newUserName)) {
            return ['message' => "El nombre no puede estar vacío.", 'class' => 'error'];
        }

        // Obtener el nombre actual del usuario
        $currentData = self::getUserData($userId);
        if ($currentData && $currentData['nombre_usuario'] === $newUserName) {
            // Si el nombre es igual, no hacemos nada ni mostramos mensaje
            return null;
        }

        // Verificar si el nombre de usuario ya existe para otro usuario
        $queryCheck = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = :nombre AND id_usuario != :id";
        $stmtCheck = self::executeQuery($queryCheck, [
            ':nombre' => $newUserName,
            ':id' => $userId
        ]);

        if ($stmtCheck && $stmtCheck->rowCount() > 0) {
            return ['message' => "El nombre de usuario ya está en uso.", 'class' => 'error'];
        }

        // Actualizar el nombre de usuario
        $queryUpdate = "UPDATE usuarios SET nombre_usuario = :nombre WHERE id_usuario = :id";
        $stmtUpdate = self::executeQuery($queryUpdate, [
            ':nombre' => $newUserName,
            ':id' => $userId
        ]);

        if ($stmtUpdate) {
            $_SESSION['user_name'] = $newUserName;
            return ['message' => "Nombre de usuario actualizado correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar el nombre de usuario.", 'class' => 'error'];
    }

    public static function updateDescription($userId, $newDescription)
    {
        if (empty($newDescription)) {
            return ['message' => "La descripción no puede estar vacía.", 'class' => 'error'];
        }

        if (strlen($newDescription) > 150) {
            return ['message' => "La descripción no puede tener más de 150 caracteres.", 'class' => 'error'];
        }

        // Obtener la descripción actual del usuario
        $currentData = self::getUserData($userId);
        if ($currentData && $currentData['descripcion'] === $newDescription) {
            // Si la descripción es igual, no hacemos nada ni mostramos mensaje
            return null;
        }

        // Actualizar la descripción
        $query = "UPDATE usuarios SET descripcion = :descripcion WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [
            ':descripcion' => $newDescription,
            ':id' => $userId
        ]);

        if ($stmt) {
            $_SESSION['description'] = $newDescription;
            return ['message' => "Descripción actualizada correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar la descripción.", 'class' => 'error'];
    }

    public static function updateProfilePicture($userId, $file)
    {
        $baseDir = __DIR__ . '/../assets/images/profileImages/';
        $userDir = $baseDir . $userId . '/';

        // Crear la carpeta del usuario si no existe
        if (!file_exists($userDir) && !mkdir($userDir, 0777, true)) {
            return ['message' => "Error: No se pudo crear el directorio del usuario.", 'class' => 'error'];
        }

        $fileName = basename($file['name']);
        $targetFile = $userDir . $fileName;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['message' => "Error al subir el archivo: Código de error " . $file['error'], 'class' => 'error'];
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            return ['message' => "Error: El archivo debe ser una imagen válida (JPEG, PNG, GIF).", 'class' => 'error'];
        }

        // Verificar si el archivo con el mismo nombre ya existe
        $currentData = self::getUserData($userId);
        $currentProfilePicture = $currentData['foto_perfil'] ?? null;

        if ($currentProfilePicture && $currentProfilePicture === 'assets/images/profileImages/' . $userId . '/' . $fileName) {
            return ['message' => "Error: Ya existe una foto de perfil con ese nombre. Cambie el nombre del archivo e intente nuevamente.", 'class' => 'error'];
        }

        // Mover el archivo a la carpeta del usuario
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['message' => "Error al mover el archivo a la carpeta del usuario.", 'class' => 'error'];
        }

        // Guardar la ruta relativa en la base de datos
        $relativePath = 'assets/images/profileImages/' . $userId . '/' . $fileName;
        $query = "UPDATE usuarios SET foto_perfil = :foto WHERE id_usuario = :id";
        $stmt = self::executeQuery($query, [
            ':foto' => $relativePath,
            ':id' => $userId
        ]);

        if ($stmt) {
            $_SESSION['profile_picture'] = $relativePath;
            return ['message' => "Foto de perfil actualizada correctamente.", 'class' => 'exito'];
        }

        return ['message' => "Error al actualizar la foto de perfil.", 'class' => 'error'];
    }
}