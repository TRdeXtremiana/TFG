// **UTILIDADES**
/**
 * Oculta un enlace si corresponde a la página actual.
 * @param {Element} enlace - Elemento del enlace del menú.
 * @param {string} currentPage - Ruta actual de la página.
 */
function toggleLinkVisibility(enlace, currentPage) {
	const enlacePath = enlace.getAttribute('href');
	if (currentPage.includes(enlacePath)) {
		enlace.classList.add('hidden');
	} else {
		enlace.classList.remove('hidden');
	}
}

/**
 * Obtiene la ruta actual de la página.
 * @returns {string} - Ruta actual.
 */
function getCurrentPage() {
	return window.location.pathname;
}

/**
 * Oculta enlaces en función de la página actual.
 */
function esconderEnlace() {
	const currentPage = getCurrentPage();
	document.querySelectorAll('.menu-link').forEach(enlace => toggleLinkVisibility(enlace, currentPage));
}

/**
 * Envía datos al backend mediante POST.
 * @param {string} url - URL del backend.
 * @param {Object} data - Datos a enviar.
 * @returns {Promise<Response>} - Respuesta del servidor.
 */
async function postData(url, data) {
	return await fetch(url, {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify(data),
	});
}

// **FORMULARIO DE GASTOS**
/**
 * Maneja el envío del formulario de gastos.
 * @param {Event} event - Evento de formulario.
 */
function handleExpenseFormSubmit(event) {
	event.preventDefault();

	const cantidad = document.getElementById("amount").value;
	const idEtiqueta = document.getElementById("category").dataset.idEtiqueta || null;
	const descripcion = document.getElementById("description").value || null;
	const fechaGasto = document.getElementById("date").value;

	addExpense({ cantidad, idEtiqueta, descripcion, fechaGasto });
}

/**
 * Envía un nuevo gasto al backend.
 * @param {Object} expenseData - Datos del gasto.
 */
async function addExpense(expenseData) {
	try {
		const response = await postData("../backend/expensesController.php", expenseData);

		if (response.ok) {
			alert("Gasto añadido con éxito");
		} else {
			alert("Error al añadir el gasto");
		}
	} catch (error) {
		console.error("Error al enviar el gasto:", error);
		alert("Error al conectar con el servidor");
	}
}

// **GRÁFICOS**
/**
 * Crea un gráfico de tipo Pie.
 * @param {Object} data - Datos del gráfico.
 */
function createPieChart(data) {
	new Chart(document.getElementById('pie-chart'), {
		type: 'pie',
		data,
	});
}

/**
 * Crea un gráfico de tipo Bar.
 * @param {Object} data - Datos del gráfico.
 */
function createBarChart(data) {
	new Chart(document.getElementById('bar-chart'), {
		type: 'bar',
		data,
		options: { scales: { y: { beginAtZero: true } } },
	});
}

/**
 * Alterna la visibilidad de los gráficos.
 */
function toggleCharts() {
	const pieChart = document.getElementById('pie-chart');
	const barChart = document.getElementById('bar-chart');
	const switcherButton = document.getElementById('switcher');

	const isPieVisible = pieChart.style.display !== 'none';
	pieChart.style.display = isPieVisible ? 'none' : 'block';
	barChart.style.display = isPieVisible ? 'block' : 'none';
	switcherButton.classList.toggle('active', !isPieVisible);
}

/**
 * Genera datos ficticios para los gráficos (ejemplo; reemplazar por datos reales del backend).
 * @returns {Object} - Datos para los gráficos.
 */
function getExpenseCategories() {
	return {
		labels: ['Ocio', 'Transporte', 'Alimentación', 'Vivienda'],
		datasets: [{
			label: 'Gastos por Categoría',
			data: [120, 80, 50, 30],
			backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'],
			borderColor: '#fff',
			borderWidth: 1,
		}],
	};
}

// **MENÚ**
/**
 * Alterna el menú lateral en dispositivos móviles.
 */
function toggleMobileMenu() {
	const menu = document.getElementById('mobile-menu');
	const isOpen = menu.classList.toggle('open');
	menu.style.left = isOpen ? '0px' : '-250px';
}

// **INICIALIZACIÓN**
/**
 * Configura los eventos iniciales y la UI.
 */
function init() {
	document.getElementById("expense-form").addEventListener("submit", handleExpenseFormSubmit);
	document.getElementById('menu-toggle').addEventListener('click', toggleMobileMenu);

	// Inicializar gráficos
	createPieChart(getExpenseCategories());
	createBarChart(getExpenseCategories());
	document.getElementById('bar-chart').style.display = 'none';

	// Ocultar enlaces en función de la página
	esconderEnlace();
}

// Ejecutar la configuración inicial al cargar el documento
document.addEventListener("DOMContentLoaded", init);
