<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/habilitarProcesoEvaldocente/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

unset($variable);

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroPeriodo))
{
    $variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
    

    $cadena_sql = $this->sql->cadena_sql("consultarFormatos", $variable);
    $registroFormatos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    //$valorCodificado="pagina=armarFormularios";
    $valorCodificado="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarFormatos";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionFormatos";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Formatos Evaluación Docente";
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
        $esteCampo = "informacion";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "";
        $atributos["tipo"] = "message";
        $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);

        //------------------Control Lista Desplegable------------------------------
        $esteCampo = "tipoEvaluacion";
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
        $atributos["etiqueta"] = "Tipo de Evaluación: ";
        //-----De donde rescatar los datos ---------
        $atributos["cadena_sql"] = $this->sql->cadena_sql("consultarTipoEvaluacion");
        $atributos["baseDatos"] = "evaldocentes";
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        
        //-------------Control cuadroTexto-----------------------
        $esteCampo="formatoNumero";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Nombre del formato";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 125;
        $atributos["tamanno"]="5";
        $atributos["maximoTamanno"]="4";
        $atributos["tipo"]="text";
        $atributos["estilo"]="jqueryui";
        $atributos["obligatorio"] = true;
        $atributos["validar"]="required,custom[integer]";
        //$atributos["validar"]="required,number";
        $atributos["categoria"]="";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        //-------------Control cuadroTexto-----------------------
        $esteCampo="porcentaje";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Porcentaje ";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 125;
        $atributos["tamanno"]="5";
        $atributos["maximoTamanno"]="3";
        $atributos["tipo"]="text";
        $atributos["estilo"]="jqueryui";
        $atributos["obligatorio"] = true;
        $atributos["validar"]="required,custom[integer],max[100],min[0]";
        $atributos["categoria"]="";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        //-------------Control Textarea-----------------------
        $esteCampo="descripcion";
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
        $atributos["obligatorio"] = true;
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

        //-------------Fin de Conjunto de Controles----------------------------
        echo $this->miFormulario->marcoAgrupacion("fin");

        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");


    echo "Formatos registrados en el sistema:<hr />";

    if($registroFormatos)
    {	
            echo "<table id='tablaFormatos'>";

            echo "<thead>
                    <tr>
                        <th>Tipo de Evalación</th>
                        <th>Formato No.</th>
                        <th>Descripción</th>
                        <th>Porcentaje</th>
                        <th>Periodo académico</th>
                        <th>Estado</th>
                        <th>Editar</th>
                   </tr>
                </thead>
                <tbody>";

            for($i=0;$i<count($registroFormatos);$i++)
            {
                unset($variable);
                $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                $variable.="&opcion=editarFormatos";
                $variable.="&usuario=". $_REQUEST['usuario'];
                $variable.="&formatoNumero=".$registroFormatos[$i][2];
                $variable.="&tipoEvaluacion=".$registroFormatos[$i][7];
                $variable.="&periodo=".$registroFormatos[$i][1];
                $variable.="&tipo=".$_REQUEST['tipo'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                echo "<tr>
                        <td align='center'>".$registroFormatos[$i][6]."</td>
                        <td align='center'>".$registroFormatos[$i][2]."</td>
                        <td>".$registroFormatos[$i][3]."</td>
                        <td align='center'>".$registroFormatos[$i][4]."</td>
                        <td align='center'>".$registroFormatos[$i][8]."-".$registroFormatos[$i][9]."</td>    
                        <td align='center'>".$registroFormatos[$i][5]."</td>    
                        <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>
                    </tr>";
                //unset($variable);
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


