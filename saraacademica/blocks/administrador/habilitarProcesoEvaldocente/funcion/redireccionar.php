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
        case "regresaraNuevo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=nuevo";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraAbrirFechas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=eventos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=error";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "inserto":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=mensaje";
			$variable.="&mensaje=confirma";
			if($valor!=""){
				$variable.="&proceso=".$valor;
			}
			break;

        case "paginaPrincipal":
            $variable = "pagina=indexEvaldocentes";
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
