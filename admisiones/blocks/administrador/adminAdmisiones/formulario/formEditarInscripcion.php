<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//var_dump($_REQUEST);

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}

if($variable['periodo']==1)
{
    $periodo="PRIMER";
}
elseif($variable['periodo']==3)
{
    $periodo="SEGUNDO";
} 
else
{
    $periodo=" ";
}

$variable['consultaCredencial']=$_REQUEST['consultaCredencial'];
$variable['id_periodo']=$_REQUEST['id_periodo'];

$tipo = 'message';
$mensaje = "<H3><center>ADMISIONES  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."<br>
            Credencial No.".$_REQUEST['consultaCredencial']."</H3>";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//Descomponemos la cadena que coneitne el SNP para rescatar el año y el semestre que se presentó el ICFES
$snpIcfes=substr($registro[0]['asp_snp'],2,5);

$valorCodificado="pagina=administracion";
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=procesarEditarInscripcion";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&asp_id=".$registro[0]['asp_id'];
$valorCodificado.="&consultaCredencial=".$_REQUEST['consultaCredencial'];
$valorCodificado.="&id_periodo=".$_REQUEST['id_periodo'];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Editar Inscripción";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);
////-------------------------------Mensaje-------------------------------------
/*$tipo = 'message';
$mensaje = "<span class='textoNegrita textoPequenno'>Colilla que va a editar: ".$_REQUEST['nombre']."<br>";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);*/
$tab = 1;
 
//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);
    
    $variable['evento']=1;             

    $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
    $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
    $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    echo "<table widht='100%'>";
    echo "<tr>";
    echo "<td>";
    //-------------Control cuadroTexto-----------------------
    $esteCampo="registroIcfes";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Número de registro del ICFES o SABER-PRO 11";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="30";
    $atributos["maximoTamanno"]="14";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_snp'];
    $atributos["validar"]="required,min[0],minSize[12],maxSize[14],custom[noFirstNumber],custom[minLowerAlphaChars],custom[minNumberChars],custom[onlyLetterNumber]";
    //$atributos["validar"]="required,number";
    $atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "carrera";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_cra_cod'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = "Carrera: ";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("carrerasOfrecidas");
    $atributos["baseDatos"] = "aspirantes";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "tipoInscripcion";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['ti_id'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = $registroPreguntas[2]['preg_nombre'];
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("tiposInscripcion");
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="apellidos";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Apellidos";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="30";
    $atributos["maximoTamanno"]="14";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_apellido'];
    $atributos["validar"]="required";
    //$atributos["validar"]="required,number";
    //$atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="nombres";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Nombres";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="30";
    $atributos["maximoTamanno"]="14";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_nombre'];
    $atributos["validar"]="required";
    //$atributos["validar"]="required,number";
    //$atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="documento";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Documento de identidad";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="30";
    $atributos["maximoTamanno"]="14";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_nro_iden_icfes'];
    $atributos["validar"]="required";
    //$atributos["validar"]="required,number";
    //$atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "sexo";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_sexo'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] =$registroPreguntas[6]['preg_nombre'];
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = array(array('M', 'Masculino'),array('F', 'Femenino'));
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "medio";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['med_id'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] ="Medio de publicidad";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("buscarMedio");
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "localidadColegio";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_localidad_colegio'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] ="Localidad del colegio";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "localidadResidencia";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_localidad'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] ="Localidad de residencia";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "estratoResidencia";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_estrato'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] ="Estrato de residencia";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("estrato",$variable);
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "serMilitar";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $registro[0]['asp_ser_militar'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "200px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 180;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] ="Servicio Militar";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = array(array('N', 'No'),array('S', 'Si'));
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="puntajeTotal";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Puntaje Total";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    //$atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="10";
    $atributos["maximoTamanno"]="6";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_ptos'];
    $atributos["validar"]="custom[number]";
    $atributos["categoria"]="";
    $atributos["deshabilitado"]=true;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="puntajeCal";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Puntaje Calculado";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    //$atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="10";
    $atributos["maximoTamanno"]="6";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_ptos_cal'];
    $atributos["validar"]="custom[number]";
    $atributos["categoria"]="";
    $atributos["deshabilitado"]=true;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
   
    echo "</td>";
    echo "<td width='40%' valign='top'>";
    
    if($registro[0]['asp_cie_soc'] != '')
    {
    	//-------------Control cuadroTexto-----------------------
    	$esteCampo="cieSociales";
    	$atributos["id"]=$esteCampo;
    	$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    	$atributos["titulo"]="Ciencias Sociales";
    	$atributos["tabIndex"]=$tab++;
    	$atributos["obligatorio"]=true;
    	//$atributos["etiquetaObligatorio"] = true;
    	$atributos["anchoEtiqueta"] = 180;
    	$atributos["tamanno"]="10";
    	$atributos["maximoTamanno"]="6";
    	$atributos["tipo"]="text";
    	$atributos["estilo"]="jqueryui";
    	$atributos["obligatorio"] = true;
    	$atributos["valor"] = $registro[0]['asp_cie_soc'];
    	$atributos["validar"]="custom[number]";
    	$atributos["categoria"]="";
        $atributos["deshabilitado"]=true;
    	echo $this->miFormulario->campoCuadroTexto($atributos);
    	unset($atributos);
    }
    
    if($registro[0]['asp_bio'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
    	$atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Biología";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Biología";
	    }
	    else 
	    {
	    	$atributos["etiqueta"]="Ciencias Naturales";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_bio'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_qui'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="quimica";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Química";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_qui'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_fis'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="fisica";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Física";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_fis'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_soc'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="sociales";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Sociales";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Sociales";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Sociales y Ciudadanas";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_soc'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_apt_verbal'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="aptitudVerbal";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Aptitud Verbal";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_apt_verbal'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_esp_y_lit'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="espaniolLit";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Español y Literatura";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Español y Lit";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Lectura Critica";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_esp_y_lit'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_apt_mat'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="aptitudMat";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Aptitud Matemática";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Aptitud Matemat.";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Razonamiento Cuántico";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_apt_mat'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_con_mat'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="conMat";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Conocimiento Matemático";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Conoc Mat.";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Matemáticas";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_con_mat'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_idioma'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="idioma";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Idioma";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Idioma";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Inglés";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_idioma'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_geo'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="geografia";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Geografía";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_geo'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_his'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="historia";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Historia";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_his'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_fil'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="filosofia";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Filosofía";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_fil'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    if($registro[0]['asp_interdis'] != '')
    {
	    //-------------Control cuadroTexto-----------------------
	    $esteCampo="interdiciplinaria";
	    $atributos["id"]=$esteCampo;
	    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
	    $atributos["titulo"]="Interdisciplinaria";
	    $atributos["tabIndex"]=$tab++;
	    $atributos["obligatorio"]=true;
	    //$atributos["etiquetaObligatorio"] = true;
	    $atributos["anchoEtiqueta"] = 180;
	    if($snpIcfes <= 20141)
	    {
	    	$atributos["etiqueta"]="Interdisciplinaria";
	    }
	    else
	    {
	    	$atributos["etiqueta"]="Competencias Ciudadanas";
	    }
	    $atributos["tamanno"]="10";
	    $atributos["maximoTamanno"]="6";
	    $atributos["tipo"]="text";
	    $atributos["estilo"]="jqueryui";
	    $atributos["obligatorio"] = true;
	    $atributos["valor"] = $registro[0]['asp_interdis'];
	    $atributos["validar"]="custom[number]";
	    $atributos["categoria"]="";
            $atributos["deshabilitado"]=true;
	    echo $this->miFormulario->campoCuadroTexto($atributos);
	    unset($atributos);
    }
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="codInter";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Código Interdisciplinaria";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    //$atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="10";
    $atributos["maximoTamanno"]="6";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_cod_inter'];
    $atributos["validar"]="custom[number]";
    $atributos["categoria"]="";
    $atributos["deshabilitado"]=true;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="electiva";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Electiva";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    //$atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="10";
    $atributos["maximoTamanno"]="6";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_electiva'];
    $atributos["validar"]="custom[number]";
    $atributos["categoria"]="";
    $atributos["deshabilitado"]=true;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="puntosHom";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Puntos Homologados";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    //$atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 180;
    $atributos["tamanno"]="10";
    $atributos["maximoTamanno"]="6";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"] = $registro[0]['asp_ptos_hom'];
    $atributos["validar"]="custom[number]";
    $atributos["categoria"]="";
    $atributos["deshabilitado"]=true;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    echo "</td>";
    echo "</tr>";
    echo "</table>";
        //------------------Division para los botones-------------------------
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);
    
   //-------------Control Boton-----------------------
    $esteCampo = "botonActualizar";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = ""; 
   //$atributos["estilo"]="jqueryui";
    $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
    //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos["tipoSubmit"]="jquery";
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["nombreFormulario"] = $nombreFormulario;
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
   
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
     $esteCampo="botonCancelar";
    $atributos["id"]=$esteCampo;
    $atributos["tabIndex"]=$tab++;
    $atributos["verificar"]="";
    $atributos["tipo"]="boton";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $atributos["cancelar"]=true;
    //$atributos["tipoSubmit"] = "jquery";
    //$atributos["onclick"]=true;
    $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
//-------------Fin Control Boton----------------------

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
    $atributos["id"] = "formSaraData"; //No cambiar este nombre
    $atributos["tipo"] = "hidden";
    $atributos["obligatorio"] = false;
    $atributos["etiqueta"] = "";
    $atributos["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    
//Fin del Formulario
echo $this->miFormulario->formulario("fin");

//-------------Fin de Conjunto de Controles----------------------------
echo $this->miFormulario->marcoAgrupacion("fin");

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");
echo "<div align='center'>";
    //Redireeciona al formulario de consulta por credencial
    $variable ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
    $variable.="&opcion=editarInscripcion";
    //$variable.="&action=".$esteBloque["nombre"];
    $variable.="&usuario=". $_REQUEST['usuario'];
    $variable.="&tipo=".$_REQUEST['tipo']."";
    $variable.="&bloque=".$esteBloque["id_bloque"];
    $variable.="&bloqueGrupo=".$esteBloque["grupo"];
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
    echo "<a href='".$variable."'>               
    Consultar otra credencial 
    </a>";
    echo "</div>";
