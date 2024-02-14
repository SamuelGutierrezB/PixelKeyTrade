// Puedes agregar funciones de JavaScript según sea necesario
document.getElementById("login").addEventListener("click", function () {
  alert("Función de inicio de sesión aún no implementada.");
});

document.getElementById("register").addEventListener("click", function () {
  alert("Función de registro aún no implementada.");
});

document.getElementById("cart").addEventListener("click", function () {
  alert("Función de carrito aún no implementada.");
});

$(document).ready(function () {
  $(".game-carousel").slick({
    slidesToShow: 3, // Número de juegos mostrados al mismo tiempo
    slidesToScroll: 1, // Número de juegos desplazados al avanzar o retroceder
    autoplay: true, // Reproducción automática
    autoplaySpeed: 2000, // Velocidad de reproducción automática en milisegundos
  });
});
