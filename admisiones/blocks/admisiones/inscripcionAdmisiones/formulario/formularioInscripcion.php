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

$conexion1="admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB1) {

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

$variable['evento']=$_REQUEST['evento'];
$cadena_sql = $this->sql->cadena_sql("consultarEventosRegistrados", $variable);
$registroEventosRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($cierto==1)
{
    $variable['rba_id']=$_REQUEST['rba_id'];

    $cadena_sql = $this->sql->cadena_sql("consultarInscripcionAcaspw", $variable);
    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $cadena_sql = $this->sql->cadena_sql("consultarInscripcionReingreso", $variable);
    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if(!is_array($registroInscripcion))
    { 
        $cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
        @$registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(isset($_REQUEST['carreras']))
        {    
            $variable['carrera']=$_REQUEST['carreras'];

            $cadena_sql = $this->sql->cadena_sql("consultarCarrera", $variable);
            $registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
        }
        //$valorCodificado="pagina=habilitarEvaluacion";
        $valorCodificado="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=verificaInscripcion";
        $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
        $valorCodificado.="&id_periodo=".$variable['id_periodo'];
        $valorCodificado.="&anio=".$variable['anio'];
        if(isset($_REQUEST['carreras']))
        {
            $valorCodificado.="&carreras=".$_REQUEST['carreras'];
        }
        $valorCodificado.="&evento=".$_REQUEST['evento'];
        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
        $valorCodificado.="&periodo=".$variable['periodo'];
        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
        $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);
               

        $tab = 1;

        //---------------Inicio Formulario (<form>)--------------------------------
        $atributos["id"] = $nombreFormulario;
        $atributos["tipoFormulario"] = "multipart/form-data";
        $atributos["metodo"] = "POST";
        $atributos["nombreFormulario"] = $nombreFormulario;
        $verificarFormulario = "1";
        echo $this->miFormulario->formulario("inicio", $atributos);
        unset($atributos);

        
        
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
        
        if($_REQUEST['evento']==1)
        { 
            //Formulario de inscripción para aspirantes a primer semestre.
            if(isset($_REQUEST['carreras']))
            {    
                
                //-------------Control Mensaje-----------------------
                $tipo = 'message';
                $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA INGRESO  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p>
                            CARRERA A LA QUE SE INSCRIBE:  ".$_REQUEST['carreras']." - ".$registroCarreras[0][1]." </center></H3> "
                        . " Los campos con * son obligatorios.";

                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "centrar";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $mensaje;
                echo $this->miFormulario->cuadroMensaje($atributos);
                unset($atributos);
                
                $variable['evento']=$_REQUEST['evento'];             

                $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
                $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
                $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                /*$atributos["id"] = "paso1";
                $atributos["leyenda"] = "Universidad";
                echo $this->miFormulario->agrupacion("inicio",$atributos);*/
                /*//Mensaje UNIVERSIDAD
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[0]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);*/

                $atributos["id"] = "marcoUniversidad";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[0]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "medio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[0]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("buscarMedio");
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "prestentaPor";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[1]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('1', 'Primera vez'),array('2', 'Segunda vez'),array('3', 'Tercera vez o más veces'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoInscripcion";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[2]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("buscartipInscripcion");
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //Nota tipo de inscripción
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                //$atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[1]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");

                //echo $this->miFormulario->agrupacion("fin");
                
                /*$atributos["id"] = "paso2";
                $atributos["leyenda"] = "Lugar y Fecha de Nacimiento";
                $atributos["estilo"] = "jqueryui";
                echo $this->miFormulario->agrupacion("inicio",$atributos);*/
                //LUGAR Y FECHA DE NACIMIENTO
                $atributos["id"] = "marcoAgrupacionLugarFechaNacimiento";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[2]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "pais";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[3]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('COLOMBIA', 'COLOMBIA'),array('EXTRANJERO', 'EXTRANJERO'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "departamento";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[4]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("gedepartamento");
                $atributos["baseDatos"] = "aspirantes";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //$variable['dep_cod']=$_REQUEST['elegido'];
                $variable['dep_cod']=11;
                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "municipio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 0;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[5]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('', 'Seleccione un municipio'));
                $atributos["baseDatos"] = "aspirantes";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Fecha Inicial------------------------------
                $esteCampo="fechaNac";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]="Fecha Nacimiento";
                $atributos["titulo"]="Haga click para seleccionar la fecha.";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["tamanno"]="20";
                $atributos["ancho"] = 350;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["deshabilitado"] = true;
                $atributos["tipo"]="";
                $atributos["estilo"]="jqueryui";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["validar"]="required";
                $atributos["categoria"]="fecha";
                $atributos["etiqueta"] =$registroPreguntas[27]['preg_nombre'];
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "sexo";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[6]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('M', 'Masculino'),array('F', 'Femenino'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "estadoCivil";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[7]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estadoCivil");
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="direccionResidencia";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Dirección de Residencia";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="75";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required";
                $atributos["etiqueta"] =$registroPreguntas[8]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "localidadResidencia";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[9]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //Mensaje localidad de residencia
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[3]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "estratoResidencia";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[10]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estrato",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "estratoCosteara";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[11]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estrato",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="telefono";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de teléfono";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="12";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0]";
                $atributos["etiqueta"] =$registroPreguntas[12]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="email";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Dirección de correo electrónico";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="75";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[email]";
                $atributos["etiqueta"] =$registroPreguntas[13]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");
                
                /*echo $this->miFormulario->agrupacion("fin");
                
                $atributos["id"] = "paso3";
                $atributos["leyenda"] = "Documento de Identidad y Grupo Sanguineo";
                echo $this->miFormulario->agrupacion("inicio",$atributos);*/
                
                //DOCUMENTO DE INDENTIDAD Y GRUPO SANGUINEO
                $atributos["id"] = "documentoGrupoSanguineo";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[4]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipDocActual";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "60px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[14]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("tipDocumento",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="documentoActual";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de documento actual";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[14]";
                $atributos["etiqueta"] =$registroPreguntas[15]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipDocIcfes";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "60px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] ="Tipo documento con el que presentó el ICFES";
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("tipDocumento",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="documentoIcfes";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de documento con el cuál presentó el ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[14]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[16]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoSangre";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "55px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[17]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('A', 'A'),array('B', 'B'),array('AB', 'AB'),array('O', 'O'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "rh";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "55px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[18]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('+', '+'),array('-', '-'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");

                /*echo $this->miFormulario->agrupacion("fin");
                
                $atributos["id"] = "paso4";
                $atributos["leyenda"] = "Registro Icfes y Colegio";
                echo $this->miFormulario->agrupacion("inicio",$atributos);*/
                
                //REGISTRO ICFES Y COLEGIO
                $atributos["id"] = "icfesColegio";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[5]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoIcfes";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[19]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('', 'Seleccione el Tipo de ICFES'),array('AC', 'AC'),array('VG', 'VG'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                $reg=0;
                if ($_REQUEST['carreras'] == 373) {
                    $reg=1;
                }
                elseif ($_REQUEST['carreras'] == 678) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 579) {
                    $reg=1;
                }
                elseif ($_REQUEST['carreras'] == 383) {
                    $reg=1;
                }   
                elseif ($_REQUEST['carreras'] == 372) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 375) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 377) {
                    $reg=1;
                }    
                else{
                    $reg=2;
                }                            

                //-------------Control cuadroTexto-----------------------
                $esteCampo="registroIcfes".$reg;
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de registro del ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["valor"] = "";
                $atributos["validar"]="required,min[0],minSize[14],maxSize[14],custom[noFirstNumber],custom[minLowerAlphaChars],custom[minNumberChars],custom[onlyLetterNumber]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="confirmarRegistroIcfes";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Confirme el número de registro del ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["valor"] = "";
                $atributos["validar"]="required,min[0],minSize[14],maxSize[14],custom[noFirstNumber],custom[minLowerAlphaChars],custom[minNumberChars],custom[onlyLetterNumber],equalsIcfes[registroIcfes".$reg."]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[21]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //Mensaje letras registro ICFES
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[6]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "localidadColegio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[22]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoColegio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[23]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('O', 'Oficial'),array('P', 'Privado'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "valido";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[24]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('NO', 'No'),array('SI', 'Si'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "numSemestres";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 0;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[25]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('0', 'Recién graduado'),array('1', '1 Semestre'),array('2', '2 Semestres'),array('3', '3 Semestres'),array('4', '4 Semestres'),array('5', '5 Semestres'),array('6', '6 Semestres'),array('7', 'Mas de tres años'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "discapacidad";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[26]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("discapacidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");
                
                
                /*$atributos["id"] = "paso5";
                $atributos["leyenda"] = "Registro Icfes y Colegio";*/
                
                //------------------Control Textarea------------------------------
                $esteCampo="observaciones";
                $atributos["id"]=$esteCampo;
                //$atributos["name"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                //$atributos["otroValor"]=$registroFormularios[$i][8];
                $atributos["titulo"]="Observaciones";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["columnas"]=110;
                $atributos["filas"]=2;
                $atributos["estiloArea"]="areaTexto";
                $atributos["estilo"]="jqueryui";
                $atributos["validar"]="required";
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

                //Mensaje que cuenta de manera regressiva el número de caracteres digitados en el campo textarea 
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = 'Solo puede digitar 100 caracteres, <span id="counter"></span>';
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                
                $cadena_sql = $this->sql->cadena_sql("buscarDocumentos", $variable);
                $registroDocumentos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                if(is_array($registroDocumentos))
                {    
                    //Cargar archivos
                    $atributos["id"] = "subirDocumentos";
                    $atributos["estilo"] = "jqueryui";
                    $atributos["leyenda"] = "Subir archivos";
                    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                    unset($atributos);
                    
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[8]['enc_nombre']));
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);

                    for($i=0; $i<=count($registroDocumentos)-1; $i++)
                    {
                        if($registroDocumentos[$i]['doc_nombre_corto']!='Carta condición especial, si la posee.')
                        {    
                            $esteCampo="subirArchivo".$i;
                            $atributos["id"]=$esteCampo;
                            //$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                            $atributos["titulo"]=$registroDocumentos[$i]['doc_nombre'];
                            $atributos["tabIndex"]=$tab++;
                            $atributos["obligatorio"]=true;
                            $atributos["etiquetaObligatorio"] = true;
                            $atributos["anchoEtiqueta"] = 250;
                            $atributos["tamanno"]="35";
                            $atributos["tipo"]="file";
                            $atributos["estilo"]="jqueryui";
                            $atributos["validar"]="required";
                            $atributos["etiqueta"]=$registroDocumentos[$i]['doc_nombre_corto'];
                            $atributos["categoria"]="";
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                        }
                        else
                        {
                            $esteCampo="subirArchivo".$i;
                            $atributos["id"]=$esteCampo;
                            //$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                            $atributos["titulo"]=$registroDocumentos[$i]['doc_nombre'];
                            $atributos["tabIndex"]=$tab++;
                            $atributos["obligatorio"]=true;
                            $atributos["etiquetaObligatorio"] = "";
                            $atributos["anchoEtiqueta"] = 250;
                            $atributos["tamanno"]="35";
                            $atributos["tipo"]="file";
                            $atributos["estilo"]="jqueryui";
                            $atributos["validar"]="";
                            $atributos["etiqueta"]=$registroDocumentos[$i]['doc_nombre_corto'];
                            $atributos["categoria"]="";
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                        }    
                    }
                    echo $this->miFormulario->marcoAGrupacion("fin");
                
                }    
            }   
        }
        //formulario de inscripción para transferencia interna y reintegros
        elseif($_REQUEST['evento']==2 || $_REQUEST['evento']==3)
        {
            //-------------Control Mensaje-----------------------
            $tipo = 'message';
            $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA ".strtoupper($registroEventosRegistrados[0]['des_nombre']).", ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p></H3>";

            $esteCampo = "mensaje";
            $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos);

            $variable['evento']=$_REQUEST['evento'];             

            $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
            $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
            $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            if(is_array($registroPreguntas))
            {    
                //Documento de identidad
                //-------------Control cuadroTexto-----------------------
                $esteCampo="documento";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de documento de identidad.";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="11";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[11]";
                $atributos["etiqueta"] =$registroPreguntas[0]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //Código de estudiante
                //-------------Control cuadroTexto-----------------------
                $esteCampo="codigoEstudiante";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Código del estudiante.";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="11";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[11]";
                $atributos["etiqueta"] =$registroPreguntas[1]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //Confirmar código de estudiante
                //-------------Control cuadroTexto-----------------------
                $esteCampo="confirmarCodigoEstudiante";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Confirmar el código del estudiante.";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="12";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[12],equalsDocumento[codigoEstudiante]";
                $atributos["etiqueta"] =$registroPreguntas[2]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="telefono";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de teléfono";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="12";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0]";
                $atributos["etiqueta"] =$registroPreguntas[5]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="email";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Dirección de correo electrónico";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="75";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[email]";
                $atributos["etiqueta"] =$registroPreguntas[6]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                if($_REQUEST['evento']==2)
                {
                    //------------------Control Lista Desplegable------------------------------
                    $esteCampo = "carreraCursando";
                    $atributos["id"] = $esteCampo;
                    $atributos["tabIndex"] = $tab++;
                    $atributos["seleccion"] = 1;
                    $atributos["evento"] = 2;
                    $atributos["columnas"] = "1";
                    $atributos["limitar"] = false;
                    $atributos["tamanno"] = 1;
                    $atributos["ancho"] = "250px";
                    $atributos["estilo"] = "jqueryui";
                    $atributos["etiquetaObligatorio"] = true;
                    $atributos["validar"] = "required";
                    $atributos["anchoEtiqueta"] = 300;
                    $atributos["obligatorio"] = true;
                    $atributos["etiqueta"] = $registroPreguntas[7]['preg_nombre'];
                    //-----De donde rescatar los datos ---------
                    $atributos["cadena_sql"] = $this->sql->cadena_sql("carrerasOfrecidas");
                    $atributos["baseDatos"] = "aspirantes";
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    //------------------Control Lista Desplegable------------------------------
                    $esteCampo = "carreraInscribe";
                    $atributos["id"] = $esteCampo;
                    $atributos["tabIndex"] = $tab++;
                    $atributos["seleccion"] = 2;
                    $atributos["evento"] = 2;
                    $atributos["columnas"] = "1";
                    $atributos["limitar"] = false;
                    $atributos["tamanno"] = 1;
                    $atributos["ancho"] = "250px";
                    $atributos["estilo"] = "jqueryui";
                    $atributos["etiquetaObligatorio"] = true;
                    $atributos["validar"] = "required";
                    $atributos["anchoEtiqueta"] = 300;
                    $atributos["obligatorio"] = true;
                    $atributos["etiqueta"] = $registroPreguntas[8]['preg_nombre']; 
                    //-----De donde rescatar los datos ---------
                    $atributos["cadena_sql"] = $this->sql->cadena_sql("carrerasOfrecidas");
                    $atributos["baseDatos"] = "aspirantes";
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);
                }
                //Canceló semestre
                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "cancelo";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[3]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('NO', 'No'),array('SI', 'Si'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Textarea------------------------------
                $esteCampo="motivo";
                $atributos["id"]=$esteCampo;
                //$atributos["name"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                //$atributos["otroValor"]=$registroFormularios[$i][8];
                $atributos["titulo"]="Observaciones";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["columnas"]=110;
                $atributos["filas"]=2;
                $atributos["estiloArea"]="areaTexto";
                $atributos["estilo"]="jqueryui";
                $atributos["validar"]="required";
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

                //Mensaje que cuenta de manera regressiva el número de caracteres digitados en el campo textarea 
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = 'Solo puede digitar 100 caracteres, <span id="counter"></span>';
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
            }
            else
            {
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " No hay preguntas registradas para el evento seleccionado";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
            }
        }
        elseif($_REQUEST['evento']==4)
        { 
            //Formulario de inscripción para aspirantes a primer semestre.
            if(isset($_REQUEST['carreras']))
            {    
                //-------------Control Mensaje-----------------------
                $tipo = 'message';
                $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA ".strtoupper($registroEventosRegistrados[0]['des_nombre']).",  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p>
                            CARRERA A LA QUE SE INSCRIBE:  ".$_REQUEST['carreras']." - ".$registroCarreras[0][1]." </center></H3>";

                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "centrar";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $mensaje;
                echo $this->miFormulario->cuadroMensaje($atributos);
                unset($atributos);
                
                $variable['evento']=$_REQUEST['evento'];             

                $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
                $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
                $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[7]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);
                {
                //-------------Control cuadroTexto-----------------------
                $esteCampo="universidadProviene";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Universidad de donde viene";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="76";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required";
                $atributos["etiqueta"] =$registroPreguntas[0]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="carreraVeniaCursando";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Carrera que venía cursando";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="76";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required";
                $atributos["etiqueta"] =$registroPreguntas[1]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="semestreCursado";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Último semestre cursado";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="2";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0]";
                $atributos["etiqueta"] =$registroPreguntas[2]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                
                //------------------Control Textarea------------------------------
                $esteCampo="motivoTransferencia";
                $atributos["id"]=$esteCampo;
                //$atributos["name"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                //$atributos["otroValor"]=$registroFormularios[$i][8];
                $atributos["titulo"]="motivoTransferencia";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["columnas"]=110;
                $atributos["filas"]=2;
                $atributos["estiloArea"]="areaTexto";
                $atributos["estilo"]="jqueryui";
                $atributos["validar"]="required";
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

                //Mensaje que cuenta de manera regresiva el número de caracteres digitados en el campo textarea 
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = 'Solo puede digitar 100 caracteres, <span id="counterMotivo"></span>';
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                }
                echo $this->miFormulario->marcoAGrupacion("fin");

                //LUGAR Y FECHA DE NACIMIENTO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[2]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "pais";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[3]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('COLOMBIA', 'COLOMBIA'),array('EXTRANGERO', 'EXTRANGERO'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "departamento";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[4]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("gedepartamento");
                $atributos["baseDatos"] = "aspirantes";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //$variable['dep_cod']=$_REQUEST['elegido'];
                $variable['dep_cod']=11;
                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "municipio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 0;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[5]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('', 'Seleccione un municipio'));
                $atributos["baseDatos"] = "aspirantes";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Fecha Inicial------------------------------
                $esteCampo="fechaNac";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]="Fecha Nacimiento";
                $atributos["titulo"]="Haga click para seleccionar la fecha.";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["tamanno"]="20";
                $atributos["ancho"] = 350;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["deshabilitado"] = true;
                $atributos["tipo"]="";
                $atributos["estilo"]="jqueryui";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["validar"]="required";
                $atributos["categoria"]="fecha";
                $atributos["etiqueta"] =$registroPreguntas[6]['preg_nombre'];
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "sexo";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[7]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('M', 'Masculino'),array('F', 'Femenino'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "estadoCivil";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[8]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estadoCivil");
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="direccionResidencia";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Dirección de Residencia";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="75";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required";
                $atributos["etiqueta"] =$registroPreguntas[9]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "localidadResidencia";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[10]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                $esteCampo = "estratoResidencia";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[11]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estrato",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="telefono";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de teléfono";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="12";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0]";
                $atributos["etiqueta"] =$registroPreguntas[12]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="email";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Dirección de correo electrónico";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="75";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[email]";
                $atributos["etiqueta"] =$registroPreguntas[13]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");

                //DOCUMENTO DE INDENTIDAD Y GRUPO SANGUINEO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[4]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipDocActual";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "60px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[14]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("tipDocumento",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="documentoActual";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de documento actual";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[14]";
                $atributos["etiqueta"] =$registroPreguntas[15]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipDocIcfes";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "60px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[14]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("tipDocumento",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="documentoIcfes";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de documento con el cuál presentó el ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 300;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required,custom[integer],min[0],minSize[4],maxSize[14]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[16]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoSangre";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "55px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[17]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('A', 'A'),array('B', 'B'),array('AB', 'AB'),array('O', 'O'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "rh";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "55px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 300;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[18]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('+', '+'),array('-', '-'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAGrupacion("fin");

                //REGISTRO ICFES Y COLEGIO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[5]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "tipoIcfes";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[19]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = array(array('', 'Seleccione el Tipo de ICFES'),array('AC', 'AC'),array('VG', 'VG'));
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                $reg=0;
                if ($_REQUEST['carreras'] == 373) {
                    $reg=1;
                }
                elseif ($_REQUEST['carreras'] == 678) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 579) {
                    $reg=1;
                }
                elseif ($_REQUEST['carreras'] == 383) {
                    $reg=1;
                }   
                elseif ($_REQUEST['carreras'] == 372) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 375) {
                    $reg=1;
                }    
                elseif ($_REQUEST['carreras'] == 377) {
                    $reg=1;
                }    
                else{
                    $reg=2;
                }                            

                //-------------Control cuadroTexto-----------------------
                $esteCampo="registroIcfes".$reg;
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Número de registro del ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["valor"] = "";
                $atributos["validar"]="required,min[0],minSize[12],maxSize[14],custom[noFirstNumber],custom[minLowerAlphaChars],custom[minNumberChars],custom[onlyLetterNumber]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[20]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //-------------Control cuadroTexto-----------------------
                $esteCampo="confirmarRegistroIcfes";
                $atributos["id"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                $atributos["titulo"]="Confirme el número de registro del ICFES o SABER-PRO 11";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["anchoEtiqueta"] = 250;
                $atributos["tamanno"]="38";
                $atributos["maximoTamanno"]="14";
                $atributos["tipo"]="text";
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["valor"] = "";
                $atributos["validar"]="required,min[0],minSize[12],maxSize[14],custom[noFirstNumber],custom[minLowerAlphaChars],custom[minNumberChars],custom[onlyLetterNumber],equalsIcfes[registroIcfes".$reg."]";
                //$atributos["validar"]="required,number";
                $atributos["etiqueta"] =$registroPreguntas[21]['preg_nombre'];
                $atributos["categoria"]="";
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                //Mensaje letras registro ICFES
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[6]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

                //------------------Control Lista Desplegable------------------------------
                $esteCampo = "localidadColegio";
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "250px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = true;
                $atributos["validar"] = "required";
                $atributos["anchoEtiqueta"] = 250;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] =$registroPreguntas[22]['preg_nombre'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                

                echo $this->miFormulario->marcoAGrupacion("fin");

                //------------------Control Textarea------------------------------
                $esteCampo="observaciones";
                $atributos["id"]=$esteCampo;
                //$atributos["name"]=$esteCampo;
                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                //$atributos["otroValor"]=$registroFormularios[$i][8];
                $atributos["titulo"]="Observaciones";
                $atributos["tabIndex"]=$tab++;
                $atributos["obligatorio"]=true;
                $atributos["etiquetaObligatorio"] = true;
                $atributos["columnas"]=110;
                $atributos["filas"]=2;
                $atributos["estiloArea"]="areaTexto";
                $atributos["estilo"]="jqueryui";
                $atributos["validar"]="required";
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

                //Mensaje que cuenta de manera regressiva el número de caracteres digitados en el campo textarea 
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = 'Solo puede digitar 100 caracteres, <span id="counter"></span>';
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

             }
        }
        //------------------Division para los botones-------------------------
        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones";
        echo $this->miFormulario->division("inicio",$atributos);
       //-------------Control Boton-----------------------
        $esteCampo = "botonContinuar";
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
       // $atributos["tipoSubmit"] = "jquery";
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

       //$tab = 2;

        //echo $this->miFormulario->division("fin");
    }
    else
    {
        $tipo = 'information';
        $mensaje = "Ya existe una inscripción con los datos que ingresó al sistema, si tiene alguna inquietud, favor acercarse a la Oficina de Admisiones ubicada en la carrera 8 No 40-62, primer piso, Edificio Sabio Caldas, telefonos 3239300 Ext. 1102, o remitir su inquietud al correo electrónico admisiones@udistrital.edu.co, dentro de los plazos asignados en el proceso de admisiones. ";
        
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
    }    
}
else
{
    $nombreFormulario=$esteBloque["nombre"];

    include_once("core/crypto/Encriptador.class.php");
    $cripto=Encriptador::singleton();
    $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

    $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
    $tab=1;
    //---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"]=$nombreFormulario;
    $atributos["tipoFormulario"]="multipart/form-data";
    $atributos["metodo"]="POST";
    $atributos["nombreFormulario"]=$nombreFormulario;
    $verificarFormulario="1";
    echo $this->miFormulario->formulario("inicio",$atributos);

	$atributos["id"]="divErrores";
	$atributos["estilo"]="marcoBotones";
        //$atributos["estiloEnLinea"]="display:none"; 
	echo $this->miFormulario->division("inicio",$atributos);
	
	
            $tipo = 'information';
            $mensaje = 'No se encontrararon períodos académicos activos, para activar o registrar un periodo académico activo haga click en "Continuar"...';
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso']; 
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	
	
	$esteCampo = "botonContinuar";
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos); 
        
        //------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
        
        //------------------Division para los botones-------------------------
	$atributos["id"]="botones";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control Boton-----------------------
	$esteCampo ="botonContinuar" ;
	$atributos["id"]=$esteCampo;
	$atributos["tabIndex"]=$tab++;
	$atributos["tipo"]="boton";
	$atributos["estilo"]="jquery";
	$atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
	$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
	$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
	$atributos["nombreFormulario"]=$nombreFormulario;
	echo $this->miFormulario->campoBoton($atributos);
	unset($atributos);
	//-------------Fin Control Boton----------------------
	
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
    
	//-------------Control cuadroTexto con campos ocultos-----------------------
	//Para pasar variables entre formularios o enviar datos para validar sesiones
	$atributos["id"]="formSaraData"; //No cambiar este nombre
	$atributos["tipo"]="hidden";
	$atributos["obligatorio"]=false;
	$atributos["etiqueta"]="";
	$atributos["valor"]=$valorCodificado;
	echo $this->miFormulario->campoCuadroTexto($atributos);
	unset($atributos);
	
        //Fin del Formulario
        
        echo $this->miFormulario->formulario("fin");
        echo $this->miFormulario->division("fin");

}
