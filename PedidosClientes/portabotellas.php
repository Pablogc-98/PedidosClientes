
<?php include("includes/header.php");

$accion = (isset($_POST['accion']))?$_POST['accion']:"";
//Variables de Control de flujo:
$paso= 0;
$paso1='';

switch ($accion) {

    case 'Volver':
        $paso= 0;
        $paso1='';

        break;

    case 'Clasica':
        $paso= '';
        $paso1=0;

        break;
            
    case 'Sidra':
        $paso= '';
        $paso1=0;
        break;                
       

}?>

<div>
<br><br>

</div>
<div class="jumbotron">
    <h4 class="display-3">Portabotellas</h4>
    <hr class="my-2">
</div>
<?php if($paso==0){?>
<div class="row">
    <div class="row">
        
    <div class="col-md-2 contenedor">

        </div>

        <div class="col-md-4 contenedor">
            <div class="card">
                <img class="card-img-top" src="img\Portabotellas\sidra.png" alt="">
                <div class="card-body">
                    <h4 class="card-title">Cajas de Sidra</h4>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Sidra" class="btn btn-outline-primary" style="margin-left:30%">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                    </form>
                </div>
            </div>
        </div>



        <div class="col-md-4 contenedor">
            <div class="card" style="height: 285px;">
                <img class="card-img-top" src="img\Portabotellas\nueva_generacion.png" alt="">
                <div class="card-body">
                    <h4 class="card-title">Nueva Generación</h4>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Sidra" class="btn btn-outline-primary" style="margin-left:30%">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php }

if($paso1==0){?>
    <div style="margin-bottom: 1%;">
        <form action="" method="post">
            <button type="submit" name="accion" value="Volver" class="btn btn-warning btn-md" style="color: black;"><i class="fa-sharp fa-solid fa-arrow-left"></i>Volver</button>
        </form>
    </div>
    
    <div class="row" style=" margin-left: 8,333333% ">

            <div class="col-md-12 manutencion" style=" margin-left: 8,333333% " >
                <div class="card mb-10" style="max-width: 100%;box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.2);">
                    <div class="row no-gutters">
                        <div class="col-md-4" >
                            <img src="" class="card-img" alt="..." style="height: 350px;">
                        </div>

                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                <p class="card-text"><small class="text-muted"><i class="fa-sharp fa-regular fa-calendar"></i> 18 abril, 2023</small></p>
                                <div class="col-md-12 ">
                                    <p class=""> 
                                    </p>
                                </div>

                                <i class="fa-solid fa-angles-down fa-bounce" style=" --fa-bounce-start-scale-x: 1; --fa-bounce-start-scale-y: 1; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1; --fa-bounce-land-scale-x: 1; --fa-bounce-land-scale-y: 1; --fa-bounce-rebound: 0; margin-left:48%" ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <?php }?>


  

<?php include("includes/footer.php");?>
    