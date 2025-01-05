<?php
session_start();

require_once __DIR__ . '/../backend/models/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Gastos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/JS/script.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="saludo">Hola, <?= htmlspecialchars($user_name) ?></h1>

    <main>
        <section class="add-expense">
            <form id="expense-form">
                <h2>Añadir Gasto</h2>

                <label for="amount">Cantidad:</label>
                <input type="number" id="amount" name="amount" step="any" min="0.01">

                <label for="category">Categoría:</label>
                <select id="category">
                    <?php
                    $stm = $db->prepare('SELECT id_etiqueta, nombre FROM etiquetas ORDER BY id_etiqueta asc');

                    $stm->execute();
                    $categories = $stm->fetchAll();

                    foreach ($categories as $category) {
                        echo "<option value='{$category['id_etiqueta']}'>{$category['nombre']}</option>";
                    }
                    ?>
                </select>

                <label for="description">Descripción (Opcional):</label>
                <input type="text" id="description" name="description" placeholder="Descripción del gasto">

                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required value="<?= date('Y-m-d') ?>">

                <button type="submit" id="submit-button">Añadir <span id="spinner" class="hidden">⏳</span></button>

            </form>

            <span id="expense-message" class="hidden"></span>
        </section>


    </main>
</body>

</html>