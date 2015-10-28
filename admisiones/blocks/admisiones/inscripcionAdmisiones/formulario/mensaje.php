<?PHP
if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}else
{

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

$nombreFormulario=$esteBloque["nombre"];

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

$miPaginactual=$this->miConfigurador->getVariableConfiguracion("pagina");

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
	
	if($_REQUEST['mensaje'] == 'error')
	{
            $tipo = 'error';
            $mensaje = "Ya existe un registro con el periodo académico que intenta guardar.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
        if($_REQUEST['mensaje'] == 'aceptacion')
	{
            $conexion = "admisiones";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

            if (!$esteRecursoDB) {

                echo "Este se considera un error fatal";
                exit;
            }
            
            $variable['sesionId']=$miSesion->getsesionId();
            $cadena_sql = $this->sql->cadena_sql("buscarSesion", $variable);
            $registroSesion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
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
                
                $variable['rba_id']=$registroSesion[0]['valor'];

                $cadena_sql = $this->sql->cadena_sql("consultarInscripcionAcaspw", $variable);
                $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                if(!is_array($registroInscripcion))
                {    
                    $cadena_sql = $this->sql->cadena_sql("consultarInscripcionReingreso", $variable);
                    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    
                    if(!is_array($registroInscripcion))
                    {
                        $cadena_sql = $this->sql->cadena_sql("consultarInscripcionTransferencia", $variable);
                        $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    }   
                }
                
                $tipo = 'message';
                $mensaje = "<h1>PROCESO DE ADMISIONES ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</h1><br>";
                $mensaje.="<fieldset style='margin:3; padding:20px; font-size:12pt; width:85%;'><p align='justify' >Por medio del presente manifiesto que conozco y he leido el instructivo Oficial de 
                    Admisiones del " .$periodo. " PERÍODO ACADÉMICO DEL " .$variable['anio']. " y acorde con la ley 1581 de 2012, autorizo 
                    de manera expresa e inequívoca, que mis datos personales sean tratados conforme a las funciones 
                    propias de la Universidad, en su condición de Institución de Educación Superior.</p></fieldset>";
                if(!is_array($registroInscripcion))
                {
                    $boton = "regresar";
                    $valorCodificado="&opcion=seleccionarInscripcion"; 
                    $valorCodificado.="&tipo=".$_REQUEST["tipo"];
                    $valorCodificado.="&rba_id=".$registroSesion[0]['valor'];
                    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                    $valorCodificado=$cripto->codificar($valorCodificado);
                }
                else
                {
                    $boton = "regresar";
                    $valorCodificado="&opcion=verInscripcion"; 
                    $valorCodificado.="&tipo=".$_REQUEST["tipo"];
                    $valorCodificado.="&rba_id=".$registroSesion[0]['valor'];
                    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                    $valorCodificado=$cripto->codificar($valorCodificado);
                }
            }    
        }
        if($_REQUEST['mensaje']=='fechasEventos')
        {
            $conexion = "admisiones";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
            
            if (!$esteRecursoDB) {

                echo "Este se considera un error fatal";
                exit;
            }
            
            $variable['sesionId']=$miSesion->getsesionId();
            $cadena_sql = $this->sql->cadena_sql("buscarSesion", $variable);
            $registroSesion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            $variable['id_periodo']=$_REQUEST['id_periodo'];
            $variable['evento']=$_REQUEST['evento'];
            $cadena_sql = $this->sql->cadena_sql("consultarEventosInscripcion", $variable);
            @$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            $nombreEvento=strtoupper($registro[0][2]);
            if(is_array($registro))
            {
                 $tipo = 'information';
                $mensaje = "Las fechas para ".$nombreEvento." se encuentran cerradas.";
                $boton = "regresar";

                $valorCodificado="&opcion=seleccionarInscripcion"; 
                $valorCodificado.="&tipo=".$_REQUEST['tipo'];
                $valorCodificado.="&evento=".$_REQUEST['evento'];
                $valorCodificado.="&rba_id=".$registroSesion[0]['valor'];
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$cripto->codificar($valorCodificado);
            }    
            else
            {
                $tipo = 'information';
                $mensaje = "No hay fechas habilitadas para el evento seleccionado.";
                $boton = "regresar";

                $valorCodificado="&opcion=seleccionarInscripcion"; 
                $valorCodificado.="&tipo=".$_REQUEST['tipo'];
                $valorCodificado.="&evento=".$_REQUEST['evento'];
                $valorCodificado.="&rba_id=".$registroSesion[0]['valor'];
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$cripto->codificar($valorCodificado);
            }    
        }
        
        if($_REQUEST['mensaje']=='tituloTecnologo')
        {
            $tipo = 'information';
            $mensaje = "Esta carrera requiere título de tecnólogo. En el evento de no acreditar el título favor consultar los requisitos de inscripción de la facultad tecnológica, programas de ciclo profesional de ingeniería. ¿Desea continuar?";
            $boton = "regresar";
            
            $valorCodificado="&opcion=consultarCarreras";
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&action=".$esteBloque["nombre"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
            $valorCodificado.="&evento=".$_REQUEST['evento'];
            $valorCodificado.="&redireccion=formularioInscripcion";
            $valorCodificado.="&carreras=".$_REQUEST['carreras'];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje']=='mensajeExiste')
        {
            $conexion = "admisiones";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

            if (!$esteRecursoDB) {

                echo "Este se considera un error fatal";
                exit;
            }
            
            $variable['sesionId']=$miSesion->getsesionId();
            $cadena_sql = $this->sql->cadena_sql("buscarSesion", $variable);
            $registroSesion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            $tipo = 'information';
            $mensaje = "Ya existe una inscripción con los datos que intenta registrar.";
            $boton = "regresar";
            
            $valorCodificado="&opcion=verInscripcion"; 
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&rba_id=".$registroSesion[0]['valor'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
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
    if($_REQUEST['mensaje']=='tituloTecnologo')
    {       
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
    }	
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

?>