<?php 
include "../PixelKeyTrade/utils/connect.php"

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
            <li><a href="#">Contacto</a></li>
          </ul>
        </nav>
        <div class="user-icons">
          <a href="#" id="login">Iniciar Sesión</a>
          <a href="#" id="register">Registrarse</a>
          <a href="#" id="cart"><img src="/assets/cart-icon.png" alt="" /></a>
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

      <!-- Agrega más divs según sea necesario -->
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>
