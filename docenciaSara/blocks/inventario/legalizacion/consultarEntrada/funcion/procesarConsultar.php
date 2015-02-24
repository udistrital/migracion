<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $busqueda = NULL;

    if ($_REQUEST['entrada'])
        $busqueda['entrada'] = $_REQUEST['entrada'];
    if ($_REQUEST['txtFechaEntrada'])
        $busqueda['fechaEntrada'] = $_REQUEST['txtFechaEntrada'];
    if ($_REQUEST['proveedor'])
        $busqueda['proveedor'] = $_REQUEST['proveedor'];
    if ($_REQUEST['claseEntrada'])
        $busqueda['claseEntrada'] = $_REQUEST['claseEntrada'];
    if ($_REQUEST['ordenadorGasto'])
        $busqueda['ordenadorGasto'] = $_REQUEST['ordenadorGasto'];

    $conexion = "inventario";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//Este se considera un error fatal
    if (!$esteRecursoDB) {
        exit;
    }

    $cadena_sql = $this->sql->cadena_sql("consultarEntradas", $busqueda);
    $entradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    $this->funcion->desplegarEntradas($entradas);
}
?>