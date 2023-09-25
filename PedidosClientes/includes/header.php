
<?php include("includes\connect.php");?>
<!doctype html>
<html lang="es">
  <head>
    <title>Trilla</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos\bootstrap.min.css">

    <!-- Enlace a la hoja de estilos CSS-->
    <link rel="stylesheet" href="estilos\estilos.css">

    <!-- Enlace a la Librería FONT AWERSOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <!-- Esilos de etiqueas basicas html-->
    <style>


        body{
            background-color: #F7F9F3;
        }
       
        header{
            align-items: center;
            text-align: center;
            margin-top: 0;
        }
        .jumbotron1{
            background-color: #F0F0F3
            
        }
        .jumbotron2{
            background-color: #F3F3F3
            
        }


    </style>




</head>


<body>

<!-- Vamos a crear el menú de navegación para moverns por toda la aplicación web -->

    <header class="header" style="background-color: #ffff;">

        <img src="http://iptrilla.es/wp-content/uploads/2015/04/logo.png"  alt="">

        <nav class="navbar navbar-expand-lg navbar-light bg-light navegador">

            <div class="container-fluid">

               

                <div class="collapse navbar-collapse" id="navbarColor03" >
                    <ul class="navbar-nav me-auto " style="margin-left: 6%;">

                        <li class="nav-item">
                            <a class="nav-link " href="index.php"> Empresa</a>
                        </li>

                        <li class="nav-item">

                            <div class="dropdown">

                                <a class="nav-link" href="productos.php" style="margin-right: 10%;">Productos</a>

                                    
                                    <div class="dropdown-content">

                                        <a class="nav-link" href="agricola.php" style="transform: none;">Agrícola</a>
                                        <a class="nav-link" href="manutencion.php" style="transform: none;"> Manutención</a>
                                        <a class="nav-link" href="portabotellas.php" style="transform: none;">Portabotellas</a>
                                    
                                    </div>
                                   
                            </div>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="noticias.php">Noticias</a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="contacto.php">Contacto</a>
                        </li>
                        
                        
                        <li class="nav-item">

                            <div class="dropdown">

                                <a class="nav-link" href="usuario.php"><i class="fa-solid fa-user"></i></a>

                                    
                                    <div class="dropdown-content">

                                        <a class="nav-link" href="cerrar_sesion.php" style="transform: none;"><i class="fa-sharp fa-solid fa-right-from-bracket"></i></a>

                                    
                                    </div>
                                   
                            </div>
                        </li>

                        
                        <li class="nav-item">
                            <a class="nav-link" href="pedidos.php"><i class="fa-sharp fa-solid fa-cart-shopping"></i></a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="buscador.php"><i class="fa-sharp fa-solid fa-list-check"></i></a>
                        </li>


                </ul>

                </div>
            </div>
        </nav>
  
    </header>

    <div class="container">
        
        <div class="row" >

       

