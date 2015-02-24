<?php
$this->sql = new SqlverListaClase();

$miSesion = Sesion::singleton();
$usuario = $miSesion->getSesionUsuarioId();


$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$this->idioma["observaciones"]="Observaciones:";
$this->idioma["para"]="Para:";
$this->idioma["asunto"]="Asunto:";
$this->idioma["contenido"]="Contenido:";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonConsultar"] = "Consultar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";
$this->idioma["botonEnviar"] = "Enviar";

?>
