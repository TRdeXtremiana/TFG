<?php
include('../backend/functions.php');
session_start();

// Obtiene el ID del usuario de la sesión, si existe
$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    // Si el usuario está autenticado, obtiene los datos de su perfil
    $userData = UserManager::getUserData($userId);

    // Asigna valores predeterminados si faltan datos del perfil
    $user_name = $userData['nombre_usuario'] ?? 'Usuario';
    $description = $userData['descripcion'] ?? 'No tienes una descripción configurada';
    $profile_picture = $userData['foto_perfil'] ?? '../assets/images/account-circle.svg';

    // Verifica si el archivo de la foto de perfil existe, si no, usa una imagen predeterminada
    if (!file_exists(__DIR__ . '/../' . $profile_picture)) {
        $profile_picture = '../assets/images/account-circle.svg';
    }
} else {
    // Si el usuario no está autenticado, usa valores predeterminados
    $user_name = 'Usuario';
    $description = 'No tienes una descripción configurada';
    $profile_picture = '../assets/images/account-circle.svg';
}

// Procesa cambios en el perfil si se envía un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    $messages = []; // Almacena mensajes de éxito o error

    // Actualiza el nombre de usuario si se envió un nuevo nombre
    if (isset($_POST['user_name'])) {
        $messages[] = UserManager::updateUserName($userId, $_POST['user_name']);
    }

    // Actualiza la descripción si se envió una nueva
    if (isset($_POST['description'])) {
        $messages[] = UserManager::updateDescription($userId, $_POST['description']);
    }

    // Actualiza la foto de perfil si se subió un nuevo archivo
    if (!empty($_FILES['profile_picture']['name'])) {
        $messages[] = UserManager::updateProfilePicture($userId, $_FILES['profile_picture']);
    }

    // Recarga los datos del usuario después de los cambios
    $userData = UserManager::getUserData($userId);
    $user_name = $userData['nombre_usuario'] ?? $user_name;
    $description = $userData['descripcion'] ?? 'No tienes una descripción configurada';
    $profile_picture = $userData['foto_perfil'] ?? '../assets/images/account-circle.svg';

    // Verifica nuevamente si la foto de perfil existe
    if (!file_exists(__DIR__ . '/../' . $profile_picture)) {
        $profile_picture = '../assets/images/account-circle.svg';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Gestión de Gastos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/JS/script.js" defer></script>
</head>

<body>
    <?php include('header.php'); // Incluye la cabecera de la página
    ?>

    <h1 class="saludo">Hola, <?= htmlspecialchars($user_name) ?></h1>

    <main>
        <section class="profile-section">
            <div id="perfilContainer">
                <!-- Muestra la foto de perfil del usuario -->
                <div id="fotoPerfil">
                    <h3>Foto de Perfil</h3>
                    <?php if (file_exists(__DIR__ . '/../' . $profile_picture)): ?>
                    <img src="../<?= htmlspecialchars($profile_picture) ?>" alt="Foto de Perfil" width="100"
                        height="100">
                    <?php else: ?>
                    <img src="../assets/images/account-circle.svg" alt="Foto de Perfil Predeterminada" width="100"
                        height="100">
                    <?php endif; ?>
                </div>

                <!-- Muestra la descripción del usuario -->
                <div id="descripcion">
                    <h3>Descripción:</h3>
                    <p><?= htmlspecialchars($description) ?></p>
                </div>
            </div>

            <!-- Muestra los mensajes de éxito o error después de guardar cambios -->
            <?php if (!empty($messages)): ?>
            <div class="messages-container">
                <?php foreach ($messages as $msg): ?>
                <?php if ($msg !== null): ?>
                <div class="<?= htmlspecialchars($msg['class']) ?>"><?= htmlspecialchars($msg['message']) ?></div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Formulario para editar los datos del perfil -->
            <form method="POST" enctype="multipart/form-data">
                <label for="user_name">Nombre:</label>
                <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>">

                <label for="description">Descripción:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($description) ?></textarea>

                <label for="profile_picture">Foto de Perfil:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

                <button type="submit">Guardar Cambios</button>
            </form>
        </section>
    </main>
</body>

</html>