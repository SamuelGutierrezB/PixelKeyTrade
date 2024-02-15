<?php
session_start();

include "../utils/connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['user_id'];

$query = "
    SELECT 
        CarritoCompras.ID_Carrito, 
        CarritoCompras.FechaCreacion, 
        DetalleCarrito.ID_Producto, 
        Productos.Nombre AS ProductoNombre, 
        DetalleCarrito.Cantidad, 
        DetalleCarrito.PrecioUnitario,
        Descuentos.PorcentajeDescuento
    FROM CarritoCompras
    INNER JOIN DetalleCarrito ON CarritoCompras.ID_Carrito = DetalleCarrito.ID_Carrito
    INNER JOIN Productos ON DetalleCarrito.ID_Producto = Productos.ID_Producto
    LEFT JOIN Descuentos ON Productos.ID_Producto = Descuentos.ID_Producto
    WHERE CarritoCompras.ID_Usuario = $userID AND CarritoCompras.Estado = 'Activo'";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Error al obtener detalles del carrito: " . mysqli_error($connection));
}

$cartDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

$totalCost = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        .cart-container {
            background-color: #555;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
            width: 80%;
            max-width: 600px;
        }

        h2 {
            color: #fff;
        }

        table {
            width: 100%;
            margin-top: 10px;
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

        p {
            color: #ccc;
            margin-top: 10px;
        }

        form {
            margin-top: 20px;
        }

        button {
            padding: 10px;
            background-color: #777;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #999;
        }
        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2em;
            }
            .top-menu-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
    <link rel="stylesheet" href="styles.css" />
    <title>Carrito de Compras - PixelKeyTrade</title>
</head>
<body>
    <header>
        
    <div class="top-menu-container">
            <h1>PixelKeyTrade</h1>
            <a href="../index.php">Volver a la página principal</a>
        </div>
    </header>
    <div class="cart-container">
        <h2>Carrito de Compras</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartDetails as $item): 
                    $discountedPrice = $item['PrecioUnitario'] * (1 - $item['PorcentajeDescuento'] / 100);
                    ?>
                    <tr>
                        <td><?php echo $item['ProductoNombre']; ?></td>
                        <td><?php echo $item['Cantidad']; ?></td>
                        <td>$<?php echo number_format($discountedPrice, 2); ?></td>
                        <td>
                            <form action="../includes/remove_from_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['ID_Producto']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php $totalCost += $item['Cantidad'] * $discountedPrice; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total: $<?php echo number_format($totalCost, 2); ?></p>
        <form action="../includes/generate_pdf.php" method="post">
            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
            <button type="submit">Comprar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
