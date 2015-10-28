<?php
//$this->sql = new SqlinscripcionAdmisiones();

$miSesion = Sesion::singleton();
$usuario = $miSesion->getSesionUsuarioId();


$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

//$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
//$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$this->idioma["mensajePeriodo"] = "YA EXISTE UN PERÍDO ACADÉMICO ACTIVO CON LA INFORMACIÓN QUE ESTÁ INTENTANDO GUARDAR, SI ES NECESARIO, REGISTRE OTRO PERÍODO ACADÉMICO. ";
$this->idioma["informacion"]="Para habilitar el proceso de admisiones, registre un periodo nuevo o active un período académico.
                               <br>Para registrar un periodo nuevo, seleccione al año, seleccione el periodo, haga click en 'Guardar'.
                              <br>NOTA: Los campos con * son obligatorios.";
$this->idioma["informacionEventos"]="EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ABRIR O CERRAR LAS FECHAS DE LOS EVENTOS QUE HACEN PARTE DEL PROCESO DE EVALUACIÓN DOCENTE....";
$this->idioma["anio"]="Año: ";
$this->idioma["periodo"]="Periodo: ";
$this->idioma["medio"]="Medio: ";
$this->idioma["medios"]="Medio: ";
$this->idioma["estado"]="Estado: ";
$this->idioma["medioNuevo"]="Medio: ";
$this->idioma["estadoNuevo"]="Estado: ";
$this->idioma["localidad"]="Localidad: ";
$this->idioma["numero"]="Loc. Número: ";
$this->idioma["puntosv"]="Puntos ICFES Viejo: ";
$this->idioma["puntosn"]="Puntos ICFES Nuevo: ";
$this->idioma["valor"]="Valor: ";
$this->idioma["porcentaje"]="Porcentaje: ";
$this->idioma["estadosalmin"]="Estado: ";
$this->idioma["valorsalmin"]="Valor: ";
$this->idioma["porcentajesalmin"]="Porcentaje: ";
$this->idioma["localidadNueva"]="Localidad: ";
$this->idioma["estratoNuevo"]="Estrato: ";
$this->idioma["numeroNuevo"]="Loc. Número: ";
$this->idioma["puntosvNuevo"]="Puntos ICFES Viejo: ";
$this->idioma["puntosnNuevo"]="Puntos ICFES Nuevo: ";
$this->idioma["puntosestNuevo"]="Puntos: ";
$this->idioma["estrato"]="Estrato: ";
$this->idioma["numeroestNuevo"]="Estrato Número: ";
$this->idioma["numeroest"]="Estrato Número: ";
$this->idioma["puntos"]="Puntos: ";
$this->idioma["instructivo"]="Instructivo: ";
$this->idioma["nombre"]="Nombre de la colilla: ";
$this->idioma["carreras"]="Carreras: ";
$this->idioma["contenido"]="Contenido: ";
$this->idioma["nombreNuevo"]="Nombre de la colilla: ";
$this->idioma["carrerasNuevas"]="Carreras: ";
$this->idioma["contenidoNuevo"]="Contenido: ";
for($i=0; $i<=100; $i++)
{
    $this->idioma["subirArchivo".$i.""] = "Cargar Archivo";
}   
$this->idioma["nombreTipIns"]="Nombre del Tipo de Inscripción: ";
$this->idioma["nombreTipInsNuevo"]="Nombre del Tipo de Inscripción: ";
$this->idioma["numeroTipInsNuevo"]="Número del Tipo de Inscripción: ";
$this->idioma["codTipIns"]="Número Tipo de Inscripción: ";
$this->idioma["nombreDiscapacidad"]="Nombre de la Discapacidad: ";
$this->idioma["numeroDiscapacidad"]="Número de la Discapacidad: ";
$this->idioma["nombreDiscapacidadNuevo"]="Nombre de la Discapacidad: ";
$this->idioma["numeroDiscapacidadNuevo"]="Número de la Discapacidad: ";
$this->idioma["direccionResidencia"]="Dirección de residencia: ";
$this->idioma["telefono"]="Teléfono : ";
$this->idioma["email"]="Correo electrónico: ";
$this->idioma["documentoActual"]="Documento actual: ";
$this->idioma["documentoIcfes"]="Documento de identidad con el que presentó el exámen de estado ICFES o SABER 11: ";
$this->idioma["registroIcfes1"]="Número del registro del icfes (SNP) ";
$this->idioma["registroIcfes2"]="Número del registro del icfes (SNP) ";
$this->idioma["confirmarRegistroIcfes"]="Confirmnar el número del registro del icfes (SNP) ";
$this->idioma["observaciones"]="observaciones ";
$this->idioma["documento"]="Documento de identidad: ";
$this->idioma["codigoEstudiante"]="codigo de estudiante ";
$this->idioma["confirmarCodigoEstudiante"]="confirmar cóodigo de estudiante ";
$this->idioma["cancelo"]="Canceló semestre ";
$this->idioma["motivo"]="Motivo del retiro: ";
$this->idioma["universidadProviene"]="Universidad de donde proviene";
$this->idioma["carreraCursando"]="Carrera que venía cursando";
$this->idioma["carreraVeniaCursando"]="Carrera que venía cursando";
$this->idioma["semestreCursado"]="Último semestre cursado";
$this->idioma["motivoTransferencia"]="Motivo de la transferencia";
$this->idioma["lienaHorizontal"]="<hr /> ";
$this->idioma["botonConfirmar"] = "Confirmar";
//$this->idioma["periodoAcademico"] = "Período Académico".$registroPeriodo[0]['aca_anio']."-".$registroPeriodo[0]['aca_periodo']."";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["botonActualizar"] = "Actualizar";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";
$this->idioma["botonImprimir"] = "Imprimir";
$this->idioma["imprime"] = "Imprimir";
	
                       
			

?>
