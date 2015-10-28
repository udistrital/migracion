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

if(isset($_REQUEST['consulta']))
{
    $variable['consulta']=$_REQUEST['consulta'];
}    
else
{
    $variable['consulta']=1;
}
$valorCodificado="pagina=administracion";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=consultarInscripcion";
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
$atributos["leyenda"] = "Consulta Referencia Bancaria";
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
$mensaje = "<center><h3>CONSULTA DE INSCRIPCIONES 
    <br>".$periodo." PERIODO ACADÉMICO ".$variable['anio']."<br></h3></center>
            - Digite el número de credencial.<br>
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
$esteCampo="consultaCredencial";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Valor";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 275;
$atributos["tamanno"]="25";
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

$esteCampo = "botonConsultar";
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

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

echo "Este se considera un error fatal";
exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarReferenciaBancaria", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(isset($_REQUEST['consulta']))
{
    if($registro)
    {
        echo "<hr />";
        
        echo "<table id='tablaMedios'>";

        echo "<thead>
                <tr>
                    <th>Año</th>
                    <th>Periodo</th>
                    <th>Identificación</th>
                    <th>Referencia</th>
                    <th>Fecha de Pago</th>
                    <th>No. de Credencial</th>
               </tr>
            </thead>
            <tbody>";

        for($i=0;$i<count($registro);$i++)
        {
            echo "<tr>
                    <td align='center'>".$variable['anio']."</td>
                    <td align='center'>".$variable['periodo']."</td>
                    <td align='center'>".$registro[$i]['rba_nro_iden']."</td>
                    <td align='center'>".$registro[$i]['rba_ban_cod']."</td>
                    <td align='center'>".$registro[$i]['rba_dia']."/".$registro[$i]['rba_mes']."/".$registro[$i]['rba_anio']."</td>    
                    <td align='center'>".$registro[$i]['rba_asp_cred']."</td>
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
}