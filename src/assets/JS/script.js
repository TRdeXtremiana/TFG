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
    messageSpan.className = isSuccess ? 'exito' : 'error';
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
        } else {
            const error = await response.json();
            showExpenseMessage(error.error || 'Error al añadir el gasto', false);
        }
    } catch (error) {
        showExpenseMessage('Error al conectar con el servidor', false);
    } finally {
        button.disabled = false;
        spinner.classList.add('hidden');
    }
}

function handleExpenseFormSubmit(event) {
    event.preventDefault();
    const messageSpan = document.getElementById('expense-message');
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

function handleEditFormSubmit(event) {
    event.preventDefault();
    const id = document.getElementById("id").value;
    const amount = document.getElementById("amount").value;
    const category = document.getElementById("category").value;
    const description = document.getElementById("description").value || null;
    const date = document.getElementById("date").value;

    if (!amount || amount <= 0) {
        showExpenseMessage('La cantidad debe ser mayor que 0', false);
        return;
    }

    editExpense({ id, amount, category, description, date });
}

async function editExpense(expenseData) {
    const button = document.getElementById('edit-button');
    const spinner = document.getElementById('spinner');

    button.disabled = true;
    spinner.classList.remove('hidden');

    try {
        const response = await postData("../backend/controllers/editExpensesController.php", expenseData);
        if (response.ok) {
            const data = await response.json();
            showExpenseMessage(data.message || 'Gasto actualizado con éxito', true);

            window.location.href = 'history.php?anio=' + expenseData.date.split('-')[0] + '&mes=' + expenseData.date.split('-')[1];
        } else {
            const error = await response.json();
            showExpenseMessage(error.error || 'Error al añadir el gasto', false);
        }
    } catch (error) {
        showExpenseMessage('Error al conectar con el servidor', false);
    } finally {
        button.disabled = false;
        spinner.classList.add('hidden');
    }
}

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

function init() {
    const expenseForm = document.getElementById("expense-form");
    if (expenseForm) {
        expenseForm.addEventListener("submit", handleExpenseFormSubmit);
    }

    const editForm = document.getElementById("edit-form");
    if (editForm) {
        editForm.addEventListener("submit", handleEditFormSubmit);
    }

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
