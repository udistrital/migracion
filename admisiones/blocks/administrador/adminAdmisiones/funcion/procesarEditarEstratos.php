<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_estrato']=$_REQUEST['id_estrato'];
$variable['estratoNuevo']=strtoupper($_REQUEST['estratoNuevo']);
$variable['numeroestNuevo']=$_REQUEST['numeroestNuevo'];
$variable['puntosnNuevo']=$_REQUEST['puntosnNuevo'];
$variable['puntosvNuevo']=$_REQUEST['puntosvNuevo'];
$variable['puntosestNuevo']=$_REQUEST['puntosestNuevo'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];
$valor['opcionPagina']="estratos";

if(!is_numeric($_REQUEST['numeroestNuevo']) || !is_numeric($_REQUEST['puntosnNuevo']) || !is_numeric($_REQUEST['puntosvNuevo']) || !is_numeric($_REQUEST['puntosestNuevo']))
{
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("actualizaEstrato", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registro==true) {
         $this->funcion->redireccionar('regresar',$valor);
    }
}
?>

