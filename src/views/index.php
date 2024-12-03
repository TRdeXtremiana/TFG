<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
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
    <header>
        <button id="menu-toggle" aria-label="Abrir menú">&#9776;</button> <!-- Icono de menú hamburguesa -->
        <h1>Gestión de Gastos</h1>

        <!-- Menú de navegación (visible solo en escritorio) -->
        <nav id="desktop-menu">
            <a href="profile.php">Perfil</a>
            <a href="history.php">Historial</a>
            <a href="../login.php">Cerrar Sesión</a>
        </nav>
    </header>

    <!-- Menú lateral para móviles -->
    <nav id="mobile-menu">
        <a href="profile.php">Perfil</a>
        <a href="history.php">Historial</a>
        <a href="../login.php">Cerrar Sesión</a>
    </nav>

    <main>
        <section class="add-expense">
            <h2>Añadir Gasto</h2>

            <form id="expense-form">
                <label for="amount">Cantidad:</label>
                <input type="number" id="amount" name="amount" required>

                <label for="category">Categoría:</label>
                <input type="text" id="category" name="category" required>

                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required>

                <button type="submit">Añadir</button>
            </form>
        </section>

        <section class="expenses">
            <h2>Gastos Recientes</h2>
            <div id="expenses-list"></div>
        </section>
    </main>
</body>

</html>