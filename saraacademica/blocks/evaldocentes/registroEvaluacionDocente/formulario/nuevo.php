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

$valor['anio']=$registroPeriodo[0]['acasperiev_anio'];
$valor['per']=$registroPeriodo[0]['acasperiev_periodo'];

$conexion = "autoevaluadoc";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("consultaCarreras", $variable);
$registroCarreras = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultarCarga", $variable);
$registroCarga = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");  

$cadena_sql = $this->sql->cadena_sql("consultaAsignaturas", $variable);
$registroAsignaturas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultaCoordinadores", $variable);
$registroCoordinadores = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

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
   
    $tab = 1;
   
    $accion=$_REQUEST['tipo'];
    switch($accion)
    {
            case 4: //Evaluación Por Coordinadores
                  if(is_array($registroCarreras))
                  {    
                    echo "<table id='tablaCarreras'>";

                    echo "<thead>
                            <tr>
                                <th>Cod. Carrera</th>
                                <th>Carrera</th>
                                <th>Evaluar</th>
                           </tr>
                        </thead>
                        <tbody>";
                        for($i=0; $i<=count($registroCarreras)-1; $i++)
                        {
                            $valor[0]=$registroCarreras[$i][0];
                            $valor[1]="(37)";
                            $valor[2]=$_REQUEST['anio'];
                            $valor[3]=$_REQUEST['periodo'];
                            
                            $cadena_sql = $this->sql->cadena_sql("consultarEventos", $valor);
                            $registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                            
                            $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro 
                            $variable.="&usuario=". $_REQUEST['usuario'];
                            $variable.="&action=".$esteBloque["nombre"];
                            $variable.="&opcion=consultarDocentes";
                            $variable.="&tipo=".$_REQUEST['tipo'];
                            $variable.="&usuario=".$_REQUEST['usuario'];
                            $variable.="&anio=".$_REQUEST['anio'];
                            $variable.="&periodo=".$_REQUEST['periodo'];
                            $variable.="&periodoId=".$_REQUEST['periodoId'];
                            $variable.="&carrera=".$registroCarreras[$i][0];
                            $variable.="&nombreCarrera=".$registroCarreras[$i][1];
                            $variable.="&bloque=".$esteBloque["id_bloque"];
                            $variable.="&bloqueGrupo=".$esteBloque["grupo"];

                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

                            echo "<tr>
                                    <td>".$registroCarreras[$i][0]."</td>";
                                    if($registroEventos[0][0]>0)
                                    {
                                       echo "<td><a href='".$variable."'>".$registroCarreras[$i][1]."</a></td>
                                        <td align='center'><a href='".$variable."'>               
                                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                        </a></td>"; 
                                    }
                                    else
                                    {
                                        echo "<td>".$registroCarreras[$i][1]." (Evaluación no habilitada)</td>
                                        <td align='center'></td>"; 
                                    }
                                    
                               echo "</tr>";
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
            break;

            case 16: //Evaluación Por Decanos
                  if(is_array($registroCoordinadores))
                  {    
                    echo "<table id='tablaDocentesCoordinadores'>";

                    echo "<thead>
                            <tr>
                                <th>No. Identificación</th>
                                <th>Nombre</th>
                                <th>Tipo de vinculación</th>
                                <th>Proyecto Curricular</th>
                                <th>Evaluar</th>
                           </tr>
                        </thead>
                        <tbody>";
                        $usuario=0;
                        $carrera=0;
                        $evento=0;
                        $carreraCoordinador=array();
                        for($i=0; $i<=count($registroCoordinadores)-1; $i++)
                        {
                            //$valor[0]=$registroCoordinadores[$i][1];
                            if(!is_array($registroCoordinadores))
                            {
                                $usuario=$usuario.",". $registroCoordinadores[$i][3];
                                $variable['docentes']=$variablaVacia;
                                
                                $carreraCoordinador[$i]['docente']=$registroCoordinadores[$i][3];
                                
                                $carrera=$carrera.",". $registroCoordinadores[$i][1];
                                $variable['carrera']=$carrera;
                                
                                $carreraCoordinador[$i]['carrera']=$registroCoordinadores[$i][1];
                                
                                $evento=$evento.",". $registroCoordinadores[$i][1];
                                $valor[0]=$evento;                                
                            }
                            else
                            {
                                $usuario=$usuario.",". $registroCoordinadores[$i][3];
                                $valor['docentes']=$usuario;
                                
                                $carreraCoordinador[$i]['docente']=$registroCoordinadores[$i][3];
                                
                                $carrera=$carrera.",". $registroCoordinadores[$i][1];
                                $valor['carrera']=$carrera;
                                
                                $carreraCoordinador[$i]['carrera']=$registroCoordinadores[$i][1];
                                
                                $evento=$evento.",". $registroCoordinadores[$i][1];
                                $valor[0]=$evento;
                            }
                        }    
                            $valor[1]="(37)";
                            $valor[2]=$_REQUEST['anio'];
                            $valor[3]=$_REQUEST['periodo'];
                            $valor['tipoId']="3";
                            $valor['usuario']=$_REQUEST['usuario'];
                            
                            $cadena_sql = $this->sql->cadena_sql("consultarEventos", $valor);
                            $registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                            
                            $cadena_sql = $this->sql->cadena_sql("consultaDocenteCoordinador", $valor);
                            $registroDocenteCoordinador = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda"); 
                            
                            if(is_array($registroDocenteCoordinador))
                            {    
                                for($j=0; $j<=count($registroDocenteCoordinador)-1; $j++)
                                {
                                        
                                    $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                    $variable.="&opcion=formularios";
                                    $variable.="&usuario=". $_REQUEST['usuario'];
                                    $variable.="&documentoId=".$registroDocenteCoordinador[$j][0];
                                    $variable.="&docenteNombre=".$registroDocenteCoordinador[$j][1];
                                    $variable.="&tipo=". $_REQUEST['tipo'];
                                    $variable.="&carrera=".$registroDocenteCoordinador[$j][2] ;
                                    $variable.="&asignatura=0";
                                    $variable.="&grupo=0";
                                    $variable.="&tipoVinculacion=".$registroDocenteCoordinador[$j][3];
                                    $variable.="&nombreVinculacion=".$registroDocenteCoordinador[$j][4];
                                    $variable.="&periodoId=".$_REQUEST['periodoId'];
                                    $variable.="&anio=".$_REQUEST['anio'];
                                    $variable.="&periodo=".$_REQUEST['periodo'];
                                    $variable.="&nombreCarrera=".$registroDocenteCoordinador[$j][5];
                                    $variable.="&tipoId=3";

                                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                   // var_dump($carreraCoordinador);
                                    foreach ($carreraCoordinador as $key => $value)
                                    {
                                        //echo "CarrerasDecano".$carreraCoordinador[$key]['carrera']."<br>";
                                        //echo "docenteDecano".$carreraCoordinador[$key]['docente']."<br>";
                                        
                                        if($registroDocenteCoordinador[$j][2]==$carreraCoordinador[$key]['carrera'] && $registroDocenteCoordinador[$j][0]==$carreraCoordinador[$key]['docente'])
                                        {
                                            $valor[1]="(37)";
                                            $valor[2]=$_REQUEST['anio'];
                                            $valor[3]=$_REQUEST['periodo'];
                                            $valor['tipoId']="3";
                                            $valor['usuario']=$_REQUEST['usuario'];
                                            $valor['carreras']=$registroDocenteCoordinador[$j][2];

                                            $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosCarrera", $valor);
                                            $registroDcentesEvaluados = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                            
                                            echo "<tr>";
                                                    if($registroDcentesEvaluados[0][0]==$registroDocenteCoordinador[$j][0])
                                                    {
                                                        echo "<td>".$registroDocenteCoordinador[$j][0]."</td>
                                                        <td>".$registroDocenteCoordinador[$j][1]. " <a href='".$variable."'>(Docente evaluado)</a></td>
                                                        <td>".$registroDocenteCoordinador[$j][4]."</td>
                                                        <td>".$registroDocenteCoordinador[$j][5]."</td>    
                                                        <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 
                                                    }    
                                                    elseif($registroEventos[0][0]>0)
                                                    {
                                                        echo "<td>".$registroDocenteCoordinador[$j][0]."</td>
                                                        <td><a href='".$variable."'>".$registroDocenteCoordinador[$j][1]."</a></td>
                                                        <td>".$registroDocenteCoordinador[$j][4]."</td>
                                                        <td>".$registroDocenteCoordinador[$j][5]."</td>    
                                                        <td align='center'><a href='".$variable."'>               
                                                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                                        </a></td>"; 
                                                    }

                                                    else
                                                    {
                                                        echo "<td>".$registroDocenteCoordinador[$j][0]."</td>
                                                        <td>".$registroDocenteCoordinador[$j][1]." (Evaluación no habilitada)</td>
                                                        <td>".$registroDocenteCoordinador[$j][4]."</td>
                                                        <td>".$registroDocenteCoordinador[$j][5]."</td>    
                                                        <td align='center'></td>"; 
                                                    }

                                               echo "</tr>";
                                            //unset($variable);
                                        }    
                                    }       
                                }
                                $cadena_sql = $this->sql->cadena_sql("consultaDocenteCatedras", $valor);
                                $registroDocenteCatedras = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                               
                                unset($valor['tipoId']);
                                $valor['usuario']=$_REQUEST['usuario'];
                                $valor['tipoId']="6";
                                
                                for($k=0; $k<=count($registroDocenteCatedras)-1; $k++)
                                {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroDocenteCatedras[$k][0];
                                        $variable.="&docenteNombre=".$registroDocenteCatedras[$k][1];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroDocenteCatedras[$k][2] ;
                                        $variable.="&asignatura=0";
                                        $variable.="&grupo=0";
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=";
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroDocenteCatedras[$k][5];
                                        $variable.="&tipoId=6";

                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor[1]="(37)";
                                        $valor[2]=$_REQUEST['anio'];
                                        $valor[3]=$_REQUEST['periodo'];
                                        $valor['tipoId']="6";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['carreras']=$registroDocenteCatedras[$k][2];

                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosCarrera", $valor);
                                        $registroDcentesEvaluados = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                        
                                        echo "<tr>";
                                                if($registroDcentesEvaluados[0][0]==$registroDocenteCatedras[$k][0])
                                                {
                                                    echo "<td>".$registroDocenteCatedras[$k][0]."</td>
                                                    <td>".$registroDocenteCatedras[$k][1]. " <a href='".$variable."'> (Docente evaluado)</a></td>
                                                    <td>Cátedra Institucional</td>
                                                    <td>".$registroDocenteCatedras[$k][5]."</td>    
                                                    <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>";  
                                                }   
                                                elseif($registroEventos[0][0]>0)
                                                {
                                                   echo "<td>".$registroDocenteCatedras[$k][0]."</td>
                                                    <td><a href='".$variable."'>".$registroDocenteCatedras[$k][1]."</a></td>
                                                    <td>Cátedra Institucional</td>
                                                    <td>".$registroDocenteCatedras[$k][5]."</td>    
                                                    <td align='center'><a href='".$variable."'>               
                                                    <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                                    </a></td>"; 
                                                }
                                                else
                                                {
                                                    echo "<td>".$registroDocenteCatedras[$k][0]."</td>
                                                    <td>".$registroDocenteCatedras[$k][1]." (Evaluación no habilitada)</td>
                                                    <td>Cátedra Institucional</td>
                                                    <td>".$registroDocenteCatedras[$k][5]."</td>    
                                                    <td align='center'></td>"; 
                                                }

                                           echo "</tr>";
                                        //unset($variable);
                                    }
                                }   
                  echo "</tbody>";      
                  echo "</table>";

                  }
                  else
                  {
                        $tipo = 'information';
                        $mensaje = "Usuario no reconocido como Decano en el Sistema. ";

                        echo $mensaje;
                  }    
            break; 
          

            case 30: //Autoevaluación
                  if(is_array($registroCarga))
                  {    
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
                        for($i=0; $i<=count($registroCarga)-1; $i++)
                        {
                            $valor[0]=$registroCarga[$i][0];
                            $valor[1]="(36)";
                            $valor[2]=$_REQUEST['anio'];
                            $valor[3]=$_REQUEST['periodo'];
                            
                            $cadena_sql = $this->sql->cadena_sql("consultarEventos", $valor);
                            $registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                            
                            echo "<tr>
                                    <td>".$registroCarga[$i][0]."</td>";
                                    if($registroCarga[$i][6]=='N')
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroCarga[$i][1];
                                        $variable.="&docenteNombre=".$registroCarga[$i][2];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroCarga[$i][0] ;
                                        $variable.="&asignatura=0";
                                        $variable.="&grupo=0";
                                        $variable.="&tipoVinculacion=".$registroCarga[$i][4];
                                        $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroCarga[$i][3];
                                        $variable.="&tipoId=2";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor['tipoId']="2";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['carrera']=$registroCarga[$i][0];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        
                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosAutoevaluacion", $valor);
                                        $registroDcentesEvaluados = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                        
                                        if($_REQUEST['usuario']==$registroDcentesEvaluados[0][0])
                                        {
                                            echo "<td>".$registroCarga[$i][3]. " <a href='".$variable."'>(Evaluado)</a></td>
                                            <td>".$registroCarga[$i][5]."</td>   
                                            <td align='center'><a href='".$variable."'>Ver evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroCarga[$i][3]."</a></td>
                                            <td>".$registroCarga[$i][5]."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroCarga[$i][3]." (Evaluación no habilitada)</td>
                                            <td>".$registroCarga[$i][5]."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                                    else
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroCarga[$i][1];
                                        $variable.="&docenteNombre=".$registroCarga[$i][2];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroCarga[$i][0] ;
                                        $variable.="&asignatura=0";
                                        $variable.="&grupo=0";
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=".$registroCarga[$i][5];
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroCarga[$i][3];
                                        $variable.="&tipoId=5";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        $valor['tipoId']="2";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['carrera']=$registroCarga[$i][0];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        
                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosAutoevaluacion", $valor);
                                        $registroDcentesEvaluados = $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                        
                                        if($_REQUEST['usuario']==$registroDcentesEvaluados[0][0])
                                        {
                                            echo "<td>".$registroCarga[$i][3]. " <a href='".$variable."'>(Evaluado)</a></td>
                                            <td>".$registroCarga[$i][5]."</td>   
                                            <td align='center'><a href='".$variable."'>Ver evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroCarga[$i][3]." CÁTEDRA</a></td>
                                            <td>".$registroCarga[$i][5]."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroCarga[$i][3]." CÁTEDRA (Evaluación no habilitada)</td>
                                            <td>".$registroCarga[$i][5]."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                               echo "</tr>";
                            //unset($variable);
                        }
                  echo "</tbody>";      
                  echo "</table>";

                  }
                  else
                  {
                        $tipo = 'information';
                        $mensaje = "Usuario sin registros de carga académica. ";

                        echo $mensaje;
                  }
            break;

            case 51: //Evaluación Estudiantes Horas
                  if(is_array($registroAsignaturas))
                  {    
                    echo "<table id='tablaAsignaturas'>";

                    echo "<thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Docente</th>
                                <th>Asignatura</th>
                                <th>Evaluar</th>
                           </tr>
                        </thead>
                        <tbody>";
                    
                        for($i=0; $i<=count($registroAsignaturas)-1; $i++)
                        {
                            $valor[0]=$registroAsignaturas[$i][8];
                            $valor[1]="(53,54)";
                            $valor[2]=$_REQUEST['anio'];
                            $valor[3]=$_REQUEST['periodo'];
                            
                            $cadena_sql = $this->sql->cadena_sql("consultarEventos", $valor);
                            $registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                           
                            echo "<tr>
                                    <td>".$registroAsignaturas[$i][5]."</td>";
                                    if($registroAsignaturas[$i][9]=='N')//No es cátedra institucional
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroAsignaturas[$i][6];
                                        $variable.="&docenteNombre=".$registroAsignaturas[$i][7];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroAsignaturas[$i][8];
                                        $variable.="&asignatura=".$registroAsignaturas[$i][3];
                                        $variable.="&grupo=".$registroAsignaturas[$i][5];
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=";
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroAsignaturas[$i][4];
                                        $variable.="&tipoId=1";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor['tipoId']="1,4";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['asignatura']=$registroAsignaturas[$i][3];
                                        $valor['grupo']=$registroAsignaturas[$i][5];
                                        $valor['carrera']=$registroAsignaturas[$i][8];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        $valor['documentoId']=$registroAsignaturas[$i][6];


                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosEstudiantes", $valor);
                                        $registroDcentesEvaluadosEstudiantes= $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                       
                                        if($registroAsignaturas[$i][6]==$registroDcentesEvaluadosEstudiantes[0][0])
                                        {
                                            echo "<td>".$registroAsignaturas[$i][7]. " <a href='".$variable."'>(Docente ya evaluado)</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroAsignaturas[$i][7]."</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroAsignaturas[$i][4]." (Evaluación no habilitada)</td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                                    else //Es cátedra institucional
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroAsignaturas[$i][6];
                                        $variable.="&docenteNombre=".$registroAsignaturas[$i][7];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroAsignaturas[$i][8];
                                        $variable.="&asignatura=".$registroAsignaturas[$i][3];
                                        $variable.="&grupo=".$registroAsignaturas[$i][5];
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=";
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroAsignaturas[$i][4];
                                        $variable.="&tipoId=4";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor['tipoId']="1,4";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['asignatura']=$registroAsignaturas[$i][3];
                                        $valor['grupo']=$registroAsignaturas[$i][5];
                                        $valor['carrera']=$registroAsignaturas[$i][8];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        $valor['documentoId']=$registroAsignaturas[$i][6];


                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosEstudiantes", $valor);
                                        $registroDcentesEvaluadosEstudiantes= $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                       
                                        if($registroAsignaturas[$i][6]==$registroDcentesEvaluadosEstudiantes[0][0])
                                        {
                                            echo "<td>".$registroAsignaturas[$i][7]. " <a href='".$variable."'>(Docente ya evaluado)</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroAsignaturas[$i][4]." CÁTEDRA</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroAsignaturas[$i][4]." (Evaluación no habilitada)</td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                               echo "</tr>";
                            //unset($variable);
                        }
                  echo "</tbody>";      
                  echo "</table>";

                  }
                  else
                  {
                        $tipo = 'information';
                        $mensaje = "No tiene asignaturas registradas en el sistema. Consulte en su Coordinación de Carrera ";

                        echo $mensaje;
                  } 
            break;

             case 52: //Evaluación Estudiantes Créditos
                  if(is_array($registroAsignaturas))
                  {    
                    echo "<table id='tablaAsignaturas'>";

                    echo "<thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Docente</th>
                                <th>Asignatura</th>
                                <th>Evaluar</th>
                           </tr>
                        </thead>
                        <tbody>";
                    
                        for($i=0; $i<=count($registroAsignaturas)-1; $i++)
                        {
                            $valor[0]=$registroAsignaturas[$i][8];
                            $valor[1]="(53,54)";
                            $valor[2]=$_REQUEST['anio'];
                            $valor[3]=$_REQUEST['periodo'];
                            
                            $cadena_sql = $this->sql->cadena_sql("consultarEventos", $valor);
                            $registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                           
                            echo "<tr>
                                    <td>".$registroAsignaturas[$i][5]."</td>";
                                    if($registroAsignaturas[$i][9]=='N')
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroAsignaturas[$i][6];
                                        $variable.="&docenteNombre=".$registroAsignaturas[$i][7];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroAsignaturas[$i][8];
                                        $variable.="&asignatura=".$registroAsignaturas[$i][3];
                                        $variable.="&grupo=".$registroAsignaturas[$i][5];
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=";
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroAsignaturas[$i][4];
                                        $variable.="&tipoId=1";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor['tipoId']="1,4";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['asignatura']=$registroAsignaturas[$i][3];
                                        $valor['grupo']=$registroAsignaturas[$i][5];
                                        $valor['carrera']=$registroAsignaturas[$i][8];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        $valor['documentoId']=$registroAsignaturas[$i][6];


                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosEstudiantes", $valor);
                                        $registroDcentesEvaluadosEstudiantes= $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                        
                                        if($registroAsignaturas[$i][6]==$registroDcentesEvaluadosEstudiantes[0][0])
                                        {
                                            echo "<td>".$registroAsignaturas[$i][7]. " <a href='".$variable."'>(Docente evaluado)</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroAsignaturas[$i][7]."</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroAsignaturas[$i][4]." (Evaluación no habilitada)</td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                                    else
                                    {
                                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                                        $variable.="&opcion=formularios";
                                        $variable.="&usuario=". $_REQUEST['usuario'];
                                        $variable.="&documentoId=".$registroAsignaturas[$i][6];
                                        $variable.="&docenteNombre=".$registroAsignaturas[$i][7];
                                        $variable.="&tipo=". $_REQUEST['tipo'];
                                        $variable.="&carrera=".$registroAsignaturas[$i][8];
                                        $variable.="&asignatura=".$registroAsignaturas[$i][3];
                                        $variable.="&grupo=".$registroAsignaturas[$i][5];
                                        $variable.="&tipoVinculacion=0";
                                        $variable.="&nombreVinculacion=";
                                        $variable.="&periodoId=".$_REQUEST['periodoId'];
                                        $variable.="&anio=".$_REQUEST['anio'];
                                        $variable.="&periodo=".$_REQUEST['periodo'];
                                        $variable.="&nombreCarrera=".$registroAsignaturas[$i][4];
                                        $variable.="&tipoId=4";
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                        
                                        $valor['tipoId']="1,4";
                                        $valor['usuario']=$_REQUEST['usuario'];
                                        $valor['asignatura']=$registroAsignaturas[$i][3];
                                        $valor['grupo']=$registroAsignaturas[$i][5];
                                        $valor['carrera']=$registroAsignaturas[$i][8];
                                        $valor['anio']=$_REQUEST['anio'];
                                        $valor['periodo']=$_REQUEST['periodo'];
                                        $valor['documentoId']=$registroAsignaturas[$i][6];


                                        $cadena_sql = $this->sql->cadena_sql("docentesEvaluadosEstudiantes", $valor);
                                        $registroDcentesEvaluadosEstudiantes= $esteRecursoDBPG->ejecutarAcceso($cadena_sql, "busqueda");
                                      
                                        if($registroAsignaturas[$i][6]==$registroDcentesEvaluadosEstudiantes[0][0])
                                        {
                                            echo "<td>".$registroAsignaturas[$i][7]. " <a href='".$variable."'>(Docente evaluado)</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][4])."</td>   
                                            <td align='center'><a href='".$variable."'>Ver Evaluación</a></td>"; 
                                        }    
                                        elseif($registroEventos[0][0]>0)
                                        {
                                           echo "<td><a href='".$variable."'>".$registroAsignaturas[$i][4]." CÁTEDRA</a></td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>   
                                            <td align='center'><a href='".$variable."'>               
                                            <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                                            </a></td>"; 
                                        }
                                        else
                                        {
                                            echo "<td>".$registroAsignaturas[$i][4]." (Evaluación no habilitada)</td>
                                            <td>".utf8_decode($registroAsignaturas[$i][7])."</td>    
                                            <td align='center'></td>"; 
                                        }
                                    }
                               echo "</tr>";
                            //unset($variable);
                        }
                  echo "</tbody>";      
                  echo "</table>";

                  }
                  else
                  {
                        $tipo = 'information';
                        $mensaje = "No tiene asignaturas registradas en el sistema. Consulte en su Coordinación de Carrera ";

                        echo $mensaje;
                  } 
            break;  

    }
           
                //-------------Fin de Conjunto de Controles----------------------------
        echo $this->miFormulario->marcoAgrupacion("fin");

        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");


