<?php
// Verificar si el usuario está intentando cerrar sesión
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Obtener la página actual para asignar clases activas
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header>
    <h1><a href="./index.php">Gestión de Gastos</a></h1>

    <!-- Menú de navegación (visible solo en escritorio) -->
    <nav id="desktop-menu">
        <a href="profile.php" class="menu-link <?php if ($currentPage == 'profile.php') echo 'active' ?>">Perfil</a>
        <a href="history.php" class="menu-link <?php if ($currentPage == 'history.php') echo 'active' ?>">Historial</a>
        <a href="?logout=true" class="menu-link">Cerrar Sesión</a>
    </nav>
</header>