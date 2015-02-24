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

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo'] = $registroPeriodo[0][0];

$cadena_sql = $this->sql->cadena_sql("consultarAsociacion", $variable);
$registroForatosAsociados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$conexion = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}
$cadena_sql = $this->sql->cadena_sql("consultarTipoVinculacion", "");
$registroVinculacionDocente = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado = "pagina=armarFormularios";
$valorCodificado.="&action=" . $esteBloque["nombre"];
$valorCodificado.="&opcion=guardarAsociacion";
$valorCodificado.="&usuario=" . $_REQUEST['usuario'];
$valorCodificado.="&periodo=" . $registroPeriodo[0][0];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionAsociarFormatos";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Armar formularios";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);


////-------------------------------Mensaje-------------------------------------
$tipo = 'information';
$mensaje = "EN EL SIGUIENTE FORMULARIO PODRÁ ASOCIAR LOS FORMATOS A UNA VINCULACIÓN DOCENTE.";

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
$esteCampo = "consultarFormatos";
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
$atributos["etiqueta"] = "Formatos: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("consultarFormatosAsociar",$variable);
$atributos["baseDatos"] = "evaldocentes";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//------------------Control Lista Desplegable------------------------------
$esteCampo = "vinculacionDocentes";
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
$atributos["etiqueta"] = "Vinculación Docentes: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("consultarTipoVinculacion");
$atributos["baseDatos"] = "autoevaluadoc";
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

echo "<br>Formatos Asociados con vinculación Docente :<hr />";


if($registroForatosAsociados)
    {	
            echo "<table id='tablaAsociacion'>";

            echo "<thead>
                    <tr>
                        <th>Id asociación</th>
                        <th>Vinculación Docente</th>
                        <th>Formato</th>
                        <th>Estado</th>
                        <th>Editar</th>
                   </tr>
                </thead>
                <tbody>";

            for($i=0;$i<count($registroForatosAsociados);$i++)
            {
                unset($variable);
                $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                $variable.="&action=" . $esteBloque["nombre"];
                $variable.="&opcion=editarAsociacion";
                $variable.="&usuario=". $_REQUEST['usuario'];
                $variable.="&asociacion=".$registroForatosAsociados[$i][0];
                $variable.="&estado=".$registroForatosAsociados[$i][4];
                $variable.="&bloque=" . $esteBloque["id_bloque"];
                $variable.="&bloqueGrupo=" . $esteBloque["grupo"];
                $variable.="&tipo=".$_REQUEST['tipo'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                echo "<tr>
                        <td align='center'>".$registroForatosAsociados[$i][0]."</td>";
                        for($j=0; $j<=count($registroVinculacionDocente)-1; $j++)
                        {
                            if($registroForatosAsociados[$i][3]== $registroVinculacionDocente[$j][0])
                            {
                                echo "<td align='center'>".$registroVinculacionDocente[$j][1]."</td>";
                            }    
                        }    
                       echo "<td>".stripslashes(html_entity_decode($registroForatosAsociados[$i][2]))."</td>
                        <td align='center'>".$registroForatosAsociados[$i][4]."</td>
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

    


