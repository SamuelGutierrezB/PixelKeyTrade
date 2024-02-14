<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

include "../utils/connect.php";

function agregarJuego($nombre, $descripcion, $precio, $stock, $idVendedor, $desarrollador, $foto, $idCategoria) {
    global $connection;

    $fotoNombre = $nombre;

    $rutaFoto = "../assets/" . $fotoNombre . ".jpg";

    if (move_uploaded_file($foto["tmp_name"], $rutaFoto)) {
        
        $sql = "INSERT INTO Productos (Nombre, Descripcion, Precio, Stock, ID_Vendedor, Desarrollador, Foto) 
                VALUES ('$nombre', '$descripcion', $precio, $stock, $idVendedor, '$desarrollador', '$fotoNombre')";
        $result = $connection->query($sql);

        if ($result) {
            
            $idProducto = $connection->insert_id;

            $sqlRelacion = "INSERT INTO Producto_Categoria (ID_Producto, ID_Categoria) 
                            VALUES ($idProducto, $idCategoria)";
            $resultRelacion = $connection->query($sqlRelacion);

            return $resultRelacion;
        } else {
            // Error al insertar el juego
            return false;
        }
    } else {
        // Error al mover la foto
        return false;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $desarrollador = $_POST['desarrollador'];
        $foto = $_FILES['foto'];
        $idCategoria = $_POST['categoria']; 

        $resultado = agregarJuego($nombre, $descripcion, $precio, $stock, $_SESSION['user_id'], $desarrollador, $foto, $idCategoria);

        if ($resultado) {
            echo "Juego agregado con éxito.";
        } else {
            echo "Error al agregar el juego.";
        }
    } elseif (isset($_POST['borrar'])) {
        $idProducto = $_POST['idProducto'];

        $sqlObtenerCategoria = "SELECT ID_Categoria FROM Producto_Categoria WHERE ID_Producto = $idProducto";
        $resultObtenerCategoria = $connection->query($sqlObtenerCategoria);

        if ($resultObtenerCategoria->num_rows > 0) {
            $rowCategoria = $resultObtenerCategoria->fetch_assoc();
            $idCategoria = $rowCategoria['ID_Categoria'];

            $sqlBorrarRelacion = "DELETE FROM Producto_Categoria WHERE ID_Producto = $idProducto";
            $resultBorrarRelacion = $connection->query($sqlBorrarRelacion);

            if ($resultBorrarRelacion) {
                $sqlBorrarJuego = "DELETE FROM Productos WHERE ID_Producto = $idProducto";
                $resultBorrarJuego = $connection->query($sqlBorrarJuego);

                if ($resultBorrarJuego) {
                    echo "Juego y relación con la categoría borrados con éxito.";
                } else {
                    echo "Error al borrar el juego.";
                }
            } else {
                echo "Error al borrar la relación con la categoría.";
            }
        } else {
            echo "No se encontró la relación con la categoría.";
        }
    } elseif (isset($_POST['editar'])) {
        $idProducto = $_POST['idProducto'];
    
        $sqlObtenerJuego = "SELECT * FROM Productos WHERE ID_Producto = $idProducto";
        $resultObtenerJuego = $connection->query($sqlObtenerJuego);
    
        if ($resultObtenerJuego->num_rows > 0) {
            $datosJuego = $resultObtenerJuego->fetch_assoc();
    
            echo "<form method='post' action='' style='margin: 20px 10px 0px 0px' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='idProducto' value='{$idProducto}'>";
            echo "<label for='nombre'>Nombre:</label>";
            echo "<input type='text' name='nombre_editar' value='{$datosJuego['Nombre']}' required>";
    
            echo "<label for='descripcion'>Descripción:</label>";
            echo "<textarea name='descripcion_editar' required>{$datosJuego['Descripcion']}</textarea>";
    
            echo "<label for='precio'>Precio:</label>";
            echo "<input type='number' name='precio_editar' value='{$datosJuego['Precio']}' required>";
    
            echo "<label for='stock'>Stock:</label>";
            echo "<input type='number' name='stock_editar' value='{$datosJuego['Stock']}' required>";
    
            echo "<label for='desarrollador'>Desarrollador:</label>";
            echo "<input type='text' name='desarrollador_editar' value='{$datosJuego['Desarrollador']}' required>";
    
            echo "<label for='foto'>Foto:</label>";
            echo "<input type='file' name='foto_editar' accept='image/*'>";
    
            echo "<label for='categoria'>Categoría:</label>";
            echo "<select name='categoria_editar' required>";
            
            $sqlCategorias = "SELECT * FROM Categorias";
            $resultCategorias = $connection->query($sqlCategorias);
            
            while ($rowCategoria = $resultCategorias->fetch_assoc()) {
                $selected = ($rowCategoria['ID_Categoria'] == $datosJuego['ID_Categoria']) ? 'selected' : '';
                echo "<option value='{$rowCategoria['ID_Categoria']}' $selected>{$rowCategoria['Nombre']}</option>";
            }
            echo "</select>";
    
            echo "<input type='submit' name='guardar_edicion' value='Guardar Edición'>";
            echo "</form>";
        } else {
            echo "No se encontró el juego para editar.";
        }
    } elseif (isset($_POST['guardar_edicion'])) {
        
        $idProducto = $_POST['idProducto'];
        $nombre_editar = $_POST['nombre_editar'];
        $descripcion_editar = $_POST['descripcion_editar'];
        $precio_editar = $_POST['precio_editar'];
        $stock_editar = $_POST['stock_editar'];
        $desarrollador_editar = $_POST['desarrollador_editar'];
        $foto_editar = $_FILES['foto_editar'];
        $idCategoria_editar = $_POST['categoria_editar'];
        
        global $connection;
        
        $sqlEditarJuego = "UPDATE Productos SET
            Nombre = '$nombre_editar',
            Descripcion = '$descripcion_editar',
            Precio = $precio_editar,
            Stock = $stock_editar,
            Desarrollador = '$desarrollador_editar'
            WHERE ID_Producto = $idProducto";
    
        if ($connection->query($sqlEditarJuego)) {
            
            $sqlActualizarCategoria = "UPDATE Producto_Categoria SET
                ID_Categoria = $idCategoria_editar
                WHERE ID_Producto = $idProducto";
    
            if ($connection->query($sqlActualizarCategoria)) {
                echo "Juego editado con éxito.";

            } else {
                echo "Error al actualizar la categoría del juego.";

            }
        } else {
            echo "Error al editar el juego.";

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
    <label for="categoria">Categoría:</label>
    <select name="categoria" required>
        <?php
        $sqlCategorias = "SELECT * FROM Categorias";
        $resultCategorias = $connection->query($sqlCategorias);

        while ($rowCategoria = $resultCategorias->fetch_assoc()) {
            echo "<option value='{$rowCategoria['ID_Categoria']}'>{$rowCategoria['Nombre']}</option>";
        }
        ?>
    </select>
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
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT P.*, C.Nombre AS CategoriaNombre
        FROM Productos P
        LEFT JOIN Producto_Categoria PC ON P.ID_Producto = PC.ID_Producto
        LEFT JOIN Categorias C ON PC.ID_Categoria = C.ID_Categoria";
$result = $connection->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['ID_Producto']}</td>";
                    echo "<td>{$row['Nombre']}</td>";
                    echo "<td>{$row['Descripcion']}</td>";
                    echo "<td>{$row['Precio']}</td>";
                    echo "<td>{$row['Stock']}</td>";
                    echo "<td>{$row['Desarrollador']}</td>";
                    echo "<td>{$row['CategoriaNombre']}</td>"; 
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