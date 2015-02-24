<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {

	case "iraListaDocentes":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=listaDocentes";
            $variable.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&periodoId=".$_REQUEST['periodoId'];
            $variable.="&anio=".$_REQUEST['anio'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            $variable.="&carrera=".$_REQUEST['carrera'];
            break;
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&carrera=".$_REQUEST['carrera'];
            $variable.="&anio=".$_REQUEST['anio'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            $variable.="&mensaje=informacion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraFormularios":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=formularios";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&mensaje=registroExitoso";
            $variable.="&formatoId=".$_REQUEST['formatoId'];
            $variable.="&periodoId=".$_REQUEST['periodoId'];
            $variable.="&documentoId=".$_REQUEST['documentoId'];
            $variable.="&carrera=".$_REQUEST['carrera'];
            $variable.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
            $variable.="&docenteNombre=".$_REQUEST['docenteNombre'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&asignatura=".$_REQUEST['asignatura'];
            $variable.="&grupo=".$_REQUEST['grupo'];
            $variable.="&tipoVinculacion=".$_REQUEST['tipoVinculacion'];
            $variable.="&nombreVinculacion=".$_REQUEST['nombreVinculacion'];
            $variable.="&tipoId=".$_REQUEST['tipoId'];
            $variable.="&anio=".$_REQUEST['anio'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            break;
        case "iraAvanceEvaluacion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=avanceEvaluacion";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            break;
        case "iraObservaciones":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=observacionesEstudiantes";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            break;
        case "iraResultadosParciales":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=resultadosParciales";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            break;
        case "paginaPrincipalAdministrador":
            $variable = "pagina=indexEvaldocentes";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        case "paginaPrincipal":
            $variable = "pagina=indexEvaluacion";
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
