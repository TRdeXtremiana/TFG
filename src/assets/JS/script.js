async function postData(url, data) {
    return await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
    });
}

function showExpenseMessage(message, isSuccess) {
    const messageSpan = document.getElementById('expense-message');
    messageSpan.textContent = message;
    messageSpan.className = isSuccess ? 'success' : 'error';
    messageSpan.classList.remove('hidden');
}

async function addExpense(expenseData) {
    const button = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    button.disabled = true;
    spinner.classList.remove('hidden');

    try {
        const response = await postData("../backend/controllers/expensesController.php", expenseData);
        if (response.ok) {
            const data = await response.json();
            showExpenseMessage(data.message || 'Gasto añadido con éxito', true);
            // Actualiza gráficos
            //TODO
            // updateCharts(data.updatedCategories, data.updatedAmounts);
        } else {
            const error = await response.json();
            showExpenseMessage(error.error || 'Error al añadir el gasto', false);
        }
    } catch (error) {
        // showExpenseMessage('Error al conectar con el servidor', false);
        showExpenseMessage(error.message || 'Error al conectar con el servidor', false);
    } finally {
        button.disabled = false;
        spinner.classList.add('hidden');
    }
}

function handleExpenseFormSubmit(event) {
    const messageSpan = document.getElementById('expense-message');
    event.preventDefault();
    event.stopPropagation();
    const amount = document.getElementById("amount").value;
    const category = document.getElementById("category").value;
    const description = document.getElementById("description").value || null;
    const date = document.getElementById("date").value;

    if (!amount || amount <= 0) {
        showExpenseMessage('La cantidad debe ser mayor que 0', false);
        return;
    }

    messageSpan.classList.add('hidden');
    messageSpan.textContent = '';
    addExpense({ amount, category, description, date });
}

function createChartConfig(type, labels, data) {
    return {
        type,
        data: {
            labels,
            datasets: [{
                label: 'Gastos por Categoría',
                data,
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'],
                borderColor: '#fff',
                borderWidth: 1,
            }],
        },
        options: { scales: { y: { beginAtZero: true } } },
    };
}

function toggleCharts() {
    const pieChart = document.getElementById('pie-chart');
    const barChart = document.getElementById('bar-chart');
    const isPieVisible = pieChart.style.display !== 'none';
    pieChart.style.display = isPieVisible ? 'none' : 'block';
    barChart.style.display = isPieVisible ? 'block' : 'none';
}

function init() {

}

document.addEventListener("DOMContentLoaded", init);
