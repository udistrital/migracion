<?php
//var_dump($_REQUEST);
//echo $_REQUEST['elm1']."<br>";
//echo $_REQUEST['tipoEvaluacion']."<br>";

$variable=$_REQUEST['tipoEvaluacion'];
 
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//echo "mmm".$cadena_sql;
unset($variable);
$variable['tipoEvaluacion']=$_REQUEST['tipoEvaluacion'];
$variable['elm1']=$_REQUEST['elm1']; 
if(!isset($registro)){
    $cadena_sql = $this->sql->cadena_sql("insertarRegistro", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    //echo $cadena_sql;  
}
else{
    $cadena_sql = $this->sql->cadena_sql("actualizarRegistro", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
}
//echo $cadena_sql;    

if ($registro==true) {
    $this->redireccionar ("mostrarMensaje");
}

//$this->funcion->mostrarResultados($noEnviados, $enviados);
?>

