<?php
session_start();

// Verifica si el usuario es un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

// Include your database connection code
include "../utils/connect.php";

// Función para agregar un juego
function agregarJuego($nombre, $descripcion, $precio, $stock, $idVendedor, $desarrollador, $foto) {
    global $connection;

    // Obtener el nombre de la foto
    $fotoNombre = $nombre;

    // Ruta donde se guardará la foto
    $rutaFoto = "../assets/" . $fotoNombre . ".jpg"; // Ajusta la ruta según tu estructura

    // Mover la foto al directorio destino
    if (move_uploaded_file($foto["tmp_name"], $rutaFoto)) {
        // La foto se movió correctamente, ahora puedes insertar el juego en la base de datos
        $sql = "INSERT INTO Productos (Nombre, Descripcion, Precio, Stock, ID_Vendedor, Desarrollador, Foto) 
                VALUES ('$nombre', '$descripcion', $precio, $stock, $idVendedor, '$desarrollador', '$fotoNombre')";
        $result = $connection->query($sql);

        return $result;
    } else {
        // Error al mover la foto
        return false;
    }
}

// Procesar formularios de agregar, editar y borrar juegos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agregar'])) {
        // Procesar formulario de agregar
        // Obtener datos del formulario
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $desarrollador = $_POST['desarrollador'];
        $foto = $_FILES['foto']; // Obtener el archivo de la foto

        // Puedes validar los datos antes de agregar el juego
        $resultado = agregarJuego($nombre, $descripcion, $precio, $stock, $_SESSION['user_id'], $desarrollador, $foto);

        if ($resultado) {
            echo "Juego agregado con éxito.";
        } else {
            echo "Error al agregar el juego.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        h2 {
            color: #fff;
            margin-top: 20px;
        }

        form {
            background-color: #555;
            padding: 0px 7px 7px 7px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            width: 80%;
            max-width: 600px;
        }

        label {
            margin-top: 10px;
        }

        input, textarea, select {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #777;
            background-color: #444;
            color: #ccc;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 10px;
            background-color: #777;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #999;
        }

        table {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
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
        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2em;
            margin:20px  0px 0px 1500px;
        }
    </style>
    <title>Inventario - PixelKeyTrade</title>
</head>
<body>
    <a href="../index.php">Volver a la página principal</a>
    <h2>Inventario de Juegos</h2>
    <div style="display:flex;">
        <!-- Formulario para agregar juego -->
        <form method="post" action="" style="margin: 20px 10px 0px 0px" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required></textarea>

            <label for="precio">Precio:</label>
            <input type="number" name="precio" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" required>

            <label for="desarrollador">Desarrollador:</label>
            <input type="text" name="desarrollador" required>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" accept="image/*">

            <input type="submit" name="agregar" value="Agregar Juego">
        </form>

        
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Desarrollador</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener todos los juegos existentes
                $sql = "SELECT * FROM Productos";
                $result = $connection->query($sql);

                // Mostrar juegos en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['ID_Producto']}</td>";
                    echo "<td>{$row['Nombre']}</td>";
                    echo "<td>{$row['Descripcion']}</td>";
                    echo "<td>{$row['Precio']}</td>";
                    echo "<td>{$row['Stock']}</td>";
                    echo "<td>{$row['Desarrollador']}</td>";
                    echo "<td>
                            <form method='post' action=''>
                                <input type='hidden' name='idProducto' value='{$row['ID_Producto']}'>
                                <input type='submit' name='editar' value='Editar'>
                            </form>
                            <form method='post' action=''>
                                <input type='hidden' name='idProducto' value='{$row['ID_Producto']}'>
                                <input type='submit' name='borrar' value='Borrar'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
