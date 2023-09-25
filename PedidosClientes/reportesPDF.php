<?php session_start();

   
ob_start();


?>


<!doctype html>
<html lang="en">
  <head>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos\bootstrap.min.css">

    <!-- Enlace a la hoja de estilos CSS-->
    <link rel="stylesheet" href="estilos\style.css">


  </head>
  <body>

  <div class="col-md-3">
     
     <div class="card" style="box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.5);">
         <div class="card-header">
             <h4 style="text-align: center;">Pedido</h4>
         </div>

         <div class="card-body">

             
         <?php  
                     foreach($_SESSION['DATOS_PDS'] as $datos){
                     ?>

                     <div class = "form-group">
                         
                         <label for="">Código de Cliente:</label>
                         <label for=""><b> <?php echo $datos['numeroCliente'];?></b></label>
                            
                     </div>
                     
                     <div class="form-group">

                         <label for="Categoria">Código del Pedido:</label>
                         <label for=""><b><?php echo $datos['numeroPedido']; ?></b></label>
 
                     </div>
                    


             <?php 
             }?>                 
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

                 </tr>
             </thead>
             
             <tbody>
                 
                 <?php     

                     if (!empty($_SESSION['REG_PEDIDOS'])) {
                         //Tenemos que recorrer toda la consulta que hemos realizado a la base de datos
                         foreach ($_SESSION['REG_PEDIDOS'] as $pedido) { ?>     
                                            
                             <tr>

                                 <td><?php echo $pedido['cod_cte'];?></td>

                                 <td><?php echo $pedido['cod_dir'];?></td>

                                 <td><?php echo $pedido['fecha_exp'];?></td>

                                 <td><?php echo $pedido['pedido_cte'];?></td>

                                 <td><?php echo $pedido['arti'];?></td>
                                 
                                 <td><?php echo $pedido['cantidad'];?> unds</td>

                                 <td><?php echo $pedido['fecha'];?></td>
                                 
                                 
                             </tr>
                 <?php
                 //Cerramos las llaves del "if" y del "foreach"
                         }}
                        
                       ?>
             </tbody>
         </table>



     </div>
     
 </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/scrollreveal"></script>

</body>

</html>

<?php 

//Codigo para capturar el HTML en una variable para poder renderizar y convertir a PDF.
    $html = ob_get_contents();
    ob_get_clean();
    //$html .= $html;
    
    require_once 'libreria\dompdf\autoload.inc.php';
    use Dompdf\Dompdf;
    $dompdf= new Dompdf();

    //opciones para poder incluir imagenes en el PDF:
    $options =$dompdf->getOptions();
    $options-> set(array('isRemoteEnabled'=>true)); 
    $dompdf->setOptions($options);

   $dompdf->loadHtml($html);
    //$dompdf->loadHtml("HOLA MUNDO");

    //$dompdf->setPaper('A4', 'landscape');
    $dompdf->setPaper('letter');

    // Renderizar el HTML como un PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream("pedido.pdf", array("Attachment" => true));//false para que se muestre en el buscador, si ponemos true se descarga automáticamente.

?>