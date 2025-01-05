// Subfunción para manejar la configuración y ejecución de peticiones
async function postData(url, data) {
    return await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
    });
}

// Subfunción para mostrar mensajes al usuario
function showExpenseMessage(message, isSuccess) {
    const messageSpan = document.getElementById('expense-message');
    messageSpan.textContent = message;
    messageSpan.className = isSuccess ? 'exito' : 'error';
    messageSpan.classList.remove('hidden');
}

// Subfunción para gestionar el estado del botón y el spinner
function toggleButtonAndSpinner(buttonId, spinnerId, isLoading) {
    const button = document.getElementById(buttonId);
    const spinner = document.getElementById(spinnerId);
    button.disabled = isLoading;
    spinner.classList.toggle('hidden', !isLoading);
}

// Subfunción para validar el formulario de gastos
function validateExpenseForm(amount) {
    if (!amount || amount <= 0) {
        showExpenseMessage('La cantidad debe ser mayor que 0', false);
        return false;
    }
    return true;
}

// Subfunción genérica para manejar respuestas del servidor
async function handleResponse(response, successMessage, redirectUrl = null) {
    if (response.ok) {
        const data = await response.json();
        showExpenseMessage(data.message || successMessage, true);
        if (redirectUrl) window.location.href = redirectUrl;
    } else {
        const error = await response.json();
        showExpenseMessage(error.error || 'Error en la operación', false);
    }
}

// Función para añadir un gasto
async function addExpense(expenseData) {
    toggleButtonAndSpinner('submit-button', 'spinner', true);
    try {
        const response = await postData("../backend/controllers/expensesController.php", expenseData);
        await handleResponse(response, 'Gasto añadido con éxito');
    } catch (error) {
        showExpenseMessage('Error al conectar con el servidor', false);
    } finally {
        toggleButtonAndSpinner('submit-button', 'spinner', false);
    }
}

// Función para editar un gasto
async function editExpense(expenseData) {
    toggleButtonAndSpinner('edit-button', 'spinner', true);
    try {
        const response = await postData("../backend/controllers/editExpensesController.php", expenseData);
        const redirectUrl = `history.php?anio=${expenseData.date.split('-')[0]}&mes=${expenseData.date.split('-')[1]}`;
        await handleResponse(response, 'Gasto actualizado con éxito', redirectUrl);
    } catch (error) {
        showExpenseMessage('Error al conectar con el servidor', false);
    } finally {
        toggleButtonAndSpinner('edit-button', 'spinner', false);
    }
}

// Función para eliminar un gasto
async function deleteExpense(expenseId, expenseDate) {
    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        try {
            const response = await postData("../backend/controllers/editExpensesController.php", { id: expenseId });
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    const [year, month] = expenseDate.split('-');
                    window.location.href = `history.php?anio=${year}&mes=${month}`;
                } else {
                    alert(data.error || "Error al eliminar el registro.");
                }
            } else {
                const error = await response.json();
                alert(error.error || "Error al eliminar el registro.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Error al procesar la solicitud.");
        }
    }
}

// Manejo del formulario de añadir gasto
function handleExpenseFormSubmit(event) {
    event.preventDefault();
    const amount = document.getElementById("amount").value;
    if (!validateExpenseForm(amount)) return;

    const category = document.getElementById("category").value;
    const description = document.getElementById("description").value || null;
    const date = document.getElementById("date").value;
    addExpense({ amount, category, description, date });
}

// Manejo del formulario de edición de gasto
function handleEditFormSubmit(event) {
    event.preventDefault();
    const amount = document.getElementById("amount").value;
    if (!validateExpenseForm(amount)) return;

    const id = document.getElementById("id").value;
    const category = document.getElementById("category").value;
    const description = document.getElementById("description").value || null;
    const date = document.getElementById("date").value;
    editExpense({ id, amount, category, description, date });
}

// Inicialización de eventos
function init() {
    const expenseForm = document.getElementById("expense-form");
    if (expenseForm) expenseForm.addEventListener("submit", handleExpenseFormSubmit);

    const editForm = document.getElementById("edit-form");
    if (editForm) editForm.addEventListener("submit", handleEditFormSubmit);

    const deleteButton = document.getElementById('delete-button');
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            const id = document.getElementById('id').value;
            const date = document.getElementById('date').value;
            if (!id || !date) {
                alert("Datos incompletos. Por favor, verifica el formulario.");
                return;
            }
            deleteExpense(id, date);
        });
    }
}

document.addEventListener("DOMContentLoaded", init);
