<?php
session_start();

// Verifica si se envió un formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code
    include "../utils/connect.php"; // Ajusta la ruta según sea necesario

    // Obtener datos del formulario
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT ID_Usuario, Nombre, Contraseña, TipoUsuario FROM Usuarios WHERE CorreoElectronico = '$mail' LIMIT 1";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verifica la contraseña
        if ($password === $row['Contraseña']) {
            // Inicio de sesión exitoso
            $_SESSION['user_id'] = $row['ID_Usuario'];
            $_SESSION['username'] = $row['Nombre'];
            $_SESSION['email'] = $row['CorreoElectronico'];
            $_SESSION['tipo_usuario'] = $row['TipoUsuario']; // Guarda el tipo de usuario en la sesión
            
            header("Location: ../index.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta. Intenta nuevamente.";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado. Intenta nuevamente.";
    }

    // Cierra la conexión a la base de datos
    $connection->close();
} else {
    // Redirige a la página de inicio de sesión si se accede directamente
    header("Location: login.php");
    exit();
}
?>
