<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

if($_REQUEST['facultades'])
{
    $valor['opcionPagina']="inscritosxFacultad";   
    $this->funcion->redireccionar('regresar',$valor);
}
else
{
    echo "Ups... error!!!";
}
