<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['numeroDiscapacidad']=$_REQUEST['numeroDiscapacidad'];
$variable['nombreDiscapacidad']=strtoupper($_REQUEST['nombreDiscapacidad']);
$variable['estado']='A';
$valor['opcionPagina']="registrarTipDiscapacidad";

$cadena_sql = $this->sql->cadena_sql("buscarDiscapacidad", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=count($registro);
if(!is_numeric($_REQUEST['numeroDiscapacidad']))
{
    $valor['opcionPagina']="registrarTipDiscapacidad";
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}
else
{
        $cierto=0;
    for ($i=0; $i<=$cuenta-1; $i++) {
        if($registro[$i]['dis_nombre']==$variable['nombreDiscapacidad'] || $registro[$i]['dis_cod']==$variable['numeroDiscapacidad']){
            $cierto=1;
        }
    }
    if($cierto==1){
        $this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
    }
    else    
    {   
        $cadena_sql = $this->sql->cadena_sql("insertaDiscapacidad", $variable);
        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if($registro==true)
        {
            $valor['opcionPagina']="registrarTipDiscapacidad";    
            $this->funcion->redireccionar ("regresar",$valor);
        }    

    }
}
?>

