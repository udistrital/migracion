<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB)
{

    echo "//Este se considera un error fatallll";
    exit;
}

$variable['id_salmin']=$_REQUEST['id_salmin'];
$variable['anio']=$_REQUEST['anio'];
$variable['valor']=$_REQUEST['valorsalmin'];
$variable['porcentaje']=$_REQUEST['porcentajesalmin'];
$variable['estado']=$_REQUEST['estadoNuevo'];

if(!is_numeric($_REQUEST['valor']) || !is_numeric($_REQUEST['porcentaje']) || $_REQUEST['valor']<0)
{
        $this->funcion->redireccionar ("mostrarMensajeFormatoCampo");
}
else
{
    if($_REQUEST['porcentaje']>100 || $_REQUEST['porcentaje']<0)
    {
        $this->funcion->redireccionar ("mostrarMensajePorcentaje");
    }
    else
    {   
        $cadena_sql = $this->sql->cadena_sql("cambiarSalMin", $variable);
        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

        if($registro==TRUE)
        {
            $this->funcion->redireccionar ("regresaraSalMin");
        }
    }
}
?>

