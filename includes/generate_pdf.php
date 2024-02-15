<?php
session_start();

require_once('../tcpdf/tcpdf.php');
include "utils/connect.php";

if (!isset($_POST['email'])) {
    header("Location: ../index.php");
    exit();
}

$userEmail = $_POST['email'];

$query = "SELECT CarritoCompras.ID_Carrito, CarritoCompras.FechaCreacion, DetalleCarrito.ID_Producto, Productos.Nombre AS ProductoNombre, DetalleCarrito.Cantidad, DetalleCarrito.PrecioUnitario
FROM CarritoCompras
INNER JOIN DetalleCarrito ON CarritoCompras.ID_Carrito = DetalleCarrito.ID_Carrito
INNER JOIN Productos ON DetalleCarrito.ID_Producto = Productos.ID_Producto
WHERE CarritoCompras.ID_Usuario = ? AND CarritoCompras.Estado = 'Activo'";

$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$db = "pixelkeytrade";
$connection = mysqli_connect($servidor, $usuario, $password, $db);

if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}

$stmt = mysqli_prepare($connection, $query);

if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Error al obtener detalles del carrito: " . mysqli_error($connection));
}

$cartDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

$totalCost = 0;

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12); // Set font and size for the entire document
$pdf->Cell(0, 10, 'Factura de Compra', 0, 1, 'C');

foreach ($cartDetails as $item) {
    $pdf->Cell(0, 10, 'Producto: ' . $item['ProductoNombre'], 0, 1);
    $pdf->Cell(0, 10, 'Cantidad: ' . $item['Cantidad'], 0, 1);
    $pdf->Cell(0, 10, 'Precio Unitario: $' . number_format($item['PrecioUnitario'], 2), 0, 1);
    $totalCost += $item['Cantidad'] * $item['PrecioUnitario'];
}

$pdf->Cell(0, 10, 'Total: $' . number_format($totalCost, 2), 0, 1);

$pdfFileName = __DIR__ . '/factura_' . time() . '.pdf';
$pdf->Output($pdfFileName, 'F');
header("Location: ../index.php");

mysqli_stmt_close($stmt);
?>
