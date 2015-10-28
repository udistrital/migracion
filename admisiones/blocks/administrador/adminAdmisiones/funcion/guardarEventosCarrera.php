<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


$conexion1 = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['anio']= $_REQUEST['anio'];
$variable['periodo']= $_REQUEST['periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarEventos", "");
$registroEventosCarrera = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

if(isset($_REQUEST['fechaIni']) || isset($_REQUEST['fechaFin']))
{        
    $variable['fechaIni']=$_REQUEST['fechaIni'];
    $variable['fechaFin']=$_REQUEST['fechaFin'];

    $fechaIni=strtotime($_REQUEST['fechaIni']);
    $fechaFin=strtotime($_REQUEST['fechaFin']);
    $variable['carrera']=$_REQUEST['carrera'];

}
if(isset($_REQUEST['evento']))
{
    $variable['evento']=$_REQUEST['evento'];
}
else
{    
    $variable['evento']=11;
}

if($fechaIni>$fechaFin)
{
     $this->funcion->redireccionar ("mostrarMensaje");
}
else
{    
    $cadena_sql = $this->sql->cadena_sql("actualizaEventosCarrera", $variable);
     
     $registroEvento = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "acceso");

     if ($registroEvento==true)
     {
        $this->funcion->redireccionar ("regresaraAbrirFechas");
     }
     else
    {
        echo "Ups... error!!!";
    }
}
?>

