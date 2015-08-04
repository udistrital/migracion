<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
	
	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	switch($opcion)
	{

		
		case "indexUsuario":
			$variable="pagina=indexUsuario";
			$variable.="&opcion=mostrar";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$valor["id_usuario"];
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
			break;
                    
                case "indexDocente":
			$variable="pagina=estadoCuenta";
			$variable.="&opcion=formReporte";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$valor["CLA_CODIGO"];
			$variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
			break;    
			// Buscar proveedores// Buscar proveedores// Bu// Buscar proveedores// Buscar proveedoresscar proveedores
                case "admin_carga_docente":
			$variable="pagina=cargaDocente";
			$variable.="&opcion=nuevo";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$_REQUEST["usuario"];
			$variable.="&tiempo=".time();
			break;

			case "admin_carga_docente":
				$variable="pagina=cargaDocente";
				$variable.="&opcion=nuevo";
				$variable.="&redireccionar=true";
				$variable.="&mensaje=bienvenida";
				$variable.="&usuario=".$_REQUEST["usuario"];
				$variable.="&tiempo=".time();
				break;
				
		case "validarEstudiantesActivos":
                 
				$nuevoArray = array();
				foreach ($_REQUEST as $idx => $val){
					if($idx!="datos"&&$idx!="pagina"&&$idx!="opcion")
						$nuevoArray[$idx] = $_REQUEST[$idx] ;
				}
					
				$variable = http_build_query($nuevoArray);
				
				$variable.="&pagina=validarEstudiantes";
				$variable.="&redireccionar=true";
				$variable.="&tiempo=".time();
			
				break;
				
		case "administrarDeudas":
				$nuevoArray = array();
				foreach ($_REQUEST as $idx => $val){
					if($idx!="datos"&&$idx!="pagina"&&$idx!="opcion")
						$nuevoArray[$idx] = $_REQUEST[$idx] ;
				}
			
				$variable = http_build_query($nuevoArray);
				
				$variable.="&pagina=administrarDeudas";
				$variable.="&redireccionar=true";
				$variable.="&tiempo=".time();
				
				break;
				
		case "relacionarLaboratoriosUsuario":
				$nuevoArray = array();
				foreach ($_REQUEST as $idx => $val){
					if($idx!="datos"&&$idx!="pagina"&&$idx!="opcion")
						$nuevoArray[$idx] = $_REQUEST[$idx] ;
				}
					
				$variable = http_build_query($nuevoArray);
				
				$variable.="&pagina=relacionarLaboratoriosUsuario";
				$variable.="&redireccionar=true";
				$variable.="&tiempo=".time();
			break;
			
			
			case "icetex":
				$nuevoArray = array();
				foreach ($_REQUEST as $idx => $val){
					if($idx!="datos"&&$idx!="pagina"&&$idx!="opcion")
						$nuevoArray[$idx] = $_REQUEST[$idx] ;
				}
					
				$variable = http_build_query($nuevoArray);
			
				$variable.="&pagina=icetex";
				$variable.="&redireccionar=true";
				$variable.="&tiempo=".time();
				
				break;
				
				case "mantis":
					$nuevoArray = array();
					foreach ($_REQUEST as $idx => $val){
						if($idx!="datos"&&$idx!="pagina"&&$idx!="opcion")
							$nuevoArray[$idx] = $_REQUEST[$idx] ;
					}
						
					$variable = http_build_query($nuevoArray);
						
					$variable.="&pagina=mantisSoporte";
					$variable.="&redireccionar=true";
					$variable.="&tiempo=".time();
				
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
	$_REQUEST["recargar"]=false;

}

?>