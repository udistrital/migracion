<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/habilitarProcesoEvaldocente/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
//$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarTipoPregunta", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];

$cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
$registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroPeriodo))
{
    $valorCodificado="pagina=armarFormularios";
    $valorCodificado.="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarPreguntas";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionPreguntas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Preguntas Evaluación Docente";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
    unset($atributos);

    ////-------------------------------Mensaje-------------------------------------
    /*$esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = "information";
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
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


        //-------------Control Mensaje-----------------------
        $esteCampo = "periodoAcademico";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "";
        $atributos["tipo"] = "message";
        $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->cuadroMensaje($atributos);

        //------------------Control Lista Desplegable------------------------------
        $esteCampo = "tipoPregunta";
        $atributos["id"] = $esteCampo;
        $atributos["tabIndex"] = $tab++;
        $atributos["seleccion"] = 0;
        $atributos["evento"] = 2;
        $atributos["columnas"] = "1";
        $atributos["limitar"] = false;
        $atributos["tamanno"] = 1;
        $atributos["ancho"] = "150px";
        $atributos["estilo"] = "jqueryui";
        $atributos["etiquetaObligatorio"] = true;
        $atributos["validar"] = "required";
        $atributos["anchoEtiqueta"] = 125;
        $atributos["obligatorio"] = true;
        $atributos["etiqueta"] = "Tipo de Pregunta: ";
        //-----De donde rescatar los datos ---------
        $atributos["cadena_sql"] = $this->sql->cadena_sql("consultarTipoPregunta");
        $atributos["baseDatos"] = "evaldocentes";
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);

        //-------------Control cuadroTexto-----------------------
        $esteCampo="valorPregunta";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Valor de la prgunta. Ej. si es de selección, la mejos es 5 y la menor es 0, se debe digitar 5";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 125;
        $atributos["tamanno"]="5";
        $atributos["tipo"]="text";
        $atributos["estilo"]="jqueryui";
        $atributos["validar"]="required";
        //$atributos["validar"]="required, min[6]";
        $atributos["categoria"]="";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        //-------------Control Textarea-----------------------
        $esteCampo="pregunta";
        $atributos["id"]=$esteCampo;
        //$atributos["name"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Descripción";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["columnas"]=110;
        $atributos["filas"]=4;
        $atributos["estiloArea"]="areaTexto";
        $atributos["estilo"]="jqueryui";
        $atributos["validar"]="required";
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);


        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones";
        echo $this->miFormulario->division("inicio",$atributos);   

        $esteCampo = "botonGuardar";
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
        $atributos["tipoSubmit"] = "jquery";
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
        echo $this->miFormulario->formulario("fin");
        echo $this->miFormulario->marcoAGrupacion("fin");
        echo $this->miFormulario->division("fin");


    echo "Preguntas registradas en el sistema:<hr />";

    if($registroPreguntas)
    {	
            echo "<table id='tablaFormatos'>";

            echo "<thead>
                    <tr>
                        <th>Pregunta</th>
                        <th>Periodo académico</th>
                        <th>Tipo de pregunta</th>
                        <th>Valor Máximo de la pregunta</th>
                        <th>Estado</th>
                        <th>Editar</th>
                   </tr>
                </thead>
                <tbody>";

            for($i=0;$i<count($registroPreguntas);$i++)
            {
                $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                $variable.="&opcion=editarPreguntas";
                $variable.="&usuario=". $_REQUEST['usuario'];
                $variable.="&preguntaId=".$registroPreguntas[$i][0];
                $variable.="&tipo=".$_REQUEST['tipo'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                echo "<tr>
                        <td>".$registroPreguntas[$i][6]."</td>
                        <td align='center'>".$registroPreguntas[$i][2]."-".$registroPreguntas[$i][3]."</td>
                        <td align='center'>".$registroPreguntas[$i][5]."</td>
                        <td align='center'>".$registroPreguntas[$i][7]."</td>
                        <td align='center'>".$registroPreguntas[$i][8]."</td>    
                        <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>
                    </tr>";
                unset($variable);
            }

            echo "</tbody>";

            echo "</table>";	

    }else
    {
            $atributos["id"]="divNoEncontroRegistro";
            $atributos["estilo"]="marcoBotones";
            //$atributos["estiloEnLinea"]="display:none"; 
            echo $this->miFormulario->division("inicio",$atributos);

            //-------------Control Boton-----------------------
            $esteCampo = "noEncontroRegistro";
            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = 'error';
            $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
            echo $this->miFormulario->cuadroMensaje($atributos);
             unset($atributos); 
            //-------------Fin Control Boton----------------------

            //------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
    }

    //echo $this->miFormulario->division("fin");
}
else
{
    $nombreFormulario="habilitarProcesoEvaldocente";

    include_once("core/crypto/Encriptador.class.php");
    $cripto=Encriptador::singleton();
    $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

    $miPaginaActual="habilitarEvaluacion";
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
            $mensaje = 'No se encontrararon períodos académicos activos, para activar o registrar un periodo académico activo, diríjase al menú, haga click en "Habilitar Proceso", luego en "Habilitar Periodo".';
            $boton = "regresar";
                        
            $valorCodificado="pagina=habilitarEvaluacion";
            $valorCodificado.="&opcion=nuevo"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso']; 
            $valorCodificado.="&bloque=habilitarProcesoEvaldocente";
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
	/*$esteCampo ="botonContinuar" ;
	$atributos["id"]=$esteCampo;
	$atributos["tabIndex"]=$tab++;
	$atributos["tipo"]="boton";
	$atributos["estilo"]="jquery";
	$atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
	$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
	$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
	$atributos["nombreFormulario"]=$nombreFormulario;
	echo $this->miFormulario->campoBoton($atributos);
	unset($atributos);*/
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
}    





