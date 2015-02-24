<?php
// echo'modificar';
// var_dump($_REQUEST);exit;
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$miSesion = Sesion::singleton ();

$nombreFormulario = $esteBloque ["nombre"];

include_once ("core/crypto/Encriptador.class.php");
$cripto = Encriptador::singleton ();
$valorCodificado = "&action=" . $esteBloque ['nombre'];
$valorCodificado .= "&opcion=actualizarDatos";
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado = $cripto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";

$tab = 1;

$esteCampo = "CuadroModificarRegistros";
$atributos ["id"] = $esteCampo;
$atributos ["estilo"] = "jqueryui";
$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
unset ( $atributos );

// ---------------Inicio Formulario (<form>)--------------------------------
$atributos ["id"] = $nombreFormulario;
$atributos ["tipoFormulario"] = "multipart/form-data";
$atributos ["metodo"] = "POST";
$atributos ["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario ( "inicio", $atributos );

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$id_experiencia = $_REQUEST ['idExperiencia'];

$cadena_sql = $this->sql->cadena_sql ( "consultarExperienciaModificar", $id_experiencia );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$otra_entidad = trim ( $resultado [0] ['otra_entidad'] );

if ($otra_entidad != 'NULL') {
	$seleccionOtra = $resultado [0] ['otra_entidad'];
	$seleccion = 0;
} else if ($otra_entidad == 'NULL') {
	
	$seleccion = $resultado [0] ['entidad'];
	$seleccionOtra = '';
}

?>
    <?php
				$atributos ["id"] = "CuadroModificarRegistros";
				$atributos ["estilo"] = "";
				// $atributos["estiloEnLinea"]="display:none";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				$esteCampo = "identificacion";
				$atributos ["id"] = $esteCampo;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["seleccion"] = $resultado [0] ['id_docente'];
				$atributos ["evento"] = 2;
				$atributos ["columnas"] = "1";
				$atributos ["limitar"] = false;
				$atributos ["tamanno"] = 1;
				$atributos ["ancho"] = "450px";
				$atributos ["estilo"] = "jqueryui";
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ["validar"] = "required";
				$atributos ["anchoEtiqueta"] = 310;
				$atributos ["obligatorio"] = true;
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// -----De donde rescatar los datos ---------
				$atributos ["cadena_sql"] = $this->sql->cadena_sql ( "buscarNombreDocente" );
				$atributos ["baseDatos"] = "estructura";
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				
				{
					
					// ------------------Control Lista Desplegable------------------------------
					$esteCampo = "entidad";
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["seleccion"] = $seleccion;
					$atributos ["evento"] = 2;
					$atributos ["columnas"] = 1;
					$atributos ["limitar"] = false;
					$atributos ["tamanno"] = 1;
					$atributos ["ancho"] = "250px";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["validar"] = "required";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["obligatorio"] = true;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					// -----De donde rescatar los datos ---------
					$atributos ["cadena_sql"] = $this->sql->cadena_sql ( "universidad" );
					$atributos ["baseDatos"] = "estructura";
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "otraEntidad";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 20;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[3], maxSize[2000]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $seleccionOtra;
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ------------------Control Lista Desplegable------------------------------
					$esteCampo = "tipo_entidad";
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["seleccion"] = $resultado [0] ['tipo_entidad'];
					$atributos ["evento"] = 2;
					$atributos ["columnas"] = "1";
					$atributos ["limitar"] = false;
					$atributos ["tamanno"] = 1;
					$atributos ["ancho"] = "133px";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["validar"] = "required";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["obligatorio"] = true;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					// -----De donde rescatar los datos ---------
					$atributos ["cadena_sql"] = $this->sql->cadena_sql ( "tipo_entidad" );
					$atributos ["baseDatos"] = "estructura";
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "numeHoras";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 5;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[1],min[1],max[168],maxSize[15],custom[onlyNumberSp]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $resultado [0] ['num_horas'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "fechaInicio";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 8;
					$atributos ["ancho"] = 350;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["deshabilitado"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required";
					$atributos ["categoria"] = "fecha";
					$atributos ["valor"] = $resultado [0] ['fecha_inicio'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "fechaFinal";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 8;
					$atributos ["ancho"] = 350;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["deshabilitado"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required";
					$atributos ["categoria"] = "fecha";
					$atributos ["valor"] = $resultado [0] ['fecha_fin'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "duracion";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 4;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[1],min[1],maxSize[30],custom[onlyNumberSp]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $resultado [0] ['duracion_dias'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "numeActa";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 29;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[1],min[1],maxSize[30]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $resultado [0] ['nume_acta'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "fechaActa";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 8;
					$atributos ["ancho"] = 350;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["deshabilitado"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required";
					$atributos ["categoria"] = "fecha";
					$atributos ["valor"] = $resultado [0] ['fecha_acta'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "numeCaso";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 29;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[1],min[1],maxSize[30],custom[onlyNumberSp]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $resultado [0] ['nume_caso'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "puntaje";
					$atributos ["id"] = $esteCampo;
					$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
					$atributos ["tabIndex"] = $tab ++;
					$atributos ["obligatorio"] = true;
					$atributos ["tamanno"] = 10;
					$atributos ["columnas"] = 1;
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ["tipo"] = "";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["anchoEtiqueta"] = 310;
					$atributos ["validar"] = "required, minSize[1],min[0.1],custom[number]";
					$atributos ["categoria"] = "";
					$atributos ["valor"] = $resultado [0] ['puntaje'];
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
                                        
                                        $esteCampo="detalleDocencia";
                                        $atributos["id"]=$esteCampo;
                                        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                                        $atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
                                        $atributos["tabIndex"]=$tab++;
                                        $atributos["obligatorio"]=true;
                                        $atributos["etiquetaObligatorio"] = false;
                                        $atributos["tipo"]="";
                                        $atributos["columnas"] = 100;
                                        $atributos["filas"] = 5;
                                        $atributos["estilo"]="jqueryui";
                                        $atributos["anchoEtiqueta"] = 300;
                                        $atributos["validar"]="required";
                                        $atributos["categoria"]="";
                                        $atributos ["valor"] = $resultado [0] ['detalledocencia'];
                                        echo $this->miFormulario->campoTextArea($atributos);
                                        unset($atributos);
				}
				// ------------------Fin Division para los botones-------------------------
				// echo $this->miFormulario->division("fin");
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				$esteCampo = "botonCancelar";
				$atributos ["id"] = $esteCampo;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["tipo"] = "boton";
				$atributos ["estilo"] = "";
				$atributos ["verificar"] = ""; // Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
				$atributos ["tipoSubmit"] = "jquery"; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
				$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["nombreFormulario"] = $nombreFormulario;
				echo $this->miFormulario->campoBoton ( $atributos );
				unset ( $atributos );
				// -------------Control Boton-----------------------
				$esteCampo = "botonAceptar";
				$atributos ["id"] = $esteCampo;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["tipo"] = "boton";
				$atributos ["estilo"] = "";
				$atributos ["verificar"] = ""; // Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
				$atributos ["tipoSubmit"] = "jquery"; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
				$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["nombreFormulario"] = $nombreFormulario;
				echo $this->miFormulario->campoBoton ( $atributos );
				unset ( $atributos );
				// -------------Fin Control Boton----------------------
				
				// ------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				
				// -------------Control cuadroTexto con campos ocultos-----------------------
				// Para pasar variables entre formularios o enviar datos para validar sesiones
				$atributos ["id"] = "formSaraData"; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ["obligatorio"] = false;
				$atributos ["etiqueta"] = "";
				$atributos ["valor"] = $valorCodificado;
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				
				// Fin del Formulario
				echo $this->miFormulario->formulario ( "fin" );
				
				echo $this->miFormulario->marcoAgrupacion ( "fin" );
				
				?>