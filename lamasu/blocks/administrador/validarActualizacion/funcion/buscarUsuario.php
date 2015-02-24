<?php
$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['fecha']=date("d/m/Y");

$variable['usuario_id']=$_REQUEST['nombreUsuario'];
isset($_REQUEST['tipo'])?$variable['tipo']=$_REQUEST['tipo']:'';

$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta=count($registro);

if(is_array($registro))
{
    $this->funcion->redireccionar ("iraValidacionDatos");
}    
else
{
    $this->funcion->redireccionar ("mensajeUsuarioInexistente");
}
?>
