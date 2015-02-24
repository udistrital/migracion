<?php

//$this->sql = new SqlMonitoreo();

//$miSesion = Sesion::singleton();
//$usuario = $miSesion->getSesionUsuarioId();
//
//$conexion = "voto";
//$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
//
//$cadena_sql = trim($this->sql->cadena_sql("consultaVotantes", ''));
//$votantes = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//
//$cadena_sql = trim($this->sql->cadena_sql("consultaVotos", ''));
//$votos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//$this->idioma["votoRegistrado"] = "Su voto ha sido registrado";

$this->idioma["mensaje"] = "Estimado usuario en el siguiente formulario podrá registrar o editar el instructivo para el módulo de evaluación docente.";
$this->idioma["observacion"]="Instructivo Evaluación Docentes:";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["tipoEvaluacion"]="tipoEvaluacion";
$this->idioma["botonConfirmar"] = "Confirmar";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
?>
