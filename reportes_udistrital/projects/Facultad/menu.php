<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "Facultad",
                        "title" => "Estudiantes",
                        "items" => array (
                            array ( "reportfile" => "total_matriculados.xml" ),
                            array ( "reportfile" => "total_matriculados_per_ant.xml" ),
                            array ( "reportfile" => "reporte_estudiantes_x_estado_facultad.xml"),
                            array ( "reportfile" => "total_estudiantes_inscritos_y_reprobados_x_espacio.xml" ),
                            array ( "reportfile" => "estudiantes_por_cohorte.xml" ),
                            array ( "reportfile" => "estudiantes_x_colegio-proyecto.xml" ),
                            array ( "reportfile" => "estudiantes_x_colegio-facultad.xml" ),
                                          array ( "reportfile" => "estudiantes_con_fraccionamiento.xml" ),
                                          array ( "reportfile" => "resultados_icfes_x_estudiante.xml" ),
                            //array ( "reportfile" => "numero_matriculados_primer_semestre.xml" ),
                            
                            )
                        ),

                    array (
                        "project" => "Facultad",
                        "title" => "Docentes",
                        "items" => array (
                            array ( "reportfile" => "plan_trabajo_docente.xml" ),
                            array ( "reportfile" => "informacion_docente_planta.xml" ),
                            array ( "reportfile" => "info_basica_docentes_xfacultad_y_proyecto.xml" ),

                            array ( "reportfile" => "informacion_docentes_con_carga.xml" ),
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "Facultad",
                        "title" => "Inscripciones",
                        "items" => array (
                            array ( "reportfile" => "total_inscripciones_x_facultad_y_proyecto.xml" )
			    //array ( "reportfile" => "preins_x_demanda.xml" ),
                            )
                        ),
                    array (
                        "project" => "Facultad",
                        "title" => "Notas",
                        "items" => array (
                            array ( "reportfile" => "reprobados_x_proyecto_facultad.xml" ),
                            array ( "reportfile" => "estudiantes_reprobados_x_proyecto_facultad.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Facultad",
                        "title" => "Cursos",
                        "items" => array (
                            array ( "reportfile" => "total_inscritos_xgrupo.xml"),
                            array ( "reportfile" => "numero_cursos_horarios_x_proyecto.xml" ),

                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Facultad",
                        "title" => "Horarios",
                        "items" => array (
                                array ( "reportfile" => "horarios.xml"),
                                array ( "reportfile" => "horarios_por_salon.xml"),
								array ( "reportfile" => "disponibilidad_salones.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Facultad",
                        "title" => "Admisiones",
                        "items" => array (
                            array ( "reportfile" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml" ),
                            array ( "reportfile" => "numero_matriculados_primer_semestre.xml" )
                            //array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "Facultad",
                        "title" => "Financiera",
                        "items" => array (
                            array ( "reportfile" => "listado_estudiantes_SED.xml" ),
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "Facultad",
                        "title" => "Log",
                        "items" => array (
                            array ( "reportfile" => "reporte_inscripcion_automatica.xml" ),
                            array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            array ( "reportfile" => "reporte_log_inscripciones_estudiante.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "Facultad",
                        "title" => "Egresados",
                        "items" => array (
                            array ( "reportfile" => "total_egresados_por_facultad_y_proyecto.xml" ),
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        )

                );

      $menu_title = "Reportes Académicos Facultad <BR>PREGRADO";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "total_matriculados.xml", "title" => "Matriculados" ),
        array ( "report" => "total_matriculados_per_ant.xml", "title" => "Matriculados (período anterior)" ), 
        array ( "report" => "reporte_estudiantes_x_estado_facultad.xml", "title" => "Por estado" ),
        array ( "report" => "total_estudiantes_inscritos_y_reprobados_x_espacio.xml", "title" => "Inscritos y reprobados por espacio académico" ), 
        array ( "report" => "estudiantes_por_cohorte.xml", "title" => "Por cohorte" ), 
        array ( "report" => "estudiantes_x_colegio-proyecto.xml", "title" => "Estudiantes por colegio - Proyecto" ),
        array ( "report" => "estudiantes_x_colegio-facultad.xml", "title" => "Estudiantes por colegio - Facultad" ),
        array ( "report" => "estudiantes_con_fraccionamiento.xml", "title" => "Estudiantes con Fraccionamiento" ),
        array ( "report" => "resultados_icfes_x_estudiante.xml", "title" => "Resultados Icfes por Estudiantes" ),
        //array ( "report" => "numero_matriculados_primer_semestre.xml", "title" => "Número de estudiantes matriculados a primer semestre" ),
        

        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "plan_trabajo_docente.xml", "title" => "Plan de trabajo docente" ),
        array ( "report" => "informacion_docente_planta.xml", "title" => "Información docentes planta" ),
        array ( "report" => "info_basica_docentes_xfacultad_y_proyecto.xml", "title" => "Docentes por Facultad y Proyecto Curricular" ),
        array ( "report" => "informacion_docentes_con_carga.xml", "title" => "Información y carga académica docentes" ),
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>INSCRIPCIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "total_inscripciones_x_facultad_y_proyecto.xml", "title" => "Total inscripciones por Facultad y Proyecto Curricular" ),
        //array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>NOTAS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "reprobados_x_proyecto_facultad.xml", "title" => "Espacios reprobados por proyecto y facultad" ),
        array ( "report" => "estudiantes_reprobados_x_proyecto_facultad.xml", "title" => "Estudiantes reprobados por proyecto y facultad" ),
        //array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "total_inscritos_xgrupo.xml", "title" => "Total inscritos por grupo" ),
        array ( "report" => "numero_cursos_horarios_x_proyecto.xml", "title" => "Número de cursos y horarios por proyecto" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horarios.xml", "title" => "Horarios por Facultad y Proyecto curricular" ),
        array ( "report" => "horarios_por_salon.xml", "title" => "Horarios por salón" ),
        array ( "report" => "disponibilidad_salones.xml", "title" => "Disponibilidad de Salones" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "numero_aspirantes_universidad_por_facultad_y_proyecto.xml", "title" => "Número de aspirantes de la universidad" ), 
        array ( "report" => "numero_admitidos_universidad_por_facultad_y_proyecto.xml", "title" => "Número de admitidos de la universidad" ), 
        array ( "report" => "numero_matriculados_primer_semestre.xml", "title" => "Número de estudiantes matriculados a primer semestre" ), 
          
        //array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>FINANCIERA<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "listado_estudiantes_SED.xml", "title" => "Listado estudiantes beneficiados SED" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "reporte_inscripcion_automatica.xml", "title" => "Inscripción automática por proyectos" ),
        array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "reporte_log_inscripciones_estudiante.xml", "title" => "Log de inscripciones por estudiante" ),        
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
    	array ( "report" => "HEADER", "title" => "<b>EGRESADOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "total_egresados_por_facultad_y_proyecto.xml", "title" => "Total egresados por Facultad y Proyecto" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
