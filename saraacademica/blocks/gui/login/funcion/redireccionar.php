<?

$miSesion = Sesion::singleton ();


if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	switch($opcion)
	{
                case "indexEvaldocentes":
			$variable="pagina=indexEvaldocentes";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
                        $variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
			break;
                
                case "indexEvaluacion":
                        $variable="pagina=indexEvaluacion";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
                        $variable.="&resultado=".$valor["resultado"];
			break;
                        
                case "observaciones":
                        $variable="pagina=evaluacionDocente";
			$variable.="&redireccionar=true";
			$variable.="&opcion=consultaObservacionesCoordinador";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
                        $variable.="&resultado=".$valor["resultado"];
			break;        
                        
                case "listaClase":
                        $variable="pagina=listaClase";
			$variable.="&redireccionar=true";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
                        $variable.="&resultado=".$valor["resultado"];
			break;
                //Para que desde el perfil Docente, se vean los resultados parciales de la evaluación docente 
                //y las observaciones realizadas por los estudiantes.        
                case "resultadosEvaluacion":
                        $variable="pagina=evaluacionDocente";
			$variable.="&redireccionar=true";
                        $variable.="&opcion=resultadosEvaluacion"; 
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
                        break;        
                
                case "paginaPrincipal":
			$variable="pagina=index";
			if(isset($valor) && $valor!='')
			{
				$variable.="&error=".$valor;
			}
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