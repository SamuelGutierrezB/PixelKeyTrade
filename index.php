<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    // El usuario ha iniciado sesión
    $username = $_SESSION['username'];
    $isAdmin = $_SESSION['tipo_usuario'] === 'Admin';
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
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Juegos</a></li>
            <li><a href="#">Ofertas</a></li>
          </ul>
        </nav>
        <div class="user-icons">
        <?php
        if (isset($_SESSION['user_id'])) {
          if ($isAdmin) {
            echo '<a style="text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;"  href="../PixelKeyTrade/admin/inventario.php" id="inventory">Inventario</a>';
        }  
          echo '<a style="margin-left: 15px; text-decoration: none;color: #fff;font-weight: bold;font-size: 1.2em;" href="../PixelKeyTrade/utils/logout.php" id="logout">Cerrar Sesión</a>';
            
            // Si el usuario es administrador, muestra los botones del inventario
            
        } else {
            echo '<a href="../PixelKeyTrade/pages/login.php" id="login">Iniciar Sesión</a>';
            echo '<a href="../PixelKeyTrade/pages/register.php" id="register">Registrarse</a>';
        }
        ?>
        <a href="#" id="cart"><img src="../PixelKeyTrade/assets/cart-icon.png" alt="" /></a>
    </div>
      </div>
    </header>
    <div class="game-carousel">
      <div><img src="https://picsum.photos/200/300" alt="Juego 1" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 2" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 3" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 3" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 3" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 3" /></div>
      <div><img src="https://picsum.photos/200/300" alt="Juego 3" /></div>

      </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>