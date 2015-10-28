<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['codColilla']=$_REQUEST['codColilla'];
$variable['nombreNuevo']=$_REQUEST['nombreNuevo'];
$variable['carrerasNuevas']=$_REQUEST['carrerasNuevas'];
$variable['contenidoNuevo']=$_REQUEST['contenidoNuevo'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];

$cadena_sql = $this->sql->cadena_sql("actualizaAcaspAdmitidos", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true) {
     $this->funcion->redireccionar('regresaraColillas');
}
?>

