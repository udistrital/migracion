<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['nombreEncabezado']=htmlentities(stripslashes($_REQUEST['nombreEncabezado']));

$cadena_sql = $this->sql->cadena_sql("buscarEncabezados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
if(is_array($registro))
{
    $cierto=1;
}    

$valor['opcionPagina']="registrarEncabezados"; 
if($variable['nombreEncabezado']=="")
{
    $this->funcion->redireccionar ("mostrarMensajeCampoVacio",$valor);
}  
else
{
    if($cierto==1)
    {
       $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
    }
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaEncabezado", $variable);
        $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if ($registroPreguntas==true)
        {
           $this->funcion->redireccionar ("regresaraFormularioRegistro",$valor);

        }
        else
        {
            echo "Error";
        }
    }
}
    


?>

