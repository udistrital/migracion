<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['insNombre']=$_REQUEST['insNombre'];
$variable['instructivo']=$_REQUEST['instructivo'];

$cadena_sql = $this->sql->cadena_sql("actualizaInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true)
{
   $this->funcion->redireccionar ("regresaraInstructivo");
}
else
{
    echo "Ups... error!!!";
}


?>

