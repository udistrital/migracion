<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['formatoNumero']=$_REQUEST['formatoNumero'];
$variable['periodo']=$_REQUEST['periodo'];
$variable['formatoId']=$_REQUEST['formatoId'];
$variable['formularioId']=$_REQUEST['formularioId'];

    $cadena_sql = $this->sql->cadena_sql("editarFormulario", $variable);
    $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    //echo $cadena_sql;        
    if ($registroPreguntas==true)
    {
       $this->funcion->redireccionar ("regresaraarmarFormulario");

    }
    else
    {
        echo "Error";
    }
?>

