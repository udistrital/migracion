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

    echo "//Este se considera un error fatal";
    exit;
}

$variable['documentoId']=$_REQUEST['documentoId'];
$variable['perAcad']=$_REQUEST['perAcad'];
$variable['tipoId']=$_REQUEST['tipoEvaluacionExt'];

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", $variable);
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valor=explode('-',$registroPeriodo[0][1]);

$variable['anio']=$valor[0];
$variable['per']=$valor[1];

$cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
$registroTipoEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$conexion1 = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

//$conexion1 = "CONSULTAEVADOC";
//$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
                                        
if (!$esteRecursoDBORA) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarCargaDocenteHistorico", $variable);
$registroCarga = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

if(!is_array($registroCarga))
{
    $cadena_sql = $this->sql->cadena_sql("buscarCargaDocente", $variable);
    $registroCarga = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
}    

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionEvExtemporaneas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Evaluación Extemporánea Docente";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);
   
    $tab = 1;

////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = $registroTipoEvaluacion[0][2]."<br>
            DOCENTE: ".$registroCarga[0][2]." <br>
            Documento de Identidad No. ".$_REQUEST['documentoId']." <br>
            Piodo académico ".$registroPeriodo[0][1]." .";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);

echo "Carreras vinculadas :<hr />";
 
echo "<table id='tablaCargaAcademica'>";

echo "<thead>
        <tr>
            <th>Cod. Carrera</th>
            <th>Carrera</th>
            <th>Tipo de Vinculación</th>
            <th>Evaluar</th>
       </tr>
    </thead>
    <tbody>";
    if(is_array($registroCarga))
    {    
        for($i=0; $i<=count($registroCarga)-1; $i++)
        {
            echo "<tr>
                    <td>".$registroCarga[$i][0]."</td>";
                    if($_REQUEST['tipoEvaluacionExt']==2)
                    {    
                        if($registroCarga[$i][6]=='N')
                        {
                            $variable ="pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=formularios";
                            $variable.="&usuario=". $_REQUEST['usuario'];
                            $variable.="&documentoId=".$registroCarga[$i][1];
                            $variable.="&docenteNombre=".$registroCarga[$i][2];
                            $variable.="&modulo=". $_REQUEST['tipoEvaluacionExt'];
                            $variable.="&carrera=".$registroCarga[$i][0] ;
                            $variable.="&asignatura=0";
                            $variable.="&grupo=0";
                            $variable.="&tipoVinculacion=".$registroCarga[$i][4];
                            $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                            $variable.="&perAcad=".$_REQUEST['perAcad'];
                            $variable.="&anio=".$valor[0];
                            $variable.="&per=".$valor[1];
                            $variable.="&nombreCarrera=".$registroCarga[$i][3];
                            $variable.="&tipoId=2";
                            $variable.="&tipo=".$_REQUEST['tipo'];
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                            echo "<td><a href='".$variable."'>".$registroCarga[$i][3]."</a></td>
                            <td>".$registroCarga[$i][5]."</td>   
                            <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>";
                        }
                        else
                        {
                            $variable ="pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=formularios";
                            $variable.="&usuario=". $_REQUEST['usuario'];
                            $variable.="&documentoId=".$registroCarga[$i][1];
                            $variable.="&docenteNombre=".$registroCarga[$i][2];
                            $variable.="&modulo=". $_REQUEST['tipoEvaluacionExt'];
                            $variable.="&carrera=".$registroCarga[$i][0] ;
                            $variable.="&asignatura=0";
                            $variable.="&grupo=0";
                            $variable.="&tipoVinculacion=0";
                            $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                            $variable.="&perAcad=".$_REQUEST['perAcad'];
                            $variable.="&anio=".$valor[0];
                            $variable.="&per=".$valor[1];
                            $variable.="&nombreCarrera=".$registroCarga[$i][3];
                            $variable.="&tipoId=5";
                            $variable.="&tipo=".$_REQUEST['tipo'];
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                            echo "<td><a href='".$variable."'>".$registroCarga[$i][3]." CÁTEDRA</a></td>
                            <td>".$registroCarga[$i][5]."</td>   
                            <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>"; 
                        }
                    }
                    else
                    {
                        if($registroCarga[$i][6]=='N')
                        {
                            $variable ="pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=formularios";
                            $variable.="&usuario=". $_REQUEST['usuario'];
                            $variable.="&documentoId=".$registroCarga[$i][1];
                            $variable.="&docenteNombre=".$registroCarga[$i][2];
                            $variable.="&modulo=". $_REQUEST['tipoEvaluacionExt'];
                            $variable.="&carrera=".$registroCarga[$i][0] ;
                            $variable.="&asignatura=0";
                            $variable.="&grupo=0";
                            $variable.="&tipoVinculacion=".$registroCarga[$i][4];
                            $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                            $variable.="&perAcad=".$_REQUEST['perAcad'];
                            $variable.="&anio=".$valor[0];
                            $variable.="&per=".$valor[1];
                            $variable.="&nombreCarrera=".$registroCarga[$i][3];
                            $variable.="&tipoId=3";
                            $variable.="&tipo=".$_REQUEST['tipo'];
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                            echo "<td><a href='".$variable."'>".$registroCarga[$i][3]."</a></td>
                            <td>".$registroCarga[$i][5]."</td>   
                            <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>";
                        }
                        else
                        {
                            $variable ="pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=formularios";
                            $variable.="&usuario=". $_REQUEST['usuario'];
                            $variable.="&documentoId=".$registroCarga[$i][1];
                            $variable.="&docenteNombre=".$registroCarga[$i][2];
                            $variable.="&modulo=". $_REQUEST['tipoEvaluacionExt'];
                            $variable.="&carrera=".$registroCarga[$i][0] ;
                            $variable.="&asignatura=0";
                            $variable.="&grupo=0";
                            $variable.="&tipoVinculacion=0";
                            $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                            $variable.="&perAcad=".$_REQUEST['perAcad'];
                            $variable.="&anio=".$valor[0];
                            $variable.="&per=".$valor[1];
                            $variable.="&nombreCarrera=".$registroCarga[$i][3];
                            $variable.="&tipoId=6";
                            $variable.="&tipo=".$_REQUEST['tipo'];
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                            echo "<td><a href='".$variable."'>".$registroCarga[$i][3]." CÁTEDRA</a></td>
                            <td>".$registroCarga[$i][5]."</td>   
                            <td align='center'><a href='".$variable."'>               
                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                            </a></td>"; 
                        }
                    }
                }
           echo "</tr>";
        //unset($variable);
    }
echo "</tbody>";      
echo "</table>";

                  
             
                  
echo $this->miFormulario->marcoAgrupacion("fin");

        //------------------Fin Division para los botones-------------------------
 echo $this->miFormulario->division("fin");
