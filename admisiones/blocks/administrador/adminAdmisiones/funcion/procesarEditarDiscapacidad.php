<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_discapacidad']=$_REQUEST['id_discapacidad'];
$variable['nombreDiscapacidadNuevo']=strtoupper($_REQUEST['nombreDiscapacidadNuevo']);
$variable['numeroDiscapacidadNuevo']=$_REQUEST['numeroDiscapacidadNuevo'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];
$valor['opcionPagina']="registrarTipDiscapacidad";

if(!is_numeric($_REQUEST['numeroDiscapacidadNuevo']))
{
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("actualizaDiscapacidad", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registro==true) {
         $this->funcion->redireccionar('regresar',$valor);
    }
}
?>

