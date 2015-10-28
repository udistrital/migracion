<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['nombrePregunta']=$_REQUEST['nombrePregunta'];
$variable['preguntaTipo']=$_REQUEST['preguntaTipo'];
$variable['evento']=$_REQUEST['evento'];
$variable['parametro1']=$_REQUEST['parametro1'];
$variable['parametro2']=$_REQUEST['parametro2'];
$variable['parametro3']=$_REQUEST['parametro3'];
$variable['parametro4']=$_REQUEST['parametro4'];
$variable['estado']='A';
$variable['fechaHoy']=date("d/m/Y");

$variable['preg']=htmlentities(stripslashes($_REQUEST['nombrePregunta']));;

$cadena_sql = $this->sql->cadena_sql("buscarPreguntas", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cierto=0;
if(is_array($registro))
{
    $cierto=1;
}    

$valor['opcionPagina']="registrarPreguntas";
if($variable['nombrePregunta']=="")
{
    $this->funcion->redireccionar ("mostrarMensajeCampoVacio",$valor);
}  
else
{
    if($cierto==1)
    {
       $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
    }
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaPreguntas", $variable);
        $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if ($registroPreguntas==true)
        {
           $this->funcion->redireccionar ("regresaraFormularioRegistro",$valor);

        }
        else
        {
            echo "Error";
        }
    }
}
    


?>

