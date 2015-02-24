<?php
$miSesion = Sesion::singleton();
$usuario=$miSesion->getSesionUsuarioId();

$rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
include_once($rutaClases."/log.class.php");
$this->log_us = new log();

$variable['tipo']=$_REQUEST['tipo'];

//Conexión con POSTGRES
$conexion1 = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB) {

    echo "/Este se considera un error fatal";
    exit;
}

//Conexión con ORACLE
$conexion2 = "wconexionclave";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion2);
if (!$esteRecursoDBORA) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['fecha']=date("d/m/Y");
if(isset($_REQUEST["actualClave"]))
{ 
    $variable['usuario_id']=$_REQUEST['usuario_id'];
}
else
{
    $variable['usuario_id']=$_REQUEST['nombreUsuario'];
}

$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta=count($registro);
//echo $cadena_sql."<br>";
if(!is_array($registro))
{
    $mensaje="El usuario no exite, intente de nuevo...";
    $html="<script>alert('".$mensaje."');</script>";
    echo $html;
    echo "<script>location.replace('')</script>";
}    
else
{

    for($i=0; $i<=count($registro)-1; $i++)
    {


        if($registro[$i][11]==51 || $registro[$i][11]==52)
        {    
            $conexion3 = "conexionMySQLest"; //Conexión MySQL Estudiantes
        }
        elseif($registro[$i][11]==121)
        {
            $conexion3="conexionMySQLfun";
            $conexion4="conexionMySQLest";

            $esteRecursoDBMySQL = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion3);
            $esteRecursoDBMySQL1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion4);
            if (!$esteRecursoDBMySQL1) {

                echo "///Este se considera un error fatal";
                exit;
            }
        }
        
        else
        {
            $conexion3 = "conexionMySQLfun"; //Conexión MySQL Funcionarios
        }    
    }
    
    $esteRecursoDBMySQL=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion3);
    
    if (!$esteRecursoDBMySQL) {

        echo "///Este se considera un error fatal";
        exit;
    }
    
    
    $variable['nuevaClave']=$this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST["nuevaClave"]);
    if(isset($_REQUEST["actualClave"]))
    {    
        $claveActual = $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST["actualClave"]); //Encripta la contraseña
    }
    
    $cierto=0;
    for ($i=0; $i < $cuenta; $i++)
    {
        if(isset($_REQUEST["actualClave"]))
        {    
            if($registro[$i]['cta_clave']!=$claveActual)//Si la contraseña actual no es correcta, genera error
            {
                $cierto=1;
            }
        }
        if($registro[$i]['usu_nro_doc_actual']==$_REQUEST["nuevaClave"]) //Las contraseñas no peden ser el número de cédula
        {
            $cierto=2;
        }
        if($registro[$i]['cta_nombre_usuario']==$_REQUEST["nuevaClave"]) //La contraseña no puede ser el mismo usuario
        {
            $cierto=3;
        }
        if($variable['nuevaClave']==$registro[$i]['cta_clave'])
        {
            $cierto=4;
        }    

    }

    $resultNumeros = preg_match("/^(?=.*\d{2})/", $_REQUEST["nuevaClave"]); //Valida que contenga como mínimo dos caracteres numŕeicos
    $resultCaracteres= preg_match("/^(.*[a-zA-Z].*){2}/", $_REQUEST["nuevaClave"]); //Valida que contenga como mínimo dos caracteres alfabeticos
    $resultPrimerCaracter= preg_match("/^(?!\d)/", $_REQUEST["nuevaClave"]); //Valida que el primer caracter no sea numérico
    
    if($cierto==1){
        $mensaje="La contraseña actual no es correcta.";
        $html="<script>alert('".$mensaje."');</script>";
            echo $html;
            echo "Redireccionando.";
           // $indice = $registro[0][10];
            echo "<script>location.replace('')</script>"; 
    }
    elseif($cierto==2 || $cierto==3)
    {
        $mensaje="Contraseña nueva inválida.";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>"; 
        
    }
    elseif($cierto==4)
    {
        $mensaje= "La contraseña nueva no puede ser igual a la contraseña actual, intentelo de nuevo...";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>"; 
        
    }    
    elseif(strlen ($_REQUEST["nuevaClave"])>16 || strlen ($_REQUEST["nuevaClave"])<8)
    {
        $mensaje="La contraseña debe contener mínimo 8 y máximo 16 caracteres alfanuméricos.";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>"; 
    }
    elseif(!$resultNumeros || !$resultCaracteres){ 
        $mensaje="La contraseña debe contener como mínimo dos caracteres numéricos o como mínimo dos caracteres alfabeticos.";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>";
    }
    elseif(!$resultPrimerCaracter)
    {
        $mensaje="La contraseña no debe comenzar por un caracter numérico.";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>";
    }    
    else
    {
        $cadena_sql = $this->sql->cadena_sql("actualizaPassword", $variable);
        $registroPassword1 = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
                                
        if ($registroPassword1==true)
        {
            $cadena_sql = $this->sql->cadena_sql("actualizaPasswordORACLE", $variable);
            $registroPassword2 = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "acceso");
            
            $cadena_sql = $this->sql->cadena_sql("actualizaPasswordMySQL", $variable);
            $registroPassword3 = $esteRecursoDBMySQL->ejecutarAcceso($cadena_sql, "acceso");
            
            if(isset($esteRecursoDBMySQL1))
            {
                $cadena_sql = $this->sql->cadena_sql("actualizaPasswordMySQL", $variable);
                $registroPassword4 = $esteRecursoDBMySQL1->ejecutarAcceso($cadena_sql, "acceso");
            }

            if($registroPassword2==true || $registroPassword3==true)
            {    
                $this->funcion->redireccionar ("mostrarMensaje");
                if($usuario==159357645)//159357645 es el usuario que con el que se conecta a lamasu para la recuperación de contraseña
                {    
                    $registro[6]=$variable['usuario_id'];
                    $registro[0]="RECUPERAR";
                    
                }
                else
                {
                    $registro[6]=$usuario;
                    $registro[0]="ACTUALIZAR";
                }    
                $registro[1]=$variable['usuario_id']; //
                $registro[2]="contraseña ";
                $registro[3]="Actualización de contraseña"; //
                $registro[4]=time();
                $registro[5]="Registra datos del cambio de contraseña ";
                $registro[5].="identificacion: ". $variable['usuario_id'];
                
                $this->log_us->log_usuario($registro,$esteRecursoDB);
            }
        }
    }
}
?>

