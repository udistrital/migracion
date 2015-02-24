<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {

	case "iraCargaDocente":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=cargaDocente";
            $variable.="&documentoId=" . $_REQUEST['documentoId'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            $variable.="&perAcad=" . $_REQUEST['perAcad'];
            $variable.="&tipoEvaluacionExt=" . $_REQUEST['tipoEvaluacionExt'];
            $variable.="&tipo=".$_REQUEST['tipo'];
             break;
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=informacion";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            break;
        case "regresaraFormularios":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=formularios";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&formatoId=".$_REQUEST['formatoId'];
            $variable.="&docenteNombre=".$_REQUEST['docenteNombre'];
            $variable.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
            $variable.="&perAcad=".$_REQUEST['perAcad'];
            $variable.="&documentoId=".$_REQUEST['documentoId'];
            $variable.="&carrera=".$_REQUEST['carrera'];
            $variable.="&asignatura=".$_REQUEST['asignatura'];
            $variable.="&grupo=".$_REQUEST['grupo'];
            $variable.="&tipoVinculacion=".$_REQUEST['tipoVinculacion'];
            $variable.="&nombreVinculacion=".$_REQUEST['nombreVinculacion'];
            $variable.="&tipoId=".$_REQUEST['tipoId'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            break;
        case "mostrarMensajeFormatoCampo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=formatoCampo";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
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
