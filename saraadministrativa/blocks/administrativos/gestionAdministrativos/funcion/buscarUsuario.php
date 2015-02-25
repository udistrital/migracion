<?php
$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['fecha']=date("d/m/Y");

$variable['usuario_id']=$_REQUEST['nombreUsuario'];

$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=count($registro);

if(is_array($registro))
{
    if($registro[0]['cta_estado']==0)
    {
        $this->funcion->redireccionar ("mensajeUsuarioInactivo");
    }
    else
    {
        $this->funcion->redireccionar ("iraValidacionDatos");
    }
}    
else
{
    $this->funcion->redireccionar ("mensajeUsuarioInexistente");
}

?>

