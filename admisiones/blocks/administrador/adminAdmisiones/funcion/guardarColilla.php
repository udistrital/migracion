<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['nombre']=$_REQUEST['nombre'];
$variable['carreras']=$_REQUEST['codCarrera'];
$variable['contenido']=$_REQUEST['contenido'];

$cadena_sql = $this->sql->cadena_sql("consultarColillasRegistradas", $variable);
$registroColillasRegistradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroColillasRegistradas))
{
    $this->funcion->redireccionar ("mostrarMensajeColillas");
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("insertaColillas", $variable);
    $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
  
    if ($registroEvento==true)
    {
       $this->funcion->redireccionar ("regresaraColillas");
    }
    else
    {
        echo "Ups... error!!!";
    }
}

?>

