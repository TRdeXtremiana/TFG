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
    // Alterna la visibilidad del menú lateral
    if (menu.style.left === '0px') {
        menu.style.left = '-250px'; // Ocultar menú
    } else {
        menu.style.left = '0px'; // Mostrar menú
    }
});

