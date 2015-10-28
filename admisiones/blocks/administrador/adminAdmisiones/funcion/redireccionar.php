<?php

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
        case "regresaraMedio":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=medios";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraAbrirFechas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=eventos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresar":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=".$valor['opcionPagina'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            if(isset($_REQUEST['consulta']))
            {
                $variable.="&consulta=" . $_REQUEST['consulta'];
            }
            if(isset($_REQUEST['consultaCredencial']))
            {
                $variable.="&consultaCredencial=" . $_REQUEST['consultaCredencial'];
                $variable.="&id_periodo=" . $_REQUEST['id_periodo'];
            }
            if(isset($valor['codcra'])){
                 $variable.="&codcra=".$valor['codcra'];
            }
            if(isset($_REQUEST['facultades']))
            {
                $variable.="&facultad=" . $_REQUEST['facultades'];
            }
            if(isset($_REQUEST['tipoInscripcion'])){
                $variable.="&tipoInscripcion=" . $_REQUEST['tipoInscripcion'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            
            break;
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=error";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeSinRegistro":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            if(isset($valor['codcra'])){
                 $variable.="&codcra=".$valor['codcra'];
            }
            $variable.="&opcionPagina=".$valor['opcionPagina'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=sinRegistro";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeRegistroExistente":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&opcionPagina=".$valor['opcionPagina'];
            $variable.="&mensaje=registroExiste";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeOtros":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=".$valor['opcionPagina'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeMedio":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=errorMedio";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeFecha":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=errorFecha";
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "mostrarMensajePerExiste":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&estadoNuevo=".$_REQUEST['estadoNuevo'];
            $variable.="&mensaje=perExiste";
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
            $variable.="&opcionPagina=".$valor['opcionPagina'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeSalarioMin":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=salarioMin";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeLocalidades":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=localidades";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        
        case "regresaraSalMin":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=salmin";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeEstado":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=estado";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraLocalidades":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=localidades";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeEstratos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=estratos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraEstratos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=estratos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraInstructivo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=instructivo";
            $variable.="&seccion=".$_REQUEST['seccion'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeColillas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=colillas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraColillas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=colillas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeArchivoPines":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=archivoPines";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeArchivo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=archivoAdmitidos";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraRegistroPines":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=registrarPines";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeArchivoRepetido":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=archivoPinesRepetido";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraHabilitarCarrera":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=habilitarCarreras";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mostrarMensajeTipIns":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=tipoInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
         case "regresaraTipIns":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=registarTipInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "paginaPrincipal":
            $variable = "pagina=indexAdminAdmisiones";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            break;
        case "mostrarMensajeCampoVacio":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&mensaje=campoVacio";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&opcionPagina=".$valor['opcionPagina'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraFormularioRegistro":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=".$valor['opcionPagina'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraSnpAspirantes":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=snpAspirantes";
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&tipoInscripcion=".$_REQUEST['tipoInscripcion'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraCalculoResultados":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=calcularResultados";
            $variable.="&mensaje=RegistroExitoso";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraCargaAdmitidos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=cargarAdmitidos";
            $variable.="&mensaje=registroExitoso";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraMarcaAdmitidos":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=marcarAdmitidosRangos";
            $variable.="&mensaje=registroExitoso";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "regresaraMarcaAdmitidosCredencial":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=marcarAdmitidosCredencial";
            $variable.="&mensaje=registroExitoso";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
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
