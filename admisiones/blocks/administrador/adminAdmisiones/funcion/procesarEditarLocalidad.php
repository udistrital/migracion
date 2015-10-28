<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_localidad']=$_REQUEST['id_localidad'];
$variable['localidadNueva']=strtoupper($_REQUEST['localidadNueva']);
$variable['numeroNuevo']=$_REQUEST['numeroNuevo'];
$variable['puntosnNuevo']=$_REQUEST['puntosnNuevo'];
$variable['puntosvNuevo']=$_REQUEST['puntosvNuevo'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];
$valor['opcionPagina']="localidades";

if(!is_numeric($_REQUEST['numero']) || !is_numeric($_REQUEST['puntosn']) || !is_numeric($_REQUEST['puntosv']))
{
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("actualizaLocalidad", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registro==true) {
         $this->funcion->redireccionar('regresar',$valor);
    }
}
?>

