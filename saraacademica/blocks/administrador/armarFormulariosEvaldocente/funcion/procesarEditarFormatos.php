<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset ($variable);
$variable['tipEvaluacion']=$_REQUEST['tipEvaluacion'];
$variable['fto_numero']=$_REQUEST['formatoNumeroActual'];
$variable['fto_num']=$_REQUEST['formatoNum'];
$variable['porcentaje']=$_REQUEST['porcentaje'];
$variable['descripcion']=$_REQUEST['descripcion'];
$variable['periodo']=$_REQUEST['periodo'];
$variable['estado']=$_REQUEST['estado'];
$variable['fechaHoy']=date("d/m/Y");
        
$cadena_sql = $this->sql->cadena_sql("buscarFormatos", $variable);
$registroFormatos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

      
if($registroFormatos)
{
    $cadena_sql = $this->sql->cadena_sql("actualizaFormatos", $variable);
    $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registroEvento==true)
    {
       $this->funcion->redireccionar ("regresaraFormatos");
        
    }
    else
    {
        echo "Error";
    }    
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("insertaFormatos", $variable);
    $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registroEvento==true)
    {
       $this->funcion->redireccionar ("regresaraFormatos");
        
    }
    else
    {
        echo "Error";
    }
}
?>

