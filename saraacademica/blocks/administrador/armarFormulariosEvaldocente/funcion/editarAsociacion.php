<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset ($variable);
$variable['asociacion']=$_REQUEST['asociacion'];

if($_REQUEST['estado']=='A')
{
    $variable['estadoInicial']=$_REQUEST['estado'];
    $variable['estadoFinal']='I';
}
else
{
    $variable['estadoInicial']=$_REQUEST['estado'];
    $variable['estadoFinal']='A';
}
        
$cadena_sql = $this->sql->cadena_sql("cambiarEstadoAsociacion", $variable);
$registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registroEvento==true)
{
   $this->funcion->redireccionar ("regresaraAsociarFormatos");

}
else
{
    echo "Error";
}    

?>

