<?php

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}

if ($accion == "actualizarParametro") {
    $cadena_sql = $this->sql->cadena_sql("obtenerScriptActualizarDatos", $nombre_tabla);
    $operacion = "áctualizó";
}
if ($accion == "crearParametro") {
    $cadena_sql = $this->sql->cadena_sql("obtenerScriptInsertarDatos", $nombre_tabla);
    $operacion = "almacenó";
}

$cadena_sql = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cadena_sql = $cadena_sql[0][0];

foreach ($datos as $key => $value) {
    $valor = "{valor" . $key . "}";
    $cadena_sql = str_replace($valor, $value, $cadena_sql);
}

$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

if ($resultado == true) {
    $mensaje = "Se " . $operacion . " exitosamente el parámetro";
    $error = "exito";
} else {
    $mensaje = "Se presentó un problema en la inserción del parámetro, \n por favor contacte al administrador del sistema";
    $error = "error";
}

$this->mostrarMensaje($mensaje, $error);
?>
