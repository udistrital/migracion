<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['codTipIns']=$_REQUEST['codTipIns'];
$variable['nombreTipIns']=strtoupper($_REQUEST['nombreTipIns']);
$variable['estado']='A';

$cadena_sql = $this->sql->cadena_sql("buscartipInscripcion", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=count($registro);
if(!is_numeric($_REQUEST['codTipIns']))
{
    $valor['opcionPagina']="registarTipInscripcion";
    $this->funcion->redireccionar ("mostrarMensajeFormatoCampo",$valor);
}
else
{
        $cierto=0;
    for ($i=0; $i<=$cuenta-1; $i++) {
        if($registro[$i]['ti_nombre']==$variable['nombreTipIns'] || $registro[$i]['ti_cod']==$variable['codTipIns']){
            $cierto=1;
        }
    }
    if($cierto==1){
        $this->funcion->redireccionar ("mostrarMensajeTipIns");
    }
    else    
    {   
        $cadena_sql = $this->sql->cadena_sql("insertaTipInscripcion", $variable);
        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

        if($registro==true)
        {
            $this->funcion->redireccionar ("regresaraTipIns");
        }    

    }
}
?>

