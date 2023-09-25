
<?php
session_start();
include("includes/header.php");
include("includes\connect.php");
error_log(0);

//Variables para recoger los datos del Pedido.
$user = (isset($_SESSION['USUARIO'])) ? $_SESSION['USUARIO'] : "";
$usuario = (isset($_SESSION['NOMBRE'])) ? $_SESSION['NOMBRE'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
$codCliente = (isset($_POST['codCliente'])) ? $_POST['codCliente'] : "";
$codDireccion = (isset($_POST['select'])) ? $_POST['select'] : "";
$referencia = (isset($_POST['referencia'])) ? $_POST['referencia'] : "";

//Variables para recuperar los primeros datos tras el segundo envío.
$codigoCliente = (isset($_POST['codigoCliente'])) ? $_POST['codigoCliente'] : "";

//Variables para controlar las estructuras de código a mostrar según se avance en el Pedido.
$paso = 0;
$paso0 = '';
$paso1 = '';
$paso2 = '';
$paso3 = '';
$paso4 = '';

$boton="submit";

//Variables para mostrar mensajes de error.
$mensaje0 ='';
$mensaje = '';
$mensaje1 = '';

// ***************************************************************************************************
// ******************** EN ESTA PARTE VAMOS A IMPLEMENTAR LA LÓGICA DEL FORMULARIO *******************
// ***************************************************************************************************

switch ($accion) {

    case 'codCliente':

        //En esta primera parte realizamos la consulta a la base de datos CLIENTES  para comprobar si existe el Código de Cliente que nos pasa el usuario por pantalla
        
        $consulta = $conn->prepare("SELECT * FROM CLIENTES where COD_CTE = '$codCliente'");
        $consulta->execute();
        $resul = $consulta->fetch(PDO::FETCH_ASSOC);

        //En caso de que no se encuentre la variable de la consulta, detendremos el swich y lanzaremos un mensaje de error por pantalla.
        if (!isset($resul['COD_CTE'])) {
            $mensaje = "Código de Cliente incorrecto.";
            break;
        }

        //Una vez comprobado que el código de cliente es correcto, realizaremos una consula a la tabla DIRECCIONES_CTE para poder importar los Códigoos de las Direcciones
        try {
            $query = $conn->prepare("SELECT * FROM DIRECCIONES_CTE WHERE COD_CTE ='$codCliente'");
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $mensaje = "Código de Cliente incorrecto.";
        }

        //Realizamos un condicional para Pasar a la siuinete extructura en caso de que salga bien las operaciones de este apartado.
        if (isset($resultado)) {
            $paso = 1;
            $paso0 = 1;
        }

        //Cerramos la sesión que nos muestra el mensaje de Pedido realizado Correctamente
        unset($_SESSION['MENSAJE']);
        break;

        //Para mostrar en el select desplegable cada uno de los Códigos de Direccion del cliente indicado
        // debemos realizar una consulta a la tabla DIRECCIONES_CTE con la condición del código de cliente.
    case 'codDireccion':
        $paso = 1;

        try {
            $query2 = $conn->prepare("SELECT * FROM DIRECCIONES_CTE WHERE COD_CTE = '$codigoCliente' AND COD_DIR = '$codDireccion'");
            $query2->execute();
            $resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $mensaje = "fallo en la consulta";
        }
        //En caso de que exista el código de direccion del cliente indicado, daremos valor a la variable de control de flujo para que de esta manera se nos muestren los siguientes 
        //campos del formulario.

        if (isset($resultado2)) {
            $paso1 = 1;
        }
        break;

    case 'Registrar':
        //Establecemos las variables para los controles de flujo de código para que no se altere la apariencia de la web.
        $paso = 1;
        $paso2 = 1;

        //Registramos cada una de las variables que hemos ido recogiendo para crear un array de sesionn para poder almacenarlos todos de forma permanente hasta tramitar el pedido.
        $usuario = (isset($_SESSION['NOMBRE'])) ? $_SESSION['NOMBRE'] : "";
        $codigoCte =  (isset($_POST['CD_CTE'])) ? $_POST['CD_CTE'] : "";
        $codDir =  (isset($_POST['COD_DIR'])) ? $_POST['COD_DIR'] : "";
        $razonSocial = (isset($_POST['RZ_Social'])) ? $_POST['RZ_Social'] : "";
        $codPosal = (isset($_POST['CP'])) ? $_POST['CP'] : "";
        $ciudad = (isset($_POST['CIUDAD'])) ? $_POST['CIUDAD'] : "";
        $provincia = (isset($_POST['PROV'])) ? $_POST['PROV'] : "";
        $pais = (isset($_POST['PAIS'])) ? $_POST['PAIS'] : "";
        $numero_pedido = (isset($_POST['NUM_PED'])) ? $_POST['NUM_PED'] : "";
        $fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : "";

        // Almacenamos el valor de cada una de las variables anteriores en los distintos elementos del array.
        if (!isset($_SESSION['DATOS'])) {
            $datos = array(
                'user' => $usuario,
                'codCliente' => $codigoCte,
                'codDir' => $codDir,
                'razonSocial' => $razonSocial,
                'codPostal' => $codPosal,
                'ciudad' => $ciudad,
                'provincia' => $provincia,
                'pais' => $pais,
                'numPedido' => $numero_pedido,
                'fecha' => $fecha,
            );

            $_SESSION['DATOS'][0] = $datos;
        }
        $paso3 = 1;
        break;

    case 'Añadir':
        //Establecemos las variables para los controles de flujo de código para que no se altere la apariencia de la web.
        $paso = 1;
        $paso2 = 1;
        $paso3 = 1;
        // Recogemos las variables del formulario enviado al pulsar el botón de añadir al carrito.
        $refArticulo =  (isset($_POST['refArticulo'])) ? $_POST['refArticulo'] : "";
        $descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : "";
        $ean = (isset($_POST['ean'])) ? $_POST['ean'] : "";

        if ($_POST['cantidad'] > 0) {

            $cantidad = $_POST['cantidad'];
            if(!isset($_SESSION['correo'])){
                $_SESSION['correo']="";
            }

            //En caso de que no exista la variable de Sesión creamos una en la que almacenamos las variables de con los datos de los productos seleccionados
            if (!isset($_SESSION['PEDIDOS'])) {
                $pedido = array(
                    'referencia' => $refArticulo,
                    'descripcion' => $descripcion,
                    'ean' => $ean,
                    'cantidad' => $cantidad,
                );
                $_SESSION['PEDIDOS'][0] = $pedido;
            } else {
                //En caso de que se seleccione más de un producto lo que vamos a hacer es crear un array multidimensional con los datos de los distintos productos seleccionados
                //Vamos a comprobar que no se seleccionen dos veces el mismo producto antes de crear el array de productos seleccionados
                $index = array_column($_SESSION['PEDIDOS'], "referencia");

                if (in_array($refArticulo, $index)) {
                    $mensaje0 = "El producto ya ha sido seleccionado...";
                    //echo "<script>alert('El producto ya ha sido seleccionado...')</script>";
                } else {

                    $pedidos = count($_SESSION['PEDIDOS']);
                    $pedido = array(
                        'referencia' => $refArticulo,
                        'descripcion' => $descripcion,
                        'ean' => $ean,
                        'cantidad' => $cantidad,

                    );
                    $_SESSION['PEDIDOS'][$pedidos] = $pedido;
                    }
                }
            } 
        break;

    case 'Buscar':
        //Establecemos las variables para los controles de flujo de código para que no se altere la apariencia de la web.
        $paso = 1;
        $paso2 = 1;
        $paso3 = 1;
        //Realizaremos una consulta a la base de datos en la tabla "ITMMASTER" donde emplearemos la variable $referencia como índice de la búsqueda de la consulta.
        try {
            $query1 = $conn->prepare("SELECT * FROM ITMMASTER WHERE COD_ART= '$referencia';");
            $query1->execute();
            $resultado1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $mensaje0 = "No existe la referencia indicada... ";
        }
        break;

    case 'Eliminar':
        //Establecemos las variables para los controles de flujo de código para que no se altere la apariencia de la web.
        $paso = 1;
        $paso2 = 1;
        $paso3 = 1;

        //Para poder eliminar un producto de la lista hemos creado  un formulario en la Tabla en la que se muestran los productos seleccionados para poder enviar un "id" 
        // para de esta manera poder identificar cada uno de los registros de los productos seleccionados.
        if ($_POST['ID']) {

            $id = $_POST['ID'];
            // Recorremos la variable de sesion indexando cada una de las variables para poder acceder a cada una de ellas de forma aislada.
            foreach ($_SESSION['PEDIDOS'] as $indice => $pedido) {

                if ($pedido['referencia'] == $id) {
                    unset($_SESSION['PEDIDOS'][$indice]);
                }
            }
        }
        break;


    case 'Tramitar':
        //Creamos una variable de sessión para mostrar un mensaje por pantalla para la validación del PEDIDO
        if(!isset($_SESSION['TRAMITAR'])){
            $_SESSION['TRAMITAR'] = "";
        }
        break;

        case 'Validar':
                //Para Tramitar el pedido y proceder a su registro en la Base de Datos hemos realizado en primer lugar un condicional que compruebe que xisten las sesiones para que 
                 //en caso contrario no se nos muestren errores por pantalla. 
            
            if(isset($_SESSION['DATOS'])&&isset($_SESSION['PEDIDOS'])){
                try {
                    //Recorremos los arrays de SESSION para grabar sus datoos en variables locales para realizar la consulta e insertar los datos
                    foreach ($_SESSION['DATOS'] as $datos) {
    
                        $codCte = $datos['codCliente'];
                        $codDir = $datos['codDir'];
                        $fechaExp = $datos['fecha'];
                        $pedidoCTE = $datos['numPedido'];
                    }
        

                    
                        
                       
                    if(!isset($resultado_art['COD_CTE'])&&!isset($resultado_art['PEDIDO_CTE'])&&!isset($resultado_art['ARTI'])){
                               // if(($referencia!=$resultado_art['ARTI'])&&($codCte!=$resultado_art['COD_CTE'])&&($pedidoCTE!=$resultado_art['PEDIDO_CTE'])){
                            //if(($referencia!=$resultado_art['ARTI'])&&($codCte!=$resultado_art['COD_CTE'])){
                    $consulta = $conn->prepare("SELECT * fROM PEDIDOS where COD_CTE = $codCte AND PEDIDO_CTE = '$pedidoCTE';") ;
                    $consulta->execute();
                    $resultado_art = $consulta->fetchALL(PDO::FETCH_ASSOC);

                    foreach ($_SESSION['PEDIDOS'] as $pedido) {
                        $cantidad = $pedido['cantidad'];
                        $referencia = $pedido['referencia'];

                        $query = $conn->prepare("INSERT INTO PEDIDOS (COD_CTE, COD_DIR, FECHA_EXP, PEDIDO_CTE, ARTI, CANTIDAD, USUARIO) VALUES ('$codCte', '$codDir','$fechaExp', '$pedidoCTE', '$referencia', $cantidad, '$user');");
                        $query->execute();
                            if(isset($query)){
                                $_SESSION['MENSAJE']="";

                            }
                        }
                    }
                    unset($_SESSION['DATOS']);
                    unset($_SESSION['PEDIDOS']);              
                    unset($_SESSION['correo']);
    
                } catch (Exception $e) {
                    echo $e;
                    if(!isset($_SESSION['ERROR'])){
                        $_SESSION['ERROR'] = "";}
                    
                    unset($_SESSION['MENSAJE']);
                }
                unset($_SESSION['TRAMITAR']);
            }  
            break;

        case 'Cerrar':
            //Este apartado es solo para cerrar el mensaje emergente que sale al tramitar el PEDIDO.
            unset($_SESSION['TRAMITAR']);
            break;

            case 'Cerrar0':
                //Este apartado es solo para cerrar el mensaje emergente que sale al tramitar el PEDIDO.
                unset($_SESSION['ERROR']);
                break;

        case 'Close':
            unset($_SESSION['MENSAJE']);
            break;

        case 'Enviar':
            foreach($_SESSION['DATOS'] as $dato){

            $codigoCte =  $dato['codCliente'];
            $razonSocial = $dato['razonSocial'];
            $codPosal = $dato['codPostal'];
            $ciudad = $dato['ciudad'];
            $provincia = $dato['provincia'];
            $pais = $dato['pais'];
            $numero_pedido = $dato['numPedido'];
            $fecha = $dato['fecha'];            
            }
                //Configuración del puerto de envío
                ini_set('smtp_port', '587');
            if(isset($_POST['email'])){
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $destinatario = $email;
                $asunto = 'Pedido Recibido';
                $cuerpo = '
                <html>
                  <body>
                  <img src="http://iptrilla.es/wp-content/uploads/2015/04/logo.png"  alt="">

                    <table class="table table-bordered">
                        <thead>
                        <ul>
                           <li> <h2> Información del Cliente </h2> </li>
                        </ul>
                        </thead>

                        <tbody>
                            <tr>
                            <th scope="row"><b>Código de Cliente:</b></th>
                            <td>'. $codigoCte .  '</td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Código Postal:</b></th>
                            <td> '. $codPosal. '</td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Ciudad:</b></th>
                            <td> '. $ciudad . '</td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Provincia:</b></th>
                            <td> '. $provincia .' </td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Pais:</b></th>
                            <td>'. $pais .'</td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Número del Pedido de Cliente:</b></th>
                            <td> '.$numero_pedido .'</td>
                            </tr>

                            <tr>
                            <th scope="row"><b>Fecha de Expedición:</b></th>
                            <td> '.$fecha . '</td>
                            </tr>
                  
                        </tbody>
                    </table><br>

                    <table class="table table-bordered">
                    <thead>
                    <ul>
                       <li> <h2> Productos Seleccionados </h2> </li>
                    </ul>
                    </thead><tbody>';
                $cuerpo .='</td><td>';
                foreach ($_SESSION['PEDIDOS'] as $indice => $pedido) {
                    $cuerpo.='<tr>
                            <th scope="row"><b>Referencia del Artículo:</b></th>';
                    $cuerpo .= "<td><b>".$pedido['referencia']."         ". $pedido['descripcion']."</b></td></tr>";
                    
                    $cuerpo.='<tr>
                    <th scope="row"><b>Cantidad solicitada:</b></th>';
                    $cuerpo .= "<td><b>".$pedido['cantidad']."</b></td></tr>";
                    $cuerpo .= "<hr><br>";
                }
                $cuerpo.='</tbody></table></body></html> ';
                //para el  envío en formato HTML
                $headers = "MIME-Version: 1.0\r\n";
                $headers = "Content-type: text/html; charset=UTF8\r\n";
                //direccion del remitente:
                $headers .= "FROM: <$email>\r\n";
                //Ruta del mensaje desde origen a destino:
                $headers.="Return-path: $destinatario\r\n";
               mail($destinatario, $asunto, $cuerpo, $headers);
            }
            unset($_SESSION['correo']);
         break;

    case 'Descargar':
        //En este caso solo volvemos a dar valor a las variables de control de flujo para evitar que al redireccionar la pagina el boton submit nos deeje la página en la vista inicical.
        $paso = 1;
        $paso2 = 1;
        $paso3 = 1;
        $paso4 = 1;
        break;

    case 'Volver':
            //En este caso solo volvemos a dar valor a las variables de control de flujo para evitar que al redireccionar la pagina el boton submit nos deeje la página en la vista inicical.
        unset($_SESSION['DATOS']);
        unset($_SESSION['PEDIDOS']);
        unset($_SESSION['correo']);
        break;
}

// En esta parte vamos a comprobar si existen las variables de Session se muestre ya la página actualizada por si nos movemos de la venatana de carrito por algún motivo:
if (isset($_SESSION['DATOS'])) {
    $paso = 1;
    $paso2 = 1;
    $paso3 = 1;
}
if (isset($_SESSION['ERROR'])||isset($_SESSION['TRAMITAR'])) {
    $boton = 'button';
}


?>

            <!--*************************************************************************************************************************************** -->
            <!--******************************************* PARTE VISIBLE DE LA WEB ******************************************************************* -->
            <!--*************************************************************************************************************************************** -->
<div>
    <br><br>
</div>
<div class="jumbotron">
    <h4 class="display-3">Generar un Pedido</h4>
    <hr class="my-2">
</div>

<?php if (isset($_SESSION['NOMBRE'])) { ?>

    <div class="col-md-4" >

        <div class="card" style="box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.5);">

            <div class="card-header">
                <h4 style="text-align: center;">Datos del Pedido</h4>
            </div>

            <div class="card-body">
                <label for="" style="text-align: center;">
                    <h5><?php echo $_SESSION['NOMBRE']; ?></h5>
                </label>

                <!-- Introducir el Código del Cliente -->
                <?php if ($paso == 0) { ?>
                    <form method="post">

                        <div class="form-group">
                            <!-- Mensaje en caso de error en la consultad el Código Cliente -->
                            <?php if (!empty($mensaje)) { ?>
                                <p style="color:red;"> <?= $mensaje ?> </p>
                            <?php } ?>
                            <label for="Referencia">Introducir el Código del Cliente</label>
                            <input type="text" class="form-control" id="codCliente" name="codCliente" value="" placeholder="Código del Cliente">
                            <button type="submit" class="btn btn-outline-primary" name="accion" value="codCliente" style="margin-left:35%; margin-top:3%"> Siguiente</button>

                        </div>
                    </form>

                <?php } if ($paso0 == 1) {  ?>


                    <form method="post">


                            <input type="hidden" name="codigoCliente" value="<?php echo $codCliente ?>">
                                                  
                            <div class="form-group">

                            <label for="" style="text-align: center;">Código de Cliente:</label> <br>
                            <label for="" style="text-align: center;">
                                <h6><?php echo $codCliente ?></h6>
                            </label><br>
                            <label for="" style="text-align: center;">Selecciona el Código de Dirección del Cliente:</label>

                            <select class="custom-select" name="select" id="inputGroupSelect04" require>
                                <option selected>Código de Dirección...</option>

                                <?php foreach ($resultado as $codDirec) { ?>

                                    <option value="<?php echo $codDirec['COD_DIR'] ?>"><?php echo $codDirec['COD_DIR'] ?> <b>Dirección:</b><?php echo $codDirec['DIR'] ?> <b>CP:</b><?php echo $codDirec['CP'] ?> Ciudad:<?php echo $codDirec['CIUDAD'] ?> </option>

                                <?php  } ?>
                            </select>
                            <button type="submit" class="btn btn-outline-primary" name="accion" value="codDireccion" style="margin-left:35%; margin-top:3%"> Siguiente</button>
                        </div>
                    </form>

                <?php   } if ($paso1 == 1) {

                    try {

                        $query0 = $conn->prepare("SELECT * FROM CLIENTES WHERE COD_CTE = $codigoCliente");
                        $query0->execute();
                        $resultado0 = $query0->fetch(PDO::FETCH_LAZY);
                    } catch (Exception $e) {
                        $mensaje = "Código de Cliente incorrecto.";
                    }
                ?>
                    <br>
                    <form method="POST">

                        <label for="" style="text-align: center;">Código de Cliente:</label> <br>
                        <input type="hidden" name="CD_CTE" value="<?php echo $codigoCliente ?>">
                        <label for="" style="text-align: center;">
                            <h6><?php echo $codigoCliente ?></h6>
                        </label><br>
                        <input type="hidden" name="COD_DIR" value=<?php echo $codDireccion ?>>
                        <label for="" style="text-align: center;">Razon Social:</label> <br>
                        <input type="hidden" name="RZ_Social" value="<?php echo $resultado0['RAZON_SOCIAL']; ?>">
                        <label for="" style="text-align: center;">
                            <h6><?php echo $resultado0['RAZON_SOCIAL']; ?></h6>
                        </label><br>

                        <?php foreach ($resultado2 as $dirreccion) { ?>

                            <br>
                            <label for="" style="text-align: center;">Código Postal:</label>
                            <input type="hidden" name="CP" value="<?php echo $dirreccion['CP']; ?>">
                            <label for="" style="text-align: center;">
                                <h6><?php echo $dirreccion['CP']; ?></h6>
                            </label><br>

                            <label for="" style="text-align: center;">Ciudad:</label>
                            <input type="hidden" name="CIUDAD" value="<?php echo $dirreccion['CIUDAD']; ?>">
                            <label for="" style="text-align: center;">
                                <h6><?php echo $dirreccion['CIUDAD']; ?></h6>
                            </label><br>

                            <label for="" style="text-align: center;">Provincia:</label>
                            <input type="hidden" name="PROV" value="<?php echo $dirreccion['PROVINCIA']; ?>">
                            <label for="" style="text-align: center;">
                                <h6><?php echo $dirreccion['PROVINCIA']; ?></h6>
                            </label><br>

                            <label for="" style="text-align: center;">País:</label>
                            <input type="hidden" name="PAIS" value="<?php echo $dirreccion['PAIS']; ?>">
                            <label for="" style="text-align: center;">
                                <h6><?php echo $dirreccion['PAIS']; ?></h6>
                            </label><br>

                            <label for="" style="text-align: center;">Introducir el Número del Pedido del Cliente:</label>
                            <input type="text" name="NUM_PED" class="form-control" id="numPedido" value="" required placeholder="Número de Pedido del Cliente.">

                            <label for="" style="text-align: center;">Fecha de expedición del Pedido:</label>
                            <input type="date" name="fecha" class="form-control" id="fechaPedido"  required min=<?php $hoy = date("Y-m-d"); echo $hoy; ?>>
                                                                                                                                   

                            <div style="  display: flex; justify-content: center;">

                                <button type="submit" name="accion" value="Registrar" style="margin-top: 2%;" class="btn btn-outline-success">Registrar Datos<i class="fa-sharp fa-solid fa-memo-circle-check"></i></button>
                            </div>
                    </form>

            <?php }
                    } 

             if ($paso2 == 1) {
                
                foreach ($_SESSION['DATOS'] as $datos) {
            ?>
                    <br>

                    <label for="" style="text-align: center;">Código de Cliente:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['codCliente'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">Razon Social:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['razonSocial'] ?></h6>
                    </label><br>


                    <label for="" style="text-align: center;">Código Postal:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['codPostal'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">Ciudad:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['ciudad'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">Provincia:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['provincia'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">País:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['pais'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">Número del Pedido del Cliente:</label><br>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['numPedido'] ?></h6>
                    </label><br>

                    <label for="" style="text-align: center;">Fecha de expedición del Pedido:</label>
                    <label for="" style="text-align: center;">
                        <h6><?php echo $datos['fecha'] ?></h6>
                    </label><br>




            <?php  }
            } ?>

            </form>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Volver" class="btn btn-warning btn-md" style="color: black;"><i class="fa-sharp fa-solid fa-arrow-left"></i>Volver</button>
                    </form>

            </div>

        </div>

    </div>


    <div class="col-md-8 formlario"  >

        <div class="card" style="box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.45);">

            <!--*************************************************************************************************************************************** -->
            <!--**************************************** TABLA PARA MOSTRAR PRODUCTOS BUSCADOS ******************************************************** -->
            <!--*************************************************************************************************************************************** -->
            <table class="table table-bordered">

                <?php if ($paso3 == 1) {
                    //Vamos a realizar las consultas a la base de datos para recuperar los datos del arículo buscado.
                    //En caso de no encontrar el artículo deseado mostraremos un mensaje por pantalla con el error que se produzca durante la búsqueda.
                ?>

                    <div class="col-md-6">
                            <!-- Mensaje en caso de error en caso de que la referencia introducida no exista en el master de productos -->
                                <?php if (!empty($mensaje0)) { ?>
                                <p style="color:red;"> <?= $mensaje0 ?> </p>
                            <?php } ?>
                        <form action="" method="post">

                            <div class="input-group mb-2" style="margin-top: 2%;">

                                <input type="text" class="form-control" placeholder="Referencia del Artículo." name="referencia" value="" required">

                                <div class="input-group-append">
                                    <button type="<?php echo $boton?>" class="btn btn-outline-primary" name="accion" value="Buscar">Buscar</button>
                                </div>

                            </div>

                        </form>

                    <?php  } ?>

                    <thead>

                        <tr>
                            <th>Código de Artículo</th>
                            <th>Descripción</th>
                            <th>EAN</th>
                            <th>Cantidad</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($mensaje1)) { ?>
                            <p style="color:red; margin-left: 1%"> <?= $mensaje1 ?> </p>
                        <?php } 
   
                    //******************************************************************************************************************************** */
                    // ************** ESTE FRAGMENTO DE CÓDIGO ES PARA MOSTRAR LOS PRODUCTOS SELECCIONADOS Y AÑADIDOS AL CARRITO **********************
                    //******************************************************************************************************************************** */

                        if (isset($resultado1)) {
                            //Tenemos que recorrer toda la consulta que hemos realizado a la base de datos
                            foreach ($resultado1 as $producto) {

                        ?>
                                <tr>

                                    <form action="" method="post">
                                        <td><?php echo $producto['COD_ART']; ?></td>
                                        <td><?php echo $producto['DESCRIPCION']; ?></td>
                                        <td><?php echo $producto['EAN']; ?></td>
                                        <td><input type="number" name="cantidad" require style="text-align: right;" min="1" default="0"></td>

                                        <input type="hidden" name="refArticulo" value="<?php echo $producto['COD_ART']; ?>">
                                        <input type="hidden" name="descripcion" value="<?php echo $producto['DESCRIPCION']; ?>">
                                        <input type="hidden" name="ean" value="<?php echo $producto['EAN']; ?>">

                                        <td><button type="submit" class="btn btn-outline-primary" name="accion" value="Añadir">Añadir al Carrito<i class="fa-sharp fa-solid fa-cart-shopping"></i></button></td>
                                    </form>

                                </tr>

                    </div>
        </div>
                    <?php
                    //Cerramos las llaves del "if" y del "foreach"
                            }
                        }
                        
                        ?>
                    </tbody>
                    </table>

                    <?php 
                    //******************************************************************************************************************************** */
                    //ESTE FRAGMENTO DE CÓDIGO ES PARA MOSTRAR EL MENSAJE DE VALIDACIÓN DEL PEDIDO POR SI NO SE HAN INTRODUCIDO CORRECTAMENTE LOS DATOS:
                    //******************************************************************************************************************************** */

                    if(isset($_SESSION['TRAMITAR'])){ ?>

                        <div class="col-md-5  mensaje" style="margin-top: 1.5%; margin-left:1%;">

                            <div class="alert alert-warning alert-dismissible fade show" style="background-color: rgba(201, 201, 201, 0.95);" role="alert">

                                    <form action="" method="post">
                                        <button type="submit" class="btn-close" data-bs-dismiss="alert" name="accion" value="Cerrar"></button>

                                         <h4 style="color: black;text-align:center;">¿Estás seguro de que están todos los datos del Pedido introducidos correctamente?</h4>
                               
                                        <div class="btn-group" role="group" aria-label=""  style=" margin-left: 40%;">
                                            <button type="submit" class="btn btn-success btn-lg" name="accion" value="Validar">Realizar Pedido</button>   
                                        </div>
                                    </form>
                            </div>
                        </div>

                    <?php }
                    //******************************************************************************************************************************** */
                    //ESTE FRAGMENTO DE CÓDIGO ES PARA MOSTRAR EL MENSAJE DE ERROR EN CASO DE QUE HAYAN PRODUCTOS REPETIDOS EN UN PEDIDO YA GENERADO ***
                    //******************************************************************************************************************************** */

                    if(isset($_SESSION['ERROR'])){ ?>

                        <div class="col-md-5  mensaje" style="margin-top: 1.5%; ">

                            <div class="alert alert-warning alert-dismissible fade show" role="alert">

                                    <form action="" method="post">
                                    
                                        <div class="col-md-12">

                                                <h5 style="text-align: center;"> El cliente <?php echo $codCte ?> ya tiene un pedido realizado con el mismo Código de Pedido (<?php echo $pedidoCTE?>)
                                                y con las referencias:</h5>
                                            <?php 

                                                foreach ($_SESSION['PEDIDOS'] as  $pedido) {
                                                    
                                                    $referencia = $pedido['referencia'];                
                                                
                                                    if(isset($resultado_art)){

                                                         foreach ($resultado_art as $problema) {   

                                                            if($referencia == $problema['ARTI']){?>
                                                                <ul  style="margin-left: 18%" >                
                                                                    <li><h6> <?php echo $problema['ARTI'];?> unidades del arículo soliciadas: <?php echo $problema['CANTIDAD']?>.</h6></li>                       
                                                                </ul>

                                            <?php  } } }}?>
                                            <h5>ya solicitadas.</h5>
                                        </div>

                                        <div class="btn-group" role="group" aria-label=""  style=" margin-left: 43%;">
                                            <button type="submit" class="btn btn-success btn-mg" name="accion" require value="Cerrar0">Aceptar</button>
                                           
                                        </div>
                                    </form>
                            </div>
                        </div>

                    <?php 
                    }?>

<!--*************************************************************************************************************************************** -->
<!--******************************************** TABLA DE PRODUCTOS REGISTRADOS *********************************************************** -->
<!--*************************************************************************************************************************************** -->

<table class="table table-bordered" style="text-align: center;">

    <?php if ($paso3 == 1) { ?>

        <ul style="margin-top: 2%;">
            <li><label for="" >
                    <h5> Productos Selecionados:</h5>
                </label> <button class="btn btn-outline-warning" style="margin-left:50%"><a href="reportes.php" style="text-decoration: none;">Descargar PDF</a></button></li>
        </ul>

        <div class="col-md-5">
            <form action="" method="post">
                <thead>

                    <tr>
                        <th>Código de Artículo</th>
                        <th>Descripción</th>
                        <th>EAN</th>
                        <th>Cantidad</th>

                    </tr>
                </thead>
            <?php  } ?>

            <tbody>
                <?php
                if (!empty($_SESSION['PEDIDOS'])) {
                    //Tenemos que recorrer toda la consulta que hemos realizado a la base de datos
                    foreach ($_SESSION['PEDIDOS'] as $pedido) { ?>
                        <tr>
                            <form action="" method="post">
                                <td><?php echo $pedido['referencia']; ?></td>
                                <td><?php echo $pedido['descripcion']; ?></td>
                                <td><?php echo $pedido['ean']; ?></td>
                                <td><?php echo $pedido['cantidad']; ?> &nbsp unds</td>

                                <input type="hidden" name="ID" value="<?php echo $pedido['referencia']; ?>">

                                <td><button type="<?php echo $boton?>" class="btn btn-outline-danger" name="accion" value="Eliminar"><i class="fa-sharp fa-regular fa-trash-can"></i></button></td>

                            </form>
                        </tr>
        </div>
    </div>

         <?php //Cerramos las llaves del "if" y del "foreach"
             } } ?>
</tbody>
</table>

<?php if ($paso3 == 1) { 
    ?>
    <div style="display: flex; justify-content: center;" >
        <form action="" method="post" style="display: flex; justify-content: center;">
            <button class="btn btn-outline-success" name="accion" value="Tramitar" type="<?php echo $boton;?>" ">Tramitar Pedido</button>
        </form>
    </div>

    <div><br></div>
<?php } 
        //CONTENEDOR PARA INCLUIR EL CORREO AL QUE ENVIAR LA INFORMACIÓN DEL PEDIDO:

                if(isset($_SESSION['correo'])){ ?>

                    <div class="col-md-8  " style="margin-top: 1.5%; margin-left:18%;">

                        <div class="alert alert-success alert-dismissible fade show" style="background-color: #C9EEF2;" ;" role="alert">

                                <form action="" method="post">
                                    <div class="col-md-12">
                                        <h6 style="color: black; text-align:center;">Introduce tu correo para que se te envíe el albarán del Pedido.</h6>
                                        <input type="email" name="email" id="email" style="width: 100%;" style="text-align:center;">
                                    </div>

                                    <div class="btn-group" role="group" aria-label=""  style=" margin-left: 40%; margin-top: 1.5%;">
                                      <button type="<?php echo $boton?>" class="btn btn-primary btn-md" name="accion" value="Enviar">Enviar Correo</button>
                                      
                                    </div>
                                </form>
                            
                        </div>

                    </div>

    <?php }?>
</div>
<?php 
                    //******************************************************************************************************************************** */
                    //ESTE FRAGMENTO DE CÓDIGO ES PARA MOSTRAR EL MENSAJE DE VALIDACIÓN DEL PEDIDO POR SI NO SE HAN INTRODUCIDO CORRECTAMENTE LOS DATOS:
                    //******************************************************************************************************************************** */

                    if(isset($_SESSION['MENSAJE'])){ ?>

                        <div class="col-md-5  mensaje" style="margin-top: 1.5%; margin-left:1%;">

                            <div class="alert alert-success alert-dismissible fade show"  role="alert">

                                    <form action="" method="post">
                                        <button type="submit" class="btn-close" data-bs-dismiss="alert" name="accion" value="Close"></button>

                                         <h3 style="color: black;text-align:center;">Pedido realizado Correctamente </h3>
                               
                                    </form>
                                
                            </div>            

                        </div>

                    <?php }?>
<div>
    <br>
</div>


<?php } else { ?>

    <div class="col-md-2">

    </div>
    <div class="alert alert-success mensaje0" style="margin-top: 6%; width:60%; text-align: center; align-items:center">
        <h3>Inicia sesón para poder registrar un Pedido.</h3>
        <a href="usuario.php" class="" style="color:black; text-decoration:none;"><button type="button" class="btn btn-outline-dark">
                <h6>Iniciar Sesión</h6>
            </button></a>

    </div>

<?php }?>
</div>
<?php include("includes/footer.php"); ?>


