<?php
$this->sql = new SqlinicioRegistroEvaluacion();

$miSesion = Sesion::singleton();
$usuario = $miSesion->getSesionUsuarioId();


$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

?>

