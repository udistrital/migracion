<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {

	case "mostrarAlerta":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=alerta";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        
        case "iraCambioClave":
            $variable="pagina=gestionPassword";
            $variable.="&redireccionar=true";
            $variable.="&opcion=nuevo";
            if(isset($_REQUEST["tipo"]))
            {    
                $variable.="&tipo=".$valor[1];
            }
            else
            {
                $variable.="&tipo=1";
            }    
            $variable.="&usuario=" . $valor[0];
            break;
            
         case "iraCondor":
            $variable="pagina=gestionPassword";
            $variable.="&redireccionar=true";
            $variable.="&opcion=nuevo";
            if(isset($_REQUEST["tipo"]))
            {    
                $variable.="&tipo=".$valor[1];
            }
            else
            {
                $variable.="&tipo=1";
            }    
            $variable.="&usuario=" . $valor[0];
            break;    
            
        case "paginaPrincipal":
            $variable = "pagina=indexEvaldocentes";
            break;
    }

    foreach ($_REQUEST as $clave => $valor) {
        unset($_REQUEST[$clave]);
    }

    $enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar($variable);

    $_REQUEST[$enlace] = $variable;
    $_REQUEST["recargar"] = true;
}
?>
