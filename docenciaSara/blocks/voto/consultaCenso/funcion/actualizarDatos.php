<?php

if ($_REQUEST['nombre'] == null && $_REQUEST['tipoDocAnterior'] == null && $_REQUEST['numeroDocAnterior'] == null &&
        $_REQUEST['tipoDocNuevo'] == null && $_REQUEST['numeroDocNuevo'] == null && $_REQUEST['correoPrincipal'] == null &&
        $_REQUEST['correoInstitucional'] == null && $_REQUEST['telefonoFijo'] == null && $_REQUEST['telefonoCelular'] == null &&
        $_REQUEST['direccion'] == null && $_REQUEST['codigo'] == null && $_REQUEST['facultad'] == null && $_REQUEST['carrera'] == null &&
        $_REQUEST['anoGraduado'] == null && $_REQUEST['periodoGraduado'] == null && $_REQUEST['botonAceptar'] == null) {
    echo "Se ha presentado un error durante la actualización, por favor contacte al administrador del sistema.";
    exit;
}

if ($_REQUEST['tipoCenso'] != "egresado") {
    if ($_REQUEST['codigo'] == null && $_REQUEST['facultad'] == null && $_REQUEST['carrera'] == null && $_REQUEST['anoGraduado'] == null &&
            $_REQUEST['periodoGraduado'] == null) {
        $_REQUEST['codigo'] = null;
        $_REQUEST['facultad'] = '';
        $_REQUEST['carrera'] = '';
        $_REQUEST['anoGraduado'] = null;
        $_REQUEST['periodoGraduado'] = null;
    }
}

//Generar contraseña
$_REQUEST['clave'] = $this->randomString();

$conexion = "votocenso";
try {
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    $cadena_sql = $this->cadena_sql = $this->sql->cadena_sql("actualizarCenso", $_REQUEST);

    $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

    if ($resultado == true) {
        
        $msj = $this->funcion->enviarCorreoClave($_REQUEST['correoPrincipal'], $_REQUEST['numeroDocNuevo'], $_REQUEST['clave']);
        $msj = $this->funcion->enviarCorreoClave($_REQUEST['correoInstitucional'], $_REQUEST['numeroDocNuevo'], $_REQUEST['clave']);
        $mensaje = $_REQUEST['votacionMensaje']." <p>...El sistema enviará a su correo la clave de acceso para la votación...</p>".$msj;
        $error = "exito";
        $datos = array("mensaje" => $mensaje, "error" => $error);
        $this->redireccionar("mostrarMensaje", $datos);
    } else {
        $mensaje = "...Oops, se ha presentado un error, por favor contacte al administrador del sistema...";
        $error = "error";
        $datos = array("mensaje" => $mensaje, "error" => $error);
        $this->redireccionar("mostrarMensaje", $datos);
    }
} catch (Exception $e) {
    $mensaje = "...Oops, se ha presentado un error, por favor contacte al administrador del sistema... ".$e;
    $error = "error";
    $datos = array("mensaje" => $mensaje, "error" => $error);
    $this->redireccionar("mostrarMensaje", $datos);
}
?>
