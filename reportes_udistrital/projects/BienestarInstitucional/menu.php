<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Estudiantes",
                        "items" => array (
                            array ( "reportfile" => "listado_estudiantes_bajo_rendimiento.xml" ),
                            array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_artes.xml" ),
                            array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_ciencias.xml" ),
                            array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_ingenieria.xml" ),
                            array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_medio_ambiente.xml" ),
                            array ( "reportfile" => "listado_estudiante_con_niveles_riesgo_tecno.xml" ),
                            array ( "reportfile" => "historico_notas_estudiante.xml" ),
                            array ( "reportfile" => "listado_inscritos_matriculados_primer_sem_proyecto.xml" ),
                            array ( "reportfile" => "estudiantes_perdieron_espacio.xml" ),
                            array ( "reportfile" => "listado_estudiantes_perdida_calidad_estudiante.xml" ),
                            array ( "reportfile" => "estudiantes_prueba_acad.xml" ),
                            array ( "reportfile" => "listado_matriculados_per_actual.xml" ),                            
                            array ( "reportfile" => "listado_matriculados_per_anteriores.xml" ),                            
                            
                            )
                        ),

                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Docentes",
                        "items" => array (
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Espacios Académicos",
                        "items" => array (
                            array ( "reportfile" => "informacion_espacio_academico.xml" ),
                            )
                        ),
                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Inscripciones",
                        "items" => array (
			    //array ( "reportfile" => "preins_x_demanda.xml" ),
                            )
                        ),
                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Cursos",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "BienestarInstitucional",
                        "title" => "Horarios",
                        "items" => array (
							array ( "reportfile" => "horario_x_estudiante.xml"),
							//array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Admisiones",
                        "items" => array (
                            array ( "reportfile" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            array ( "reportfile" => "listado_de_aspirantes_para_un_proyecto_curricular.xml" ),
                            //array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            )
                        ),                    array (
                        "project" => "bienestarInstitucional",
                        "title" => "Log",
                        "items" => array (
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Bienestar Institucional";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "listado_estudiantes_bajo_rendimiento.xml", "title" => "Listado estudiantes en bajo rendimiento" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_artes.xml", "title" => "Estudiantes con niveles de riesgo Fac. Artes" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_ciencias.xml", "title" => "Estudiantes con niveles de riesgo Fac. Ciencias" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_ingenieria.xml", "title" => "Estudiantes con niveles de riesgo Fac. Ingeniería" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_medio_ambiente.xml", "title" => "Estudiantes con niveles de riesgo Fac. Medio Ambiente" ), 
        array ( "report" => "listado_estudiante_con_niveles_riesgo_tecno.xml", "title" => "Estudiantes con niveles de riesgo Fac. Tecnológica" ), 
        array ( "report" => "historico_notas_estudiante.xml", "title" => "Histórico de notas de estudiante" ), 
        array ( "report" => "listado_inscritos_matriculados_primer_sem_proyecto.xml", "title" => "Listado matrículados a primer semestre" ),
        array ( "report" => "estudiantes_perdieron_espacio.xml", "title" => "Listado de Estudiantes reprobados por espacio académico" ),
        array ( "report" => "listado_estudiantes_perdida_calidad_estudiante.xml", "title" => "Listado estudiantes en pérdida de la calidad estudiante" ), 
        array ( "report" => "estudiantes_prueba_acad.xml", "title" => "Listado estudiantes en prueba académica o bajo rendimiento" ),
        array ( "report" => "listado_matriculados_per_actual.xml", "title" => "Listado matrículados período actual" ),
        array ( "report" => "listado_matriculados_per_anteriores.xml", "title" => "Listado matrículados períodos anteriores" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ESPACIOS ACADÉMICOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "informacion_espacio_academico.xml", "title" => "Información espacios académicos" ), 
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
        array ( "report" => "horario_x_estudiante.xml", "title" => "Horario de estudiantes" ),        
        //array ( "report" => "ejemplo1.xml", "title" => "Horarios - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml", "title" => "Número de aspirantes de la universidad" ), 
        array ( "report" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml", "title" => "Número de admitidos de la universidad" ), 
        array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "listado_de_aspirantes_para_un_proyecto_curricular.xml", "title" => "Listado de aspirantes" ), 
        //array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
