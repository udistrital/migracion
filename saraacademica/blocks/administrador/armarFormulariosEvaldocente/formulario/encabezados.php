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

    echo "//Este se considera un error fatal";
    exit;
}

//$cadena_sql = $this->sql->cadena_sql("consultarEncabezados", "");
//$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=armarFormularios";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardarEncabezados";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionEncabrzados";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Encabezados de los formatos de Evaluación Docente";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$esteCampo = "informacionEncabezados";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "";
$atributos["tipo"] = "message";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);

//

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);

//-------------Control cuadroTextArea-----------------------
    $esteCampo='encabezado';
    $atributos["id"]=$esteCampo;
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=false;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["columnas"]=110;
    $atributos["filas"]=14;
    //$atributos["valor"]=$registro[0]['instructivo_texto'];
    $atributos["estilo"]="jqueryui";
    echo $this->miFormulario->campoTextArea($atributos);
    unset($atributos);
 
   
    //------------------Division para los botones-------------------------
    $atributos["id"] = "botones";
    $atributos["estilo"] = "marcoBotones";
    echo $this->miFormulario->division("inicio", $atributos);
    
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
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");



echo "Encabezados registrados en el sistema:<hr />";

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscaEncabezados", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registro))
{	
        echo "<table width='100%' id='tablaEncabezados'>";
        
        echo "<thead>
                <tr>
                    <th>Id</th>
                    <th>Encabezado</th>
                    <th>Editar</th>
               </tr>
            </thead>
            <tbody>";
        
        for($i=0;$i<count($registro);$i++)
        {
            $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
            $variable.="&opcion=editarEncabezados";
            $variable.="&usuario=". $_REQUEST['usuario'];
            $variable.="&id=".$registro[$i][0];
            $variable.="&encabezado=".$registro[$i][1];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            
            echo "<tr>
                    <td align='center'>".$registro[$i][0]."</td>
                    <td>".$registro[$i][1]."</td>
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



