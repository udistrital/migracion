<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['nombreDocumento']=$_REQUEST['nombreDocumento'];
$variable['nombreCorto']=$_REQUEST['nombreCorto'];
$variable['prefijo']=$_REQUEST['prefijo'];
$variable['carreras']=$_REQUEST['codCarreras'];
$variable['estado']="A";
$valor['opcionPagina']="registrarDocumentacion";


$cadena_sql = $this->sql->cadena_sql("consultaDocumentacion", $variable);
$registroDocumentacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroDocumentacion))
{
    $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("insertaDocumentacion", $variable);
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

?>

