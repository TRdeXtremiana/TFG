<?php
// Iniciar sesión
session_start();

// Si el usuario intenta cerrar sesión
if (isset($_GET['logout'])) {
	// Borrar todas las variables de sesión
	session_unset();

	// Destruir la sesión
	session_destroy();

	// Redirigir al login
	header('Location: login.php');
	exit();
}

// Ver si se ha enviado el formulario
if (isset($_POST['user_name'])) {
	// Guardar el nombre en la sesión
	$_SESSION['user_name'] = htmlspecialchars($_POST['user_name']);
}

// Coger el nombre de la sesión (por si se necesita en otra parte del código)
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Verificar si el usuario no está logueado, redirigir a login
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
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
	<header>
		<button id="menu-toggle" aria-label="Abrir menú">&#9776;</button> <!-- Icono de menú hamburguesa -->
		<h1>Gestión de Gastos</h1>

		<!-- Menú de navegación (visible solo en escritorio) -->
		<nav id="desktop-menu">
			<a href="profile.php">Perfil</a>
			<a href="history.php">Historial</a>
			<!-- Cerrar sesión -->
			<a href="?logout=true">Cerrar Sesión</a>
		</nav>
	</header>

	<!-- Menú lateral para móviles -->
	<nav id="mobile-menu">
		<button id="menu-toggle" aria-label="Abrir menú">&#9776;</button> <!-- Icono de menú hamburguesa -->

		<a href="profile.php">Perfil</a>
		<a href="history.php">Historial</a>
		<!-- Cerrar sesión -->
		<a href="?logout=true">Cerrar Sesión</a>
	</nav>

	<h1 class="saludo">Hola, <?php echo $_SESSION['user_name']; ?></h1>


	<main>
		<section class="add-expense">


			<form id="expense-form">
				<h2>Añadir Gasto</h2>

				<label for="amount">Cantidad:</label>
				<input type="number" id="amount" name="amount" step="any" required>


				<label for="category">Categoría:</label>
				<input type="text" id="category" name="category" required>

				<label for="date">Fecha:</label>
				<input type="date" id="date" name="date" required>
				<script>
					document.getElementById('date').value = new Date().toISOString().split('T')[0];
				</script>


				<button type="submit">Añadir</button>
			</form>
		</section>

		<section class="expenses">
			<h2>Gráficos</h2>
			<div class="graphicsContainer">
				<!-- Botón de interruptor -->
				<button id="switcher" onclick="toggleCharts()"></button>

				<!-- Gráfico Circular -->
				<canvas class="graphic" id="pie-chart" width="400" height="400"></canvas>

				<!-- Gráfico de Barra Progresiva -->
				<canvas class="graphic" id="bar-chart" width="400" height="400"></canvas>
			</div>
		</section>

	</main>
</body>

</html>