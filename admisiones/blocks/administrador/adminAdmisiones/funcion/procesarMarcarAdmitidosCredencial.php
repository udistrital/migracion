<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable["credencial"]=$_REQUEST["credencial"];
$variable["admitido"]=$_REQUEST["admision"];
$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable["consultaCredencial"]=$_REQUEST["credencial"];

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registroAcasp = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroAcasp)){
    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspAdmitidos", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");  
    
    if ($registro==true)
    {
       $this->funcion->redireccionar ("regresaraMarcaAdmitidosCredencial");
    }
    else
    {
        echo "Ups... error!!!";
    }
}else{
    $mensaje="El número de credencail digitado no existe...";
    $html="<script>alert('".$mensaje."');</script>";
    echo $html;
    echo "<script>location.replace('')</script>";
}
?>

