<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    switch ($opcion) {

        case "confirmarNuevo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=confirmar";
            $variable.="&tipoAccion=".$valor;
            $variable.="&nombre_tabla=" . $_REQUEST["nombreTabla"];
            break;

        case "exitoRegistro":
            $variable = "pagina=inscripcionConferencia";
            $variable.="&opcion=mostrar";
            $variable.="&mensaje=exitoRegistro";
            $variable.="&registro=" . $configuracion["idRegistrado"];

            break;

        case "falloRegistro":
            $variable = "pagina=adminParticipante";
            $variable.="&opcion=mostrar";
            $variable.="&mensaje=falloRegistro";
            break;

        case "exitoEdicion":
            $variable = "pagina=adminParticipante";
            $variable.="&opcion=mostrar";
            $variable.="&mensaje=exitoEdicion";
            break;

        case "falloEdicion":
            $variable = "pagina=adminParticipante";
            $variable.="&opcion=mostrar";
            $variable.="&mensaje=falloRegistro";
            break;

        case "paginaPrincipal":
            $variable = "pagina=index";
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