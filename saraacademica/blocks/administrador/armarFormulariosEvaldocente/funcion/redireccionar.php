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
        case "regresaraFormatos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=formatos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajePorcentaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=porcentaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeFormatoCampo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=formatoCampo";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraPreguntas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=preguntas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraEncabezados":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=encabezados";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraarmarFormulario":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=armarFormulario";
            $variable.="&formatoId=".$_REQUEST['formatoId'];
            $variable.="&formatoNumero=".$_REQUEST['formatoNumero'];
            $variable.="&periodo=".$_REQUEST['periodo'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraAsociarFormatos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=asociarFormatos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraAbrirFechas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=formatos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=informacion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajePreguntas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=preguntas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeCampoVacioEnc":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=campoVacioEncabezados";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeValorPregunta":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=valorPreguntas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeCampoVacioPreg":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=campoVacioPreguntas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeCamposVacios":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=camposVaciosFormulario";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeTipoValorPregunta":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=tipoValorPreguntas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeValorPreguntaRadio":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=valorPreguntasRadio";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeEncabezado":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=encabezados";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeFormulario":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=formulario";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeAsociacion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=asociacion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mensajeAsociacionSeleccionar":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=seleccionar";
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
