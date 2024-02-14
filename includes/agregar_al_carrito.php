<?php
session_start();

include "../utils/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_producto']) && is_numeric($_POST['id_producto'])) {
        $productoId = $_POST['id_producto'];

        // Verifica si el producto existe
        $query = "SELECT * FROM Productos WHERE ID_Producto = $productoId";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $producto = mysqli_fetch_assoc($result);

            // Verifica si el usuario está autenticado
            if (isset($_SESSION['username'])) {
                $usuarioId = $_SESSION['user_id'];

                // Crea un carrito de compras si no existe
                $queryCarrito = "INSERT INTO CarritoCompras (ID_Usuario, FechaCreacion, Estado) VALUES ($usuarioId, NOW(), 'Activo')";
                mysqli_query($connection, $queryCarrito);

                // Obtiene el ID del carrito recién creado
                $carritoId = mysqli_insert_id($connection);

                // Agrega el producto al carrito
                $queryDetalle = "INSERT INTO DetalleCarrito (ID_Carrito, ID_Producto, Cantidad, PrecioUnitario) VALUES ($carritoId, $productoId, 1, {$producto['Precio']})";
                mysqli_query($connection, $queryDetalle);

                // Redirecciona a la página de éxito o al carrito
                header("Location: ../index.php");
                exit();
            } else {
                // Redirecciona al inicio de sesión si el usuario no está autenticado
                header("Location: ../pages/login.php");
                exit();
            }
        }
    }
}

// Redirecciona a la página de inicio si no se proporciona un ID de producto válido
header("Location: ../index.php");
exit();
?>
