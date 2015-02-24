<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id']=$_REQUEST['id'];
$variable['encabezado1']=$_REQUEST['encabezado1'];
        
$cadena_sql = $this->sql->cadena_sql("actualizaEncabezados", $variable);
$registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
//echo $cadena_sql."<br>";
if ($registroEvento==true)
{
   $this->funcion->redireccionar ("regresaraEncabezados");

}
else
{
    echo "Error";
}    

?>

