<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['id_medio']=$_REQUEST['id_medio'];
$variable['medioNuevo']=strtoupper($_REQUEST['medioNuevo']);
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];

$cadena_sql = $this->sql->cadena_sql("actualizaMedio", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true) {
     $this->funcion->redireccionar('regresaraMedio');
}
?>

