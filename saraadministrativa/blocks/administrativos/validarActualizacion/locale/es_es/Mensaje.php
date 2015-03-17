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

$this->idioma["informacion"] = "<center><span class='textoNegrita textoGrande textoCentrar'><br>CAMBIO DE CONTRASEÑA</span></center>
     </p>
    <p class='textoJustificar'>
    Hoy en día la seguridad en Internet es fundamental para proteger nuestra información de posibles ladrones informáticos, 
    en la oficina o en nuestra casa podemos tener información muy valiosa que es fundamental proteger, esto hace muy importante
    poner la información bajo una clave de acceso difícil de adivinar.</p>
    <p class='textoJustificar'>
    Este punto es fundamental ya que la mayoría de las personas utilizan las palabras mas fáciles de recordar, lo mas común es poner nombres de mascotas, números de documentos de identidad, fechas de aniversarios etc. esto es un gran error ya que facilita el poder acceder a personal no autorizado a sus datos, también una mala elección de clave puede producir un posible intruso en sus datos, pueden obtener de usted toda la información que pretendía proteger con una mala elección de clave de acceso.
    </p>
    <p class='textoJustificar'>
    Nota. No digite la clave en presencia de otras personas, recuerde que usted es el único responsable e interesado en la información aquí guardada.</p>
    Cualquier inquietud, sugerencia o inconsistencia en la información por favor remita un correo a: 
    <b><a href='mailto:computo@udistrital.edu.co'>computo@udistrital.edu.co</a></b> 
    </p>";
$this->idioma["informacionRecuperacion"] = "<center><span class='textoNegrita textoGrande textoCentrar'><br>RECUPERACIÓN DE CONTRASEÑA</span></center>
     </p>
    <p class='textoJustificar'>
    Hoy en día la seguridad en Internet es fundamental para proteger nuestra información de posibles ladrones informáticos, en la oficina o en nuestra casa podemos tener información muy valiosa que es fundamental proteger, esto hace muy importante poner la información bajo una clave de acceso difícil de adivinar.
    </p>
    <p class='textoJustificar'>Digite el usuario y haga click en 'Buscar':</p>
    ";
$this->idioma["informacionValidaDatos"] = "<center><span class='textoNegrita textoGrande textoCentrar'><br>VALIDACIÓN DE INFORMACIÓN</span></center>
     </p>
    <p class='textoJustificar'>
    Señor usuario, a continuación se le presenta un formulario con tres preguntas, seleccione la respuesta correcta para continuar con la recuperación de su contraseña.
    </p>
    ";
$this->idioma["nombreUsuario"] = "Usuario ";
$this->idioma["actualClave"] = "Contraseña actual";
$this->idioma["nuevaClave"] = "Nueva contraseña";
$this->idioma["confirmarNuevaClave"] = "Confirmar nueva contraseña";
$this->idioma["noEncontroRegistro"]="No se encontraron registros...";
$this->idioma["noEncontroPeriodoActivo"]="No se encontrararon períodos académicos activos...";
$this->idioma["botonAceptar"] = "Aceptar";
$this->idioma["botonBuscar"] = "Buscar";
$this->idioma["botonGuardar"] = "Cambiar Contraseña";
$this->idioma["botonCancelar"]="Cancelar";
$this->idioma["botonContinuar"] = "Continuar";

	
                       
			

?>
