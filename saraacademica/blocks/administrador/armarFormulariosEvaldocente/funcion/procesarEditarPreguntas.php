<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['preguntaId']=$_REQUEST['preguntaId'];
$variable['tipPregunta']=$_REQUEST['tipPregunta'];
$variable['pregunta']=$_REQUEST['pregunta'];
$variable['valorPregunta']=$_REQUEST['valorPregunta'];
$variable['estado']=$_REQUEST['estado'];
$variable['fechaHoy']=date("d/m/Y");

$cadena_sql = $this->sql->cadena_sql("actualizaPreguntas", $variable);
$registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
//echo $cadena_sql."<br>";
if ($registroEvento==true)
{
   $this->funcion->redireccionar ("regresaraPreguntas");

}
else
{
    echo "Error";
}    

?>

