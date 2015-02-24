<?php

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    //Este se considera un error fatal
    exit;
}


$i = 0;
foreach ($_REQUEST as $key => $value) {

    if (strpos($key, "seleccionado") === 0) {
        $eliminados[$i] = $value;
        $i++;
    }
}

$nombre_tabla_bd = $_REQUEST["nombreTabla"];

$cadena_sql = $this->sql->cadena_sql("obtenerConsultaEliminacion", $nombre_tabla_bd);
$cadena_sql = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cadena_sql = $cadena_sql[0]["tbl_script_delete"];


// Validar si selecciono un dato para eliminar, de lo contrario muestra un error
if (isset($eliminados)) {
    
    foreach ($eliminados as $key => $value) {
        $cadena_sql .= $value . ",";
    }

    $cadena_sql = substr($cadena_sql, 0, (strlen($cadena_sql) - 1)) . ")";
    $cadena_sql = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

    if ($cadena_sql == true) {
        $mensaje = "Se ha eliminado el registro del sistema";
        $error = "exito";
    } else {
        $mensaje = "Se presentó un problema durante la eliminación, \n por favor intente de nuevo o contacte al administrador del sistema";
        $error = "error";
    }

}else {
    $mensaje = "Debe seleccionar al menos un dato para eliminar";
    $error = "alerta";
}

$this->funcion->mostrarMensaje($mensaje, $error);

?>
