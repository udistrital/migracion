<?php
$this->sql = new SqlarmarFormulariosEvaldocente();

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

$this->idioma["mensaje"]="A continuación se presenta la lista de los formatos registrados en el sistema para el proceso de Evaluación Docente para el periodo académico ".$registroPeriodo[0]['acasperiev_anio']."-".$registroPeriodo[0]['acasperiev_periodo'].", seleccione el registro, haga click en 'Armar formulario' para asociar los encabezados y las preguntas que hacen parte del formulario.";
$this->idioma["mensajePeriodo"] = "YA EXISTE UN PERÍDO ACADÉMICO ACTIVO CON LA INFORMACIÓN QUE ESTÁ INTENTANDO GUARDAR, SI ES NECESARIO, REGISTRE OTRO PERÍODO ACADÉMICO. ";
$this->idioma["informacion"]="Registre los formatos que hacen parte del proceso de Evaluación Docente para el período académico ".$registroPeriodo[0]['acasperiev_anio']."-".$registroPeriodo[0]['acasperiev_periodo']."";
$this->idioma["informacionEventos"]="EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ABRIR O CERRAR LAS FECHAS DE LOS EVENTOS QUE HACEN PARTE DEL PROCESO DE EVALUACIÓN DOCENTE....";
$this->idioma["informacionEncabezados"]="EL SIGUIENTE FORMLUARIO LE PERMITIRÁ REGISTRAR O EDITAR LOS ENCABEZADOS DE LOS FORMULARIOS QUE HACEN PARTE DEL PROCESO DE EVALUACIÓN DOCENTE....";
$this->idioma["texto"]="Texto ";
$this->idioma["lienaHorizontal"]="<hr /> ";
$this->idioma["botonConfirmar"] = "Confirmar";
$this->idioma["tipoEvaluacion"]="Tipo de Evaluación:";
$this->idioma["tipEvaluacion"]="Tipo de Evaluación:";
$this->idioma["formatoNumero"]="Formato No.:";
$this->idioma["formatoNum"]="Formato No.:";
$this->idioma["porcentaje"]="Porcentaje:";
$this->idioma["estado"]="Estado:";
$this->idioma["descripcion"]="Descripción:";
$this->idioma["periodoAcademico"] = "REGISTRE O EDITE LAS PREGUNTAS PARA EL PERIODO ACADÉMICO: ".$registroPeriodo[0]['acasperiev_anio']."-".$registroPeriodo[0]['acasperiev_periodo']."";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["encabezado"]="Encabezado:";
$this->idioma["encabezado1"]="Editar encabezado:";
$this->idioma["tipoPregunta"]="Tipo de Pregunta:";
$this->idioma["tipPregunta"]="Tipo de Pregunta:";
$this->idioma["valorPregunta"]="Valor Máx. Pregunta:";
$this->idioma["preguntaNombre"]="Número de la Pregunta:";
$this->idioma["pregunta"]="Pregunta:";
$this->idioma["encabezado"]="Encabezados:";
$this->idioma["valor"]="valor";
$this->idioma["obesrvaciones"]="Observaciones";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

	
                       
			

?>
