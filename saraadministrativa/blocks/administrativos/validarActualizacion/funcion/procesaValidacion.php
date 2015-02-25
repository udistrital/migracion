<?php
$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['fecha']=date("Y-m-d");

$variable['usuario_id']=79708124;

$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=count($registro);

$tiempoCambioClave=$registro[0]['apl_tiempo_cambio_clave'];
$fechaActual=strtotime($variable['fecha']);
$fechaUltimaActualizacion=strtotime($registro[0]['cta_fecha_actualizacion']);
$diferencia=($fechaActual-$fechaUltimaActualizacion)/86400;

if($diferencia<10)
{
    $this->funcion->redireccionar ("mostrarAlerta");
}    
elseif($diferencia==1 || $diferencia==0)
{
    //$this->funcion->redireccionar ("iraCambioClave");
}
?>

