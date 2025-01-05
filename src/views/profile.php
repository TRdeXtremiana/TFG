<?php
include('../backend/functions.php');
session_start();

$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    $userData = UserManager::getUserData($userId);

    $user_name = $userData['nombre_usuario'] ?? 'Usuario';
    $description = $userData['descripcion'] ?? 'No tienes una descripción configurada.';
    $profile_picture = $userData['foto_perfil'] ?? '../assets/images/account-circle.svg';

    if (!file_exists(__DIR__ . '/../' . $profile_picture)) {
        $profile_picture = '../assets/images/account-circle.svg';
    }
} else {
    $user_name = 'Usuario';
    $description = 'No tienes una descripción configurada.';
    $profile_picture = '../assets/images/account-circle.svg';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    $messages = [];
    if (isset($_POST['user_name'])) {
        $messages[] = UserManager::updateUserName($userId, $_POST['user_name']);
    }
    if (isset($_POST['description'])) {
        $messages[] = UserManager::updateDescription($userId, $_POST['description']);
    }
    if (!empty($_FILES['profile_picture']['name'])) {
        $messages[] = UserManager::updateProfilePicture($userId, $_FILES['profile_picture']);
    }

    $userData = UserManager::getUserData($userId);
    $user_name = $userData['nombre_usuario'] ?? $user_name;
    $description = $userData['descripcion'] ?? 'No tienes una descripción configurada.';
    $profile_picture = $userData['foto_perfil'] ?? '../assets/images/account-circle.svg';

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
    <?php include('header.php'); ?>

    <h1 class="saludo">Hola, <?= htmlspecialchars($user_name) ?></h1>

    <main>
        <section class="profile-section">
            <div id="perfilContainer">
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

                <div id="descripcion">
                    <h3>Descripción:</h3>
                    <p><?= htmlspecialchars($description) ?></p>
                </div>
            </div>

            <?php if (!empty($messages)): ?>
            <div class="messages-container">
                <?php foreach ($messages as $msg): ?>
                <?php if ($msg !== null): ?>
                <div class="<?= htmlspecialchars($msg['class']) ?>"><?= htmlspecialchars($msg['message']) ?></div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label for="user_name">Nombre:</label>
                <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>" required>

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