<?php
session_start();

include "../utils/connect.php";

$gameId = $_GET['id'];
$isOferta = isset($_GET['oferta']) && $_GET['oferta'] === 'true';
$precioConDescuento = $isOferta && isset($_GET['precio_descuento']) ? $_GET['precio_descuento'] : null;

$query = "SELECT p.*, c.Nombre AS CategoriaNombre
          FROM productos p
          JOIN producto_categoria pc ON p.ID_Producto = pc.ID_Producto
          JOIN categorias c ON pc.ID_Categoria = c.ID_Categoria
          WHERE p.Foto = '$gameId'";

$result = mysqli_query($connection, $query);

$gameDetails = mysqli_fetch_assoc($result);

mysqli_close($connection);
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

        .game-details {
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

        p {
            color: #ccc;
            margin-top: 10px;
        }

        a {
            color: #fff;
            text-decoration: none;
            margin-top: 20px;
            display: block;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="styles.css" />
    <title>Detalles del Juego - PixelKeyTrade</title>
</head>
<body>
    <header>
        <h1>PixelKeyTrade</h1>
    </header>
    
    <div class="game-details">
        <img src='../assets/<?php echo $gameDetails["Nombre"] ?>.jpg' alt="Juego" style="width: 100px;" />
        <h2><?php echo $gameDetails['Nombre']; ?></h2>
        <p><?php echo $gameDetails['Descripcion']; ?></p>
        <p>Categoría: <?php echo $gameDetails['CategoriaNombre']; ?></p>
        <p>Precio: $<?php echo $precioConDescuento ? number_format($precioConDescuento, 2) : $gameDetails['Precio']; ?></p>

        <form action="../includes/agregar_al_carrito.php" method="post">
            <input type="hidden" name="id_producto" value="<?php echo $gameDetails['ID_Producto']; ?>">
            <input type="hidden" name="precio" value="<?php echo $precioConDescuento ? $precioConDescuento : $gameDetails['Precio']; ?>">
            <button type="submit">Agregar al carrito</button>
        </form>
        <a href="../index.php">Volver a la lista de juegos</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
</body>
</html>
