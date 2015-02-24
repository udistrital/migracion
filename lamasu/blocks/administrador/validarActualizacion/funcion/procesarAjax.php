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
    
    case "#entrada":
        $cadena_sql = $this->sql->cadena_sql("buscarEntrada", $_REQUEST["name_startsWith"]);
        break;
    
    case "#elemento":
        $cadena_sql = $this->sql->cadena_sql("buscarElemento", $_REQUEST["name_startsWith"]);
        break;    
    
    case "#sede":
        $cadena_sql = $this->sql->cadena_sql("buscarSedes", $_REQUEST["name_startsWith"]);
        break;    
    
    case "#dependencia":
        $cadena_sql = $this->sql->cadena_sql("buscarDependencias", $_REQUEST["name_startsWith"]);
        break;    
    
    case "#funcionario":
        $cadena_sql = $this->sql->cadena_sql("buscarFuncionario", $_REQUEST["name_startsWith"]);
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