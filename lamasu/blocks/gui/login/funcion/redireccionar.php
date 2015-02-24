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
                case "cambioPassword":
			$variable="pagina=gestionPassword";
			$variable.="&redireccionar=true";
			$variable.="&opcion=nuevo";
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
                     
			//$variable.="&sesionID=".$valor["sesionID"];
			break;
                case "recuperaPassword":
                    	$variable="pagina=gestionPassword";
			$variable.="&redireccionar=true";
			$variable.="&opcion=nuevo";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        $variable.="&recuperaPassword=";
                        $variable.="&mail=".$_REQUEST['mail'];
                        $variable.="&documentoActual=".$_REQUEST['documentoActual'];
                        $variable.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
                        $variable.="&nombre=".$_REQUEST['nombre'];
                        $variable.="&fechaHoy=".$_REQUEST['fechaHoy'];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
                        $variable.="&tiempo=".time();
			//$variable.="&sesionID=".$valor["sesionID"];
			break;        
                case "gestionPassword":
			$variable="pagina=gestionPassword";
			$variable.="&redireccionar=true";
			$variable.="&opcion=recuperacionContasena";
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
                        
			//$variable.="&sesionID=".$valor["sesionID"];
                        break;
                        
                case "validaActualizacion":
                        $variable="pagina=validaActualizacion";
			$variable.="&redireccionar=true";
                        //$variable.="&action=validarActualizacion";
                        //$variable.="&opcion=procesarValidacion";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=2";
                        }    
                        $variable.="&tiempo=".time();
			//$variable.="&sesionID=".$valor["sesionID"];
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