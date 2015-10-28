<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_tipIns']=$_REQUEST['id_tipIns'];
$variable['nombreTipInsNuevo']=strtoupper($_REQUEST['nombreTipInsNuevo']);
$variable['numeroTipInsNuevo']=$_REQUEST['numeroTipInsNuevo'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];

if(!is_numeric($_REQUEST['numeroTipInsNuevo']))
{
    $valor['opcionPagina']="registarTipInscripcion";
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("actualizaTipInscripcion", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registro==true) {
         $this->funcion->redireccionar('regresaraTipIns');
    }
}
?>

