<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $configuracion['id_sesion'] = "123";
    $conexion = "configuracion";
    $this->cadena_sql = $this->sql->cadena_sql("rescatarTemp");
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    $resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");

    $i = 0;
    foreach ($resultado as $key => $value) {
        
        if (strpos($value["campo"], "campo") === 0) {
            
            if (strpos(trim($value["valor"]), "*") === 0) {
                $value["valor"] = substr(trim($value["valor"]), 1);
            }
            
            $datos[$i] = $value["valor"];
            $i++;
        }
    }

    if ($resultado == true) {           
        $this->guardarParametro($datos, $_REQUEST["nombreTabla"], $_REQUEST["tipoAccion"]);        
    }
}
?>