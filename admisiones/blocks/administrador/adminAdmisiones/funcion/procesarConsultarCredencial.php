<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['consultaCredencial']=$_REQUEST['consultaCredencial'];
$variable['id_periodo']=$_REQUEST['id_periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if (is_array($registro)) {
     $valor['opcionPagina']="formEditarInscripcion";   
     $this->funcion->redireccionar('regresar',$valor);
}
 else {
     $valor['opcionPagina']="editarInscripcion";
     $this->funcion->redireccionar('mostrarMensajeSinRegistro',$valor);
}
?>

