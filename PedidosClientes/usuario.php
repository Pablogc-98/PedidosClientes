
<?php 
session_start();
include("includes/header.php");
?>

<?php 
    

    $usuario = (isset($_POST['usuario']))?$_POST['usuario']:"";
    $contra = (isset($_POST['contra']))?$_POST['contra']:"";



    //Comenzamos a validar las credenciales del Usuario para comprobar que los datos son correctos.
    if($_POST){
       

        if (!empty($_POST['usuario'])&& !empty($_POST['contra'])) {
            
            $query= $conn->prepare("SELECT * FROM USUARIOS WHERE COD_USER = '$usuario'");
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            //Vamos a crear dos variales de error en caso de que se introduzca mal el usuario o la contraseña o que en caso contrario, se inicie sesión:

            $mensaje= '';
            $mensaje0= '';
            $mensaje1 = '';
            $mensaje2 = '';


            if(isset($resultado['COD_USER'])){
                //Validación del nombre de usuario si existe seguimos con el proceso de validación.
                if ($contra == $resultado['PWD']) {
                    $_SESSION['USUARIO']= $resultado['COD_USER'];
                    $_SESSION['NOMBRE']= $resultado['NOMBRE'];
                    $mensaje="Sesión iniciada Correctamente.";
                }else {
                    $mensaje0 ="Contraseña incorrecta";
                }

            }else {
                $mensaje1= "No existe el nombre de Usuario";
            }

    }else{
        $mensaje2= "Completa todos los campos para poder iniciar sesión.";
    }
}
?>

<div>
<br><br>

</div>
<div class="jumbotron">
    <h4 class="display-3">Perfil</h4>
    <hr class="my-2">
</div>

    <div class="row">
        <div class="col-md-3">
        </div>

    <?php if (!isset($_SESSION['NOMBRE'])) {
       
    ?>
        <div class="col-md-6">
        <?php if(!empty($mensaje)){ ?>
            
            <h5 style= "color:blue; text-aling: center;"> <?= $mensaje ?> </h5>
             <?php }?>

            <div class="card">
                

                <!-- Cabecera del Formulario de Inicio de Sesión -->
                <div class="card-header" style="text-align: center;">
                    <h4> Inicio de Sesión </h4>
                </div>
                <!-- Cuerpo del Formulario de Inicio de Sesión -->
                <div class="card-body">

                    <form method="post">

                        <!-- Input para el código de Usuario" -->
                        <div class="form-outline mb-4">


                            <?php if(!empty($mensaje1)){ ?>
                                <p style= "color:red;"> <?= $mensaje1 ?> </p>
                            <?php }?>
                            <?php if(!empty($mensaje2)){ ?>
                                <p style= "color:red;"> <?= $mensaje2 ?> </p>
                            <?php }?>


                            <label class="form-label" for="form2Example1"><h5>Usuario</h5></label>
                            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Introduce el Nombre de Usuario"/>
                            
                        </div>

                        <!-- Input para la contraseña del Cliente -->
                        <div class="form-outline mb-4">
                            
                        <?php if(!empty($mensaje0)){ ?>
                                <p style= "color:red;"> <?= $mensaje0 ?> </p>
                            <?php }?>
                            <label class="form-label" for="form2Example2"><h5>Contraseña</h5></label>
                            <input type="password" id="contra"  name="contra" class="form-control"  placeholder="Introduce la Contraseña"/>
                            
                        </div>

                        
                        <!-- Botón para enviar -->
                        <button type="submit" class="btn btn-primary " value="iniciar">Iniciar Sesión</button>



                    </form>

                </div>
            </div>
        </div> 
        <?php  } else{?> 


          

                <div class="alert alert-success mensaje0" style="margin-top: 6%; width:60%; text-align: center; align-items:center;">
                    <h2>Sesión iniciada correctamente.</h2>
                    <a href="cerrar_sesion.php" class="" style="color:black; text-decoration:none;"><button type="button" class="btn btn-outline-dark"><h6>Cerrar Sesión</h6></button></a>
                    
                </div> 

           
  
        
        <?php  }?>
    
    </div>
 


  

<?php include("includes/footer.php");?>
    