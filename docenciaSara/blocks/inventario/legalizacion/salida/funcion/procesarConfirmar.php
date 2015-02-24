<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $configuracion['id_sesion'] = "123";
    $conexion = "configuracion";
    $this->cadena_sql = $this->sql->cadena_sql("rescatarTemp", "123");
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    $resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");

    $datosConfirmar = array("idElemento", "idEntrada", "idSalida", "fechaRegistro", "fechaSalida", "sede", "dependencia", "funcionario", "observacion");

    if ($resultado == null) {
        echo "...Se ha presentado un error...";
        exit;
    }
    
    $i=0;
    foreach ($resultado as $key => $value) {
        $campo = trim($value["campo"]);
        if (in_array($campo, $datosConfirmar)) {
            $datos[$campo] = $value["valor"];
            $i++;
        }
    }

    $this->guardarSalida($datos); 
}
?>