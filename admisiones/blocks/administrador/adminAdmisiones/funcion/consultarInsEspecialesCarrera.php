<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['codcra']=$_REQUEST['carreras'];
$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['tipoInscripcion']=$_REQUEST['tipoInscripcion'];
$variable['noEspeciales']=1;

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if (is_array($registro)) {
     $valor['opcionPagina']="formInsEspCarrera";
     $valor['codcra']=$variable['codcra'];
     $this->funcion->redireccionar('regresar',$valor);
}
 else {
     $mensaje="No existen registros con los datos consultados.";
    $html="<script>alert('".$mensaje."');</script>";
    echo $html;
    echo "<script>location.replace('')</script>";
}
?>

