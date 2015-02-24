<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['periodo']=$_REQUEST['periodo'];
$variable['valorPregunta']=$_REQUEST['valorPregunta'];
$variable['tipoPregunta']=$_REQUEST['tipoPregunta'];
$variable['pregunta']=$_REQUEST['pregunta'];
$variable['estado']='A';
$variable['fechaHoy']=date("d/m/Y");

$variable['preg']=htmlentities(stripslashes($_REQUEST['pregunta']));;

$cadena_sql = $this->sql->cadena_sql("buscarPreguntas", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta=count($registro);

$cierto=0;
for($i=0; $i<=$cuenta-1; $i++)
{
    $s1=$_REQUEST['pregunta'];
    $s2=$registro[$i][0];
    if(strcmp($s1,$s2)==0)
    {
        $cierto=1;
    }        
}

if($variable['valorPregunta']<0)
{
    $this->funcion->redireccionar ("mostrarMensajeValorPregunta");
}
elseif(($variable['valorPregunta']<=0) && ($variable['tipoPregunta']==1))
{
    $this->funcion->redireccionar ("mostrarMensajeValorPreguntaRadio");
}
elseif (!is_numeric($variable['valorPregunta']))
{
    $this->funcion->redireccionar ("mostrarMensajeTipoValorPregunta");
}
elseif($variable['pregunta']=="")
{
    $this->funcion->redireccionar ("mostrarMensajeCampoVacioPreg");
}  
else
{
    if($cierto==1)
    {
        $this->funcion->redireccionar ("mostrarMensajePreguntas");
    }
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaPreguntas", $variable);
        $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

        if ($registroPreguntas==true)
        {
           $this->funcion->redireccionar ("regresaraPreguntas");

        }
        else
        {
            echo "Error";
        }
    }
}
    


?>

