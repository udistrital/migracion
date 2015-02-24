<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
unset($variable);
$variable['formatos']=$_REQUEST['consultarFormatos'];
$variable['vinculacionDocentes']=$_REQUEST['vinculacionDocentes'];
$variable['periodo']=$_REQUEST['periodo'];

$cierto=0;
if(isset($_REQUEST['consultarFormatos']) || isset($_REQUEST['vinculacionDocentes']))
{    
    if($_REQUEST['consultarFormatos']>=0 && $_REQUEST['vinculacionDocentes']>=0)
    {    
        //echo $_REQUEST['formatoNumero'];
        $cadena_sql = $this->sql->cadena_sql("buscarAsociacion", $variable);
        $registroFormatos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        if($registroFormatos)
        {
            $this->funcion->redireccionar ("mostrarMensajeAsociacion");
        }    
        else
        {
            $cadena_sql = $this->sql->cadena_sql("insertaAsociacion", $variable);
            $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            //echo $cadena_sql."<br>";
            if ($registroEvento==true)
            {
               $this->funcion->redireccionar ("regresaraAsociarFormatos");

            }
            else
            {
                echo "Error";
            }
        }
    }
    else
    {
        $this->funcion->redireccionar ("mensajeAsociacionSeleccionar");
    }    
}
 else
{
    $this->funcion->redireccionar ("mensajeAsociacionSeleccionar");
}
 
?>

