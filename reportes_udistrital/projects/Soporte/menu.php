<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "Soporte",
                        "title" => "Proyectos Curriculares",
                        "items" => array (
                            	array ( "reportfile" => "proyectos_curriculares.xml" ),
						array ( "reportfile" => "eventos_por_proyecto.xml" ),
						array ( "reportfile" => "lista_egresados_x_proyecto.xml" ),
						array ( "reportfile" => "planes_horas.xml" ),
                            )
                        ),

                    array (
                        "project" => "Soporte",
                        "title" => "Estudiantes",
                        "items" => array (
                            //array ( "reportfile" => "historico_notas_estudiante.xml" ),
      
                            )
                        ),


                    array (
                        "project" => "Soporte",
                        "title" => "Docentes",
                        "items" => array (
                        array ( "reportfile" => "notas_parciales_periodo_anterior.xml" ),
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "Soporte",
                        "title" => "Inscripciones",
                        "items" => array (
                        array ( "reportfile" => "cupos_x_preinscripciones.xml" ),
			    //array ( "reportfile" => "preins_x_demanda.xml" ),
                            )
                        ),
                    array (
                        "project" => "Soporte",
                        "title" => "Currículo",
                        "items" => array (
                        array ( "reportfile" => "datos_espacio_academico.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Soporte",
                        "title" => "Cursos",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Soporte",
                        "title" => "Horarios",
                        "items" => array (
                                array ( "reportfile" => "horarios.xml"),
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "Soporte",
                        "title" => "Admisiones",
                        "items" => array (
                            //array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "Soporte",
                        "title" => "Financiera",
                        "items" => array (
                            array ( "reportfile" => "recibos_ECAES_pagados.xml" ),
                            )
                        ),                    
                    array (
                        "project" => "Soporte",
                        "title" => "Log",
                        "items" => array (
                            array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            array ( "reportfile" => "reporte_inscripcion_automatica.xml" ),
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Soporte";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>PROYECTOS CURRICULARES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "proyectos_curriculares.xml", "title" => "Proyectos Curriculares" ),
        array ( "report" => "eventos_por_proyecto.xml", "title" => "Fechas por Proyecto" ),
        array ( "report" => "lista_egresados_x_proyecto.xml", "title" => "Listado Egresados por Proyecto" ),
        array ( "report" => "planes_horas.xml", "title" => "Planes de estudios de horas" ),
        array ( "report" => "", "title" => "BLANKLINE" ),        


	    array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "historico_notas_estudiante.xml", "title" => "Histórico de Notas de estudiante" ), 
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "notas_parciales_periodo_anterior.xml", "title" => "Notas parciales período anterior" ),
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>INSCRIPCIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "cupos_x_preinscripciones.xml", "title" => "Cupos preinscripción automática" ),
        //array ( "report" => "preins_x_demanda.xml", "title" => "Preinscripciones por demanda" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURRÍCULO<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "datos_espacio_academico.xml", "title" => "Información Espacios Académicos" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horarios.xml", "title" => "Horarios" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Horarios - Ejemplo 1" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "<b>ADMISIONES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
     	array ( "report" => "HEADER", "title" => "<b>FINANCIERA<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "recibos_ECAES_pagados.xml", "title" => "Recibos ECAES pagados" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "reporte_inscripcion_automatica.xml", "title" => "Inscripción automática por proyectos" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
