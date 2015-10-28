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

$conexion2="aspirantes";
$esteRecursoDB2 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion2);
if (!$esteRecursoDB2) {

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

if($cierto==1)
{
    $cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
    @$registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $variable['evento']=$_REQUEST['evento'];
    $cadena_sql = $this->sql->cadena_sql("consultarEventosRegistrados", $variable);
    $registroEventosRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
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
        
        
        if($_REQUEST['evento']==2 || $_REQUEST['evento']==3)
        {    
            //-------------Control Mensaje-----------------------
           $tipo = 'message';
           $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA ".strtoupper($registroEventosRegistrados[0]['des_nombre']).",  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p>
                       Revise cuidadosamente la información consignada. Si es correcta la información haga click en 'GUARDAR', de lo contrario haga click en 'Cancelar'.</center></H3>";

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
           
           if(isset($_REQUEST['carreraCursando']))
           {
                $variable['carrera']=$_REQUEST['carreraCursando'];
                 
                $cadena_sql = $this->sql->cadena_sql("consultarCarrera", $variable);
                $registroCarreraCursando = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
           }
           if(isset($_REQUEST['carreraInscribe']))
           {
                $variable['carrera']=$_REQUEST['carreraInscribe'];
    
                $cadena_sql = $this->sql->cadena_sql("consultarCarrera", $variable);
                $registroCarreraInscribe = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
           }    

            $campovacio=0;
            foreach ($_REQUEST as $clave => $valor) {
                //echo $clave ."=>". $valor."<br>";
                if($valor=='' && $clave!='true' && $clave!='motivo')
                {
                    $mensaje="El campo ".$clave." está vacío, intente nuevamente.";
                    $html="<script>alert('".$mensaje."');</script>";
                    echo $html;
                    echo "Redireccionando.";
                   // $indice = $registro[0][10];
                    echo "<script>location.replace('')</script>";
                }
                if($clave=='codigoEstudiante')
                {    
                    if($_REQUEST['codigoEstudiante'] != $_REQUEST['confirmarCodigoEstudiante'])
                    {
                        $mensaje="El código del estudiante no coincide.";
                        $html="<script>alert('".$mensaje."');</script>";
                        echo $html;
                        echo "Redireccionando.";
                       // $indice = $registro[0][10];
                        echo "<script>location.replace('')</script>";
                    }
                    else
                    {
                        $registroIcfes=$valor;
                    }
                }
            }

            //$valorCodificado="pagina=habilitarEvaluacion";
            $valorCodificado="&action=".$esteBloque["nombre"];
            $valorCodificado.="&opcion=guardarInscripcion";
            $valorCodificado.="&usuario=".$_REQUEST['usuario'];
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
            $valorCodificado.="&id_periodo=".$variable['id_periodo'];
            $valorCodificado.="&anio=".$variable['anio'];
            $valorCodificado.="&documento=".$_REQUEST['documento'];
            $valorCodificado.="&codigoEstudiante=".$_REQUEST['codigoEstudiante'];
            $valorCodificado.="&confirmarCodigoEstudiante=".$_REQUEST['confirmarCodigoEstudiante'];
            $valorCodificado.="&cancelo=".$_REQUEST['cancelo'];
            $valorCodificado.="&telefono=".$_REQUEST['telefono'];
            $valorCodificado.="&email=".$_REQUEST['email'];
            if($_REQUEST['evento']==2)
            {    
                $valorCodificado.="&carreraCursando=".$_REQUEST['carreraCursando'];
                $valorCodificado.="&carreraInscribe=".$_REQUEST['carreraInscribe'];
            }
            $valorCodificado.="&motivo=".$_REQUEST['motivo'];
            $valorCodificado.="&evento=".$_REQUEST['evento'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&periodo=".$variable['periodo'];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

            $atributos["id"] = "marcoAgrupacionFechas";
            $atributos["estilo"] = "jqueryui";
            //$atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[0]['enc_nombre']));
            echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
            unset($atributos);

            echo "<table border='0' width='100%'>";
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //Documento de identidad
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[0]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                 //Documento de identidad
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['documento']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //código del estudiante
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[1]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                  //código del estudiante
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['codigoEstudiante']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //teléfono
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[5]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                   //teléfono
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['telefono']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //correo
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[6]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                   //correo
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['email']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            
            if($_REQUEST['evento']==2)
            {    
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Carrera que venía cursando
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[7]['preg_nombre'].":";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                    echo "<td>";
                    //Carrera que venía cursando
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['carreraCursando']." ".$registroCarreraCursando[0][1]."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Carrera que se inscribe
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[8]['preg_nombre'].":";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                    echo "<td>";
                     //Carrera que se inscribe
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['carreraInscribe']." ".$registroCarreraInscribe[0][1]."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
            }
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //Canceló semestre
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[3]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                  //Canceló semestre
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['cancelo']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Observaciones
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Motivo del retiro :";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
                echo "<td width='50%'>";
                //Observaciones
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$_REQUEST['motivo']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
            echo "</tr>";
            echo "</table>";

            echo $this->miFormulario->marcoAGrupacion("fin");

            //Mensaje que cuenta de manera regressiva el número de caracteres digitados en el campo textarea 
            $esteCampo = "mensaje";
            $atributos["id"] = "mensaje"; 
            $atributos["etiqueta"] = "";
            $atributos ["tamanno"]="pequenno";
            $atributos["estilo"] = "campoCuadroTexto";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = 'Los datos consignados en el formulario serán guardados bajo la gravedad del juramento, y en el momento de guardar y enviar la información equivale a la firma de la inscripción.<br><br> <center>¿ESTÁ SEGURO DE GUARDAR ESTA INFORMACIÓN?</center></span>';
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);

         }

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
