<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['credencial']=$_REQUEST['credencial'];
$variable['id_periodo']=$_REQUEST['id_periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


if(is_array($registro))
{    
    $this->funcion->redireccionar('mostrarResultado');
}
else
{
    $this->funcion->redireccionar('mostrarMensaje');
}    
?>

