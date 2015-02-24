<?php

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
// Buscar proveedores

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}


switch($_REQUEST["funcion"]){    
    
    case "#idProveedor":
        $cadena_sql = $this->sql->cadena_sql("buscarProveedor", $_REQUEST["name_startsWith"]);
        break;
    
    case "#idDependenciaSupervisora":
        $cadena_sql = $this->sql->cadena_sql("buscarDependenciaSupervisora", $_REQUEST["name_startsWith"]);
        break;
    
    case "#idSedes":
        $cadena_sql = $this->sql->cadena_sql("buscarSedes", $_REQUEST["name_startsWith"]);
        break;
    
    case "#iva":
        $cadena_sql = $this->sql->cadena_sql("buscarIva", $_REQUEST["name_startsWith"]);
        break;
    
    
}

$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if ($registro) {

    $respuesta = '[';

    foreach ($registro as $fila) {
        $respuesta.='{';
        $respuesta.='"label":"' . $fila[1] . '",';
        $respuesta.='"value":"' . $fila[0] . '"';
        $respuesta.='},';
    }

    $respuesta = substr($respuesta, 0, strlen($respuesta) - 1);
    $respuesta.=']';

    echo $respuesta;
} else {
    echo '[{"label":"No encontrado","value":"-1"}]';
}