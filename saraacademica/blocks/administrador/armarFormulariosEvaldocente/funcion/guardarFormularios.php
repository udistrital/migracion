<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

if($_REQUEST['encabezados']==-1)
{
    $variable['encabezados']=0;
}
else
{
    $variable['encabezados']=$_REQUEST['encabezados'];
}    
if($_REQUEST['preguntas']==-1)
{
    $variable['preguntas']=0;
}
else
{
    $variable['preguntas']=$_REQUEST['preguntas'];
}    
$variable['formatoNumero']=$_REQUEST['formatoNumero'];
$variable['periodo']=$_REQUEST['periodo'];
$variable['formatoId']=$_REQUEST['formatoId'];

$variable['fechaHoy']=date("d/m/Y");
$variable['estado']='A';

$cadena_sql = $this->sql->cadena_sql("buscarFormularios", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta=count($registro);

/*if(is_array($registro))
{    
    $this->funcion->redireccionar ("mostrarMensajeFormulario");
}*/
if(($variable['encabezados']== 0) && ($variable['preguntas']== 0))
{
    $this->funcion->redireccionar ("mostrarMensajeCamposVacios");
}
else
{
    $cadena_sql = $this->sql->cadena_sql("insertaFormulario", $variable);
    $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            
    if ($registroPreguntas==true)
    {
       $this->funcion->redireccionar ("regresaraarmarFormulario");

    }
    else
    {
        echo "Error";
    }
}

?>

