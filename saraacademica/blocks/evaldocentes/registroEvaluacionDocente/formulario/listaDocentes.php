<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/habilitarProcesoEvaldocente/";
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
$esteRecursoDBPG = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDBPG) {

    echo "//Este se considera un error fatal";
    exit;
}
$cadena_sql = $this->sql->cadena_sql("consultarAnioPeriodo", "");
$registroPeriodo = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
$variable['anio']=$registroPeriodo[0]['acasperiev_anio'];
$variable['per']=$registroPeriodo[0]['acasperiev_periodo'];

$conexion = "autoevaluadoc";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['carrera']=$_REQUEST['carrera'];
$variable['nombreCarrera']=$_REQUEST['nombreCarrera'];
$variable['usuario']=$_REQUEST['usuario'];
$variable['tipoId']=3; //Evaluación por el consejo curricular, ver tabla evaldocente_tipo_evaluacion

$cadena_sql = $this->sql->cadena_sql("consultarDocentes", $variable);
$registroDocentes = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
 
    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionEvExtemporaneas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Evaluación Docente";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
    unset($atributos);
    
    $tipo = 'message';
    $mensaje = "<b>LISTA DE DOCENTES DEL PROYECTO CURRICULAR ".$_REQUEST['nombreCarrera']."</b>";
    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
   
    $tab = 1;
    $accion=$_REQUEST['tipo'];
    
    $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosCarrera", $variable);
    $registroDcentesEvaluados = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
    
    $docentesEvaluados=0;
    for($i=0; $i<=count($registroDcentesEvaluados)-1; $i++)
    {
        if(!is_array($registroDcentesEvaluados))
        {
            $docentesEvaluados=$docentesEvaluados.",". $registroDcentesEvaluados[$i][0];
        }
        else
        {
            $docentesEvaluados=$docentesEvaluados.",". $registroDcentesEvaluados[$i][0];
        }
    }
       
    if(is_array($registroDocentes))
    {    
      echo "<table id='tablaDocentes'>";

      echo "<thead>
              <tr>
                  <th>Identificación No.</th>
                  <th>Nombre</th>
                  <th>Tipo de vinculación</th>
                  <th>Evaluar</th>
             </tr>
          </thead>
          <tbody>";
          for($i=0; $i<=count($registroDocentes)-1; $i++)
          {
                $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                $variable.="&opcion=formularios";
                $variable.="&usuario=". $_REQUEST['usuario'];
                $variable.="&documentoId=".$registroDocentes[$i][0];
                $variable.="&docenteNombre=".$registroDocentes[$i][1];
                $variable.="&tipo=". $_REQUEST['tipo'];
                $variable.="&carrera=".$registroDocentes[$i][2] ;
                $variable.="&asignatura=0";
                $variable.="&grupo=0";
                $variable.="&formatoNumero=".$registroDocentes[$i][2];
                $variable.="&formatoId=2";
                $variable.="&tipoVinculacion=".$registroDocentes[$i][4];
                $variable.="&nombreVinculacion=".$registroDocentes[$i][5];
                $variable.="&periodoId=".$_REQUEST['periodoId'];
                $variable.="&anio=".$_REQUEST['anio'];
                $variable.="&periodo=".$_REQUEST['periodo'];
                $variable.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
                $variable.="&tipoId=3";
                           
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

               $resultado=strstr($docentesEvaluados,$registroDocentes[$i][0]);
               if($resultado==true)
               {
                //echo "La subcadena ".$registroDocentes[$i][0]." fue encontrada dentro de la cadena ".$docentesEvaluados." en la posición: ".$resultado."";   
                echo "<tr>
                        <td>".$registroDocentes[$i][0]."</td>";
                        echo "<td>".$registroDocentes[$i][1]. "  <a href='".$variable."'>(Docente evaluado)</a></td>
                        <td>".$registroDocentes[$i][5]."</td>    
                        <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 

                   echo "</tr>";
               }
               else
               {
                   echo "<tr>
                        <td>".$registroDocentes[$i][0]."</td>";
                        echo "<td><a href='".$variable."'>".$registroDocentes[$i][1]."</a></td>
                        <td>".$registroDocentes[$i][5]."</td>    
                        <td align='center'><a href='".$variable."'>               
                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                        </a></td>"; 

                   echo "</tr>";
               }    
                
              //unset($variable);
          }
    echo "</tbody>";      
    echo "</table>";

    }
    else
    {
          $tipo = 'information';
          $mensaje = "Usuario no reconocido como Coordinador en el Sistema, o Coordinador sin docentes registrados. ";

          echo $mensaje;
    }
           
                //-------------Fin de Conjunto de Controles----------------------------
        echo $this->miFormulario->marcoAgrupacion("fin");

        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");


