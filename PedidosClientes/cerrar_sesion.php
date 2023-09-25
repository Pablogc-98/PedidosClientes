<?php 
    //Ventana para cerrar sesión.

    session_start();
    session_unset();
    session_destroy();
    header('Location:index.php');

    ?>
