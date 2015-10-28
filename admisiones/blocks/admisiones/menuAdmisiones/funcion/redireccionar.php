<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {
        
	case "confirmarPeriodo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=confirmacionPeriodo";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=fechasEventos";
            $variable.="&evento=".$valor['evento'];
            $variable.="&id_periodo=".$valor['id_periodo'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        
        case "mostrarMensajeExiste":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=mensajeExiste";
            $variable.="&evento=".$valor['evento'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&carreras=".$valor['carreras'];
            $variable.="&id_periodo=".$_REQUEST['id_periodo'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        
        case "iraCarrerasOfrecidas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=carrerasOfrecidas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mensajeTituloTecnologo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&carreras=".$_REQUEST['carreras'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&mensaje=tituloTecnologo";
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        case "iraFormularioInscripcion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=formularioInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            if(isset($_REQUEST['carreras']))
            {    
                $variable.="&carreras=".$_REQUEST['carreras'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "iraVerInscripcion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=verInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&carreras=".$_REQUEST['carreras'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "paginaPrincipal":
            $variable = "pagina=admisiones";
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
