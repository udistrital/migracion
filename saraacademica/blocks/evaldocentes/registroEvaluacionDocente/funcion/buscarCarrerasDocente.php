<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset($variable);
$variable['documentoId']=$_REQUEST['documentoId'];
$variable['usuario']=$_REQUEST['usuario'];
$variable['perAcad']=$_REQUEST['perAcad'];

$conexion1 = "autoevaluadoc";
$esteRecursoBDORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoBDORA) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarCarrerasDocenteHistorico", $variable);
$registroCarrerasDocente = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
//echo $cadena_sql;

if(!is_array($registroCarrerasDocente))
{
    $this->funcion->redireccionar ("iraCarrerasDocente");
}    
else
{
    $this->funcion->redireccionar ("mostrarMensaje");
}
?>

