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
    event.stopPropagation();
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
            showExpenseMessage(data.message || 'Gasto añadido con éxito', true);
            
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

function init() {
    let expense=document.getElementById("expense-form");
    if(expense)
        expense.addEventListener("submit", handleExpenseFormSubmit);

    let edit=document.getElementById("edit-form");
    if(edit)
        edit.addEventListener("submit", handleEditFormSubmit);
}

document.addEventListener("DOMContentLoaded", () => {
    init();

    const deleteButton = document.getElementById('delete-button');
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            const id = document.getElementById('id').value;

            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                fetch("../backend/controllers/editExpensesController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = "index.php"; 
                        } else {
                            alert(data.error || "Error al eliminar el registro.");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Error al procesar la solicitud.");
                    });
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", init);
