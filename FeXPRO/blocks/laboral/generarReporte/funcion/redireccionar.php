<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
	//echo "Nicolas torres";
	//var_dump($_REQUEST);exit;
	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	switch($solicitud)
	{
		case "mostrarMensaje":
			$variable="pagina=".$miPaginaActual;
			$variable.="&solicitud=mostrarMensaje";
			//var_dump($datos);exit;
			$variable.="&mensaje=".$datos["mensaje"];
			$variable.="&error=".$datos["error"];
			break;

		case "generarReporte":
			//$variable="pagina=".$miPaginaActual;
			//$variable .="&opcion=nuevo";
			$variable="pagina=index";
			break;

		case "paginaPrincipal":
			$variable="pagina=index";
			break;


	}

	foreach($_REQUEST as $clave=>$valor)
	{
		unset($_REQUEST[$clave]);

	}

	$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");
	$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar($variable);

	$_REQUEST[$enlace]=$variable;
	$_REQUEST["recargar"]=true;

}

?>