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
	<button id="menu-toggle" aria-label="Abrir menú">&#9776;</button> <!-- Icono de menú hamburguesa -->
	<h1><a href="./index.php">Gestión de Gastos</a></h1>

	<!-- Menú de navegación (visible solo en escritorio) -->
	<nav id="desktop-menu">
		<a href="profile.php" class="<?= $currentPage === 'profile.php' ? 'hidden' : '' ?>">Perfil</a>
		<a href="history.php" class="<?= $currentPage === 'history.php' ? 'hidden' : '' ?>">Historial</a>
		<a href="?logout=true" class="<?= $currentPage === 'index.php' ? 'hidden' : '' ?>">Cerrar Sesión</a>
	</nav>
</header>

<!-- Menú lateral para móviles -->
<nav id="mobile-menu">
	<button id="menu-toggle" aria-label="Abrir menú">&#9776;</button> <!-- Icono de menú hamburguesa -->

	<a href="profile.php" class="<?= $currentPage === 'profile.php' ? 'hidden' : '' ?>">Perfil</a>
	<a href="history.php" class="<?= $currentPage === 'history.php' ? 'hidden' : '' ?>">Historial</a>
	<a href="?logout=true" class="<?= $currentPage === 'index.php' ? 'hidden' : '' ?>">Cerrar Sesión</a>
</nav>