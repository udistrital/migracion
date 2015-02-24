<?php
$conexion = "evaldocentes";
$esteRecursoDBPG = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDBPG) {

    echo "//Este se considera un error fatal";
    exit;
}
$cadena_sql = $this->sql->cadena_sql("consultarAnioPeriodo", "");
$registroPeriodo = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
$variable['anio']=$registroPeriodo[0]['acasperiev_anio'];
$variable['per']=$registroPeriodo[0]['acasperiev_periodo'];
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['usuario']=$_REQUEST['usuario'];
$variable['carrera']=$_REQUEST['carrera'];
$variable['periodoId']=$_REQUEST['periodoId'];
$variable['nombreCarrera']=$_REQUEST['nombreCarrera'];

$conexion1 = "autoevaluadoc";
$esteRecursoBDORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoBDORA) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarDocentes", $variable);
$registroDocentes = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
 
if(is_array($registroDocentes))
{
    $this->funcion->redireccionar ("iraListaDocentes");
}    
else
{
    $this->funcion->redireccionar ("mostrarMensaje");
}
?>

