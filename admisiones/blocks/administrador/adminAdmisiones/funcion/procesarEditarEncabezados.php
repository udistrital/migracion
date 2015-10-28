<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['enc_id']=$_REQUEST['enc_id'];
$variable['nuevoNombreEncabezado']=$_REQUEST['nuevoNombreEncabezado'];
$valor['opcionPagina']="registrarEncabezados";

$cadena_sql = $this->sql->cadena_sql("actualizaEncabezado", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true) {
     $this->funcion->redireccionar('regresar',$valor);
}
?>

