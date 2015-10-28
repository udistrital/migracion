<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['preg_id']=$_REQUEST['preg_id'];
$variable['nuevoNombrePregunta']=$_REQUEST['nuevoNombrePregunta'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];
$valor['opcionPagina']="registrarPreguntas";

$cadena_sql = $this->sql->cadena_sql("actualizaPreguntas", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true) {
     $this->funcion->redireccionar('regresar',$valor);
}
?>

