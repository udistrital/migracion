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
$variable['tipoEvaluacionExt']=$_REQUEST['tipoEvaluacionExt'];

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", $variable);
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valor=explode('-',$registroPeriodo[0][1]);

$variable['anio']=$valor[0];
$variable['per']=$valor[1];

$conexion1 = "autoevaluadoc";
$esteRecursoBDORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoBDORA) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarCargaDocenteHistorico", $variable);
$registroCarrerasDocente = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroCarrerasDocente))
{
    $this->funcion->redireccionar ("iraCargaDocente");
}    
else
{
    $this->funcion->redireccionar ("mostrarMensaje");
}
?>

