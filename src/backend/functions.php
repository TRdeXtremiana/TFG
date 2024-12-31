<?php
include('models/database.php');

function getUserData($userId)
{
    $pdo = Database::connect();

    try {
        $stmt = $pdo->prepare("SELECT nombre_usuario, descripcion, foto_perfil FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

function updateUserName($userId, $newUserName)
{
    $pdo = Database::connect();

    if (empty($newUserName)) {
        return "El nombre no puede estar vacío.";
    }

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = :nombre WHERE id_usuario = :id");
        $stmt->execute([
            ':nombre' => $newUserName,
            ':id' => $userId
        ]);

        $_SESSION['user_name'] = $newUserName;
        return "Nombre de usuario actualizado correctamente.";
    } catch (PDOException $e) {
        return "Error al actualizar el nombre de usuario: " . $e->getMessage();
    }
}

function updateDescription($userId, $newDescription)
{
    $pdo = Database::connect();

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET descripcion = :descripcion WHERE id_usuario = :id");
        $stmt->execute([
            ':descripcion' => $newDescription,
            ':id' => $userId
        ]);

        $_SESSION['description'] = $newDescription;
        return "Descripción actualizada correctamente.";
    } catch (PDOException $e) {
        return "Error al actualizar la descripción: " . $e->getMessage();
    }
}

function updateProfilePicture($userId, $file)
{
    // Ruta al directorio de imágenes
    $targetDir = __DIR__ . "../assets/images/profileImages/";

    // Crear la carpeta si no existe
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($file['name']);
    $targetFile = $targetDir . $fileName;

    // Validar el archivo subido
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Error al subir el archivo: " . $file['error'];
    }

    // Mover el archivo a la carpeta de destino
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        return "Error al mover el archivo a la carpeta de destino.";
    }

    $pdo = Database::connect();

    try {
        // Guardar la ruta relativa en la base de datos
        $relativePath = "src/assets/images/profileImages/" . $fileName;
        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = :foto WHERE id_usuario = :id");
        $stmt->execute([
            ':foto' => $relativePath,
            ':id' => $userId
        ]);

        $_SESSION['profile_picture'] = $relativePath;
        return "Foto de perfil actualizada correctamente.";
    } catch (PDOException $e) {
        return "Error al actualizar la foto de perfil: " . $e->getMessage();
    }
}
