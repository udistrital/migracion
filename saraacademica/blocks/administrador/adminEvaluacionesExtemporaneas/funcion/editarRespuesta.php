<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset ($variable);
$variable['usuario']=$_REQUEST['usuario'];
$usuario=$_REQUEST['usuario'];
$variable['respuestaId']=$_REQUEST['respuestaId'];
$respuestaId=$_REQUEST['respuestaId'];
$variable['formatoNumeto']=$_REQUEST['formatoNumeto'];
$variable['preguntaNumeto']=$_REQUEST['preguntaNumeto'];
$pregunta=$_REQUEST['preguntaNumeto'];
$variable['respuestaNueva']=$_REQUEST['respuestaNueva'];
$respuestaNueva=$_REQUEST['respuestaNueva'];
$variable['respuesta']=$_REQUEST['respuesta'];
$respuesta=$_REQUEST['respuesta'];
$variable['estado']=$_REQUEST['estado'];
$variable['formularioId']=$_REQUEST['formularioId'];
$variable['estadoNuevo']=$_REQUEST['estadoNuevo'];
$justificacion=$_REQUEST['justificacion'];
$variable['fechaHoy']=date("d/m/Y");

echo $_REQUEST['justificacion']."<br>";

$variable['log']="Evaluación modificada por el usuario ".$usuario.", Respuesta Id No. ".$respuestaId.", pregunta No. ".$pregunta.", respuesta anterior ".$respuesta.", respuesta nueva ".$respuestaNueva.", ".$justificacion.".";
        
if(!is_numeric($_REQUEST['respuestaNueva']))
{
        $this->funcion->redireccionar ("mostrarMensajeFormatoCampo");
}
else
{    
    $cadena_sql = $this->sql->cadena_sql("actualizarEvaluacion", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
   
    $cadena_sql = $this->sql->cadena_sql("insertaLog", $variable);
    $registroLog = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
   
    if ($registro==true && $registroLog==true)
    {
       $this->funcion->redireccionar ("regresaraFormularios");

    }
    else
    {
        echo "Error";
    }
}
?>

