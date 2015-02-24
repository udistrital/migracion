<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $this->sql = new SqlMonitoreo();
    $actual = time();

    $conexion = "voto";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $cadena_sql = trim($this->sql->cadena_sql("consultaVotantes", ''));
    $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    return $resultado;
}
?>