<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #333;
            color: #ccc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: #555;
            padding: 10px;
            text-align: center;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            color: #fff;
            margin: 0;
        }

        .top-menu-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2em;
        }

        .user-icons {
            display: flex;
            align-items: center;
        }

        #inventory {
            margin-left: 15px;
        }

        .game-table-container {
            margin-top: 20px;
        }

        .game-table {
            background-color: #555;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 80%;
            max-width: 800px;
        }

        .game-table-header {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #777;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #555;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #444;
        }
        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2em;
        }
    </style>
    <title>Ofertas - PixelKeyTrade</title>
</head>
<body>
    <header>
        <div class="top-menu-container">
            <h1>PixelKeyTrade</h1>
            <a href="../index.php">Volver a la página principal</a>
        </div>
    </header>

    <div class="game-table-container">
        <div class="game-table">
            <div class="game-table-header">
                <h2>Ofertas</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th> </th>
                        <th>Juego</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Precio con Descuento</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                session_start();
                include "../utils/connect.php";

                $queryOfertas = "SELECT p.ID_Producto, p.Nombre, p.Precio, d.PorcentajeDescuento
                                FROM Productos p
                                JOIN Descuentos d ON p.ID_Producto = d.ID_Producto";
                $resultOfertas = mysqli_query($connection, $queryOfertas);

                while ($row = mysqli_fetch_assoc($resultOfertas)) {
                    $imagenUrl = "../assets/" . $row['Nombre'] . '.jpg';
                    $precioConDescuento = $row['Precio'] * (1 - $row['PorcentajeDescuento'] / 100);

                    echo '<tr>';
                    echo '<td><a href="game_detail.php?id=' . $row['Nombre'] . '&oferta=true&precio_descuento=' . $precioConDescuento . '"><img src="' . $imagenUrl . '" alt="' . $row['Nombre'] . '" style="width: 50px; height: auto;"></a></td>';
                    echo '<td><a href="game_detail.php?id=' . $row['Nombre'] . '&oferta=true&precio_descuento=' . $precioConDescuento . '">' . $row['Nombre'] . '</a></td>';
                    echo '<td>$' . $row['Precio'] . '</td>';
                    echo '<td>' . $row['PorcentajeDescuento'] . '%</td>';
                    echo '<td>$' . number_format($precioConDescuento, 2) . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
