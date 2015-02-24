<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['encabezado']=$_REQUEST['encabezado'];
     
$cadena_sql = $this->sql->cadena_sql("buscarEncabezados", $variable);
$registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//echo $cadena_sql;

           
if(is_array($registroEncabezados))
{
    $this->funcion->redireccionar ("mostrarMensajeEncabezado");
}
elseif($variable['encabezado']=="")
{
    $this->funcion->redireccionar ("mostrarMensajeCampoVacioEnc");
}    
else
{
    $cadena_sql = $this->sql->cadena_sql("insertaEncabezados", $variable);
    $registroEncabezado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registroEncabezado==true)
    {
       $this->funcion->redireccionar ("regresaraEncabezados");
        
    }
    else
    {
        echo "Error";
    }
}
?>

