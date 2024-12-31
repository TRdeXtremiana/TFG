<?php

require_once __DIR__ . '/../models/database.php';

/**
 * Verificar si el nombre de usuario ya está registrado.
 */
function isUsernameRegistered($nombre_usuario)
{
    $pdo = Database::connect();
    $query = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $query->execute(['nombre_usuario' => $nombre_usuario]);
    return $query->rowCount() > 0;
}

/**
 * Verificar si el correo ya está registrado.
 */
function isEmailRegistered($correo)
{
    $pdo = Database::connect();
    $query = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo");
    $query->execute(['correo' => $correo]);
    return $query->rowCount() > 0;
}

/**
 * Registrar un nuevo usuario.
 */
function registrarUsuario($nombre_usuario, $correo, $contrasena)
{
    $pdo = Database::connect();

    // Verificar si el nombre de usuario ya está registrado
    if (isUsernameRegistered($nombre_usuario)) {
        return "El nombre de usuario ya está en uso.";
    }

    // Verificar si el correo ya está registrado
    if (isEmailRegistered($correo)) {
        return "El correo electrónico ya está registrado.";
    }

    try {
        // Insertar el nuevo usuario
        $query = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena) VALUES (:nombre_usuario, :correo, :contrasena)");
        $query->execute([
            'nombre_usuario' => $nombre_usuario,
            'correo' => $correo,
            'contrasena' => password_hash($contrasena, PASSWORD_BCRYPT), // Encriptar la contraseña
        ]);
    } catch (PDOException $e) {
        // Manejo de errores de base de datos
        if ($e->getCode() === '23000') {
            return "El correo electrónico o el nombre de usuario ya están registrados.";
        }
        return "Ocurrió un error al registrar el usuario: " . $e->getMessage();
    }

    return true;
}
