<?php
$this->sql = new SqlhabilitarProcesoEvaldocente();

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

$this->idioma["mensajePeriodo"] = "YA EXISTE UN PERÍDO ACADÉMICO ACTIVO CON LA INFORMACIÓN QUE ESTÁ INTENTANDO GUARDAR, SI ES NECESARIO, REGISTRE OTRO PERÍODO ACADÉMICO. ";
$this->idioma["informacion"]="Registre o active el período académico, que va a habilitar para el proceso de Evaluación Docente... ";
$this->idioma["informacionEventos"]="EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ABRIR O CERRAR LAS FECHAS DE LOS EVENTOS QUE HACEN PARTE DEL PROCESO DE EVALUACIÓN DOCENTE....";
$this->idioma["lienaHorizontal"]="<hr /> ";
$this->idioma["botonConfirmar"] = "Confirmar";
$this->idioma["periodoAcademico"] = "Período Académico".$registroPeriodo[0]['acasperiev_anio']."-".$registroPeriodo[0]['acasperiev_periodo']."";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonGuardar"] = "Guardar";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

	
                       
			

?>
