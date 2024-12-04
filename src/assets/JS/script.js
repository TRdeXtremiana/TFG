// Función para enviar un nuevo gasto al backend
async function addExpense(amount, category, date) {
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
}

// Función para manejar el envío del formulario de gastos
function handleExpenseFormSubmit(event) {
	event.preventDefault();
	const amount = document.getElementById("amount").value;
	const category = document.getElementById("category").value;
	const date = document.getElementById("date").value;

	addExpense(amount, category, date);
}

// Función para abrir y cerrar el menú lateral en dispositivos móviles
function toggleMobileMenu() {
	const menu = document.getElementById('mobile-menu');
	if (menu.style.left === '0px' || menu.classList.contains('open')) {
		menu.style.left = '-250px';
		menu.classList.remove('open');
	} else {
		menu.style.left = '0px';
		menu.classList.add('open');
	}
}

// Función para configurar el gráfico circular (Pie Chart)
function createPieChart() {
	const gastosPorCategoria = getExpenseCategories();
	const pieData = {
		labels: Object.keys(gastosPorCategoria),
		datasets: [{
			label: 'Gastos por Categoría',
			data: Object.values(gastosPorCategoria),
			backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'],
			borderColor: '#fff',
			borderWidth: 1
		}]
	};

	new Chart(document.getElementById('pie-chart'), {
		type: 'pie',
		data: pieData,
	});
}

// Función para configurar el gráfico de barra progresiva (Bar Chart)
function createBarChart() {
	const gastosPorCategoria = getExpenseCategories();
	const barData = {
		labels: Object.keys(gastosPorCategoria),
		datasets: [{
			label: 'Gastos por Categoría',
			data: Object.values(gastosPorCategoria),
			backgroundColor: '#4CAF50',
			borderColor: '#388E3C',
			borderWidth: 1
		}]
	};

	new Chart(document.getElementById('bar-chart'), {
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
}

// Función para obtener las categorías de gastos (esto debería ser dinámico)
function getExpenseCategories() {
	return {
		'Ocio': 120,
		'Transporte': 80,
		'Alimentación': 50,
		'Vivienda': 30
	};
}

// Función para alternar entre los gráficos
function toggleCharts() {
	const pieChart = document.getElementById('pie-chart');
	const barChart = document.getElementById('bar-chart');
	const switcherButton = document.getElementById('switcher');

	if (pieChart.style.display !== 'none') {
		pieChart.style.display = 'none';
		barChart.style.display = 'block';
		switcherButton.classList.add('active');
	} else {
		pieChart.style.display = 'block';
		barChart.style.display = 'none';
		switcherButton.classList.remove('active');
	}
}

// Configuración inicial del gráfico y eventos
function init() {
	document.getElementById("expense-form").addEventListener("submit", handleExpenseFormSubmit);
	document.getElementById('menu-toggle').addEventListener('click', toggleMobileMenu);

	// Inicializar gráficos
	createPieChart();
	createBarChart();

	// Inicializar estado del gráfico
	document.getElementById('bar-chart').style.display = 'none';
}

// Ejecutar la configuración inicial al cargar el documento
document.addEventListener("DOMContentLoaded", init);
