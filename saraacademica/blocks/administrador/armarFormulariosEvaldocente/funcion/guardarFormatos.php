<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset($variable);
$variable['tipoEvaluacion']=$_REQUEST['tipoEvaluacion'];
$variable['fto_numero']=$_REQUEST['formatoNumero'];
$variable['porcentaje']=$_REQUEST['porcentaje'];
$variable['descripcion']=$_REQUEST['descripcion'];
$variable['periodo']=$_REQUEST['periodo'];
$variable['estado']='A';
$variable['fechaHoy']=date("d/m/Y");

if(!is_numeric($_REQUEST['formatoNumero']) || !is_numeric($_REQUEST['porcentaje']))
{
        $this->funcion->redireccionar ("mostrarMensajeFormatoCampo");
}
else
{    
    if($_REQUEST['porcentaje']<=100 && $_REQUEST['porcentaje']>=0)
    { 
        //echo $_REQUEST['formatoNumero'];
        $cadena_sql = $this->sql->cadena_sql("buscarFormatos", $variable);
        $registroFormatos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //echo $cadena_sql;            


        if($registroFormatos)
        {
            $this->funcion->redireccionar ("mostrarMensaje");
        }    
        else
        {
            $cadena_sql = $this->sql->cadena_sql("insertaFormatos", $variable);
            $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            //echo $cadena_sql."<br>";
            if ($registroEvento==true)
            {
               $this->funcion->redireccionar ("regresaraFormatos");

            }
            else
            {
                echo "Error";
            }
        }
    }
    else
    {
        $this->funcion->redireccionar ("mostrarMensajePorcentaje");
    }
}
?>

