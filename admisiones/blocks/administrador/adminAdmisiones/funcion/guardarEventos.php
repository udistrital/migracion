<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatallll";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$variable['evento']=$_REQUEST['evento'];

if(isset($_REQUEST['fechaIni']) || isset($_REQUEST['fechaFin']))
{        
    $variable['fechaIni']=$_REQUEST['fechaIni'];
    $variable['fechaFin']=$_REQUEST['fechaFin'];

    $fechaIni=strtotime($_REQUEST['fechaIni']);
    $fechaFin=strtotime($_REQUEST['fechaFin']);
}

$cadena_sql = $this->sql->cadena_sql("consultarEventosRegistrados", $variable);
$registroEventosRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($fechaIni>$fechaFin)
{
     $this->funcion->redireccionar ("mostrarMensajeFecha");
}
else
{    
    if(is_array($registroEventosRegistrados))
    {
        $cadena_sql = $this->sql->cadena_sql("actualizaEventos", $variable);
        $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if ($registroEvento==true)
        {
           $this->funcion->redireccionar ("regresaraAbrirFechas");
        }
        else
        {
            echo "Ups... error!!!";
        }
    }    
    else
    {
        $cadena_sql = $this->sql->cadena_sql("insertaEventos", $variable);
        $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
        
        if ($registroEvento==true)
        {
           $this->funcion->redireccionar ("regresaraAbrirFechas");
        }
        else
        {
            echo "Ups... error!!!";
        }
    }

}
?>

