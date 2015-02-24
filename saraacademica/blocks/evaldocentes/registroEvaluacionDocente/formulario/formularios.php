<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/armarFormulariosEvaldocente/";
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
$variable['usuario']=$_REQUEST['usuario'];
$variable['periodoId']=$_REQUEST['periodoId'];
$variable['tipoVinculacion']=$_REQUEST['tipoVinculacion'];
$variable['documentoId']=$_REQUEST['documentoId'];
$variable['carrera']=$_REQUEST['carrera'];
$variable['tipoId']=$_REQUEST['tipoId'];

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", $variable);
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
$valor=explode('-',$registroPeriodo[0][1]);

$variable['anio']=$valor[0];
$variable['per']=$valor[1];

$cadena_sql = $this->sql->cadena_sql("consultarAsociacion", $variable);
$registroAsociacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroAsociacion))
{
    for($i=0; $i<=count($registroAsociacion)-1; $i++)// Si el tipo de vinculación coincide con uno registrado en la tabla fortipvindoc, le asigna el número de formato.
    {

        if($registroAsociacion[0][2]==$_REQUEST['tipoVinculacion'])
        {
            $variable['formatoId']=$registroAsociacion[$i][1];
        }    
    }  
}
else
{
    $variable['formatoId']=0;
}    

$cadena_sql = $this->sql->cadena_sql("consultarFormularios", $variable);
$registroFormularios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variableVacia=0;
for ($i=0; $i <= count($registroFormularios)-1; $i++)
{
    $variableVacia=$variableVacia.",".$registroFormularios[$i][8];
    $variable['formularioId']=$variableVacia;
}
if(isset($_REQUEST['grupo']))
{
    $variable['grupo']=$_REQUEST['grupo'];
}
if(isset($_REQUEST['asignatura']))
{
    $variable['asignatura']=$_REQUEST['asignatura'];
}    

$cadena_sql = $this->sql->cadena_sql("consultarEvaluacion", $variable);
$registroEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//echo $cadena_sql."<br>";
if(!is_array($registroEvaluacion))
{
    if(is_array($registroFormularios))
    {
        $valorCodificado="pagina=evaluaconDocente";
        $valorCodificado="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=guardarEvaluacion";
        $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        $valorCodificado.="&documentoId=".$_REQUEST['documentoId'];
        $valorCodificado.="&carrera=".$_REQUEST['carrera'];
        $valorCodificado.="&asignatura=".$_REQUEST['asignatura'];
        $valorCodificado.="&grupo=".$_REQUEST['grupo'];
        $valorCodificado.="&formatoId=".$variable['formatoId'];
        $valorCodificado.="&perAcad=".$variable['periodoId'];
        $valorCodificado.="&anio=".$valor[0];
        $valorCodificado.="&periodo=".$valor[1];
        $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        $valorCodificado.="&tipoVinculacion=".$variable['tipoVinculacion'];
        $valorCodificado.="&tipoId=".$variable['tipoId'];
        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
        $valorCodificado.="&tipoVinculacion=".$variable['tipoVinculacion'];
        
        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);

        ////-------------------------------Mensaje-------------------------------------
        $cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
        $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if($_REQUEST['asignatura']==0 || $_REQUEST['grupo']==0)
        {
            $mensajeAsignatura=" ";
        }
        else
        {
           $mensajeAsignatura="Cod. Asignatura: ".$_REQUEST['asignatura']."  <br>
                                Grupo No.: ".$_REQUEST['grupo']."  <br>";  
        }    
        
        $tipo = 'message';
        $mensaje = $registroTipEvaluacion[0][2]." <br>
                    DOCENTE: ".$_REQUEST['docenteNombre']."<br>
                    Documento de Identidad No. ".$_REQUEST['documentoId']."<br>
                    ".$_REQUEST['nombreCarrera']."  <br>
                    $mensajeAsignatura    
                    PERIODO ACADÉMICO ".$registroPeriodo[0][1]." ";


        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);

        $atributos["id"] = "marcoAgrupacionFormulario";
        $atributos["estilo"] = "jqueryui";
        $atributos["leyenda"] = "Formulario";
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

        echo "<table>";
        for ($i=0; $i <= count($registroFormularios)-1; $i++)
        {
            $html = "   <tr>";
            $html.="        <td>";
            $html.=             $registroFormularios[$i][3];
            $html.="        </td>";
            $html.="    </tr>";
            $html.="    <tr>";
            $html.="        <td>";
            $html.=             $registroFormularios[$i][4];
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
                        switch($accion)
                        {
                                case 1: //Radio

                                        $html.="<table>";
                                        $html.="<tr>";

                                        for($j=$registroFormularios[$i][6]; $j>=$numero; $j--)
                                        {
                                                $html.="<td>";
                                                //------------------Control Lista Desplegable------------------------------
                                                if($j==0)//Contiene un item más con valor cero, corresponde a "NO APLICA".
                                                {
                                                    $esteCampo = "valores";
                                                    $atributos["id"] = $esteCampo.$i;
                                                    $atributos["tabIndex"] = $tab++;
                                                    $atributos["etiqueta"]="N.A";
                                                    $atributos["seleccionado"] = false;
                                                    $atributos["estilo"]="jqueryui";
                                                    $atributos["obligatorio"] = true;
                                                    $atributos["validar"]="required";
                                                    $atributos["seleccion"]='';
                                                    $atributos["opciones"]=$j."-".$registroFormularios[$i][8];
                                                    $atributos["valor"]=$j;
                                                    $html.=$this->miFormulario->campoBotonRadial($atributos);
                                                    unset($atributos);
                                                }
                                                else //Va sin "NO APLICA". 
                                                {    
                                                    $esteCampo = "valores";
                                                    $atributos["id"] = $esteCampo.$i;
                                                    $atributos["tabIndex"] = $tab++;
                                                    $atributos["etiqueta"]=$j;
                                                    $atributos["seleccionado"] = false;
                                                    $atributos["estilo"]="jqueryui";
                                                    $atributos["obligatorio"] = true;
                                                    $atributos["validar"]="required";
                                                    $atributos["seleccion"]=0;
                                                    $atributos["opciones"]=$j."-".$registroFormularios[$i][8];
                                                    $atributos["valor"]=$j;
                                                    $html.=$this->miFormulario->campoBotonRadial($atributos);
                                                    unset($atributos);
                                                }


                                        }
                                        $html.="</tr>";
                                        $html.="</table>";

                                break;

                                case 2: //Checkbox
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
                                       $esteCampo="observaciones";
                                       $atributos["id"]=$esteCampo;
                                        //$atributos["name"]=$esteCampo;
                                        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
                                        $atributos["otroValor"]=$registroFormularios[$i][8];
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
        
       $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado); 
        
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

        echo $this->miFormulario->formulario("fin");
        echo $this->miFormulario->marcoAGrupacion("fin");
        echo $this->miFormulario->division("fin");
    }
    else
    {
        include_once("core/crypto/Encriptador.class.php");
        $cripto=Encriptador::singleton();
        $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

        $miPaginaActual="habilitarEvaluacion";
        
        $cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
        $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $valorCodificado="pagina=evaluacionDocente";
        $variableVacia=0;
        for ($i=0; $i <= count($registroFormularios)-1; $i++)
        {
            $variableVacia=$variableVacia.",".$registroFormularios[$i][8];
            $valorCodificado.="&formularioId=".$variableVacia;
        }
                
        $accion=$_REQUEST['tipo'];
        switch($accion)
        {
                case 4: //Evaluación Por Coordinadores
                        $valorCodificado.="&opcion=listaDocentes"; 
                break;

                case 16: //Evaluación Por Decanos
                        $valorCodificado.="&opcion=nuevo";
                break;

                case 30: //Autoevaluación
                        $valorCodificado.="&opcion=nuevo"; 
                break;

                case 51: //Evaluación Estudiantes Horas
                      $valorCodificado.="&opcion=nuevo";  
                break;

                 case 52: //Evaluación Estudiantes Créditos
                      $valorCodificado.="&opcion=nuevo";
                break;  

        }
        $valorCodificado.="&anio=".$_REQUEST['anio'];
        $valorCodificado.="&periodo=".$_REQUEST['periodo'];
        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
        $valorCodificado=$cripto->codificar($valorCodificado);
        
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
                $mensaje = 'No se encontraron formularios registrados para '.$registroTipEvaluacion[0][2].' para el perido académico '.$registroPeriodo[0][1].'...';
                $boton = "regresar";

                /*$valorCodificado="pagina=evaluacionDocente";
                $valorCodificado.="&opcion=listaDocentes"; 
                $valorCodificado.="&anio=".$_REQUEST['anio'];
                $valorCodificado.="&periodo=".$_REQUEST['periodo'];
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$cripto->codificar($valorCodificado);*/


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
}
else
{
    include_once("core/crypto/Encriptador.class.php");
    $cripto=Encriptador::singleton();
    $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

    $cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
    $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $valorCodificado="pagina=evaluacionDocente";
    $accion=$_REQUEST['tipo'];
    switch($accion)
    {
            case 4: //Evaluación Por Coordinadores
                    $valorCodificado.="&opcion=listaDocentes"; 
            break;

            case 16: //Evaluación Por Decanos
                    $valorCodificado.="&opcion=nuevo";
            break;

            case 30: //Autoevaluación
                    $valorCodificado.="&opcion=nuevo"; 
            break;

            case 51: //Evaluación Estudiantes Horas
                  $valorCodificado.="&opcion=nuevo";
            break;

             case 52: //Evaluación Estudiantes Créditos
                  $valorCodificado.="&opcion=nuevo";
            break;  

    }
    $valorCodificado.="&anio=".$_REQUEST['anio'];
    $valorCodificado.="&periodo=".$_REQUEST['periodo'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$cripto->codificar($valorCodificado);        
    
    $tab=1;
    //---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"]=$nombreFormulario;
    $atributos["tipoFormulario"]="multipart/form-data";
    $atributos["metodo"]="POST";
    $atributos["nombreFormulario"]=$nombreFormulario;
    $verificarFormulario="1";
    echo $this->miFormulario->formulario("inicio",$atributos);
    
    $cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
    $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if($registroEvaluacion[0][6]==0 || $registroEvaluacion[0][7]==0)
    {
        $mensajeAsignatura=" ";
    }
    else
    {
       $mensajeAsignatura="Asignatura No.: ".$registroEvaluacion[0][6]."  <br>
                            Grupo No.: ".$registroEvaluacion[0][7]."  <br>";  
    }    
    
    if(isset($_REQUEST['registroExitoso']))
    {
        $registroExitoso="REGISTRO EXITOSO!!!";
    }
    else
    {
        $registroExitoso="";
    }
    if(isset($_REQUEST['nombreVinculacion']))
    {
        $nombreVinculacion=$_REQUEST['nombreVinculacion'];
    }
    else
    {
        $nombreVinculacion='';
    }    
    $tipo = 'message';
    $mensaje = $registroExitoso."<br>".$registroTipEvaluacion[0][2]." <br>
                DOCENTE: ".$_REQUEST['docenteNombre']."<br>
                Documento de Identidad No. ".$_REQUEST['documentoId']."<br>
                ".$nombreVinculacion." <br>    
                ".$_REQUEST['nombreCarrera']."  <br>
                $mensajeAsignatura   
                PERIODO ACADÉMICO ".$registroPeriodo[0][1]." ";


    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);

    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionFormulario";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "FORMATO No. ".$registroEvaluacion[0][11];
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
    
    $variable['usuario']=$_REQUEST['usuario'];
    $variable['asignatura']=$registroEvaluacion[0][6];
    $variable['grupo']=$registroEvaluacion[0][7];
    
    $cadena_sql = $this->sql->cadena_sql("consultarObservacion", $variable);
    $registroObservacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    
    $variable['tipoId']=$_REQUEST['tipoId'];
    if($variable['tipoId']==1)
    {
        echo "<table class='formulario'>
                <tr>
                <td>";
        echo "<b>Observación:</b> ".$registroObservacion[0][2];
        echo "</td>
              </tr>
              </table></p>";
    }    
    
    echo "<table id='tablaFormulario'>";

        echo "<thead>
                <tr>
                    <th>Pregunta No.</th>
                    <th>Pregunta </th>
                    <th>Valor Respuesta</th>
               </tr>
            </thead>
            <tbody>";
        
        for($i=0;$i<count($registroEvaluacion);$i++)
        {
            //$cadena_sql = $this->sql->cadena_sql("consultarPreguntasTipoPregunta", $variable);
           // $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            //echo $cadena_sql."<br>";
            echo "<tr>
                    <td align='center'>".$registroEvaluacion[$i][1]."</td>
                    <td >".$registroEvaluacion[$i][12]."</td>";
                    if($registroEvaluacion[$i][9]==0)
                    {
                        echo"<td align='center'>N/A</td>";
                    }
                    else
                    {
                        echo"<td align='center'>".$registroEvaluacion[$i][9]."</td>";
                    }    
            
                  echo "</tr>";
            //unset($variable);
        }

        echo "</tbody>";

    echo "</table>";
    
     
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
         
            
    echo $this->miFormulario->formulario("fin");
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
}
    









