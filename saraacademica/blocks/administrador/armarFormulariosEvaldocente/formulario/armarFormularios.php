<?php

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/armarFormulariosEvaldocente/";
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

$variable['formatoNumero'] = $_REQUEST['formatoNumero'];
$variable['periodo'] = $_REQUEST['periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarFormatosEditar", $variable);
$registroFormato = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['formatoId']=$_REQUEST['formatoId'];
$cadena_sql = $this->sql->cadena_sql("consultarFormularios", $variable);
$registroFormularios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$cuenta = count($registroFormularios);

$valorCodificado = "pagina=armarFormularios";
$valorCodificado.="&action=" . $esteBloque["nombre"];
$valorCodificado.="&opcion=guardarFormulario";
$valorCodificado.="&usuario=" . $_REQUEST['usuario'];
$valorCodificado.="&formatoNumero=" . $_REQUEST['formatoNumero'];
$valorCodificado.="&formatoId=" . $_REQUEST['formatoId'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionPreguntas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Armar formularios";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);


////-------------------------------Mensaje-------------------------------------
$tipo = 'information';
$mensaje = "" . $registroFormato[0][2] . "";

$esteCampo = $mensaje;
$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "justificar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
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


//------------------Control Lista Desplegable------------------------------
$esteCampo = "encabezados";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = -1;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "325px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 125;
//$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Encabezados: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("buscaEncabezados");
$atributos["baseDatos"] = "evaldocentes";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//------------------Control Lista Desplegable------------------------------
$esteCampo = "preguntas";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = -1;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "325px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 125;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Preguntas: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("Preguntas");
$atributos["baseDatos"] = "evaldocentes";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);


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
$atributos["tipoSubmit"] = "jquery";
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

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"] = "formSaraData"; //No cambiar este nombre
$atributos["tipo"] = "hidden";
$atributos["obligatorio"] = false;
$atributos["etiqueta"] = "";
$atributos["valor"] = $valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);
echo $this->miFormulario->division("fin");
//Fin del Formulario
echo $this->miFormulario->formulario("fin");
echo $this->miFormulario->marcoAGrupacion("fin");
echo $this->miFormulario->division("fin");

echo "<br>" . $registroFormato[0][2] . " :<hr />";


$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionPreguntas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Formulario";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos); 

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);
unset($atributos);
echo "<table>";
for ($i = 0; $i <= $cuenta-1; $i++)
{
    $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                    
    $variable.="&action=". $esteBloque["nombre"];
    $variable.="&opcion=borrarRegistroFormulario";
    $variable.="&usuario=".$_REQUEST['usuario'];
    $variable.="&formatoNumero=" . $_REQUEST['formatoNumero'];
    $variable.="&formatoId=" . $_REQUEST['formatoId'];
    $variable.="&formularioId=".$registroFormularios[$i][8];
    $variable.="&periodo=".$_REQUEST['periodo'];
    $variable.="&bloque=".$esteBloque["id_bloque"];
    $variable.="&bloqueGrupo=".$esteBloque["grupo"];
    $variable.="&tipo=".$_REQUEST['tipo'];
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
    
    $html = "   <tr>";
    $html.="        <td>";
    $html.=             $registroFormularios[$i][3];
    $html.="        </td>";
    $html.="        <td>";
                        if($registroFormularios[$i][3]!='')
                        {
                            $html.="<a href='".$variable."' title='Borrar registro'>";               
                            $html.="   <img src='".$rutaBloque."/images/cancel.png' width='15px'>"; 
                            $html.="</a>";    
                        }    
    
    $html.="        </td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td>";
    $html.=             $registroFormularios[$i][4];
    $html.="        </td>";
    $html.="        <td>";
                        if($registroFormularios[$i][4]!='')
                        {
                            $html.="<a href='".$variable."' title='Borrar registro'>";               
                            $html.="   <img src='".$rutaBloque."/images/cancel.png' width='15px'>"; 
                            $html.="</a>";    
                        }      
    $html.="        </td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td>";
                //$html.=$registroFormularios[$i][5];
                if($registroFormularios[$i][7]==7 || $registroFormularios[$i][7]==15 || $registroFormularios[$i][6]==3)
                {
                    $numero=1;
                }
                else
                {
                    $numero=0;
                }

                $accion=$registroFormularios[$i][5];
                //var_dump($accion);
                switch($accion)
                {
                        case 1: //Radio
                             unset($atributos);
                                $html.="<table>";
                                $html.="<tr>";
                                for($j=$registroFormularios[$i][6]; $j>=$numero; $j--)
                                {

                                    $html.="<td>";
                                    //------------------Control Lista Desplegable------------------------------
                                    if($j==0)
                                    {
                                        $esteCampo = "valores";
                                        $atributos["id"] = $esteCampo.$i;
                                        $atributos["tabIndex"] = $tab++;
                                        $atributos["etiqueta"]="N.A";
                                        $atributos["seleccionado"] = false;
                                        $atributos["seleccion"]='';
                                        $atributos["opciones"]=$j;
                                        $atributos["valor"]=$j;
                                        $html.=$this->miFormulario->campoBotonRadial($atributos);
                                        unset($atributos);
                                    }
                                    else
                                    {    
                                        $esteCampo = "valores";
                                        $atributos["id"] = $esteCampo.$i;
                                        $atributos["tabIndex"] = $tab++;
                                        $atributos["etiqueta"]=$j;
                                        $atributos["seleccionado"] = false;
                                        $atributos["seleccion"]=0;
                                        $atributos["opciones"]=$j;
                                        $atributos["valor"]=$j;
                                        $html.=$this->miFormulario->campoBotonRadial($atributos);
                                        unset($atributos);
                                    }
                               }
                                $html.="</tr>";
                                $html.="</table>";
                        break;
                        
                        case 2: //Checkbox 
                             unset($atributos);
                               $html.="<table>";
                                $html.="<tr>";
                                for($j=$registroFormularios[$i][6]; $j>=$numero; $j--)
                                {

                                    $html.="<td>";
                                    $html.=$j;
                                    //------------------Control Lista Desplegable------------------------------
                                    if($j==0)
                                    {
                                        $esteCampo = "valores";
                                        $atributos["id"] = $esteCampo.$i;
                                        $atributos["tabIndex"] = $tab++;
                                        $atributos["etiqueta"]="N.A";
                                        
                                        $html.=$this->miFormulario->campoCuadroSeleccion($atributos);
                                        unset($atributos);
                                    }
                                    else
                                    {    
                                        $esteCampo = "valores";
                                        $atributos["id"] = $esteCampo.$i;
                                        $atributos["tabIndex"] = $tab++;
                                        $atributos["etiqueta"]='';
                                        
                                        $html.=$this->miFormulario->campoCuadroSeleccion($atributos);
                                        unset($atributos);
                                    }
                               }
                                $html.="</tr>";
                                $html.="</table>";
                        break;
                    
                        case 3: //Text
                                $esteCampo="valores";
                                $atributos["id"]=$esteCampo.$i;
                                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                                $atributos["titulo"]="texto".$i;
                                $atributos["tabIndex"]=$tab++;
                                $atributos["obligatorio"]=true;
                                $atributos["etiquetaObligatorio"] = true;
                                $atributos["anchoEtiqueta"] = 125;
                                $atributos["tamanno"]="25";
                                $atributos["tipo"]="text";
                                $atributos["estilo"]="jqueryui";
                                $atributos["obligatorio"] = true;
                                //$atributos["validar"]="required, min[6]";
                                $atributos["validar"]="required";
                                $atributos["categoria"]="";
                                $html.=$this->miFormulario->campoCuadroTexto($atributos);
                                unset($atributos);
                        break;
                        
                        case 4: //TextArea
                               $esteCampo="obesrvaciones";
                                $atributos["id"]=$esteCampo;
                                //$atributos["name"]=$esteCampo;
                                $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                                $atributos["titulo"]="Observaciones";
                                $atributos["tabIndex"]=$tab++;
                                $atributos["obligatorio"]=true;
                                $atributos["etiquetaObligatorio"] = true;
                                $atributos["columnas"]=110;
                                $atributos["filas"]=4;
                                $atributos["estiloArea"]="areaTexto";
                                $atributos["estilo"]="jqueryui";
                                $atributos["validar"]="required";
                                $html.=$this->miFormulario->campoTextArea($atributos);
                                unset($atributos);
                        break;
                    
                        case 5: //Select
                        break;
                }


            $html.="</td>";
            $html.="</tr>";
            echo $html;
}
echo "</table>";

//Fin del Formulario
echo $this->miFormulario->formulario("fin");
echo $this->miFormulario->marcoAGrupacion("fin");
echo $this->miFormulario->division("fin");
