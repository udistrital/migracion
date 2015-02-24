<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "Tesoreria",
                        "title" => "Financiera",
                        "items" => array (
                                array ( "reportfile" => "listado_estudiantes_matriculados_recibos_diferidos.xml" ),
                                array ( "reportfile" => "estudiantes_credito_icetex.xml" ),
                                array ( "reportfile" => "reporte_recibos_pagados_periodo_activo.xml" ),
                                
                            )
                        ),                  /* array (
                        "project" => "ProyectoCurricular",
                        "title" => "Log",
                        "items" => array (
                        /*    array ( "reportfile" => "reporte_log_notas_estudiante.xml" ),
                            array ( "reportfile" => "reporte_log_inscripciones_estudiante.xml" ),
                            array ( "reportfile" => "reporte_cierre_semestre.xml" ),
							//array ( "reportfile" => "ejemplo2.xml" )
                            )
                        )*/

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Tesorería";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>FINANCIERA<b>" ),
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "listado_estudiantes_matriculados_recibos_diferidos.xml", "title" => "Listado de estudiantes matrículados con Recibos Diferidos" ),
        array ( "report" => "estudiantes_credito_icetex.xml", "title" => "Listado estudiantes crédito ICETEX" ),
        array ( "report" => "reporte_recibos_pagados_periodo_activo.xml", "title" => "Listado recibos pagados periodo activo" ),
        
        
        array ( "report" => "", "title" => "BLANKLINE" ),
   
    	/*array ( "report" => "HEADER", "title" => "<b>LOG EVENTOS<b>" ),
        array ( "report" => "", "title" => "LINE" ),
    /*    array ( "report" => "reporte_log_notas_estudiante.xml", "title" => "Log de notas por estudiante" ),
        array ( "report" => "reporte_log_inscripciones_estudiante.xml", "title" => "Log de inscripciones por estudiante" ),        
        array ( "report" => "reporte_cierre_semestre.xml", "title" => "Cierres de Semestre" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), */
      
	)


?>
