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

$variable['perAcad']=$_REQUEST['perAcad'];

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", $variable);
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


/*$cadena_sql = $this->sql->cadena_sql("buscarCarrerasDocenteHistorico", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");*/

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "EVALUACIÓN EXTEMPORÁNEA ESTUDIANTES,  DOCENTE: BENITO PÉREZ, ID. No. ".$_REQUEST['documentoId'].", PERIODO ACADÉMICO ".$registroPeriodo[0][1]." .";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);

 echo "Carreras vinculadas :<hr />";
//if($registro)
//{	
        echo "<table class='formulario'>";
       // echo "<table id='tablaCarreras'>";
        echo "<thead>
                <tr>
                    <th>Cod. Carrera</th>
                    <th>Carrera</th>
                    <th>Registrar Evaluación</th>
               </tr>
            </thead>
            <tbody>";
        $variable ="pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro
        $variable.="&opcion=formularios";
        $variable.="&documentoId=". $_REQUEST['documentoId'];
        $variable.="&estudianteCod=20032025075";
        $variable.="&tipoId=1";
        $variable.="&carrera=25" ;
        $variable.="&asignatura=1111";
        $variable.="&grupo=1";
        $variable.="&usuario=". $_REQUEST['usuario'];
        $variable.="&perAcad=".$_REQUEST['perAcad'];
        $variable.="&tipoVinculacion= 0";
          
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        echo "<tr>
                <td align='center'>
                    5
                </td>
                <td>
                    Ing. Electrónica
                </td>
              </tr>
              <tr>
                <td align='center'>
                    20
                </td>
                <td>
                    Ing. de Sistemas
                </td>
              </tr>
              <tr>
                <td align='center'>
                    199
                </td>
                <td>
                    Esp. Proyectos Informáticos
                </td>
                <td align='center'><a href='".$variable."'>               
                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                        </a>
                 </td>
              </tr>  ";
        
        /*for($i=0;$i<count($registro);$i++)
        {
            $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
            $variable.="&opcion=armarFormulario";
            $variable.="&usuario=". $_REQUEST['usuario'];
            $variable.="&formatoNumero=".$registro[$i][2];
            $variable.="&formatoId=".$registro[$i][0];
            $variable.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            
            echo "<tr>
                    <td align='center'>".$registro[$i][2]."</td>
                    <td><a href='".$variable."'>".$registro[$i][3]."</a></td>
                    <td align='center'>".$registro[$i][6]."</td>    
                    <td align='center'>".$registro[$i][8]."-".$registro[$i][9]."</td>
                    <td align='center'>".$registro[$i][5]."</td>
                    <td align='center'><a href='".$variable."'>               
                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                        </a></td>
                </tr>";
            unset($variable);
        }*/
               
        echo "</tbody>";
        
        echo "</table>";	
   
/*}else
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
}*/
        

echo $this->miFormulario->division("fin");
