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

if($cierto==1)
{
    $cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
    @$registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    //$valorCodificado="pagina=habilitarEvaluacion";
    $valorCodificado="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarEventos";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&id_periodo=".$variable['id_periodo'];
    $valorCodificado.="&anio=".$variable['anio'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&periodo=".$variable['periodo'];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    //-------------Fin de Conjunto de Controles----------------------------
    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Eventos";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
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
        
        
         //-------------Control Mensaje-----------------------
        $tipo = 'message';
        $mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ REGISTRAR, ABRIR O CERRAR LAS FECHAS DE LOS EVENTOS QUE HACEN PARTE DEL PROCESO DE ADMISIONES,
            PERIODO ACADÉMICO ".$variable['anio']."-".$variable['periodo'].".";


        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
        
        //------------------Control Lista Desplegable------------------------------
        $esteCampo = "evento";
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
        $atributos["etiqueta"] = "Evento: ";
        //-----De donde rescatar los datos ---------
        $atributos["cadena_sql"] = $this->sql->cadena_sql("consultarDesEventos");
        $atributos["baseDatos"] = "admisiones";
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);

        //------------------Fecha Inicial------------------------------
        $esteCampo="fechaIni";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]="Fecha Inicial";
        //$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
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
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $esteCampo="fechaFin";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]="Fecha Final";
        //$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
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
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);


        //------------------Division para los botones-------------------------
        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones";
        echo $this->miFormulario->division("inicio",$atributos);
       //-------------Control Boton-----------------------
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

    //$tab = 2;


    echo "Listado de eventos por carrera:<hr />";
        if(is_array($registroEventos))
        {	
            echo "<table id='tablaEventos'>";

            echo "<thead>
                    <tr>
                        <th>Cod. Evento</th>
                        <th>Evento</th>
                        <th>Fecha Inicial</th>
                        <th>Fecha Final</th>
                   </tr>
                </thead>
                <tbody>";
            echo count($registroEventos)."<br>";     
            for($i=0;$i<count($registroEventos);$i++)
            {
                echo "<tr>
                        <td align='center'>".$registroEventos[$i][0]."</td>
                        <td>".$registroEventos[$i][2]."</td>
                        <td align='center'>".$registroEventos[$i][3]."</td>
                        <td align='center'>".$registroEventos[$i][4]."</td>
                    </tr>";
                
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

    //------------------Fin Division para las pestañas-------------------------
    //echo $this->miFormulario->division("fin");
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

}
