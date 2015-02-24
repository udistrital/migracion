<?php
$conexion = "oracle";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $_REQUEST['idUsuario']);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if ($registro){
	$respuesta=json_encode($registro);
	echo $respuesta;
}else{
	echo '{"error"}';
}
?>