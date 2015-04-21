<?php

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
$directorioImagenes = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/images";

$conexion = "coordinador";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);


if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}
switch($_REQUEST["funcion"]){

	case "#nombreDoc":
		$cadena_sql = $this->sql->cadena_sql("buscarNombreDocente", $_REQUEST["name_startsWith"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;
            
        case "#apellidoDoc":
		$cadena_sql = $this->sql->cadena_sql("buscarApellidoDocente", $_REQUEST["name_startsWith"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break; 
            
        case "#nombreDocNuevo":
		$cadena_sql = $this->sql->cadena_sql("buscarNombreDocente", $_REQUEST["name_startsWith"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;   
            
        case "#docenteNuevoCurso":
		$cadena_sql = $this->sql->cadena_sql("buscarNombreDocente", $_REQUEST["name_startsWith"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;    
            
        case "#divDatosDocente":
		$cadena_sql = $this->sql->cadena_sql("datosDocente", $_REQUEST["identificacion"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;    
        
        case "#curso":
		$cadena_sql = $this->sql->cadena_sql("buscarCursoProyecto", $_REQUEST["idProyecto"]);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;   
            
        case "#cuerpoHorario":
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                $arregloHorario = array($asignatura,$grupo,$_REQUEST['ano'],$_REQUEST['periodo']);
		$cadena_sql = $this->sql->cadena_sql("horarioCurso", $arregloHorario); 
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break; 
        case "#cuerpoAsignacion":
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                $arregloHorario = array($asignatura,$grupo,$_REQUEST['ano'],$_REQUEST['periodo']);
		$cadena_sql = $this->sql->cadena_sql("cargaCurso", $arregloHorario); 
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;
            
        case "#respuestaGuardar":
            
                //Verificamos el curso al que se desea asignar
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                $proyecto = $_REQUEST['proyecto'];
                $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                $horasDocNuevo = $_REQUEST['horasDocNuevo'];
                
                $arregloAsignacion = array($annio, $periodo, $proyecto, $identificacionDocente, $asignatura, $grupo, 'A', $horasDocNuevo);

                //$cadena_sql = $this->sql->cadena_sql("cargaCurso", $arregloAsignacion);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            break;
        
        case "#respuestaModificar":
            
                //Verificamos el curso al que se desea asignar
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                $proyecto = $_REQUEST['proyecto'];
                $identificacionDocente = $_REQUEST['identificacionDocenteModificar'];
                $horasDocNuevo = $_REQUEST['horasDoc'];
                
                $arregloAsignacion = array($annio, $periodo, $proyecto, $identificacionDocente, $asignatura, $grupo, 'A', $horasDocNuevo);

                $cadena_sql = $this->sql->cadena_sql("modificarAsignacionDocente", $arregloAsignacion);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "accion");
            break;
        
            case "#respuestaEliminar":
            
                //Verificamos el curso al que se desea asignar
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                $proyecto = $_REQUEST['proyecto'];
                $identificacionDocente = $_REQUEST['identificacionDocenteEliminar'];
                
                $arregloAsignacion = array($annio, $periodo, $proyecto, $identificacionDocente, $asignatura, $grupo, 'A');

                $cadena_sql = $this->sql->cadena_sql("eliminarAsignacionDocente", $arregloAsignacion);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "accion");
            break;
          
            case "#cursosTodos":
                                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                $proyecto = $_REQUEST['proyecto'];
                
                $arregloCursos = array($proyecto, $annio, $periodo);
                
		$cadena_sql = $this->sql->cadena_sql("buscarCursosProyectoTodos", $arregloCursos);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
		break;
            
           case "#cuerpoAsignacionCurso":
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                $proyecto=$arregloCurso[2];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                
                $arregloHorario = array($asignatura,$grupo,$annio,$periodo,$proyecto);
		$cadena_sql = $this->sql->cadena_sql("cargaCursoTab", $arregloHorario);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                break; 
            
           case "#respuestaGuardarCurso":
            
               
                $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                $tipoVinculacion = $_REQUEST['tipoVinculacion'];
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                
                $mensajeCruce= '';
                
                unset($registro);
                //Verificamos el curso al que se desea asignar
                $arregloHoras = json_decode(stripslashes($_REQUEST['horasSeleccionadas']));

                foreach ($arregloHoras as $idHorario) {
                $arregloAsignacion = array($idHorario, $identificacionDocente, $tipoVinculacion, 'A');
                
                $arregloCruce = array($asignatura,$grupo,$annio,$periodo,$identificacionDocente, $idHorario);
                
                $cadena_sqlCruce = $this->sql->cadena_sql("cruceHorarioTabDocenteHora", $arregloCruce);
                $registroVerificaCruce=$esteRecursoDB->ejecutarAcceso($cadena_sqlCruce,"busqueda");
                
                if(!$registroVerificaCruce)
                    {
                        $cadena_sqlCrucePlan = $this->sql->cadena_sql("crucePlanTrabDocenteHora", $arregloCruce);
                        $registroVerificaCrucePlan=$esteRecursoDB->ejecutarAcceso($cadena_sqlCrucePlan,"busqueda");
                        if(!$registroVerificaCrucePlan)
                        {
                        $cadena_sqlInsertar = $this->sql->cadena_sql("guardarCargaCurso", $arregloAsignacion);
                        echo $cadena_sqlInsertar; exit;
                        $registro=$esteRecursoDB->ejecutarAcceso($cadena_sqlInsertar,"accion");
                        }else{
                            $mensajeCruce.="No se puede agregar por cruce de Plan de trabajo (".$registroVerificaCrucePlan[0][0]." - ".$registroVerificaCrucePlan[0][1].")\n";
                        }
                    }else
                        {
                            $mensajeCruce.="No se puede agregar por cruce de horario (".$registroVerificaCruce[0][0]." - ".$registroVerificaCruce[0][1].")\n";
                        }
                }
                
                $registro = TRUE;
                /*
                
                if(!$registroVerificaCruce)
                    {
                         unset($registro);
                            //Verificamos el curso al que se desea asignar
                            $arregloHoras = json_decode(stripslashes($_REQUEST['horasSeleccionadas']));

                            foreach ($arregloHoras as $idHorario) {
                            $arregloAsignacion = array($idHorario, $identificacionDocente, $tipoVinculacion, 'A');

                            $cadena_sqlInsertar = $this->sql->cadena_sql("guardarCargaCurso", $arregloAsignacion);
                            $registro=$esteRecursoDB->ejecutarAcceso($cadena_sqlInsertar,"accion");
                            $registro = TRUE;
                            }
                        
                    }else
                        {
                            $registro = FALSE;
                        }
                */
             break;           
               
            case "#respuestaModificarCurso":
            
                $arregloCurso = explode('-', $_REQUEST['curso']);
                $asignatura = $arregloCurso[0];
                $grupo = $arregloCurso[1];
                
                $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                $annio = $arregloPeriodo[0];
                $periodo = $arregloPeriodo[1];
                
                $mensajeCruce= '';
                //Verificamos el curso al que se desea asignar
                $arregloHorasSeleccionadas = json_decode(stripslashes($_REQUEST['horasSeleccionadas']));
                $arregloHorasNoSeleccionadas = json_decode(stripslashes($_REQUEST['horasNoSeleccionadas']));
                
                $arregloHorasNoSeleccionadasArreglo = get_object_vars($arregloHorasNoSeleccionadas); 
                
                if(is_array($arregloHorasNoSeleccionadasArreglo))
                    {
                        foreach ($arregloHorasNoSeleccionadasArreglo as $idHorarioNo) 
                            {
                                $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                                $tipoVinculacion = $_REQUEST['tipoVinculacion'];

                                $arregloAsignacion = array($idHorarioNo, $identificacionDocente,$tipoVinculacion, 'A');

                                $cadena_sql = $this->sql->cadena_sql("buscarCursoNoSeleccionado", $arregloAsignacion);
                                $registroVerifica=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                                
                                if(is_array($registroVerifica))
                                    {
                                        $cadena_sql = $this->sql->cadena_sql("inhabilitarCarga", $arregloAsignacion);
                                        $registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"accion");
                                        echo $cadena_sql; exit;
                                    }
                                unset($registroVerifica);    
                            }
                    }
                    
                
                foreach ($arregloHorasSeleccionadas as $idHorarioSi) {
                    
                    $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                    $tipoVinculacion = $_REQUEST['tipoVinculacion'];
                    
                    $arregloAsignacion = array($idHorarioSi, $identificacionDocente,$tipoVinculacion, 'A');

                            $cadena_sql = $this->sql->cadena_sql("buscarCursoSiSeleccionado", $arregloAsignacion);
                            $registroVerifica=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

                            if(!$registroVerifica)
                                {
                                    $arregloCruce = array($asignatura,$grupo,$annio,$periodo,$identificacionDocente, $idHorarioSi);
                
                                    $cadena_sqlCruce = $this->sql->cadena_sql("cruceHorarioTabDocenteHora", $arregloCruce);
                                    $registroVerificaCruce=$esteRecursoDB->ejecutarAcceso($cadena_sqlCruce,"busqueda");

                                    if(!$registroVerificaCruce)
                                        {
                                            $cadena_sqlCrucePlan = $this->sql->cadena_sql("crucePlanTrabDocenteHora", $arregloCruce);
                                            $registroVerificaCrucePlan=$esteRecursoDB->ejecutarAcceso($cadena_sqlCrucePlan,"busqueda");
                                            if(!$registroVerificaCrucePlan)
                                            {
                                            $cadena_sql = $this->sql->cadena_sql("guardarCargaCursoModificar", $arregloAsignacion);
                                            echo $cadena_sql; exit;
                                            $registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"accion");
                                                
                                            }else{
                                                $mensajeCruce.="No se puede agregar por cruce de Plan de trabajo (".$registroVerificaCrucePlan[0][0]." - ".$registroVerificaCrucePlan[0][1].")\n";
                                            }
                                        }else
                                            {
                                                $mensajeCruce.="No se puede agregar por cruce de horario (".$registroVerificaCruce[0][0]." - ".$registroVerificaCruce[0][1].")\n";
                                            }
                                }
                                unset($registroVerifica);
                        
                        
                    }

                    $registro = TRUE;
            break; 
            
            case "#respuestaEliminarCurso":
            
                //Verificamos el curso al que se desea asignar
                $arregloHorasSeleccionadas = json_decode(stripslashes($_REQUEST['horasSeleccionadas']));
                $arregloHorasNoSeleccionadas = json_decode(stripslashes($_REQUEST['horasNoSeleccionadas']));
                
                $arregloHorasNoSeleccionadasArreglo = get_object_vars($arregloHorasNoSeleccionadas); 
                
                if(is_array($arregloHorasNoSeleccionadasArreglo))
                    {
                        foreach ($arregloHorasNoSeleccionadasArreglo as $idHorarioNo) 
                            {
                                $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                                $tipoVinculacion = $_REQUEST['tipoVinculacion'];

                                $arregloAsignacion = array($idHorarioNo, $identificacionDocente,$tipoVinculacion, 'A');

                                $cadena_sql = $this->sql->cadena_sql("buscarCursoNoSeleccionado", $arregloAsignacion);
                                $registroVerifica=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                                
                                if(is_array($registroVerifica))
                                    {
                                        $cadena_sql = $this->sql->cadena_sql("inhabilitarCarga", $arregloAsignacion);
                                        $registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"accion");
                                        echo $cadena_sql; exit;
                                    }
                                unset($registroVerifica);    
                            }
                    }
                    
                
                foreach ($arregloHorasSeleccionadas as $idHorarioSi) {
                    $identificacionDocente = $_REQUEST['identificacionDocenteNuevo'];
                    $tipoVinculacion = $_REQUEST['tipoVinculacion'];
                
                    $arregloAsignacion = array($idHorarioSi, $identificacionDocente,$tipoVinculacion, 'A');

                    $cadena_sql = $this->sql->cadena_sql("buscarCursoSiSeleccionado", $arregloAsignacion);
                    $registroVerifica=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                    
                    if(is_array($registroVerifica))
                        {
                            $cadena_sql = $this->sql->cadena_sql("inhabilitarCarga", $arregloAsignacion);
                            $registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"accion");
                            echo $cadena_sql; exit;
                        }
                        unset($registroVerifica);
                }
                
                $registro = TRUE;
            break;
        
        
}

//echo $cadena_sql;
//print_r($registro);

if (is_array($registro)) {

	if(($_REQUEST["funcion"]=="#nombreDoc") || ($_REQUEST["funcion"]=="#docenteNuevoCurso")){

		//Para autocomplete
		$respuesta = '[';

		foreach ($registro as $fila) {
			$respuesta.='{';
			$respuesta.='"label":"' . $fila[0] . ' - ' . $fila[1] . '",';
			$respuesta.='"value":"' . $fila[0] . '"';
			$respuesta.='},';
		}

		$respuesta = substr($respuesta, 0, strlen($respuesta) - 1);
		$respuesta.=']';
	}else if(($_REQUEST["funcion"]=="#curso")){
		//Para jqgrid

                $respuesta="<select id='cursos' class='FormElement ui-widget-content ui-corner-all' role='select' name='cursos' size='1' onchange='cargarHorario();cargarAsignacion()'>";
                $respuesta.="<option role='option' value='-1'>Seleccionar...</option>";
                foreach ($registro as $fila) {
                        $respuesta.="<option role='option' value='".$fila[0].'-'.$fila[2]."'>". utf8_decode($fila[1])."</option>";
                }
		
		$respuesta.='</select>';
        }else if(($_REQUEST["funcion"]=="#cuerpoHorario"))
            {
                
            
                $respuesta = "<table width='100%' class='jqueryui' border='0'>";
                
                $respuesta.="<tr class='jqueryui'>";                    
                    $respuesta.="<th rowspan='2' class='celda_titulo_horario'>HORA</th>";
                    $respuesta.="<th colspan='6' class='celda_titulo_horario'>DIA</th>";
                $respuesta.="</tr>";
                $respuesta.="<tr class='jqueryui centrar'>";                    
                    $respuesta.="<th class='celda_titulo_horario'>LUNES</th>";
                    $respuesta.="<th class='celda_titulo_horario'>MARTES</th>";
                    $respuesta.="<th class='celda_titulo_horario'>MIERCOLES</th>";
                    $respuesta.="<th class='celda_titulo_horario'>JUEVES</th>";
                    $respuesta.="<th class='celda_titulo_horario'>VIERNES</th>";
                    $respuesta.="<th class='celda_titulo_horario'>SABADO</th>";
                $respuesta.="</tr>";
                
                for($i=6;$i<=23;$i++)
                {
                    $respuesta.="<tr class='jqueryui'>";
                    $respuesta.="<th class='celda_titulo_horario'>";
                        if($i<12)
                            {
                                $respuesta.=$i." A.M.";
                            }else if($i==12)
                                {
                                    $respuesta.=$i ." M";
                                }else 
                                {
                                    $respuesta.=($i - 12)." P.M.";
                                }
                                
                        
                    $respuesta.="</td>";
                    for($j=1;$j<=6;$j++)
                    {
                        $respuesta.="<td class='celda_hora'>";
                        for($m=0;$m<count($registro);$m++)
                        {
                            if(($registro[$m]['HOR_DIA_NRO'] == $j) && ($registro[$m]['HOR_HORA'] == $i))
                                {
                                    $respuesta.="Sede: ".$registro[$m]['SED_NOMBRE'];
                                    $respuesta.="<br>Salon: ".$registro[$m]['SAL_NOMBRE'];
                                }else
                                    {
                                        $respuesta.=" ";
                                    }
                        }
                        $respuesta.="</td>";
                    }
                    $respuesta.="</tr>";
                }
                
                
                $respuesta .= "</table>";
                
                
            }else if(($_REQUEST["funcion"]=="#cuerpoAsignacion"))
            {
        
        
                $respuesta = "<table width='100%' class='jqueryui' border='0'>";
                
                $respuesta.="<tr class='jqueryui centrar'>";
                $respuesta.="<td class='centrar' colspan='10' align='center'>";
                $respuesta.="<a onclick='adicionarDocente();'><img src='".$directorioImagenes."/add_user.png'><br>Adicionar Docente</a>";
                $respuesta.="</td>";
                $respuesta.="</tr>";
                
                //Consulta para los nombres de los docentes
                $cadena_sqlDocentes = $this->sql->cadena_sql("buscarDocentes");
                $registroDocentes = $esteRecursoDB->ejecutarAcceso($cadena_sqlDocentes, "busqueda");
                
                $respuesta.="<tr class='jqueryui'>"; 
                $respuesta.="<td colspan='10'>";
                $respuesta.="<div name='adicionarDocente' id='adicionarDocente' style='display: none;'> ";
                    $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="Seleccione el docente:";
                            $respuesta.="</td>"; 
                            $respuesta.="<td align='center'>";
                            $respuesta.="<select id='docenteNuevo' name='docenteNuevo'>";
                            $respuesta.="<option value='0'>Seleccione...</option>";
                            for($i=0;$i<count($registroDocentes);$i++)
                            {
                                $respuesta.="<option value='".$registroDocentes[$i][0]."'>".$registroDocentes[$i][1]."</option>";
                            }
                            $respuesta.="</select>"; 
                            //$respuesta.="<input id='nombreDocNuevo' class='ui-widget ui-widget-content validate[required]' type='text' tabindex='2' maxlength='100' size='40' name='nombreDocNuevo' title='Digite el nombre o apellido del docente:' autocomplete='off'><br>";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="Digite el número de horas a asignar:";
                            $respuesta.="</td>"; 
                            $respuesta.="<td align='center'>";
                            $respuesta.="<input id='horasDocNuevo' class='ui-widget ui-widget-content validate[required]' type='text' tabindex='2' maxlength='100' size='5' name='horasDocNuevo' title='Digite el número de horas a asignar:' autocomplete='off'>";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td class='centrar' colspan='10' align='center'>";
                            $respuesta.="<input type='button' id='guardarNuevo' name='guardarNuevo' value='Guardar' class='ui-widget ui-widget-content jqueryui' onclick='guardarDocente();' >";
                            $respuesta.="<div name='respuestaGuardar' id='respuestaGuardar' style='display: none;'></div> ";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                    $respuesta.="</table>";    
                $respuesta.="</div> "; 
                $respuesta.="</td>"; 
                $respuesta.="</tr>";
                
                
                $respuesta.="<tr class='jqueryui'>";                    
                    $respuesta.="<th class='celda_titulo_horario'>DOCENTE</th>";
                    $respuesta.="<th class='celda_titulo_horario'>IDENTIFICACIÓN</th>";
                    $respuesta.="<th class='celda_titulo_horario'>PROYECTO</th>";
                    $respuesta.="<th class='celda_titulo_horario'>HORAS ASIGNADAS</th>";
                    $respuesta.="<th class='celda_titulo_horario'>MODIFICAR CARGA</th>";
                    $respuesta.="<th class='celda_titulo_horario'>BORRAR CARGA</th>";
                $respuesta.="</tr>";
                
                for($m=0;$m<count($registro);$m++)
                {
                    $respuesta.="<tr class='jqueryui'>";                    
                        $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['doc_nombre'].' '.$registro[$m]['doc_apellido']."</td>";
                        $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['car_doc_nro_iden']."</td>";
                        $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['cra_nombre']."</td>";
                        $respuesta.="<td class='celda_hora' align='center'><div id='horas".$registro[$m]["car_doc_nro_iden"]."' name='horas".$registro[$m]['car_doc_nro_iden']."'>".$registro[$m]['car_nro_hrs']."</div></td>";
                        $respuesta.="<td class='celda_hora' align='center'><a onclick='modificarDocente(".$registro[$m]["car_doc_nro_iden"]." );'><img src='".$directorioImagenes."/edit.png'></a></td>";
                        $respuesta.="<td class='celda_hora' align='center'><a onclick='eliminarDocente(".$registro[$m]["car_doc_nro_iden"].");'><img src='".$directorioImagenes."/cancel.png'></a></td>";
                    $respuesta.="</tr>";
                    
                    /*
                     * DIV PARA MODIFICAR LAS HORAS POR DOCENTE
                     */
                    $respuesta.="<tr class='jqueryui'>"; 
                    $respuesta.="<td colspan='10'>"; 
                    $respuesta.="<div name='modifica".$registro[$m]['car_doc_nro_iden']."' id='modifica".$registro[$m]['car_doc_nro_iden']."' style='display: none;'> ";
                    $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="Docente:";
                            $respuesta.="</td>"; 
                            $respuesta.="<td align='center'>";
                            $respuesta.=$registro[$m]['doc_nombre'].' '.$registro[$m]['doc_apellido'];
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="Digite el número de horas a asignar:";
                            $respuesta.="</td>"; 
                            $respuesta.="<td align='center'>";
                            $respuesta.="<input id='horasDoc".$registro[$m]['car_doc_nro_iden']."' value='".$registro[$m]['car_nro_hrs']."' class='ui-widget ui-widget-content validate[required]' type='text' tabindex='2' maxlength='100' size='5' name='horasDoc".$registro[$m]['car_doc_nro_iden']."' title='Digite el número de horas a asignar:' autocomplete='off'>";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td class='centrar' colspan='10' align='center'>";
                            $respuesta.="<input type='button' id='guardarModificar' name='guardarModificar' value='Guardar' class='ui-widget ui-widget-content jqueryui' onclick='modificarHorasDocente(".$registro[$m]['car_doc_nro_iden'].");' >";
                            $respuesta.="<div name='respuestaModificar' id='respuestaModificar' style='display: none;'></div> ";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                    $respuesta.="</table>";    
                    $respuesta.="</div> ";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    
                    /*
                     * DIV PARA ELIMINAR LAS HORAS POR DOCENTE
                     */
                    $respuesta.="<tr class='jqueryui'>"; 
                    $respuesta.="<td colspan='10'>"; 
                    $respuesta.="<div name='elimina".$registro[$m]['car_doc_nro_iden']."' id='elimina".$registro[$m]['car_doc_nro_iden']."' style='display: none;'> ";
                    $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="Esta seguro que desea eliminar las horas asignadas a este curso?:";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                        $respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td class='centrar' colspan='10' align='center'>";
                            
                            $respuesta.="<input type='button' id='guardarEliminar' name='guardarEliminar' value='Si' class='ui-widget ui-widget-content jqueryui' onclick='eliminarHorasDocente(".$registro[$m]['car_doc_nro_iden'].");' >";
                            $respuesta.="<input type='button' id='noguardarEliminar' name='noguardarEliminar' value='No' class='ui-widget ui-widget-content jqueryui' onclick='eliminarDocente(".$registro[$m]['car_doc_nro_iden'].");' >";
                            
                            $respuesta.="<div name='respuestaEliminar' id='respuestaEliminar' style='display: none;'></div> ";
                            $respuesta.="</td>"; 
                        $respuesta.="</tr>";
                    $respuesta.="</table>";    
                    $respuesta.="</div> ";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    
                }
                $respuesta .= "</table>";
                
                
            }else if(($_REQUEST["funcion"]=="#divDatosDocente"))
            {
                
                $respuesta ="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                $respuesta .= "<legend class='ui-state-default ui-corner-all'> Información del Docente:</legend>";
                
                $respuesta .= "<div class='campoTextoEtiqueta'>";
                $respuesta .= "Nombre: ";
                $respuesta .= "</div>";
                $respuesta .= "<div class='campoTextoContenido'>";
                $respuesta .= nl2br($_REQUEST['nombreDoc']);                
                $respuesta .= "</div>";
                
                $respuesta .= "<div class='campoTextoEtiqueta'>";
                $respuesta .= "Tipo de Vinculación: ";
                $respuesta .= "</div>";
                $respuesta .= "<div class='campoTextoContenido'>";
                $respuesta .= nl2br($registro[0]['tvi_nombre']);
                $respuesta .= "</div>";
                
                
                $respuesta .= "<div class='campoTextoEtiqueta'>";
                $respuesta .= "Proyecto: ";
                $respuesta .= "</div>";
                $respuesta .= "<div class='campoTextoContenido'>";
                $respuesta .= nl2br($registro[0]['cra_nombre']);
                $respuesta .= "</div>";
                               
            }else if(($_REQUEST["funcion"]=="#respuestaModificar"))
                {
                    $respuesta.=$_REQUEST['horasDoc'];
                }else if(($_REQUEST["funcion"]=="#respuestaEliminar"))
                {
                    $respuesta.=$cadena_sql;
                }else if(($_REQUEST["funcion"]=="#cursosTodos"))
                    {
                        $respuesta .= "<br>".$cadena_sql;
                        $respuesta ="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                        $respuesta .= "<legend class='ui-state-default ui-corner-all'> Cursos Registrados:</legend>";

                        for($i=0;$i<count($registro);$i++)
                        {
                            $respuesta .= "<div id='".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' name='".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' class='celda_titulo_cursos'>";
                            $respuesta .= "<table width='100%'>";
                            $respuesta .= "<tr>";
                            $respuesta .= "<td><a style='text-decoration:none' href='#' title='Click aqui para ver la información del curso' onclick='mostrarInfoCurso(\"".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."\");return false;'><img id='flecha".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' src='".$directorioImagenes."/rightarrow.png' width='12px'></a></td>";
                            $respuesta .= "<td width='60%'><a href='#' style='text-decoration:none' onclick='mostrarInfoCurso(\"".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."\");return false;'>".$registro[$i][0]." - ".$registro[$i][1]."</a></td>";
                            $respuesta .= "<td align='center'><a style='text-decoration:none' href='#' onclick='mostrarInfoCurso(\"".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."\");return false;'> GRUPO: ".$registro[$i][2]."</td>";
                            $respuesta.="<td align='center'>";
                            $respuesta.="<div id='imgAdicionDocente".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' style='display: none'><a href='#' onclick='adicionarDocenteCurso(\"".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."\");return false;' style='text-decoration:none'><img src='".$directorioImagenes."/add_user.png' width='16px'>Adicionar Docente</a></div>";
                            $respuesta.="</td>";                            
                            
                            $respuesta .= "</tr>";
                            $respuesta .= "</table>";
                            $respuesta .= "</div>";
                            $respuesta .= "<div id='info".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' name='info".$registro[$i][0]."-".$registro[$i][2]."-".$registro[$i][3]."' class='celda_hora' style='display: none;' >";
                            $respuesta .= "</div>";
                        }
                        
                        $respuesta .= "</fieldset>";
                    }else if(($_REQUEST["funcion"]=="#cuerpoAsignacionCurso"))
                        {
                            //echo "<br>".$cadena_sql;
                            //Verificamos el curso al que se desea asignar
                            $arregloCurso = explode('-', $_REQUEST['curso']);
                            $asignatura = $arregloCurso[0];
                            $grupo = $arregloCurso[1];
                            $proyecto = $arregloCurso[2];
                            
                            $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                            $annio = $arregloPeriodo[0];
                            $periodo = $arregloPeriodo[1];
                            
                            $arregloHorario = array($asignatura,$grupo,$annio,$periodo,$proyecto);
                            
                            //Consulta para los nombres de los docentes
                            $cadena_sqlHorario = $this->sql->cadena_sql("horarioCursoTabCurso",$arregloHorario);
                            $registroHorario = $esteRecursoDB->ejecutarAcceso($cadena_sqlHorario, "busqueda");
                         
                            $respuesta = "<table width='100%' class='jqueryui' border='0'>";

                            $respuesta .= "<caption>DOCENTES ASIGNADOS</caption>";
                            /*$respuesta.="<tr class='jqueryui centrar'>";
                            $respuesta.="<td class='centrar' colspan='10' align='center'>";
                            $respuesta.="<a onclick='adicionarDocenteCurso(\"".$_REQUEST['curso']."\");'><img src='".$directorioImagenes."/add_user.png'><br>Adicionar Docente</a>";
                            $respuesta.="</td>";
                            $respuesta.="</tr>";*/

                            $respuesta.="<tr class='jqueryui'>";                    
                                $respuesta.="<th class='celda_titulo_cursos'>DOCENTE</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>IDENTIFICACIÓN</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>PROYECTO</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>HORAS ASIGNADAS</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>TIPO VINCULACIÓN</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>MODIFICAR CARGA</th>";
                                $respuesta.="<th class='celda_titulo_cursos'>BORRAR CARGA</th>";
                            $respuesta.="</tr>";
                           
                            for($m=0;$m<=count($registro)-1;$m++)
                            {
                                $respuesta.="<tr class='jqueryui'>";                    
                                    $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['doc_nombre'].' '.$registro[$m]['doc_apellido']."</td>";
                                    $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['car_doc_nro']."</td>";
                                    $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['cra_nombre']."</td>";
                                    $respuesta.="<td class='celda_hora' align='center'><div id='horas".$registro[$m]["car_doc_nro"]."' name='horas".$registro[$m]['car_doc_nro']."'>".$registro[$m]['horas']."</div></td>";
                                    $respuesta.="<td class='celda_hora' align='center'>".$registro[$m]['tvi_nombre']."</td>";
                                    $respuesta.="<td class='celda_hora' align='center'><a onclick='modificarDocente(".$registro[$m]["car_doc_nro"].", \"".$_REQUEST['curso']."\");'><img src='".$directorioImagenes."/edit.png'></a></td>";
                                    $respuesta.="<td class='celda_hora' align='center'><a onclick='eliminarDocente(".$registro[$m]["car_doc_nro"].", \"".$_REQUEST['curso']."\");'><img src='".$directorioImagenes."/cancel.png'></a></td>";
                                $respuesta.="</tr>";

                                /*
                                 * DIV PARA MODIFICAR LAS HORAS POR DOCENTE
                                 */
                                $arregloHorarioDocente = array($asignatura,$grupo,$annio,$periodo,$registro[$m]['car_doc_nro'],$proyecto);
                                $cadena_sqlHorarioDocente = $this->sql->cadena_sql("cargaCursoTabDocente",$arregloHorarioDocente);
                                $registroHorarioDocente = $esteRecursoDB->ejecutarAcceso($cadena_sqlHorarioDocente, "busqueda");
                                
                                $respuesta.="<tr class='jqueryui'>"; 
                                $respuesta.="<td colspan='10'>"; 
                                $respuesta.="<div name='".$_REQUEST['curso']."modifica".$registro[$m]['car_doc_nro']."' id='".$_REQUEST['curso']."modifica".$registro[$m]['car_doc_nro']."' style='display: none;'> ";
                                $respuesta.="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                                $respuesta .= "<legend class='ui-state-default ui-corner-all'> Modificar Carga a Docente: ".$registro[$m]['doc_nombre'].' '.$registro[$m]['doc_apellido']." </legend>";
                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                
                                $respuesta.="<tr class='celda_sin_registros'>";
                                        $respuesta.="<td align='center' colspan='6'>";
                                        if(count($registroHorarioDocente) > 0)
                                            {
                                                $tituloHorasSelec = count($registroHorarioDocente);
                                                
                                            }else
                                                {
                                                    $tituloHorasSelec = "0";                                                
                                                }
                                        $respuesta.="Horas Asignadas: ";                                         
                                        $respuesta.="<input type='hidden' id='".$registro[$m]['car_doc_nro']."modcontHorasSelecCurso".$_REQUEST['curso']."' value='".$tituloHorasSelec."'> ";
                                        $respuesta.="<input type='hidden' id='".$registro[$m]['car_doc_nro']."modtotalHorasSelecCurso".$_REQUEST['curso']."' value='".count($registroHorario)."'> ";
                                        $respuesta.="<div id='".$registro[$m]['car_doc_nro']."modhorasSelecCurso".$_REQUEST['curso']."'> ".$tituloHorasSelec." </div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    
                                           $respuesta.="<tr>"; 
                                            $respuesta.="<td colspan='6'>";
                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                $respuesta.="<caption class='celda_sin_registros'>SELECCIONE LAS HORAS A ASIGNAR AL DOCENTE</caption>";
                                                $respuesta.="<tr>"; 
                                                
                                                if(is_array($registroHorario)) 
                                                    {
                                                
                                                    for($i=0;$i<count($registroHorario);$i++)
                                                    {
                                                        if($registroHorario[$i][2] != (isset($registroHorario[$i-1][2])?$registroHorario[$i-1][2]:''))
                                                            {
                                                                if($i>0)
                                                                    {
                                                                        $respuesta.="</table>";
                                                                        $respuesta.="</td>";
                                                                    }                                                                
                                                                $respuesta.="<td>";
                                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                                $respuesta.="<caption class='celda_titulo_horario_pequeno'><b>".$registroHorario[$i][3]."<b></caption>"; 
                                                                
                                                            }
                                                            $claseCelda = "celda_hora";
                                                            for($p=0;$p<count($registroHorarioDocente);$p++)
                                                            {
                                                                if($registroHorarioDocente[$p][1] == $registroHorario[$i][8])
                                                                    {
                                                                        $claseCelda = "celda_hora_seleccionada";
                                                                    }
                                                            }
                                                            $respuesta.="<tr id='".$registro[$m]['car_doc_nro']."Curso".$_REQUEST['curso']."modifica".$i."' class='".$claseCelda."' onclick='modificarHorasDocCurso(\"".$registro[$m]['car_doc_nro']."\", \"".$_REQUEST['curso']."\",\"".$i."\")'>";
                                                                $respuesta.="<td>";
                                                                $respuesta.="<input type='hidden' id='".$registro[$m]['car_doc_nro']."Curso".$_REQUEST['curso']."modhoraSelecHorario".$i."' value='".$registroHorario[$i][8]."'> ";
                                                                    $respuesta.=$registroHorario[$i][5];
                                                                $respuesta.="</td>";
                                                                $respuesta.="<td>";
                                                                    $respuesta.=$registroHorario[$i][6]." <br> ".$registroHorario[$i][7];
                                                                $respuesta.="</td>";
                                                            $respuesta.="</tr>";
                                                            
                                                            if($i ==  (count($registroHorario) - 1))
                                                                {
                                                                    $respuesta.="</table>";
                                                                    $respuesta.="</td>";
                                                                }
                                                    }
                                                }
                                                $respuesta.="</tr>";
                                                    
                                                $respuesta.="</table>";
                                            $respuesta.="</td>";
                                           $respuesta.="</tr>";
                                    
                                    
                                    
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td class='centrar' colspan='10' align='center'>";
                                        $respuesta.="<input type='button' id='guardarModificar' name='guardarModificar' value='Guardar' class='ui-widget ui-widget-content jqueryui' onclick='guardarModificarDocenteCurso(\"".$registro[$m]['car_doc_nro']."\",\"".$_REQUEST['curso']."\",\"".$registro[$m]['CAR_TIP_VIN']."\")' >";
                                        $respuesta.="<div name='respuestaModificar' id='respuestaModificar' style='display: none;'></div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                $respuesta.="</table>";
                                $respuesta.="</fieldset>";
                                $respuesta.="</div> ";
                                $respuesta .= "</td>";
                                $respuesta .= "</tr>";

                                /*
                                 * DIV PARA ELIMINAR LAS HORAS POR DOCENTE
                                 */
                                $respuesta.="<tr class='jqueryui'>"; 
                                $respuesta.="<td colspan='10'>"; 
                                $respuesta.="<div name='".$_REQUEST['curso']."elimina".$registro[$m]['car_doc_nro']."' id='".$_REQUEST['curso']."elimina".$registro[$m]['car_doc_nro']."' style='display: none;'> ";
                                $respuesta.="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                                $respuesta .= "<legend class='ui-state-default ui-corner-all'> Borrar Carga a Docente: ".$registro[$m]['doc_nombre'].' '.$registro[$m]['doc_apellido']." </legend>";
                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td align='center'>";
                                        $respuesta.="Esta seguro que desea eliminar las horas asignadas a este docente?:";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td class='centrar' colspan='10' align='center'>";

                                        $respuesta.="<input type='button' id='guardarEliminar' name='guardarEliminar' value='Si' class='ui-widget ui-widget-content jqueryui' onclick='eliminarHorasDocente(\"".$registro[$m]['car_doc_nro']."\",\"".$_REQUEST['curso']."\",\"".$registro[$m]['CAR_TIP_VIN']."\");' >";
                                        $respuesta.="<input type='button' id='noguardarEliminar' name='noguardarEliminar' value='No' class='ui-widget ui-widget-content jqueryui' onclick='$(\"#elimina".$registro[$m]['car_doc_nro']."\").css(\"display\", \"none\");' >";

                                        $respuesta.="<div name='respuestaEliminarCurso' id='respuestaEliminarCurso' style='display: none;'></div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                $respuesta.="</table>"; 
                                $respuesta.="</fieldset>";
                                $respuesta.="</div> ";
                                $respuesta .= "</td>";
                                $respuesta .= "</tr>";

                            }
                            
                            
                            
                            //Consulta para los nombres de los docentes
                            $cadena_sqlTipoVinc = $this->sql->cadena_sql("tipoVinculacion",$arregloHorario);
                            $registroTipoVinc = $esteRecursoDB->ejecutarAcceso($cadena_sqlTipoVinc, "busqueda");
                                                        
                            $respuesta.="<tr class='jqueryui'>"; 
                            $respuesta.="<td colspan='10'>";
                            $respuesta.="<div name='adicionarDocenteCurso".$_REQUEST['curso']."' id='adicionarDocenteCurso".$_REQUEST['curso']."' style='display: none;'> ";
                            $respuesta.="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                            $respuesta .= "<legend class='ui-state-default ui-corner-all'> Adicionar Docente: </legend>";
                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="Seleccione el docente:";
                                        $respuesta.="</td>"; 
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="<input id='docenteNuevoCurso".$_REQUEST['curso']."' name='docenteNuevoCurso".$_REQUEST['curso']."' class='jqueryui' type='text' tabindex='2' maxlength='100' size='40'  title='Digite el nombre o apellido del docente:' autocomplete='off'><br>";
                                        $respuesta.="<input type='hidden' id='iddocenteNuevoCurso".$_REQUEST['curso']."' name='iddocenteNuevoCurso".$_REQUEST['curso']."' value='' tabindex='2' maxlength='100' size='40'>";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="Seleccione el tipo de vinculación:";
                                        $respuesta.="</td>"; 
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="<select id='docenteNuevoTipVinCurso".$_REQUEST['curso']."' name='docenteNuevoTipVinCurso".$_REQUEST['curso']."' class='jqueryui' >";
                                        
                                        for($t=0;$t<count($registroTipoVinc);$t++)
                                        {
                                            $respuesta.="<option value='".$registroTipoVinc[$t][0]."'>".$registroTipoVinc[$t][1]."</option>";
                                        }
                                        
                                        $respuesta.="</select>"; 
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    $respuesta.="<tr class='celda_sin_registros'>";
                                        $respuesta.="<td align='center' colspan='6'>";
                                        $respuesta.="Horas Asignadas: ";                                         
                                        $respuesta.="<input type='hidden' id='contHorasSelecCurso".$_REQUEST['curso']."' value='".count($registroHorario)."'> ";
                                        $respuesta.="<input type='hidden' id='totalHorasSelecCurso".$_REQUEST['curso']."' value='".count($registroHorario)."'> ";
                                        $respuesta.="<div id='horasSelecCurso".$_REQUEST['curso']."'> ".count($registroHorario)." </div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    
                                    
                                           $respuesta.="<tr>"; 
                                            $respuesta.="<td colspan='6'>";
                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                $respuesta.="<caption class='celda_sin_registros'>SELECCIONE LAS HORAS A ASIGNAR AL DOCENTE</caption>";
                                                $respuesta.="<tr>"; 
                                                    $respuesta.="<td colspan='6'>"; 
                                                        $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                        $respuesta.="<tr>"; 
                                                        $respuesta.="<td class='celda_hora_seleccionada' colspan='6'>";
                                                        $respuesta.="Hora Seleccionada";
                                                        $respuesta.="</td>";
                                                        $respuesta.="<td class='celda_hora' colspan='3'>"; 
                                                        $respuesta.="Hora No Seleccionada";
                                                        $respuesta.="</td>";
                                                        $respuesta.="</tr>";
                                                        $respuesta.="</table>";
                                                    $respuesta.="</td>";
                                                $respuesta.="</tr>"; 
                                                 
                                                $respuesta.="<tr>"; 
                                                
                                                if(is_array($registroHorario)) 
                                                    {
                                                
                                                    for($i=0;$i<count($registroHorario);$i++)
                                                    {
                                                        if($registroHorario[$i][2] != (isset($registroHorario[$i-1][2])?$registroHorario[$i-1][2]:''))
                                                            {
                                                                if($i>0)
                                                                    {
                                                                        $respuesta.="</table>";
                                                                        $respuesta.="</td>";
                                                                    }                                                                
                                                                $respuesta.="<td>";
                                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                                $respuesta.="<caption class='celda_titulo_horario_pequeno'><b>".$registroHorario[$i][3]."<b></caption>"; 
                                                                
                                                            }
                                                            $respuesta.="<tr id='".$_REQUEST['curso']."adiciona".$i."' class='celda_hora_seleccionada' onclick='adicionarHorasDocCurso(\"".$_REQUEST['curso']."\", \"".$registroHorario[$i][8]."\",\"".$i."\")'>";
                                                                $respuesta.="<td>";
                                                                $respuesta.="<input type='hidden' id='".$_REQUEST['curso']."horaSelecHorario".$i."' value='".$registroHorario[$i][8]."'> ";
                                                                    $respuesta.=$registroHorario[$i][5];
                                                                $respuesta.="</td>";
                                                                $respuesta.="<td>";
                                                                    $respuesta.=$registroHorario[$i][6]." <br> ".$registroHorario[$i][7];
                                                                $respuesta.="</td>";
                                                            $respuesta.="</tr>";
                                                            
                                                            if($i ==  (count($registroHorario) - 1))
                                                                {
                                                                    $respuesta.="</table>";
                                                                    $respuesta.="</td>";
                                                                }
                                                    }
                                                }
                                                $respuesta.="</tr>";
                                                    
                                                $respuesta.="</table>";
                                            $respuesta.="</td>";
                                           $respuesta.="</tr>";
                                        
                                    
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td class='centrar' colspan='10' align='center'>";
                                        $respuesta.="<input type='button' id='guardarNuevoCurso' name='guardarNuevoCurso' value='Guardar' class='ui-widget ui-widget-content jqueryui' onclick='guardarDocenteCurso(\"".$_REQUEST['curso']."\");' >";
                                        $respuesta.="<div name='respuestaGuardarCurso' id='respuestaGuardarCurso' style='display: none;'></div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    
                                $respuesta.="</table>"; 
                                $respuesta.="</fieldset>"; 
                            $respuesta.="</div> "; 
                            $respuesta.="</td>"; 
                            $respuesta.="</tr>";
                            
                            $respuesta .= "</table>";


                        }else if($_REQUEST["funcion"]=="#respuestaGuardarCurso")
                            {
                                if($mensajeCruce != '')
                                    {
                                        $respuesta = $mensajeCruce;
                                    }else
                                        {
                                            $respuesta = 'ok';
                                        }
                            }else if($_REQUEST["funcion"]=="#respuestaModificarCurso")
                            {
                                if($mensajeCruce != '')
                                    {
                                        $respuesta = $mensajeCruce;
                                    }else
                                        {
                                            $respuesta = 'ok';
                                        }
                            }else if($_REQUEST["funcion"]=="#respuestaEliminarCurso")
                            {
                                $respuesta .= "<div class='celda_sin_registros'>";
                                $respuesta .= "SE BORRO LA ASIGNACIÓN CON EXITO";
                                $respuesta .= "</div>";
                            }
            


} else {
	
	if(($_REQUEST["funcion"]=="#nombreDoc") && ($_REQUEST["funcion"]=="#docenteNuevoCurso")){
		$respuesta='[{"label":"No encontrado","value":"-1"}]';
	}else if($_REQUEST["funcion"]=="#curso"){

		if($_REQUEST["funcion"]=="#curso"){
			$respuesta ='<select>';
		}else{
			$respuesta="<select id='curso' 
					class='FormElement 
					ui-widget-content 
					ui-corner-all' 
					role='select' 
					name='marca' 
					size='1'					
					>";
		}
		$respuesta.="<option value='0'>N/A</option>";
		$respuesta.='</select>';
        }else if($_REQUEST["funcion"]=="#cuerpoHorario")
            {
                $respuesta.="<div class='jqueryui'>No existe asignación de horario</div>";
            }
            else if(($_REQUEST["funcion"]=="#divDatosDocente"))
            {
                print_r($registro);
               
            }else if(($_REQUEST["funcion"]=="#respuestaModificar"))
            {
                $respuesta.="error";
            }else if(($_REQUEST["funcion"]=="#respuestaEliminar"))
            {
                $respuesta.="error";
            }else if(($_REQUEST["funcion"]=="#cursosTodos"))
                    {
                        $respuesta ="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                        $respuesta .= "<legend class='ui-state-default ui-corner-all'> Cursos Registrados:</legend>";

                            $respuesta .= "<div class='celda_sin_registros'>";
                            $respuesta .= "NO SE ENCONTRARON REGISTROS DE CURSOS PARA EL PROYECTO SELECCIONADO";
                            $respuesta .= "</div>";
                            
                        $respuesta .= "</fieldset>";
                    }else if(($_REQUEST["funcion"]=="#cuerpoAsignacionCurso"))
                    {
                        $respuesta ="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                        $respuesta .= "<legend class='ui-state-default ui-corner-all'> Docentes Asigandos:</legend>";

                            $respuesta .= "<div class='celda_sin_registros'>";
                            $respuesta .= "NO EXISTEN DOCENTES ASIGNADOS";
                            $respuesta .= "</div>";
                            
                       
                        
                        //Verificamos el curso al que se desea asignar
                            $arregloCurso = explode('-', $_REQUEST['curso']);
                            $asignatura = $arregloCurso[0];
                            $grupo = $arregloCurso[1];
                            $proyecto = $arregloCurso[2];
                            
                            $arregloPeriodo = explode('-', $_REQUEST['periodo']);
                            $annio = $arregloPeriodo[0];
                            $periodo = $arregloPeriodo[1];
                            
                            $arregloHorario = array($asignatura,$grupo,$annio,$periodo,$proyecto);
                            
                            $respuesta .= "<table width='100%' class='jqueryui' border='0'>";
                        
                            //Consulta para los nombres de los docentes
                            $cadena_sqlHorario = $this->sql->cadena_sql("horarioCursoTabCurso",$arregloHorario);
                            $registroHorario = $esteRecursoDB->ejecutarAcceso($cadena_sqlHorario, "busqueda");
                            
                            //Consulta para los nombres de los docentes
                            $cadena_sqlTipoVinc = $this->sql->cadena_sql("tipoVinculacion",$arregloHorario);
                            $registroTipoVinc = $esteRecursoDB->ejecutarAcceso($cadena_sqlTipoVinc, "busqueda");
                                                        
                            $respuesta.="<tr class='jqueryui'>"; 
                            $respuesta.="<td colspan='10'>";
                            $respuesta.="<div name='adicionarDocenteCurso".$_REQUEST['curso']."' id='adicionarDocenteCurso".$_REQUEST['curso']."' style='display: none;'> ";
                            $respuesta.="<fieldset id='cargaDocente' class='ui-widget ui-widget-content'>";
                            $respuesta .= "<legend class='ui-state-default ui-corner-all'> Adicionar Docente: </legend>";
                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="Seleccione el docente:";
                                        $respuesta.="</td>"; 
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="<input id='docenteNuevoCurso".$_REQUEST['curso']."' name='docenteNuevoCurso".$_REQUEST['curso']."' class='jqueryui' type='text' tabindex='2' maxlength='100' size='40'  title='Digite el nombre o apellido del docente:' autocomplete='off'><br>";
                                        $respuesta.="<input type='hidden' id='iddocenteNuevoCurso".$_REQUEST['curso']."' name='iddocenteNuevoCurso".$_REQUEST['curso']."' value='' tabindex='2' maxlength='100' size='40'>";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="Seleccione el tipo de vinculación:";
                                        $respuesta.="</td>"; 
                                        $respuesta.="<td align='center' colspan='3'>";
                                        $respuesta.="<select id='docenteNuevoTipVinCurso".$_REQUEST['curso']."' name='docenteNuevoTipVinCurso".$_REQUEST['curso']."' class='jqueryui' >";
                                        
                                        for($t=0;$t<count($registroTipoVinc);$t++)
                                        {
                                            $respuesta.="<option value='".$registroTipoVinc[$t][0]."'>".$registroTipoVinc[$t][1]."</option>";
                                        }
                                        
                                        $respuesta.="</select>"; 
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    $respuesta.="<tr class='celda_sin_registros'>";
                                        $respuesta.="<td align='center' colspan='6'>";
                                        $respuesta.="Horas Asignadas: ";                                         
                                        $respuesta.="<input type='hidden' id='contHorasSelecCurso".$_REQUEST['curso']."' value='".count($registroHorario)."'> ";
                                        $respuesta.="<input type='hidden' id='totalHorasSelecCurso".$_REQUEST['curso']."' value='".count($registroHorario)."'> ";
                                        $respuesta.="<div id='horasSelecCurso".$_REQUEST['curso']."'> ".count($registroHorario)." </div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                    
                                           $respuesta.="<tr>"; 
                                            $respuesta.="<td colspan='6'>";
                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                $respuesta.="<caption class='celda_sin_registros'>SELECCIONE LAS HORAS A ASIGNAR AL DOCENTE</caption>";
                                                $respuesta.="<tr>"; 
                                                $respuesta.="<tr>"; 
                                                    $respuesta.="<td colspan='6'>"; 
                                                        $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                        $respuesta.="<tr>"; 
                                                        $respuesta.="<td class='celda_hora_seleccionada' colspan='6'>";
                                                        $respuesta.="Hora Seleccionada";
                                                        $respuesta.="</td>";
                                                        $respuesta.="<td class='celda_hora' colspan='3'>"; 
                                                        $respuesta.="Hora No Seleccionada";
                                                        $respuesta.="</td>";
                                                        $respuesta.="</tr>";
                                                        $respuesta.="</table>";
                                                    $respuesta.="</td>";
                                                $respuesta.="</tr>";
                                                
                                                if(is_array($registroHorario)) 
                                                    {
                                                
                                                    for($i=0;$i<count($registroHorario);$i++)
                                                    {
                                                        if($registroHorario[$i][2] != (isset($registroHorario[$i-1][2])?$registroHorario[$i-1][2]:''))
                                                            {
                                                                if($i>0)
                                                                    {
                                                                        $respuesta.="</table>";
                                                                        $respuesta.="</td>";
                                                                    }                                                                
                                                                $respuesta.="<td>";
                                                                $respuesta.="<table width='100%' class='jqueryui' border='0'>";
                                                                $respuesta.="<caption class='celda_titulo_horario_pequeno'><b>".$registroHorario[$i][3]."<b></caption>"; 
                                                                
                                                            }
                                                            $respuesta.="<tr id='".$_REQUEST['curso']."adiciona".$i."' class='celda_hora_seleccionada' onclick='adicionarHorasDocCurso(\"".$_REQUEST['curso']."\", \"".$registroHorario[$i][8]."\",\"".$i."\")'>";
                                                                $respuesta.="<td>";
                                                                $respuesta.="<input type='hidden' id='".$_REQUEST['curso']."horaSelecHorario".$i."' value='".$registroHorario[$i][8]."'> ";
                                                                    $respuesta.=$registroHorario[$i][5];
                                                                $respuesta.="</td>";
                                                                $respuesta.="<td>";
                                                                    $respuesta.=$registroHorario[$i][6]." <br> ".$registroHorario[$i][7];
                                                                $respuesta.="</td>";
                                                            $respuesta.="</tr>";
                                                            
                                                            if($i ==  (count($registroHorario) - 1))
                                                                {
                                                                    $respuesta.="</table>";
                                                                    $respuesta.="</td>";
                                                                }
                                                    }
                                                }
                                                $respuesta.="</tr>";
                                                    
                                                $respuesta.="</table>";
                                            $respuesta.="</td>";
                                           $respuesta.="</tr>";
                                        
                                    
                                    $respuesta.="<tr class='jqueryui centrar'>";
                                        $respuesta.="<td class='centrar' colspan='10' align='center'>";
                                        $respuesta.="<input type='button' id='guardarNuevoCurso' name='guardarNuevoCurso' value='Guardar' class='ui-widget ui-widget-content jqueryui' onclick='guardarDocenteCurso(\"".$_REQUEST['curso']."\");' >";
                                        $respuesta.="<div name='respuestaGuardarCurso' id='respuestaGuardarCurso' style='display: none;'></div> ";
                                        $respuesta.="</td>"; 
                                    $respuesta.="</tr>";
                                $respuesta.="</table>"; 
                                $respuesta.="</fieldset>"; 
                            $respuesta.="</div> "; 
                            $respuesta.="</td>"; 
                            $respuesta.="</tr>";
                            
                            $respuesta .= "</table>";
                        
                         $respuesta .= "</fieldset>"; 
                        
                        
                        
                    }else if($_REQUEST["funcion"]=="#respuestaGuardarCurso")
                            {
                                $respuesta .= "error";
                            }else if($_REQUEST["funcion"]=="#respuestaModificarCurso")
                            {
                                $respuesta .= "error";
                            }else if($_REQUEST["funcion"]=="#respuestaEliminarCurso")
                            {
                                $respuesta .= "error";
                            }
}

echo $respuesta;
?>