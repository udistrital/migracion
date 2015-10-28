<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['localidad']=strtoupper($_REQUEST['localidad']);
$variable['numero']=$_REQUEST['numero'];
$variable['puntosn']=$_REQUEST['puntosn'];
$variable['puntosv']=$_REQUEST['puntosv'];
$valor['opcionPagina']="localidades";

if(!is_numeric($_REQUEST['numero']) || !is_numeric($_REQUEST['puntosn']) || !is_numeric($_REQUEST['puntosv']) || $_REQUEST['localidad']=='')
{
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("consultarLocalidadesRegistradas", $variable);
    $registroLocalidadesRegistradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if(is_array($registroLocalidadesRegistradas))
    {
        $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
    }    
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaLocalidades", $variable);
        $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
        
        if ($registroEvento==true)
        {
           $this->funcion->redireccionar ("regresar",$valor);
        }
        else
        {
            echo "Ups... error!!!";
        }
    }
}
?>

