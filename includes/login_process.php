<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include "../utils/connect.php"; // Ajusta la ruta según sea necesario

    
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    
    $sql = "SELECT ID_Usuario, Nombre, Contraseña, TipoUsuario FROM Usuarios WHERE CorreoElectronico = '$mail' LIMIT 1";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        
        if ($password === $row['Contraseña']) {
            // Inicio de sesión exitoso
            $_SESSION['user_id'] = $row['ID_Usuario'];
            $_SESSION['username'] = $row['Nombre'];
            $_SESSION['email'] = $row['CorreoElectronico'];
            $_SESSION['tipo_usuario'] = $row['TipoUsuario']; // Guarda el tipo de usuario en la sesión
            
            header("Location: ../index.php");
            exit();
        } else {
            
            echo "Contraseña incorrecta. Intenta nuevamente.";
        }
    } else {
        
        echo "Usuario no encontrado. Intenta nuevamente.";
    }

    
    $connection->close();
} else {
    
    header("Location: login.php");
    exit();
}
?>
