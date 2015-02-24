<?
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    switch ($opcion) {

        case "consultarEntrada":
            $variable ="pagina=consultarEntrada";
            $variable.="&opcion=consultar";            
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