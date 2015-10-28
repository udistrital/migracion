<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['id_aspw']=$_REQUEST['id_aspw'];
$variable['nuevoSnp']=$_REQUEST['nuevoSnp'];

if($_REQUEST['tipoInscripcion']=='nuevos')
{
    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspw", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
}
else
{
    $cadena_sql = $this->sql->cadena_sql("actualizaTransferencia", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
}    
if ($registro==true) {
     $this->funcion->redireccionar('regresaraSnpAspirantes');
}
?>

