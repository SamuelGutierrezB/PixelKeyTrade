<?php
// register_process.php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code
    include "../utils/connect.php"; // Adjust the path accordingly
    echo "se registra"; // Agregado el punto y coma
    // Retrieve user input from the form; // Comentado, y agregado el punto y coma
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT); // Hash the password for security

    // Insert user data into the 'Usuarios' table
    $query = "INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, TipoUsuario) 
              VALUES ('$nombre', '$apellido', '$correo', '$contrasena', 'cliente')";

    if (mysqli_query($connection, $query)) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        echo "Error en el registro: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
} else {
    // Redirect to the registration page if accessed directly
    header("Location: register.php");
    exit();
}
?>
