<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Estudiantes",
                        "items" => array (
							array ( "reportfile" => "estudiantes_x_colegio-proyecto.xml" ),
							array ( "reportfile" => "estudiantes_x_colegio-facultad.xml" ),
                            array ( "reportfile" => "estudiantes_x_colegio-universidad.xml" ),
                            array ( "reportfile" => "estudiantes_x_proyecto_filtro-colegio.xml" ),
                            array ( "reportfile" => "estudiantes_con_fraccionamiento.xml" ),
//                            array ( "reportfile" => "reporte_estudiantes_x_estado_VicerrectoriaAcademica.xml"),
                            )
                        ),

                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Docentes",
                        "items" => array (
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Inscripciones",
                        "items" => array (
			    //array ( "reportfile" => "preins_x_demanda.xml" ),
                            )
                        ),
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Cursos",
                        "items" => array (
                            array ( "reportfile" => "total_inscritos_xgrupo.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Horarios",
                        "items" => array (
//                                array ( "reportfile" => "horarios.xml"),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Admisiones",
                        "items" => array (
                            //array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Log",
                        "items" => array (
//                            array ( "reportfile" => "reporte_inscripcion_automatica.xml" ),
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "VicerrectoriaAcademica",
                        "title" => "Egresados",
                        "items" => array (
                            array ( "reportfile" => "total_egresados_por_facultad_y_proyecto.xml" ),
                            //array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Académicos Vicerrectoría Académica";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "estudiantes_x_colegio-proyecto.xml", "title" => "Estudiantes por colegio - Proyecto" ),
        array ( "report" => "estudiantes_x_colegio-facultad.xml", "title" => "Estudiantes por colegio - Facultad" ),
        array ( "report" => "estudiantes_x_colegio-universidad.xml", "title" => "Estudiantes por colegio - Universidad" ),
        array ( "report" => "estudiantes_x_proyecto_filtro-colegio.xml", "title" => "Estudiantes por Proyecto - Filtro por Colegio" ),
        array ( "report" => "estudiantes_con_fraccionamiento.xml", "title" => "Estudiantes con Fraccionamiento" ),
//        array ( "report" => "reporte_estudiantes_x_estado_VicerrectoriaAcademica.xml", "title" => "Total estudiantes por estado por VicerrectoriaAcademica" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>INSCRIPCIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        //array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "total_inscritos_xgrupo.xml", "title" => "Total inscritos por grupo" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
//        array ( "report" => "horarios.xml", "title" => "Horarios" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
        array ( "report" => "HEADER", "title" => "<b>EGRESADOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "total_egresados_por_facultad_y_proyecto.xml", "title" => "Total egresados por Facultad y Proyecto" ),
        //array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
