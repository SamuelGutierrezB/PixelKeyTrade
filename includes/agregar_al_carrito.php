<?php
session_start();

include "../utils/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_producto']) && is_numeric($_POST['id_producto'])) {
        $productoId = $_POST['id_producto'];

    
        $query = "SELECT * FROM Productos WHERE ID_Producto = $productoId";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $producto = mysqli_fetch_assoc($result);

        
            if (isset($_SESSION['username'])) {
                $usuarioId = $_SESSION['user_id'];

            
                $queryCarrito = "INSERT INTO CarritoCompras (ID_Usuario, FechaCreacion, Estado) VALUES ($usuarioId, NOW(), 'Activo')";
                mysqli_query($connection, $queryCarrito);

            
                $carritoId = mysqli_insert_id($connection);

            
                $queryDetalle = "INSERT INTO DetalleCarrito (ID_Carrito, ID_Producto, Cantidad, PrecioUnitario) VALUES ($carritoId, $productoId, 1, {$producto['Precio']})";
                mysqli_query($connection, $queryDetalle);

            
                header("Location: ../index.php");
                exit();
            } else {
            
                header("Location: ../pages/login.php");
                exit();
            }
        }
    }
}

header("Location: ../index.php");
exit();
?>
