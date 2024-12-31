<?php
// Iniciar sesión
session_start();

require_once __DIR__ . '/../backend/models/Database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_POST['id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edicion</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/JS/script.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="add-expense">
            <h2>Editar</h2>

            <form id="edit-form" method="POST" action="delete-expense.php">
                <?php
                $stm = $db->prepare('SELECT * FROM gastos WHERE id_gasto = :id_gasto');
                $stm->execute([':id_gasto' => $_POST['id']]);
                $expense = $stm->fetch();
                ?>

                <input type="hidden" id="id" name="id" value="<?= $expense['id_gasto'] ?>">

                <label for="amount">Cantidad:</label>
                <input type="number" id="amount" name="amount" step="any" required min="0.01"
                    value="<?= $expense['cantidad'] ?>">

                <label for="category">Categoría:</label>
                <select id="category" name="category">
                    <?php
                    $stm = $db->prepare('SELECT id_etiqueta, nombre FROM etiquetas ORDER BY id_etiqueta asc');
                    $stm->execute();
                    $categories = $stm->fetchAll();
                    foreach ($categories as $category) {
                        $selected = $category['id_etiqueta'] === $expense['id_etiqueta'] ? 'selected' : '';
                        echo "<option value='{$category['id_etiqueta']}' $selected>{$category['nombre']}</option>";
                    }
                    ?>
                </select>

                <label for="description">Descripción (Opcional):</label>
                <input type="text" id="description" name="description" value="<?= $expense['descripcion'] ?>">

                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required value="<?= $expense['fecha_gasto'] ?>">

                <div class="form-buttons">
                    <button type="submit" id="edit-button">Editar <span id="spinner" class="hidden">⏳</span></button>

                    <!-- Botón de eliminar -->
                    <button type="button" id="delete-button">
                        <img src="../assets/images/trash-can.svg" alt="Eliminar" width="12">
                    </button>
                </div>
            </form>


            <!-- Span para mensajes dinámicos -->
            <span id="expense-message" class="hidden"></span>
        </section>


    </main>
</body>

</html>