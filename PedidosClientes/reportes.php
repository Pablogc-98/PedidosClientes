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


    <div class="row">
                    <div class="col-md-4">

                        <div class="card">
                        
                            <?php  foreach($_SESSION['DATOS'] as $datos){
                            ?>
                            <br>
                           
                                <ul>
                                    
                                    <label for=""style="text-align: center;">Código de Cliente:</label>                                       
                                    <label for="" style="text-align: center;"><b><?php echo $datos['codCliente']?></b></label><br>
                
                                    <label for=""style="text-align: center;">Razon Social:</label> 
                                    <label for="" style="text-align: center;"><b><?php echo $datos['razonSocial'] ?></b></label>
            
                                    <br>
                                    <label for=""style="text-align: center;">Código Postal:</label> 
                                    <label for="" style="text-align: center;"><b><?php echo $datos['codPostal'] ?></b></label><br>
                
                                    <label for=""style="text-align: center;">Ciudad:</label> 
                                    <label for="" style="text-align: center;"><b><?php echo $datos['ciudad'] ?></b></label><br>
                                    
                                    <label for=""style="text-align: center;">Provincia:</label>
                                    <label for="" style="text-align: center;"><b><?php echo $datos['provincia'] ?></b></label><br>
                
                                    <label for=""style="text-align: center;">País:</label>
                                    <label for="" style="text-align: center;"><b><?php echo $datos['pais'] ?></b></label><br>
                                    
                                    <label for=""style="text-align: center;">Número del Pedido del Cliente:</label>
                                    <label for="" style="text-align: center;"><b><?php echo $datos['numPedido'] ?></b></label> <br>
                
                                    <label for=""style="text-align: center;">Fecha de expedición del Pedido:</label>
                                    <label for="" style="text-align: center;"><b><?php echo $datos['fecha'] ?></b></label>     


                                 <?php  } ?>
                            </ul>
                        
                        </div>
                            
                    </div>

                <div class="col-md-8">
                    
                
                            <table class="table table-bordered" >

                                <ul>
                                    <li><label for=""style=""><h5> Pedido:</h5></label></li>
                                </ul>


                                    <thead>
                                        

                                        <tr>
                                            <th>Código de Artículo</th>
                                            <th>Descripción</th>
                                            <th>EAN</th>
                                            <th>Cantidad</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php  
                                        
                                        if (!empty($_SESSION['PEDIDOS'])) {
                                                    //Tenemos que recorrer toda la consulta que hemos realizado a la base de datos
                                            foreach($_SESSION['PEDIDOS'] as $pedido){ ?> 
                                        
                                        <tr>
                                           
                                                <td><?php echo $pedido['referencia'];?></td>
                                                <td><?php echo $pedido['descripcion'];?></td>
                                                <td><?php echo $pedido['ean'];?></td>
                                                <td><?php echo $pedido['cantidad'];?> unds</td>

                                                      

                                               
                                            
                                           
                                        </tr>
                                        <?php }}?>

                                    
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


