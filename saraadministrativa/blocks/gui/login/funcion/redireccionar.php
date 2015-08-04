<?

$miSesion = Sesion::singleton();


if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    switch ($opcion) {
        case "gestionAdministrativos":
            $variable = "pagina=gestionAdministrativos";
            $variable.="&redireccionar=true";
            $variable.="&opcion=nuevo";
            $variable.="&usuario=" . $_REQUEST["usuario"];
            if (isset($_REQUEST["tipo"])) {
                $variable.="&tipo=" . $_REQUEST["tipo"];
            } else {
                $variable.="&tipo=1";
            }
            $variable.="&tiempo=" . time();
            //$variable.="&sesionID=".$valor["sesionID"];
            break;
        case "consultaCertIngRet":
            $variable = "pagina=gestionAdministrativos";
            $variable.="&redireccionar=true";
            $variable.="&opcion=consultarCertIngresosRetenciones";
            $variable.="&usuario=" . $_REQUEST["usuario"];
            if (isset($_REQUEST["tipo"])) {
                $variable.="&tipo=" . $_REQUEST["tipo"];
            } else {
                $variable.="&tipo=1";
            }
            $variable.="&tiempo=" . time();
            //$variable.="&sesionID=".$valor["sesionID"];
            break;
        case "certificadosRecursosHumanos":
            $variable = "pagina=gestionAdministrativos";
            $variable.="&redireccionar=true";
            $variable.="&opcion=consultarCertificadosRecHumanos";
            $variable.="&usuario=" . $_REQUEST["usuario"];
            if (isset($_REQUEST["tipo"])) {
                $variable.="&tipo=" . $_REQUEST["tipo"];
            } else {
                $variable.="&tipo=1";
            }
            $variable.="&tiempo=" . time();
            //$variable.="&sesionID=".$valor["sesionID"];
            break;
        case "paginaPrincipal":
            $variable = "pagina=index";
            if (isset($valor) && $valor != '') {
                $variable.="&error=" . $valor;
            }
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