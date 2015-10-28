<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['consulta']=$_REQUEST['consulta'];
$variable['id_periodo']=$_REQUEST['id_periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarReferenciaBancaria", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valor['opcionPagina']="referenciaBancaria";
    
if (is_array($registro)) {
     
     $this->funcion->redireccionar('regresar',$valor);
}
 else {
     $this->funcion->redireccionar('mostrarMensajeSinRegistro',$valor);
}
?>

