<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable["carrera"]=$_REQUEST["carreras"];
$variable["tipoIcfes"]=$_REQUEST["tipoIcfes"];
$variable["admitido"]=$_REQUEST["admision"];
$variable["rangoSuperior"]=$_REQUEST["rangoSuperior"];
$variable["rangoInferior"]=$_REQUEST["rangoInferior"];
$variable['id_periodo']=$_REQUEST['id_periodo'];

$cadena_sql = $this->sql->cadena_sql("actualizaAcaspAdmitidosRangos", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");  

if ($registro==true)
{
   $this->funcion->redireccionar ("regresaraMarcaAdmitidos");
}
else
{
    echo "Ups... error!!!";
}

?>

