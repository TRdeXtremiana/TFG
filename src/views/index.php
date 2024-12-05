<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit();
}

// Obtener el nombre de usuario desde la sesión
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
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
	<?php include 'header.php'; ?>

	<h1 class="saludo">Hola, <?= htmlspecialchars($user_name) ?></h1>

	<main>
		<section class="add-expense">
			<form id="expense-form">
				<h2>Añadir Gasto</h2>

				<label for="amount">Cantidad:</label>
				<input type="number" id="amount" name="amount" step="any" required>

				<label for="category">Categoría:</label>
				<input type="text" id="category" name="category" required>

				<label for="date">Fecha:</label>
				<input type="date" id="date" name="date" required value="<?= date('Y-m-d') ?>">

				<button type="submit">Añadir</button>
			</form>
		</section>

		<section class="expenses">
			<h2>Gráficos</h2>
			<div class="graphicsContainer">
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