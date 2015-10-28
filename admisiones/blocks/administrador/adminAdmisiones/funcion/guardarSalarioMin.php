<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB)
{

    echo "//Este se considera un error fatallll";
    exit;
}

$variable['anio']=$_REQUEST['anio'];
$variable['valor']=$_REQUEST['valor'];
$variable['porcentaje']=$_REQUEST['porcentaje'];

if(!is_numeric($_REQUEST['valor']) || !is_numeric($_REQUEST['porcentaje']) || $_REQUEST['valor']<0)
{
        $valor['opcionPagina']="salmin";
        $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}
else
{
    if($_REQUEST['porcentaje']>100 || $_REQUEST['porcentaje']<0)
    {
        $this->funcion->redireccionar ("mostrarMensajePorcentaje");
    }
    else
    {
        $cadena_sql = $this->sql->cadena_sql("consultarSalMinRegistrados", $variable);
        $registroSalMinRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(is_array($registroSalMinRegistrados))
        {
            $this->funcion->redireccionar ("mostrarMensajeSalarioMin");
        }
        else
        {
            $cadena_sql = $this->sql->cadena_sql("guardarSalMin", $variable);
            $registroSalMin = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
            
            if($registroSalMin==TRUE)
            {
                $this->funcion->redireccionar ("regresaraSalMin");
            }    
        }    
    }
}
?>

