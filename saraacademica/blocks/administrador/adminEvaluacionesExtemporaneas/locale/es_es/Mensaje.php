<?php
$this->sql = new SqladminEvaluacionesExtemporaneas();

$miSesion = Sesion::singleton();
$usuario = $miSesion->getSesionUsuarioId();


$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$this->idioma["tabEstudiantes"]="Estudiantes";
$this->idioma["tabAutoevaluacion"]="Autoevaluación";
$this->idioma["tabConsejoCurricular"]="Consejo Curricular";
$this->idioma["mensajePeriodo"] = "YA EXISTE UN PERÍDO ACADÉMICO ACTIVO CON LA INFORMACIÓN QUE ESTÁ INTENTANDO GUARDAR, SI ES NECESARIO, REGISTRE OTRO PERÍODO ACADÉMICO. ";
$this->idioma["informacion"]="Seleccione el Periodo Académico y digite el número de documento de identidad del (la) Docente para realizar la Evaluación Docente Extemporánea.";
$this->idioma["observaciones"]="Observaciones:";
$this->idioma["documentoId"]="Número de documento:";
$this->idioma["respuestaNueva"]="Respuesta nueva : ";
$this->idioma["estadoNuevo"]="Estado nuevo : ";
$this->idioma["justificacion"]="Justificación: ";
$this->idioma["botonConfirmar"] = "Confirmar";
$this->idioma["perAcad"] = "Periodo académico: ";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonConsultar"] = "Consultar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

?>
