<?php 

//Declaramos las variables para almacenar las credenciales de acceso a la Base de Datos.

$host='192.168.35.25';
$db='pract';
$usuario="famesa";
$passwrd="@Famesa123";

    try {
        
         
        $conn = new PDO("mysql:host=$host; dbname=$db;", $usuario, $passwrd);
        
        //Prueba de conexión(Mostraremos un mensaje de error por pantalla en caso de que no exista la conexión).
       
       /* if($conn){
            echo "conexión establecida";
        }else{
            echo "error de conexión";
        }
        
        */

    } catch (Exception $e) {
        echo $e-> getMessage();
    }

?>