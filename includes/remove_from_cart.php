<?php
session_start();

include "../utils/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $productID = $_POST['product_id'];
        
        
        $userID = $_SESSION['user_id'];
        $cartQuery = "SELECT ID_Carrito FROM CarritoCompras WHERE ID_Usuario = $userID AND Estado = 'Activo'";
        $cartResult = mysqli_query($connection, $cartQuery);

        if ($cartResult && mysqli_num_rows($cartResult) > 0) {
            $cartRow = mysqli_fetch_assoc($cartResult);
            $cartID = $cartRow['ID_Carrito'];

            
            $removeProductQuery = "DELETE FROM DetalleCarrito WHERE ID_Carrito = $cartID AND ID_Producto = $productID";
            $removeProductResult = mysqli_query($connection, $removeProductQuery);

            if (!$removeProductResult) {
                die("Error al eliminar producto del carrito: " . mysqli_error($connection));
            }

            
            header("Location: cart.php");
            exit();
        } else {
            die("Error al obtener detalles del carrito: " . mysqli_error($connection));
        }
    }
}


header("Location: cart.php");
exit();
?>
