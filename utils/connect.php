<?php 
//Valores para conexion

$servidor = "localhost";
$usuario = "root";
$password = "MySQL56431";
$db = "prueba";
$connection = mysqli_connect($servidor, $usuario, $password,$db);
if($conecta->connect_error){
    die("Error de conexion".$conecta->connect_error);
}

?>