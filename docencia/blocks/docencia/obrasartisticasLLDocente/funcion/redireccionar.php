<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	switch($opcion)
	{
            
                case "inserto":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=mensaje";
			$variable.="&mensaje=confirma";
			if($valor!=""){
				$variable.="&docente=".$valor;
			}
			break;
                                      
                case "noInserto":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=mensaje";
			$variable.="&mensaje=error";
			if($valor!=""){
				$variable.="&docente=".$valor;
			}
			break;
            
		case "confirmarNuevo":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=confirmar";
			if($valor!=""){
				$variable.="&id_sesion=".$valor;
			}
			break;

                case "paginaPrincipal":
			$variable="pagina=".$miPaginaActual;
			break;
                    
                case "Actualizo" :
			
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=mensajeActualizar";
			
			break;
		
		case "noActualizo" :
			
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=mensajenoActualizar";
			
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