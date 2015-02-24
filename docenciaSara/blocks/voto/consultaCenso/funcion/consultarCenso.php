<?php

$conexion = "votocenso";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->cadena_sql = $this->sql->cadena_sql("consultarCenso", $_REQUEST["idUsuario"]);
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if ($resultado == true) {
    $resultado = urlencode(serialize($resultado));
    $resultado = $this->miConfigurador->fabricaConexiones->crypto->codificar($resultado);
    $this->redireccionar("mostrarActualizacion",$resultado);
} else {
    
echo "entraaaaaaaaaaaa";exit;
    
    $mensaje = "...Oops, se ha presentado un error, por favor contacte al administrador del sistema...";
    $error = "error";
    $datos = array("mensaje"=>$mensaje, "error"=>$error);
    $this->redireccionar("mostrarMensaje", $datos);
}

?>
