<?php

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->cadena_sql = $this->sql->cadena_sql("insertarSalida", $datos);
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($resultado == true) {
    $mensaje = "Se creó la salida exitosamente el parámetro";
    $error = "exito";
} else {
    $mensaje = "...Oops, se ha presentado un error en el sistema, por favor contacte al administrador del sistema";
    $error = "error";
}

$datos = array("mensaje"=>$mensaje, "error"=>$error);

$this->redireccionar("mostrarMensaje", $datos);




?>
