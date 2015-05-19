<?php
/*$this->sql = new SqlgestionPassword();

$miSesion = Sesion::singleton();
$usuario = $miSesion->getSesionUsuarioId();


$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "////Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");*/

$this->idioma["informacion"] = "<center><span class='textoNegrita textoGrande textoCentrar'><br>CERTIFICADO DE INGRESOS Y RETENCIONES</span></center>
    <p class='textoJustificar'>
        Seleccione el a&ntilde;o, haga Click en la imagen del PDF para generar el certificado de ingresos y retenciones.
    </p>";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["docuentoNumero"] = "Número de documento:";
$this->idioma["observacion"] = "Observación:";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonBuscar"] = "Buscar";
$this->idioma["botonEnviar"] = "Enviar";
$this->idioma["botonGuardar"] = "Cambiar Contraseña";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

	
                       
			

?>
