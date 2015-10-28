<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['evento'] = $_REQUEST['evento'];
$variable['id_periodo'] = $_REQUEST['id_periodo'];

$cadena_sql = $this->sql->cadena_sql("fechaSistema", $variable);
$fechaSistema = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$fechaActual = strtotime($fechaSistema[0][0]);

$cadena_sql = $this->sql->cadena_sql("estadoEventos", $variable);
@$registroEstadoEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$fechaIni = strtotime($registroEstadoEventos[0][3]);
$fechaFin = strtotime($registroEstadoEventos[0][4]);

if ($fechaIni > $fechaFin || $fechaFin < $fechaActual || $fechaIni > $fechaActual) {
    $valor['evento'] = $variable['evento'];
    $valor['id_periodo'] = $_REQUEST['id_periodo'];
    $this->funcion->redireccionar('mostrarMensaje', $valor);
} else {
    $accion = $_REQUEST['evento'];

    switch ($accion) {
        case 1:
            $this->funcion->redireccionar('iraCarrerasOfrecidas');
            break;
        case 2:
            $this->funcion->redireccionar('iraFormularioInscripcion');
            break;
        case 3:
            $this->funcion->redireccionar('iraFormularioInscripcion');
            break;
        case 4:
            $this->funcion->redireccionar('iraCarrerasOfrecidas');
            break;
        case 7:
            $this->funcion->redireccionar('iraFormularioInscripcionDoctoradoIng');
            break;
    }
}
?>

