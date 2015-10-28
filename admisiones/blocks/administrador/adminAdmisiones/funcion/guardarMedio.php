<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['medio']=strtoupper($_REQUEST['medios']);

$cadena_sql = $this->sql->cadena_sql("buscarMedio", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta=count($registro);

$cierto=0;
for ($i=0; $i<=$cuenta-1; $i++) {
    if($registro[$i]['med_nombre']==$variable['medio']){
        $cierto=1;
    }
}
if($cierto==1){
    $this->funcion->redireccionar ("mostrarMensajeMedio");
}
else    
{   
    
      
    $cadena_sql = $this->sql->cadena_sql("insertaMedio", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if($registro==true)
    {
        $this->funcion->redireccionar ("regresaraMedio");
    }    

}
?>

