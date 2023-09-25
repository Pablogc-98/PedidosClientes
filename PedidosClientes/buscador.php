
<?php 
session_start(); 
include("includes/header.php");
include("includes\connect.php");
//error_reporting(0);
$paso=0;
$paso0=1;
$boton="submit";

//Variables necesarias para poder realizar las búsquedas:
              
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
$user = (isset($_SESSION['USUARIO'])) ? $_SESSION['USUARIO'] : "";

$mensaje='';
$mensaje1='';
$busqueda=0;


switch ($accion) {

    case 'Buscar':
           
                    //******************************************************************************************************************************** */
                    // **************************** ESTE FRAGMENTO DE CÓDIGO ES PARA BUSCAR LOS PEDIDOS REGISTRADOS  *********************************
                    //******************************************************************************************************************************** */

        //REGISTRAMOS LAS DOS VARIABLES QUE NECESIAMOS PARA LA CONSULTA A LA BASE DE DATOS:

        $numeroCliente=(isset($_POST['numCliente']))?$_POST['numCliente']:'';
        $numeroPedido=(isset($_POST['numeroPedido']))?$_POST['numeroPedido']:''; 

        if(!empty($numeroCliente)&& !empty($numeroPedido)){
            if(!isset($_SESSION['añadir'])){
                $_SESSION['añadir']='';
            }
        }
        // CREAMOS DOS VARIABLES DE SESSION PARA EVITAR QUE SE PIERDAN LOS DATOS DE LA CONSULTA AL ACTUALIZAR LA WEB:
        if (!isset($_SESSION['DATOS_PDS'])) {
            $datos = array(
                'numeroCliente' => $numeroCliente,
                'numeroPedido' => $numeroPedido,  
            );

            $_SESSION['DATOS_PDS'][0] = $datos;
        }
        //RECORREMOS LA SESION DE DATOS PARA PODER ALMACENARLOS EN VARIABLES LOCALES
        foreach($_SESSION['DATOS_PDS'] as $datos){
            $cliente= $datos['numeroCliente'];
            $pedido= $datos['numeroPedido'];
        }   

        if(empty($numeroCliente) && empty($numeroPedido)){
            $mensaje='Completa alguno de los campos para poder realizar la búsqueda';
            break;
        } 

        //************************************************************************ */
        //********** BUSUEDA EN CASO DE QUE SE RELLENEN LOS DOS CAMPOS *********** */
        //************************************************************************ */

        if(!empty($numeroCliente)&& !empty($numeroCliente)){
            $paso0 = 0;
            

            $query= $conn->prepare("SELECT * FROM PEDIDOS WHERE COD_CTE= '$cliente'  AND PEDIDO_CTE = '$pedido' AND USUARIO='$user'");
            $query->execute();
            $resultado= $query->fetchAll(PDO::FETCH_ASSOC);       
        }      

        //************************************************************************ */
        //********* BUSUEDA EN CASO DE QUE SE RELLENE EL CAMPO CLIENTE *********** */
        //************************************************************************ */

        if(!empty($numeroCliente)&& empty($numeroPedido)){
            $query= $conn->prepare("SELECT * FROM PEDIDOS WHERE COD_CTE= '$cliente' AND USUARIO='$user'");
            $query->execute();
            $resultado= $query->fetchAll(PDO::FETCH_ASSOC);

        }

        //************************************************************************ */
        //********** BUSUEDA EN CASO DE QUE SE RELLENE EL CAMPO PEDIDO *********** */
        //************************************************************************ */

        if(empty($numeroCliente)&& !empty($numeroPedido)){
            $query= $conn->prepare("SELECT * FROM PEDIDOS WHERE PEDIDO_CTE = '$pedido' AND USUARIO='$user'");
            $query->execute();
            $resultado= $query->fetchAll(PDO::FETCH_ASSOC);
        }

    //RECORREMOS LA VARIABLE QUE CONTIENE EL RESULTADO DE LA CONSULTA GUARDANDO CADA REGISTRO EN UNA VARIABLE DE SESSION.
        //DE ESTA FORMA VAMOS A LOGRAR MOSTRAR LOS DATOS DE LA CONSULTA AUNQUE SE ACTUALICE LA WEB.

        foreach($resultado as $pedido){

            if(!isset($_SESSION['REG_PEDIDOS'])){
                $pedido = array(
                    'cod_cte' => $pedido['COD_CTE'],
                    'cod_dir' => $pedido['COD_DIR'],
                    'fecha_exp' => $pedido['FECHA_EXP'],
                    'pedido_cte' => $pedido['PEDIDO_CTE'],
                    'arti' => $pedido['ARTI'],
                    'cantidad' => $pedido['CANTIDAD'],
                    'usuario' => $pedido['USUARIO'],
                    'fecha' => $pedido['FECHA_CREACION'], 
                );
                $_SESSION['REG_PEDIDOS'][0] = $pedido;
            }else{
                $index = array_column($_SESSION['REG_PEDIDOS'], "ARTI");

                if (in_array($pedido['ARTI'], $index)) {

                } else 

                $pedidos = count($_SESSION['REG_PEDIDOS']);
                $pedido = array(
                    'cod_cte' => $pedido['COD_CTE'],
                    'cod_dir' => $pedido['COD_DIR'],
                    'fecha_exp' => $pedido['FECHA_EXP'],
                    'pedido_cte' => $pedido['PEDIDO_CTE'],
                    'arti' => $pedido['ARTI'],
                    'cantidad' => $pedido['CANTIDAD'],
                    'usuario' => $pedido['USUARIO'],
                    'fecha' => $pedido['FECHA_CREACION'],
                );
                $_SESSION['REG_PEDIDOS'][$pedidos] = $pedido;
                }
            }
            if(empty($resultado)){
                $mensaje ="No hay resultados para esta búsqueda.";
                $error='';

            }
          

    case 'Borrar':

                   
                    //******************************************************************************************************************************** */
                    // ****************** ESTE FRAGMENTO DE CÓDIGO ES PARA BORRAR ARTICULOS DE LOS LOS PEDIDOS REGISTRADOS  ****************************
                    //******************************************************************************************************************************** */

        if(!isset($_SESSION['BORRAR'])){
            $_SESSION['BORRAR']='';
        }
        $paso0=0;
        $paso = 0;
        break;
      
    case 'Eliminar':    
           
        if (isset($_POST['articulo'])) {

            $id = (isset($_POST['articulo']))?$_POST['articulo']:'';
            // Recorremos la variable de sesion indexando cada una de las variables para poder acceder a cada una de ellas de forma aislada.
            foreach ($_SESSION['REG_PEDIDOS'] as $indice => $pedido) {

                if ($pedido['arti'] == $id) {
                    unset($_SESSION['REG_PEDIDOS'][$indice]);
                }
            }
            $cliente = (isset($_POST['cliente']))?$_POST['cliente']:'';
            $pedido = (isset($_POST['pedido']))?$_POST['pedido']:'';

            $query= $conn->prepare("DELETE FROM PEDIDOS WHERE COD_CTE= '$cliente'  AND PEDIDO_CTE = '$pedido' AND ARTI ='$id'");
            $query->execute();

        }

        unset($_SESSION['BORRAR']);
        $paso0=0;
        $paso = 0;
        break;

        
    case 'Seleccionar':
                           
                    //******************************************************************************************************************************** */
                    // **************** ESTE FRAGMENTO DE CÓDIGO ES PARA SELECCIONAR ARTICULOS DE LOS LOS PEDIDOS REGISTRADOS  *************************
                    //******************************************************************************************************************************** */

        $paso0=0;
        $paso = 0;

        $cantidad = (isset($_POST['cantidad']))?$_POST['cantidad']:"";
        $cantidad1 = (isset($_POST['cantidad1']))?$_POST['cantidad1']:"";

        try {
            $id = (isset($_POST['articulo']))?$_POST['articulo']:'';
            // Recorremos la variable de sesion indexando cada una de las variables para poder acceder a cada una de ellas de forma aislada.
            foreach ($_SESSION['REG_PEDIDOS'] as $indice => $pedido) {

                if ($pedido['arti'] == $id) {
                    $articulo=$pedido['arti'];
                    $cliente1=$pedido['cod_cte'];
                    $numpedido=$pedido['pedido_cte'];
                }
            }
            
        } catch (Exception $th) {
            
        }

    if(empty($cantidad)){
        break;
    }

        $query= $conn->prepare("UPDATE PEDIDOS SET CANTIDAD='$cantidad' WHERE ARTI='$articulo' AND COD_CTE= '$cliente1' AND PEDIDO_CTE='$numpedido';");
        $query->execute();
        unset($_SESSION['REG_PEDIDOS']);

        $query= $conn ->prepare("SELECT * FROM PEDIDOS WHERE COD_CTE= '$cliente1' AND PEDIDO_CTE='$numpedido'  AND USUARIO='$user'; ");
        $query->execute();
        $resultado1 = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($resultado1 as $pedido){
            if(!isset($_SESSION['REG_PEDIDOS'])){
                $pedido = array(
                    'cod_cte' => $pedido['COD_CTE'],
                    'cod_dir' => $pedido['COD_DIR'],
                    'fecha_exp' => $pedido['FECHA_EXP'],
                    'pedido_cte' => $pedido['PEDIDO_CTE'],
                    'arti' => $pedido['ARTI'],
                    'cantidad' => $pedido['CANTIDAD'],
                    'usuario' => $pedido['USUARIO'],
                    'fecha' => $pedido['FECHA_CREACION'],

    
                );
                $_SESSION['REG_PEDIDOS'][0] = $pedido;
            }else{
            $index = array_column($_SESSION['REG_PEDIDOS'], "ARTI");

            if (in_array($pedido['ARTI'], $index)) {

            } else 

            $pedidos = count($_SESSION['REG_PEDIDOS']);
            $pedido = array(
                'cod_cte' => $pedido['COD_CTE'],
                'cod_dir' => $pedido['COD_DIR'],
                'fecha_exp' => $pedido['FECHA_EXP'],
                'pedido_cte' => $pedido['PEDIDO_CTE'],
                'arti' => $pedido['ARTI'],
                'cantidad' => $pedido['CANTIDAD'],
                'usuario' => $pedido['USUARIO'],
                'fecha' => $pedido['FECHA_CREACION'],
    
            );
            $_SESSION['REG_PEDIDOS'][$pedidos] = $pedido;
            }
    }   

        break;

        case 'Seleccionar1':
                           
            //******************************************************************************************************************************** */
            // **************** ESTE FRAGMENTO DE CÓDIGO ES PARA SELECCIONAR ARTICULOS DE LOS LOS PEDIDOS REGISTRADOS  *************************
            //******************************************************************************************************************************** */

            $paso=0;
            $paso0=1;
           

            $cliente = (isset($_POST['cliente']))?$_POST['cliente']:"";
            $pedidos= (isset($_POST['pedido']))?$_POST['pedido']:"";
            $articulo = (isset($_POST['articulo']))?$_POST['articulo']:"";
            $cantidad = (isset($_POST['cantidad']))?$_POST['cantidad']:"";
            $cantidad = (isset($_POST['cantidad1']))?$_POST['cantidad1']:"";
          
            //Datos introducidos para actualizar la base de datos:
            $articuloActualizado=(isset($_POST['articuloActualizado']))?$_POST['articuloActualizado']:'';
            $cantidadActualizada=(isset($_POST['cantidadActualizada']))?$_POST['cantidadActualizada']:'';

            if(empty($cantidadActualizada)){
                $cantidadActualizada = $cantidad;                
            };
            
            $query= $conn->prepare("UPDATE PEDIDOS SET CANTIDAD='$cantidadActualizada' WHERE ARTI='$articulo' AND COD_CTE= '$cliente' AND PEDIDO_CTE='$pedidos';");
            $query->execute();

            break;

      

    case 'Volver':
            //En este caso solo volvemos a dar valor a las variables de control de flujo para evitar que al redireccionar la pagina el boton submit nos deeje la página en la vista inicical.
        unset($_SESSION['DATOS_PDS']);
        unset($_SESSION['REG_PEDIDOS']);
        unset($_SESSION['añadir']);
        break;
        

    case 'Eliminar1':

        $id = (isset($_POST['articulo']))?$_POST['articulo']:'';
        $cliente = (isset($_POST['cliente']))?$_POST['cliente']:'';
        $pedido = (isset($_POST['pedido']))?$_POST['pedido']:'';

        $query= $conn->prepare("DELETE FROM PEDIDOS WHERE COD_CTE= '$cliente'  AND PEDIDO_CTE = '$pedido' AND ARTI ='$id'");
        $query->execute();
        break;

    case 'Añadir':

        foreach ($_SESSION['REG_PEDIDOS'] as $pedido) {
            $cliente=$pedido['cod_cte'];
            $pedido1=$pedido['pedido_cte'];
            $fecha =$pedido['fecha_exp'];
            $codDir=$pedido['cod_dir'];
         } 


        $referencia = (isset($_POST['referencia']))?$_POST['referencia']:'';
        $cantidad = (isset($_POST['cantidad']))?$_POST['cantidad']:'';



            $query1 = $conn->prepare("SELECT * FROM ITMMASTER WHERE COD_ART= '$referencia';");
            $query1->execute();
            $resultado1 = $query1->fetchAll(PDO::FETCH_ASSOC);

        if(empty($resultado1)){
            $mensaje1="No existe el producto seleccionado";
            break;
        
        }

        try{

            $query = $conn->prepare("INSERT INTO PEDIDOS (COD_CTE, COD_DIR, FECHA_EXP, PEDIDO_CTE, ARTI, CANTIDAD, USUARIO) VALUES ('$cliente', '$codDir','$fecha', '$pedido1', '$referencia', $cantidad, '$user');");
            $query->execute();
            unset($_SESSION['REG_PEDIDOS']);

                    $query= $conn ->prepare("SELECT * FROM PEDIDOS WHERE COD_CTE= '$cliente' AND PEDIDO_CTE='$pedido1'  AND USUARIO='$user'; ");
                    $query->execute();
                    $resultado1 = $query->fetchAll(PDO::FETCH_ASSOC);

                    foreach($resultado1 as $pedido){
                        if(!isset($_SESSION['REG_PEDIDOS'])){
                            $pedido = array(
                                'cod_cte' => $pedido['COD_CTE'],
                                'cod_dir' => $pedido['COD_DIR'],
                                'fecha_exp' => $pedido['FECHA_EXP'],
                                'pedido_cte' => $pedido['PEDIDO_CTE'],
                                'arti' => $pedido['ARTI'],
                                'cantidad' => $pedido['CANTIDAD'],
                                'usuario' => $pedido['USUARIO'],
                                'fecha' => $pedido['FECHA_CREACION'],
            
                
                            );
                            $_SESSION['REG_PEDIDOS'][0] = $pedido;
                        }else{
                        $index = array_column($_SESSION['REG_PEDIDOS'], "ARTI");
        
                        if (in_array($pedido['ARTI'], $index)) {
        
                        } else 
        
                        $pedidos = count($_SESSION['REG_PEDIDOS']);
                        $pedido = array(
                            'cod_cte' => $pedido['COD_CTE'],
                            'cod_dir' => $pedido['COD_DIR'],
                            'fecha_exp' => $pedido['FECHA_EXP'],
                            'pedido_cte' => $pedido['PEDIDO_CTE'],
                            'arti' => $pedido['ARTI'],
                            'cantidad' => $pedido['CANTIDAD'],
                            'usuario' => $pedido['USUARIO'],
                            'fecha' => $pedido['FECHA_CREACION'],
                
                        );
                        $_SESSION['REG_PEDIDOS'][$pedidos] = $pedido;
                        }
                }   

        }catch(Exception $e){
            $mensaje1= "El Producto ya está seleccionado en el Pedido...";
            break;
        }
    
  
        break;
        
}
if(isset($_SESSION['EDITAR'])||isset($_SESSION['EDITAR1'])){
    $boton='button';
}
if(isset($_SESSION['DATOS_PDS'])&&isset($_SESSION['REG_PEDIDOS'])){
    $paso0=0;
    $paso = 0;
}

?>

<div>
<br><br>
</div>


<div class="jumbotron">
    <h4 class="display-3">Pedidos Registrados</h4>
    <hr class="my-2">
</div>

<?php if (isset($_SESSION['NOMBRE'])) { ?>

    <div class="col-md-3">
     
        <div class="card" style="box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.5);">
            <div class="card-header">
                <h4 style="text-align: center;">Buscador</h4>
            </div>

            <div class="card-body">
                
            <?php if($paso0==1){?>
                <form method="post">
                            
                            <?php if(!empty($mensaje)){ ?>
                                <p style="color: red;"><?= $mensaje ?></p>
                            <?php }?>

                        <div class="form-group">
                            <label for=""><b><?php echo $_SESSION['NOMBRE']?></b></label>
                        </div>

                        <div class = "form-group">
                            <label for="">Código de Cliente:</label>
                            <input type="text" class="form-control" id="numCliente" name="numCliente" placeholder="Código del Cliente." require>
                            
                        </div>
                        
                        <div class="form-group">

                            <label for="Categoria">Código del Pedido:</label>
                            <input type="text" class="form-control" id="numeroPedido" name="numeroPedido" placeholder="Código del Pedido."require >
    
                        </div>
                        <br>
                                                    
                        <button type="<?php echo $boton?>" class="btn btn-success" name="accion" value="Buscar" style="margin-left:25%;">Buscar Pedido</button>
                </form>  
            <?php } if($paso0==0){  
                             foreach($_SESSION['DATOS_PDS'] as $datos){
                                $_SESSION['cliente']= $datos['numeroCliente'];
                                $_SESSION['pedido']= $datos['numeroPedido'];
                               
                        ?>
                
                        <div class="form-group">
                            <label for=""><b><?php echo $_SESSION['NOMBRE']?></b></label>
                         </div>

                         <?php if(!empty($_SESSION['cliente'])){ ?>
                            <div class = "form-group">
                                <label for="">Código de Cliente:</label>
                                <label for=""><b> <?php echo $_SESSION['cliente'];?></b></label>                        
                            </div>
                        <?php } ?>
                        <?php if(!empty($_SESSION['pedido'])){ ?>
                            <div class="form-group">
                                <label for="Categoria">Código del Pedido:</label>
                                <label for=""><b><?php echo $_SESSION['pedido']; ?></b></label>
                            </div>
                        <?php } ?>

                        </form>

                        <form action="" method="post">
                            <button type="<?php echo $boton?>" name="accion" value="Volver" class="btn btn-warning btn-md" style="color: black; margin-left:25%"><i class="fa-sharp fa-solid fa-arrow-left"></i>Volver</button>
                        </form>

                <?php  }}?>                                 
            </div>
        </div>

    </div>

    <div class="col-md-9">
        <div class="card" style="box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.5);">

            <table class="table table-bordered" style="text-align: center;">

                <?php 
                //Vamos a realizar las consultas a la base de datos para recuperar los datos del arículo buscado.
                //En caso de no encontrar el artículo deseado mostraremos un mensaje por pantalla con el error que se produzca durante la búsqueda.
                ?>
                <thead>
                    <tr>
                        <th>Código de Cliente:</th>
                        <th>Código de Dirección:</th>
                        <th>Fecha de Expedición:</th>
                        <th>Código del Pedido:</th>
                        <th>Artículos:</th>
                        <th>Cantidad:</th>
                        <th>Fecha de Creacion:</th>
                        <th>Opciones:</th>

                    </tr>
                </thead>
                
                <tbody>
               
                    <?php  
                   if(!isset($error)){
                        if (!empty($_SESSION['REG_PEDIDOS'])) {
                            //Tenemos que recorrer toda la consulta que hemos realizado a la base de datos
                            foreach ($_SESSION['REG_PEDIDOS'] as $pedido) {
                                $cliente=$pedido['cod_cte'];
                                $pedido1=$pedido['pedido_cte'];
                                $fecha =$pedido['fecha_exp'];
                                $codDir=$pedido['cod_dir'];
                                ?>     
                         <form action="" method="post">                       
                                <tr>
                                    

                                    <input type="hidden" value="<?php echo $pedido['cod_cte'];?>" name="cliente">
                                    <td><?php echo $pedido['cod_cte'];?></td>

                                    <td><?php echo $pedido['cod_dir'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['fecha_exp'];?>" name="fecha">
                                    <td><?php echo $pedido['fecha_exp'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['pedido_cte'];?>"name="pedido">
                                    <td><?php echo $pedido['pedido_cte'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['arti'];?>" name="articulo">
                                    <td><?php echo $pedido['arti'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['cantidad'];?>" placeholder="<?php echo $pedido['cantidad'];?>" name="cantidad1">
                                    <td><input type="number" style="text-align: right; width:90%" min="0" placeholder="<?php echo $pedido['cantidad'];?> unds" name="cantidad"> </td>

                                    <input type="hidden" value="<?php echo $pedido['fecha'];?>"name="fecha_cra">
                                    <td><?php echo $pedido['fecha'];?></td>
                                    
                                    <td>
                                       
                                        <button type="submit"class="btn btn-outline-primary" name="accion" value="Seleccionar"><i class="fa-sharp fa-regular fa-pen-to-square"></i></button>
                                         <button type="<?php echo $boton?>" class="btn btn-outline-danger" name="accion" value="Eliminar"><i class="fa-sharp fa-regular fa-trash-can"></i></button>
                                    </td>
                                  
                                <tr>   

                                </form> 
                    <?php
                    //Cerramos las llaves del "if" y del "foreach"
                            }}else{
                                $query= $conn->prepare("SELECT * FROM PEDIDOS WHERE USUARIO= '$user';");
                                $query->execute();
                                $resultado= $query->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($resultado as $pedido) { ?> 

                                <tr>
                                    <form action="" method="post">

                                    <input type="hidden" value="<?php echo $pedido['COD_CTE'];?>" name="cliente">
                                    <td><?php echo $pedido['COD_CTE'];?></td>

                                    <td><?php echo $pedido['COD_DIR'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['FECHA_EXP'];?>" name="fecha">
                                    <td><?php echo $pedido['FECHA_EXP'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['PEDIDO_CTE'];?>"name="pedido">
                                    <td><?php echo $pedido['PEDIDO_CTE'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['ARTI'];?>" name="articulo">
                                    <td><?php echo $pedido['ARTI'];?></td>

                                    <input type="hidden" value="<?php echo $pedido['CANTIDAD'];?>" name="cantidad1">
                                    <td>  <input type="number"  style="text-align: right; width:90%"   placeholder="<?php echo $pedido['CANTIDAD'];?>unds" min= 0 name="cantidadActualizada"></td>

                                    <input type="hidden" value="<?php echo $pedido['FECHA_CREACION'];?>"name="fecha_cra">
                                    <td><?php echo $pedido['FECHA_CREACION'];?></td>
                                    
                                    <td>
                                       
                                        <button type="submit"class="btn btn-outline-primary" name="accion" value="Seleccionar1"><i class="fa-sharp fa-regular fa-pen-to-square"></i></button>
                                        <button type="<?php echo $boton?>" class="btn btn-outline-danger" name="accion" value="Eliminar1"><i class="fa-sharp fa-regular fa-trash-can"></i></button>
                                    </td>
                                    </form>                                  
                                </tr>


                             
                        <?php }}} 
                            ?>
                </tbody>
            </table>
            <?php 
            if(isset($_SESSION['añadir']) && !isset($error)){


                 ?>

            <form action="" method="post">       
                <table class="table table-bordered" style="text-align: center;">
                    <thead>
                        <tr>
    
                            <?php if(!empty($mensaje1)){ ?>
                                <p style="color: red; margin-left:2%"><b><?= $mensaje1?></b></p>
                            <?php }?>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           
                                
                                <td ><b>Introduce Referencia:</b> </td>
                                <td><input type="text" name="referencia" style="text-align: center" id="" required placeholder=""></td>
                                <td><b>Introduce Cantidad</b></td>
                                <td><input type="number" name="cantidad" style="text-align: right" id="" min= 0 required></td>
                                <td><button type="submit" class="btn btn-outline-success"name="accion" value="Añadir">Añadir Producto</button></td>
                          
                        </tr>

                    </tbody>
                </table>
                </form>
                <?php  }?>

<?php 
        if(isset($_SESSION['REG_PEDIDOS'])){?>

            <div style="display:flex; justify-content:center">
                <button class="btn btn-outline-warning " ><a href="reportesPDF.php" style="text-decoration: none;">Descargar PDF</a></button></li>
            </div>
            <?php } ?>

        </div>
    </div>
    
    <?php } else { ?>

<div class="col-md-2">

</div>
<div class="alert alert-success mensaje0" style="margin-top: 6%; width:60%; text-align: center; align-items:center">
    <h3>Inicia sesón para poder ver el Registro de Pedidos.</h3>
    <a href="usuario.php" class="" style="color:black; text-decoration:none;"><button type="button" class="btn btn-outline-dark">
            <h6>Iniciar Sesión</h6>
        </button></a>

</div>

<?php } ?>
</div>
<?php include("includes/footer.php"); ?>
    