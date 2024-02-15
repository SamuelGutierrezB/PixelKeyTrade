<?php
session_start();

include "utils/connect.php";
$categoriaSeleccionada = 0; // Por defecto, no hay filtro de categoría
$queryJuegos = ""; 

if (isset($_POST['categoria'])) {
    $categoriaSeleccionada = mysqli_real_escape_string($connection, $_POST['categoria']);
    if ($categoriaSeleccionada != 0) {
        $queryJuegos = "SELECT * FROM Productos 
                        JOIN Producto_Categoria ON Productos.ID_Producto = Producto_Categoria.ID_Producto
                        WHERE Producto_Categoria.ID_Categoria = $categoriaSeleccionada";
    } else {
        // Si la categoría seleccionada es 0 (Todas las categorías), mostramos todos los juegos
        $queryJuegos = "SELECT * FROM Productos";
    }
}

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $isAdmin = $_SESSION['tipo_usuario'] === 'Admin';
}

$query = "SELECT Foto FROM Productos";
$result = mysqli_query($connection, $query);

$imagenUrls = array();
$GameNames = array();

while ($row = mysqli_fetch_assoc($result)) {
    $imagenUrls[] = "assets/" . $row['Foto'] . '.jpg';
    $GameNames[] = $row['Foto'];
}

$queryCategorias = "SELECT * FROM Categorias";
$resultCategorias = mysqli_query($connection, $queryCategorias);

$categorias = array();
while ($rowCategoria = mysqli_fetch_assoc($resultCategorias)) {
    $categorias[] = $rowCategoria;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"
    />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>PixelKeyTrade - Tu Tienda de Videojuegos</title>
  </head>
  <body>
    <header>
      <div class="top-menu-container">
        <h1>PixelKeyTrade</h1>
        <nav>
          <ul>
            <li><a href="pages/ofertas.php">Ofertas</a></li>
          </ul>
        </nav>
        <div class="user-icons">
        <?php
        if (isset($_SESSION['user_id'])) {
          if ($isAdmin) {
            echo '<a style="text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;"  href="../PixelKeyTrade/admin/inventario.php" id="inventory">Inventario</a>';
        }  
          echo '<a style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;" href="../PixelKeyTrade/utils/logout.php" id="logout">Cerrar Sesión</a>';
            
        } else {
            echo '<a href="../PixelKeyTrade/pages/login.php" id="login">Iniciar Sesión</a>';
            echo '<a href="../PixelKeyTrade/pages/register.php" id="register">Registrarse</a>';
        }
        ?>
        <a href="pages/cart.php" id="cart"><img src="../PixelKeyTrade/assets/cart-icon.png" alt="" /></a>
    </div>
      </div>
    </header>
    <div class="game-carousel">
        <?php
        foreach ($imagenUrls as $key => $imageUrl) {
            echo '<a href="pages/game_detail.php?id=' . ($GameNames[$key]) . '">';
            echo '<div style="position: relative; width: 200px; height: 200px; text-align: center;">';
            echo '<img src="assets/pixelPhoto.png" alt="Pixel Photo" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0;" />';
            echo '<img src="' . $imageUrl . '" alt="Juego" style="width: auto; height: 60%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />';
            echo '</div>';
            echo '</a>';
        }
        ?>
    </div>

    <div class="game-table-container">
        <div class="game-table">
          <div class="game-table-header">
            <h2>Listado de Juegos</h2>
            <form method="post" action="index.php">
                <label for="categoria-filter">Filtrar por Categoría:</label>
                <select name="categoria" id="categoria-filter">
                    <option value="0">Todas las categorías</option>
                    <?php
                    foreach ($categorias as $categoria) {
                        $selected = ($categoriaSeleccionada == $categoria['ID_Categoria']) ? 'selected' : '';
                        echo '<option value="' . $categoria['ID_Categoria'] . '" ' . $selected . '>' . $categoria['Nombre'] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Filtrar">
            </form>
            </div>
            <table>
                <!-- <thead>
                    <tr>
                        <th> </th>
                        <th>Juego</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                    </tr>
                </thead> -->
                <tbody>
                    <?php
                    if (!empty($queryJuegos)) {
                      $resultJuegos = mysqli_query($connection, $queryJuegos);
                  
                      while ($rowJuego = mysqli_fetch_assoc($resultJuegos)) {
                        echo '<tr>';
                        $index = array_search($rowJuego['Foto'], $GameNames);
                        echo '<td><a style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;" href="pages/game_detail.php?id=' . $rowJuego['Nombre'] . '"><div><img src="' . $imagenUrls[$index] . '" alt="Juego" style="width: 100px;" /></div></a></td>';
                        echo '<td><a style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;" href="pages/game_detail.php?id=' . $rowJuego['Nombre'] . '">' . $rowJuego['Nombre'] . '</a></td>';
                        echo '<td style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;">' . $rowJuego['Descripcion'] . '</td>';
                        echo '<td style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;">$' . $rowJuego['Precio'] . '</td>';
                        echo '</tr style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;">';
                    }
                  } else {
                      echo "No se ha seleccionado una categoría.";
                  }
                    ?>
                </tbody>
          </table>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>