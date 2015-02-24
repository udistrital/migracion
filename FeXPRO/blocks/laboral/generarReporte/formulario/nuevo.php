<?

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

$nombreFormulario=$esteBloque["nombre"];

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
//$valorCodificado="pagina=".$esteBloque["nombre"];
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&solicitud=generarReporte";
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$cripto->codificar($valorCodificado);

$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";


$tab=1;
//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);


//-------------------------------Mensaje-------------------------------------
$esteCampo="mensaje1";
$atributos["id"]=$esteCampo;
$atributos["obligatorio"]=false;
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]="";
$atributos["mensaje"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoMensaje($atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="marcoDatosBasicos";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);

echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);
unset($atributos);


$esteCampo = "idUsuario";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = 4;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "150px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 250;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("buscarCedulaUsuario");
$atributos["baseDatos"] = "oracle";
echo $this->miFormulario->campoCuadroLista($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);
unset($atributos);


$atributos['id']="texto";
$atributos['estilo']="query";
$atributos['estiloEnLinea']="display:none";
echo $this->miFormulario->division("inicio",$atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="marcoDatosBasicos2";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//------------------ Check Box ------------------------------
$esteCampo = "basico";
$atributos["id"] = $esteCampo;
$atributos["nombre"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["columnas"] = '1';
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 50;
$atributos["validar"] = "required";
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Certificado Datos Básicos :";
echo $this->miFormulario->campoCuadroSeleccion($atributos);
unset($atributos);

//------------------ Check Box ------------------------------
$esteCampo = "sueldoBasico";
$atributos["id"] = $esteCampo;
$atributos["nombre"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["columnas"] = '1';
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 250;
$atributos["validar"] = "required";
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Certificado Sueldo Básico Mensual:";
echo $this->miFormulario->campoCuadroSeleccion($atributos);
unset($atributos);

//------------------ Check Box ------------------------------
$esteCampo = "promedioMensual";
$atributos["id"] = $esteCampo;
$atributos["nombre"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["columnas"] = '1';
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 250;
$atributos["validar"] = "required";
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Certificado Salario Promedio Mensual:";
echo $this->miFormulario->campoCuadroSeleccion($atributos);

//------------------ Check Box ------------------------------
// $esteCampo = "mensualDiscriminado";
// $atributos["id"] = $esteCampo;
// //$atributos["nombre"] = $esteCampo;
// $atributos["tabIndex"] = $tab++;
// //$atributos["columnas"] = '1';
// $atributos["etiquetaObligatorio"] = true;
// $atributos["anchoEtiqueta"] = 250;
// $atributos["validar"] = "required";
// $atributos["obligatorio"] = true;
// $atributos["etiqueta"] = "Certificado Salario Mensual Discriminado:";
// echo $this->miFormulario->campoCuadroSeleccion($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="marcoDatosBasicos3";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "textoModificable";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="95";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2";
$atributos["validar"]="required";
echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);


echo $this->miFormulario->division("fin",$atributos);
unset($atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="DatosBaseDatos";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "idBuscado";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = false;
$atributos["tamanno"] = "50";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required";
$atributos["categoria"] = "";
$atributos["deshabilitado"] = true;
//$atributos["valor"] = $registro;
// $atributos["valor"] = 'gato';
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "nameCiudad";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = false;
$atributos["tamanno"] = "50";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required";
$atributos["categoria"] = "";
//$atributos["deshabilitado"] = true;
//$atributos["valor"] = $registro;
// $atributos["valor"] = 'gato';
echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="DatosElaboro";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "nombreReviso";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = false;
$atributos["tamanno"] = "50";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required";
$atributos["categoria"] = "";
//$atributos["deshabilitado"] = true;
//$atributos["valor"] = $registro;
// $atributos["valor"] = 'gato';
echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "cargoReviso";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = false;
$atributos["tamanno"] = "50";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required";
$atributos["categoria"] = "";
//$atributos["deshabilitado"] = true;
//$atributos["valor"] = $registro;
// $atributos["valor"] = 'gato';
echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);
//-------------Control Boton-----------------------
$esteCampo = "botonAceptar";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["tipo"] = "boton";
$atributos["estilo"] = "";
$atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
$atributos["tipoSubmit"] = "jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin",$atributos);

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);

//Fin de Conjunto de Controles
//echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

//---------------Fin formulario (</form>)--------------------------------
echo $this->miFormulario->formulario("fin",$atributos);
?>
