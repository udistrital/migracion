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

$valorCodificado="pagina=administracion";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardarPreguntas";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&id_periodo=".$variable['id_periodo'];
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
$atributos["leyenda"] = "Registro de preguntas.";
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
$mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ REGISTRAR LAS PREGUNTAS PARA EL FORMULARIO DE INSCRIPCIÓN DE ADMISIONES PARA EL PERIODO ACADÉMICO: ".$variable['anio']."-".$variable['periodo'].".<br>
           
            Los campos con * son obligatorios.";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);


$esteCampo="nombrePregunta";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Pregunta para el formulario de admisiones";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 195;
$atributos["tamanno"]="35";
$atributos["maximoTamanno"]="525";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//------------------Control Lista Desplegable------------------------------
$esteCampo = "preguntaTipo";
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
$atributos["anchoEtiqueta"] = 195;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Tipo de pregunta: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("consultarTipoPregunta");
$atributos["baseDatos"] = "admisiones";
echo $this->miFormulario->campoCuadroLista($atributos);
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
$atributos["ancho"] = "150px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 195;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Tipo de inscripción: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("consultarDesEventos");
$atributos["baseDatos"] = "admisiones";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="parametro1";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Parámetro 1";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 195;
$atributos["tamanno"]="30";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
//$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="parametro2";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Parámetro 2";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 195;
$atributos["tamanno"]="30";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
//$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="parametro3";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Parámetro 3";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 195;
$atributos["tamanno"]="30";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
//$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="parametro4";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Parámetro 4";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 195;
$atributos["tamanno"]="30";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
//$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
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


echo "Preguntas registradas para el formulario de inscripción:<hr />";

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

echo "Este se considera un error fatal";
exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPreguntasRegistradas", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registro)
{	
    echo "<table id='tablaTipIns'>";

    echo "<thead>
            <tr>
                <th>Pregunta</th>
                <th>Estado</th>
                <th>Tipo de inscripción</th>
                <th>Tipo de Pregunta</th>
                <th>Editar</th>
           </tr>
        </thead>
        <tbody>";

    for($i=0;$i<count($registro);$i++)
    {
        $variable ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
        $variable.="&opcion=editarPreguntas";
        $variable.="&usuario=". $_REQUEST['usuario'];
        $variable.="&tipo=".$_REQUEST['tipo'];
        $variable.="&preg_id=".$registro[$i][0];
        $variable.="&nombrePregunta=".$registro[$i][1];
        $variable.="&estado=".$registro[$i][2];
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

        echo "<tr>
                <td>".$registro[$i][1]."</td>
                <td align='center'>".$registro[$i][2]."</td>
                <td align='center'>".$registro[$i][6]."</td>
                <td align='center'>".$registro[$i][5]."</td>
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
