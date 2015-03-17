<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {

	case "mostrarMensaje":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=muestraMensajeCambioPassword";
            $variable.="&mensaje=mensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            
            if(isset($_REQUEST['recuperaPassword']))
            {  
                $variable.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
                $variable.="&recuperaPassword=".$_REQUEST['recuperaPassword'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mensajeUsuarioInexistente":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=usuarioInexistente";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mensajeUsuarioInactivo":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=usuarioInactivo";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "iraValidacionDatos":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=validacionDatos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        
        case "paginaPrincipal":
            $variable = "pagina=gestionPassword";
            $variable.="&opcion=recuperacionContasena";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
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
