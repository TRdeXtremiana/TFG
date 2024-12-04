// Script para el formulario de gastos
document.getElementById("expense-form").addEventListener("submit", async function (e) {
	e.preventDefault();
	const amount = document.getElementById("amount").value;
	const category = document.getElementById("category").value;
	const date = document.getElementById("date").value;

	const response = await fetch("../backend/expenses.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify({ amount, category, date }),
	});

	if (response.ok) {
		alert("Gasto añadido con éxito");
	} else {
		alert("Error al añadir el gasto");
	}
});

// Script para abrir y cerrar el menú lateral en móviles
document.getElementById('menu-toggle').addEventListener('click', function () {
	const menu = document.getElementById('mobile-menu');
	// Verifica el valor de la propiedad 'left' para saber si el menú está visible o no
	if (menu.style.left === '0px' || menu.classList.contains('open')) {
		// Ocultar menú
		menu.style.left = '-250px';
		menu.classList.remove('open');
	} else {
		// Mostrar menú
		menu.style.left = '0px';
		menu.classList.add('open');
	}
});


// Datos de ejemplo para las categorías y los gastos (deberías cargar esto dinámicamente desde tu servidor o base de datos)
const gastosPorCategoria = {
	'Ocio': 120,
	'Transporte': 80,
	'Alimentación': 50,
	'Vivienda': 30
};

// Para el gráfico circular (Pie Chart)
const pieData = {
	labels: Object.keys(gastosPorCategoria),  // Categorías
	datasets: [{
		label: 'Gastos por Categoría',
		data: Object.values(gastosPorCategoria),  // Montos
		backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'], // Colores
		borderColor: '#fff',
		borderWidth: 1
	}]
};

// Configuración del gráfico circular
const pieChart = new Chart(document.getElementById('pie-chart'), {
	type: 'pie',  // Tipo de gráfico
	data: pieData,
});

// Para el gráfico de barra progresiva (Bar Chart)
const barData = {
	labels: Object.keys(gastosPorCategoria),
	datasets: [{
		label: 'Gastos por Categoría',
		data: Object.values(gastosPorCategoria),
		backgroundColor: '#4CAF50',  // Color de las barras
		borderColor: '#388E3C',  // Color del borde
		borderWidth: 1
	}]
};

// Configuración del gráfico de barra progresiva
const barChart = new Chart(document.getElementById('bar-chart'), {
	type: 'bar',
	data: barData,
	options: {
		scales: {
			y: {
				beginAtZero: true
			}
		}
	}
});

document.getElementById('bar-chart').style.display = 'none';
function toggleCharts() {
	const pieChart = document.getElementById('pie-chart');
	const barChart = document.getElementById('bar-chart');
	const switcherButton = document.getElementById('switcher');

	// Si el gráfico de pie está visible, ocultar y mostrar el gráfico de barras
	if (pieChart.style.display !== 'none') {
		pieChart.style.display = 'none';
		barChart.style.display = 'block';
		switcherButton.classList.add('active');  // Desactivar el botón
	} else {
		pieChart.style.display = 'block';
		barChart.style.display = 'none';
		switcherButton.classList.remove('active');  // Activar el botón
	}
}
