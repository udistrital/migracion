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
$valorCodificado.="&opcion=guardarDiscapacidad";
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
$atributos["leyenda"] = "Actalización de los tipos de discapacidad";
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
$mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ACTUALIZAR LOS TIPOS DE DISCAPACIDAD, PARA EL PROCESO DE INSCRIPCIÓN DE ADMISIONES PARA EL PERIODO ACADÉMICO: ".$variable['anio']."-".$variable['periodo'].".<br>
            - Digite el Número de la discapacidad.<br>
            - Digite el nombre de la discapaciad.<br><br>
            Los campos con * son obligatorios.";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="nombreDiscapacidad";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Nombre de la discapacidad.";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 175;
$atributos["tamanno"]="30";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
$atributos["validar"]="required";
//$atributos["validar"]="required,number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="numeroDiscapacidad";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Número de la discapacidad";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 175;
$atributos["tamanno"]="10";
$atributos["maximoTamanno"]="125";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["obligatorio"] = true;
$atributos["validar"]="required,custom[integer],min[0]";
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


echo "Discapacidades registradas en el sistema:<hr />";

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

echo "Este se considera un error fatal";
exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarDiscapacidad", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registro)
{	
    echo "<table id='tablaLocalidad'>";

    echo "<thead>
            <tr>
                <th>Número</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Editar</th>
           </tr>
        </thead>
        <tbody>";

    for($i=0;$i<count($registro);$i++)
    {
        $variable ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
        $variable.="&opcion=editarDiscapacidad";
        $variable.="&usuario=". $_REQUEST['usuario'];
        $variable.="&id_discapacidad=".$registro[$i][0];
        $variable.="&numeroDiscapacidad=".$registro[$i][1];
        $variable.="&nombreDiscapacidad=".$registro[$i][2];
        $variable.="&estado=".$registro[$i][3];
        $variable.="&tipo=".$_REQUEST['tipo']."";
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

        echo "<tr>
                <td align='center'>".$registro[$i][1]."</td>
                <td>".$registro[$i][2]."</td>
                <td align='center'>".$registro[$i][3]."</td>
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




