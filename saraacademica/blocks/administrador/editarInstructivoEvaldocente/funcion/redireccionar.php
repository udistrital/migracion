<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {

//		case "confirmarNuevo":
//			$variable="pagina=".$miPaginaActual;
//			$variable.="&opcion=confirmar";
//			if($valor!=""){
//				$variable.="&id_sesion=".$valor;
//			}
//			break;
//
//		case "confirmacionEditar":
//			$variable="pagina=conductorAdministrador";
//			$variable.="&opcion=confirmarEditar";
//			if($valor!=""){
//				$variable.="&registro=".$valor;
//			}
//			break;
//        case "desplegarEntradas":
//            $datos = urlencode(serialize($datos));
//            $variable = "pagina=" . $miPaginaActual;
//            $variable.="&opcion=desplegarEntradas";
//            $variable.="&datos=" . $datos;
//            break;

       
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=nuevo";
            $variable.="&tipoEvaluacion=".$_REQUEST['tipoEvaluacion'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
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
