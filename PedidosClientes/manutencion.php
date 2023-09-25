
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

    case 'Exposicion':
        $paso= '';
        $paso1=0;

        break;
            
    case 'Iotermicos':
        $paso= '';
        $paso1=0;
        break;
    
            
    case 'Pales':
        $paso= '';
        $paso1=0;
        break;
    
            
    case 'Residuos':
        $paso= '';
        $paso1=0;
        break;
                    
    case 'Apilables':
        $paso= '';
        $paso1=0;
        break;
                    
    case 'Encajables':
        $paso= '';
        $paso1=0;
        break;
                            
    case 'Suelos':
        $paso= '';
        $paso1=0;
        break;
       
       

}



?>

<div>
<br><br>

</div>
<div class="jumbotron">
    <h4 class="display-3">Manutención</h4>
    <hr class="my-2">
</div>

<?php if($paso==0){?>
<div class="row">
    <div class="col-lg-3 contenedor" style="margin-top:1% ; margin-bottom: 2%;">
        <div class="card" style="height:370px">

            <img class="card-img-top" src="img\manutencion\bandejasExposicion.png" alt="">
            <div class="card-body">
                <h5 class="card-title">Bandejas de Exposición</h5> <br>
                <form action="" method="post">
                    <button type="submit" name="accion" value="Exposicion" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-3 contenedor" style="margin-top:1% ; margin-bottom: 2%;">
        <div class="card" style="height:370px">

            <img class="card-img-top" src="img\manutencion\contenedoresIsotermicos.png" alt="">
            <div class="card-body">
                <h5 class="card-title">Contenedores Iotérmicos</h5> <br>
                <form action="" method="post">
                    <button type="submit" name="accion" value="Iotermicos" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-3 contenedor" style="margin-top:1% ; margin-bottom: 2%;">
        <div class="card" style="height:370px">

            <img class="card-img-top" src="img\manutencion\contenedoresPales.png" alt="">
            <div class="card-body">
                <h5 class="card-title">Contenedores y Palés</h5> <br>
                <form action="" method="post">
                    <button type="submit" name="accion" value="Pales" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-3 contenedor" style="margin-top:1% ; margin-bottom: 2%;">
    <div class="card" style="height:370px">

        <img class="card-img-top" src="img\manutencion\contenedoresResiduos.png" alt="">
        <div class="card-body">
            <h5 class="card-title">Contenedores de Residuos</h5> 
            <form action="" method="post">
                <button type="submit" name="accion" value="Residuos" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
            </form>
        </div>
    </div>
    </div>


    <div class="col-lg-1 ">

    </div>

    
        <div class="col-lg-3 contenedor" style="margin-left:5%; ">
            <div class="card" style="height:370px">

                <img class="card-img-top" src="img\manutencion\cubetasApilables.png" alt="">
                <div class="card-body">
                    <h5 class="card-title">Cubetas Apilables</h5> <br>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Apilables" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-3 contenedor">
            <div class="card" style="height:370px">

                <img class="card-img-top" src="img\manutencion\cubetasEncajables.png" alt="">
                <div class="card-body">
                    <h5 class="card-title">Cubetas Encajables</h5> <br>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Encajables" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                    </form>
                </div>
            </div>
        </div> 
        
        <div class="col-lg-3 contenedor">
            <div class="card" style="height:370px">

                <img class="card-img-top" src="img\manutencion\tarimasSuelos.png" alt="">
                <div class="card-body">
                    <h5 class="card-title">Tarimas y Suelos</h5> <br>
                    <form action="" method="post">
                        <button type="submit" name="accion" value="Suelos" class="btn btn-outline-primary">Más Información<i class="fa-sharp fa-solid fa-angles-right"></i></button>
                    </form>
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
    