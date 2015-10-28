<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['carreras']=$_REQUEST['carreras'];
$variable['evento']=$_REQUEST['evento'];

if(isset($_REQUEST['redireccion']))
{
    $this->funcion->redireccionar('iraFormularioInscripcion');
}
else
{    
    if (($variable['carreras']=="383")||($variable['carreras']=="373")||($variable['carreras']=="375")||($variable['carreras']=="377")||($variable['carreras']=="678")||($variable['carreras']=="579")||($variable['carreras']=="372"))
    {
       $this->funcion->redireccionar('mensajeTituloTecnologo');
    }
    else
    {
        $this->funcion->redireccionar('iraFormularioInscripcion');
    }
}
?>

