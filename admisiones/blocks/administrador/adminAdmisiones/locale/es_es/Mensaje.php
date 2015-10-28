<?php
$this->sql = new SqladminAdmisiones();

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

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

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
$this->idioma["codCarrera"]="Cod. Carrera: ";
$this->idioma["carrera"]="Carrera: ";
$this->idioma["contenido"]="Contenido: ";
$this->idioma["nombreNuevo"]="Nombre de la colilla: ";
$this->idioma["carrerasNuevas"]="Cod. Carrera: ";
$this->idioma["contenidoNuevo"]="Contenido: ";
$this->idioma["subirArchivo"]="Subir archivo: ";
$this->idioma["nombreTipIns"]="Nombre del Tipo de Inscripción: ";
$this->idioma["nombreTipInsNuevo"]="Nombre del Tipo de Inscripción: ";
$this->idioma["numeroTipInsNuevo"]="Número del Tipo de Inscripción: ";
$this->idioma["codTipIns"]="Número Tipo de Inscripción: ";
$this->idioma["nombreDiscapacidad"]="Nombre de la Discapacidad: ";
$this->idioma["numeroDiscapacidad"]="Número de la Discapacidad: ";
$this->idioma["nombreDiscapacidadNuevo"]="Nombre de la Discapacidad: ";
$this->idioma["numeroDiscapacidadNuevo"]="Número de la Discapacidad: ";
$this->idioma["nombrePregunta"]="Pregunta: ";
$this->idioma["nuevoNombrePregunta"]="Pregunta: ";
$this->idioma["parametro1"]="Parámetro 1: ";
$this->idioma["parametro2"]="Parámetro 2: ";
$this->idioma["parametro3"]="Parámetro 3: ";
$this->idioma["parametro4"]="Parámetro 4: ";
$this->idioma["nombreEncabezado"]="Encabezado: ";
$this->idioma["nuevoNombreEncabezado"]="Encabezado: ";
$this->idioma["nuevoSnp"]="Número del registro del icfes (SNP)";
$this->idioma["nombreDocumento"]="Nombre del documento: ";
$this->idioma["nombreCorto"]="Nombre corto del documento: ";
$this->idioma["prefijo"]="Prefijo del documento: ";
$this->idioma["codCarreras"]="Carreras: ";
$this->idioma["nombreDocumentoNuevo"]="Nombre del documento: ";
$this->idioma["nombreCortoNuevo"]="Nombre corto del documento: ";
$this->idioma["prefijoNuevo"]="Prefijo del documento: ";
$this->idioma["carrerasNuevo"]="Carreras: ";
$this->idioma["consulta"]="No. de referencia bancaria o documento de identidad:";
$this->idioma["consultaCredencial"]="No. de credencial:";
$this->idioma["registroIcfes"]="No. SNP:";
$this->idioma["tipoInscripcion"]="tipoInscripcion:";
$this->idioma["apellidos"]="Apellidos:";
$this->idioma["nombres"]="Nombres:";
$this->idioma["documento"]="No. documento identidad:";
$this->idioma["cieSociales"]="Cie Sociales:";
$this->idioma["cieSociales"]="Cie Sociales:";
$this->idioma["biologia"]="Biología:";
$this->idioma["PCN"]="Ciencias Naturales:";
$this->idioma["quimica"]="Química:";
$this->idioma["fisica"]="Física:";
$this->idioma["sociales"]="Sociales:";
$this->idioma["PSC"]="Sociales y Ciudadanas:";
$this->idioma["aptitudVerbal"]="Aptitud Verbal:";
$this->idioma["espaniolLit"]="Español y Lit:";
$this->idioma["PLC"]="Lectura Critica:";
$this->idioma["aptitudMat"]="Aptitud Matemat:";
$this->idioma["PRC"]="Razonamiento Cuantitativo:";
$this->idioma["conMat"]="Conoc Matemat:";
$this->idioma["PMA"]="Matemáticas:";
$this->idioma["idioma"]="idioma:";
$this->idioma["PIN"]="Inglés:";
$this->idioma["geografia"]="Geografía:";
$this->idioma["historia"]="Historia:";
$this->idioma["filosofia"]="Filosofía:";
$this->idioma["interdiciplinaria"]="Interdisciplinaria:";
$this->idioma["PCC"]="Competencias Ciudadanas:";
$this->idioma["codInter"]="Cod. Inter:";
$this->idioma["electiva"]="Electiva:";
$this->idioma["puntajeTotal"]="Puntaje Total:";
$this->idioma["puntajeCal"]="Puntaje Calculado:";
$this->idioma["puntosHom"]="Puntos Homologados:";
$this->idioma["rangoSuperior"]="Rango superior:";
$this->idioma["rangoInferior"]="Rango inferior:";
$this->idioma["credencial"]="Digite el No. de Credencial:";
$this->idioma["lienaHorizontal"]="<hr /> ";
$this->idioma["botonConfirmar"] = "Confirmar";
$this->idioma["periodoAcademico"] = "Período Académico".$registroPeriodo[0]['aca_anio']."-".$registroPeriodo[0]['aca_periodo']."";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["botonActualizar"] = "Actualizar";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";
$this->idioma["botonConsultar"] = "Consultar";
$this->idioma["botonCalcular"] = "Calcular Resultados";
?>
