<?php 
//Valores para conexion

$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$db = "pixelkeytrade";
$connection = mysqli_connect($servidor, $usuario, $password,$db);
if($connection->connect_error){
    die("Error de conexion".$connection->connect_error);
}

?>