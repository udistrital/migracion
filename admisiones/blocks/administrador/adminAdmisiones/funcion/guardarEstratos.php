<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['estrato']=strtoupper($_REQUEST['estrato']);
$variable['numeroest']=$_REQUEST['numeroest'];
$variable['puntosn']=$_REQUEST['puntosn'];
$variable['puntosv']=$_REQUEST['puntosv'];
$variable['puntos']=$_REQUEST['puntos'];
$valor['opcionPagina']="estratos";

if(!is_numeric($_REQUEST['numeroest']) || !is_numeric($_REQUEST['puntosn']) || !is_numeric($_REQUEST['puntosv'])|| !is_numeric($_REQUEST['puntos']))
{
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("consultarEstratosRegistrados", $variable);
    $registroLocalidadesRegistradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if(is_array($registroLocalidadesRegistradas))
    {
        $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
    }    
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaEstratos", $variable);
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

