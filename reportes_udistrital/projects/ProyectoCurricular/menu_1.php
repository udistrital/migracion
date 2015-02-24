<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Estudiantes",
                        "items" => array (
                            array ( "reportfile" => "historico_notas_estudiante.xml" ),
							array ( "reportfile" => "promedios_estudiante.xml" ),
                            array ( "reportfile" => "estudiantes_por_estado.xml" ),
                            array ( "reportfile" => "total_estudiante_por_estado.xml" ),
                            array ( "reportfile" => "reporte_cierre_x_proyecto.xml" ),
                            array ( "reportfile" => "listado_inscritos_matriculados_primer_sem_proyecto.xml" ),
                            array ( "reportfile" => "estudiantes_perdieron_espacio.xml" ),
                            array ( "reportfile" => "estudiantes_prueba_acad.xml" ),
                            array ( "reportfile" => "total_estudiantes_inscritos_y_reprobados_x_espacio.xml" ),
                            array ( "reportfile" => "listado_estudiantes_perdida_calidad_estudiante.xml" )

                           //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),

                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Docentes",
                        "items" => array (
                            array ( "reportfile" => "plan_trabajo_docente.xml" ),
                            array ( "reportfile" => "carga_docente_inscritos.xml" ),
                            array ( "reportfile" => "consejeros_estudiantes.xml" ),                            
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Inscripciones",
                        "items" => array (
							array ( "reportfile" => "preins_x_demanda.xml" ),
							array ( "reportfile" => "espacios_perdidos_no_inscritos.xml" ),
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Cursos",
                        "items" => array (
                            array ( "reportfile" => "cursos_x_espacio.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Horarios",
                        "items" => array (
                                array ( "reportfile" => "horario_x_grupo_espacio.xml"),
                                array ( "reportfile" => "horarios.xml"),
                                array ( "reportfile" => "horarios_por_salon.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Admisiones",
                        "items" => array (
                            array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            array ( "reportfile" => "listado_de_aspirantes_para_un_proyecto_curricular.xml" ),
                            array ( "reportfile" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml" )
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Log",
                        "items" => array (
                            array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            array ( "reportfile" => "reporte_log_inscripciones_estudiante.xml" ),
                            array ( "reportfile" => "reporte_cierre_semestre.xml" ),
							//array ( "reportfile" => "ejemplo2.xml" )
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Académicos";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "historico_notas_estudiante.xml", "title" => "Histórico de Notas de estudiante" ),
		array ( "report" => "promedios_estudiante.xml", "title" => "Promedios de estudiante" ),
        array ( "report" => "estudiantes_por_estado.xml", "title" => "Estudiantes por estado" ), 
        array ( "report" => "total_estudiante_por_estado.xml", "title" => "Total estudiantes por estado" ),
        array ( "report" => "reporte_cierre_x_proyecto.xml", "title" => "Resumen Proceso Cierre de Semestre" ),
        array ( "report" => "listado_inscritos_matriculados_primer_sem_proyecto.xml", "title" => "Listado matrículados a primer semestre" ),
        array ( "report" => "estudiantes_perdieron_espacio.xml", "title" => "Listado de Estudiantes reprobados por espacio académico" ),
        array ( "report" => "estudiantes_prueba_acad.xml", "title" => "Listado estudiantes en prueba" ),
        array ( "report" => "total_estudiantes_inscritos_y_reprobados_x_espacio.xml", "title" => "Total estudiantes inscritos y reprobados por espacio académico" ), 
        array ( "report" => "listado_estudiantes_perdida_calidad_estudiante.xml", "title" => "Listado estudiantes en pérdida de la calidad estudiante" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Estudiantes - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "plan_trabajo_docente.xml", "title" => "Plan de trabajo docente" ),
        array ( "report" => "carga_docente_inscritos.xml", "title" => "Carga docente y estudiantes inscritos" ),
        array ( "report" => "consejeros_estudiantes.xml", "title" => "Consejeros y estudiantes" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Docentes - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>INSCRIPCIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "espacios_perdidos_no_inscritos.xml", "title" => "Espacios perdidos no inscritos" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Inscripciones - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Inscripciones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "cursos_x_espacio.xml", "title" => "Cursos por espacio academico" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horario_x_grupo_espacio.xml", "title" => "Horarios por espacio academico" ),
        array ( "report" => "horarios.xml", "title" => "Horarios por Facultad y Proyecto Curricular" ),
        array ( "report" => "horarios_por_salon.xml", "title" => "Horarios por salón" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Horarios - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "listado_de_aspirantes_para_un_proyecto_curricular.xml", "title" => "Listado de aspirantes" ), 
        array ( "report" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml", "title" => "Número de aspirantes de la universidad" ), 
        array ( "report" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml", "title" => "Número de admitidos de la universidad" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "reporte_log_inscripciones_estudiante.xml", "title" => "Log de inscripciones por estudiante" ),        
        array ( "report" => "reporte_cierre_semestre.xml", "title" => "Cierres de Semestre" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
