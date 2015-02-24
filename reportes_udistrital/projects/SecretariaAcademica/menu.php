<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Estudiantes",
                        "items" => array (
                            	array ( "reportfile" => "estudiantes_prueba_acad.xml" ),
                                array ( "reportfile" => "historico_notas_estudiante.xml" ),
                                array ( "reportfile" => "estudiantes_con_fraccionamiento.xml" ),
                                array ( "reportfile" => "total_matriculados_posgrados.xml" ),
                                array ( "reportfile" => "listado_matriculados_per_actual.xml" ),
                                array ( "reportfile" => "listado_promedios_ponderados_x_proyecto.xml" ),
                                array ( "reportfile" => "promedios_estudiante.xml" ),
                                array ( "reportfile" => "total_matriculados.xml" ),
	                        array ( "reportfile" => "listado_estudiantes_bajo_rendimiento.xml" ),
                                array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_artes.xml" ),
                                array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_ciencias.xml" ),
                                array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_ingenieria.xml" ),
                                array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_medio_ambiente.xml" ),
                                array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_tecno.xml" ),
                                array ( "reportfile" => "listado_promedio_estudiantes_fac_proyecto.xml" ),

                            
//                            array ( "reportfile" => "reporte_estudiantes_x_estado_SecretariaAcademica.xml"),
                            )
                        ),

                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Docentes",
                        "items" => array (
                            array ( "reportfile" => "horario_docentes_clases_y_atencion_estudiantes.xml" ),                            
                            array ( "reportfile" => "info_basica_docentes_xfacultad_y_proyecto.xml" ),
                            array ( "reportfile" => "informacion_docente_planta.xml" ),
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Inscripciones",
                        "items" => array (
			    //array ( "reportfile" => "preins_x_demanda.xml" ),
                            )
                        ),
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Cursos",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Horarios",
                        "items" => array (
                                array ( "reportfile" => "disponibilidad_salones.xml"),
                                array ( "reportfile" => "horario_por_estudiante.xml"),
                                array ( "reportfile" => "horarios.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Egresados",
                        "items" => array (
                                array ( "reportfile" => "datos_egresados.xml" ),
                                array ( "reportfile" => "lista_egresados_x_proyecto.xml"),
//                                array ( "reportfile" => "horarios.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Admisiones",
                        "items" => array (
                            array ( "reportfile" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml" ),
                            //array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            )
                        ),                    array (
                        "project" => "SecretariaAcademica",
                        "title" => "Log",
                        "items" => array (
//                            array ( "reportfile" => "reporte_inscripcion_automatica.xml" ),
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Académicos Secretaria Académica";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "estudiantes_prueba_acad.xml", "title" => "Listado estudiantes en prueba" ),
        array ( "report" => "historico_notas_estudiante.xml", "title" => "Histórico de Notas de estudiante" ),
        array ( "report" => "estudiantes_con_fraccionamiento.xml", "title" => "Estudiantes con Fraccionamiento" ),
        array ( "report" => "total_matriculados_posgrados.xml", "title" => "Matriculados Posgrados" ),
        array ( "report" => "listado_matriculados_per_actual.xml", "title" => "Listado de estudiantes Matriculados Per. Actual" ),
        array ( "report" => "listado_promedios_ponderados_x_proyecto.xml", "title" => "Promedios ponderados" ),
        array ( "report" => "promedios_estudiante.xml", "title" => "Promedios de estudiante" ),
        array ( "report" => "total_matriculados.xml", "title" => "Matriculados" ),
        array ( "report" => "listado_estudiantes_bajo_rendimiento.xml", "title" => "Listado estudiantes en bajo rendimiento" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_artes.xml", "title" => "Estudiantes con niveles de riesgo Fac. Artes" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_ciencias.xml", "title" => "Estudiantes con niveles de riesgo Fac. Ciencias" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_ingenieria.xml", "title" => "Estudiantes con niveles de riesgo Fac. Ingeniería" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_medio_ambiente.xml", "title" => "Estudiantes con niveles de riesgo Fac. Medio Ambiente" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_tecno.xml", "title" => "Estudiantes con niveles de riesgo Fac. Tecnológica" ), 
        array ( "report" => "listado_promedio_estudiantes_fac_proyecto.xml", "title" => "Promedios Estudiantes por Facultad y Proyecto" ), 

//        array ( "report" => "reporte_estudiantes_x_estado_SecretariaAcademica.xml", "title" => "Total estudiantes por estado por SecretariaAcademica" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horario_docentes_clases_y_atencion_estudiantes.xml", "title" => "Horario de clases y atención a estudiantes" ),
        array ( "report" => "info_basica_docentes_xfacultad_y_proyecto.xml", "title" => "Docentes por Facultad y Proyecto Curricular" ),
        array ( "report" => "informacion_docente_planta.xml", "title" => "Información docentes planta" ),
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>INSCRIPCIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        //array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "disponibilidad_salones.xml", "title" => "Disponibilidad de Salones" ),        
        array ( "report" => "horario_por_estudiante.xml", "title" => "Horarios por estudiante" ),
        array ( "report" => "horarios.xml", "title" => "Horarios por Facultad y Proyecto Curricular" ),
        array ( "report" => "", "title" => "BLANKLINE" ),
          
     	array ( "report" => "HEADER", "title" => "<b>EGRESADOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "datos_egresados.xml", "title" => "Datos Egresados" ),
        array ( "report" => "lista_egresados_x_proyecto.xml", "title" => "Listado Egresados por Proyecto" ),
//        array ( "report" => "horarios.xml", "title" => "Horarios" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml", "title" => "Número de aspirantes de la universidad" ), 
        array ( "report" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml", "title" => "Número de admitidos de la universidad" ), 
                //array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
