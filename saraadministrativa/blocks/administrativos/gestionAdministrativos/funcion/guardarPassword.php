<?php
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

if($_REQUEST['tipo']==51 || $_REQUEST['tipo']==52)
{    
    $conexion3 = "conexionMySQLest"; //Conexión MySQL Estudiantes
}
else
{
    $conexion3 = "conexionMySQLfun"; //Conexión MySQL Funcionarios
}    
$esteRecursoDBMySQL = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion3);
if (!$esteRecursoDBMySQL) {

    echo "///Este se considera un error fatal";
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

if(!is_array($registro))
{
    echo "El usuario no exite, intente de nuevo...<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
}    
else
{
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
        echo "La contraseña actual no es correcta .<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
    }
    elseif($cierto==2 || $cierto==3)
    {
        echo "Contraseña nueva inválida .<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
    }
    elseif($cierto==4)
    {
        echo "La contraseña nueva no puede ser igual a la contraseña actual, intentelo de nuevo...<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
    }    
    elseif(strlen ($_REQUEST["nuevaClave"])>16 || strlen ($_REQUEST["nuevaClave"])<8)
    {
        echo "La contraseña debe contener mínimo 8 y máximo 16 caracteres alfanuméricos.<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
    }
    elseif(!$resultNumeros || !$resultCaracteres){ 
        echo "La contraseña debe contener como mínimo dos caracteres numéricos o como mínimo dos caracteres alfabeticos.<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
    }
    elseif(!$resultPrimerCaracter)
    {
        echo "La contraseña no debe comenzar por un caracter numérico.<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
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
            
            if($registroPassword2==true || $registroPassword3==true)
            {    
                $this->funcion->redireccionar ("mostrarMensaje");
        
            }
        }
    }
}
?>

