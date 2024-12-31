<?php
session_start();
require_once __DIR__ . '/../backend/models/Database.php';

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
    <title>Historial</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="historial">
        <?php if (isset($_POST['meses']) && isset($_POST['anios'])) : ?>
            <div class="tabla-historial">
                <table id="tablaGastos" class="historial-tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Etiqueta</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $gasto) : ?>
                            <tr>
                                <td><?= $gasto['fecha_gasto'] ?></td>
                                <td><?= $gasto['cantidad'] ?></td>
                                <td><?= $gasto['categoria'] ?></td>
                                <td><?= $gasto['descripcion'] ?></td>
                                <td>
                                    <form action="edit.php" method="POST">
                                        <button type="submit" name="id" value="<?= $gasto['id_gasto'] ?>">Editar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <form action="" method="POST" class="form-historial">
                <div>
                    <select name="meses" id="meses" class="historial-select">
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>

                    <select name="anios" id="anios" class="historial-select">
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>


                </div>

                <button type="submit" class="historial-boton">Buscar</button>
            </form>
        <?php endif; ?>
    </main>

</body>

</html>