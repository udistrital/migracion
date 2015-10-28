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
$this->idioma["credencial"]="No de Credencial: ";

$this->idioma["lienaHorizontal"]="<hr /> ";
$this->idioma["botonConfirmar"] = "Confirmar";
//$this->idioma["periodoAcademico"] = "Período Académico".$registroPeriodo[0]['aca_anio']."-".$registroPeriodo[0]['aca_periodo']."";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["eventoCerrado"]="Las fechas para la consulta de resultados se encuentran cerradas!";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["botonActualizar"] = "Actualizar";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonConsultar"] = "Consultar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";
$this->idioma["botonImprimir"] = "Imprimir";
$this->idioma["imprime"] = "Imprimir";
	
                       
			

?>
