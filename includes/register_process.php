<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include "../utils/connect.php";
    echo "se registra"; 
    
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT); 

    
    $query = "INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, TipoUsuario) 
              VALUES ('$nombre', '$apellido', '$correo', '$contrasena', 'cliente')";

    if (mysqli_query($connection, $query)) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
        header("Location: ../index.php");
    } else {
        echo "Error en el registro: " . mysqli_error($connection);
    }

    
    mysqli_close($connection);
} else {
    
    header("Location: register.php");
    exit();
}
?>
