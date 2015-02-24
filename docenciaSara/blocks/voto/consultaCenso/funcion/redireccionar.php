<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
        
	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");

        switch($opcion)
	{

		case "mostrarActualizacion":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=mostrarActualizacion";
			$variable.="&datos=".$datos;
			if($valor!=""){
				$variable.="&id_sesion=".$valor;
			}
                        break;
                        
		case "confirmarNuevo":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=confirmar";
			if($valor!=""){
				$variable.="&id_sesion=".$valor;
			}   
			break;
                        
		case "mostrarMensaje":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=mostrarMensaje";
			$variable.="&mensaje=".$datos["mensaje"];
			$variable.="&error=".$datos["error"];
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