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

    echo "Este se considera un error fatal";
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

$variable['tipoInstructivo']="instructivo";

$cadena_sql = $this->sql->cadena_sql("buscarNombreInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
	
echo "<table align='center' border='1'>";
echo "<tbody>";
echo "<tr>";
echo "<td>&nbsp;</td>";
for($i=0;$i<count($registro);$i++)
{
    $variable ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
    $variable.="&opcion=instructivo";
    $variable.="&seccion=".$registro[$i][1];
    $variable.="&usuario=". $_REQUEST['usuario'];
    $variable.="&tipo=".$_REQUEST['tipo']."";
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

    echo"<td bgcolor='LemonChiffon' align='center' valign='midle'>
        <a href='".$variable."'>".$registro[$i][1]."</a> 
        </td>";
}
echo "</tr>";    
echo "</tbody>";
echo "</table>";	

if(isset($_REQUEST['seccion']))
{   
    unset($variable);
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
    //var_dump($_REQUEST);
    
    $variable['seccion']=$_REQUEST['seccion'];
    $variable['tipoInstructivo']="'instructivo','inicial'";
    $cadena_sql = $this->sql->cadena_sql("buscarContenidoInstructivo", $variable);
    $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $valorCodificado="pagina=administracion";
    $valorCodificado.="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarInstructivo";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&id_periodo=".$variable['id_periodo'];
    $valorCodificado.="&insNombre=".$registroContenido[0][1];
    $valorCodificado.="&seccion=".$variable['seccion'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Actualizar instructivo: ".$registroContenido[0][1];
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
    $mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ACTUALIZAR EL INSTRUCTIVO PARA LA EVALUACIÓN DOCENTE PARA EL PERIODO ACADÉMICO: ".$variable['anio']."-".$variable['periodo'].".<br>";

    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    //-------------Control cuadroTextArea-----------------------
    $esteCampo="instructivo";
    $atributos["id"]="instructivo";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=false;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["columnas"]=125;
    $atributos["filas"]=35;
    $atributos["valor"]=$registroContenido[0][2];
    $atributos["estilo"]="jqueryui";
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
    //$atributos["tipoSubmit"] = "jquery";
    //$atributos["onclick"]=true;
    $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
    //-------------Fin Control Boton----------------------

    echo $this->miFormulario->division("fin");  

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
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
    //echo $this->miFormulario->division("fin");
}
else
{
    $tipo = 'message';
    $mensaje = "Haga click en el menú con las secciones que hacen parte del instructivo de admisiones, para editar el contenido del instructivo.";

    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    ?><center><img src='<?php echo $rutaBloque."formulario/ambiente.jpeg"?>'></center><?
}    


