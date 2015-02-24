<?php

    
$dropdown_menu = array(
                    
				array (
                        "project" => "Docencia",
                        "title" => "Docentes",
                        "items" => array (
                            array ( "reportfile" => "plan_trabajo_docente.xml" ),
                            array ( "reportfile" => "informacion_docente_planta.xml" ),
                            array ( "reportfile" => "info_basica_docentes_xfacultad_y_proyecto.xml" ),
                            array ( "reportfile" => "horario_docentes_clases_y_atencion_estudiantes.xml" ),
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
				array (
                        "project" => "Docencia",
                        "title" => "Horarios",
                        "items" => array (
							array ( "reportfile" => "horario_x_estudiante.xml"),
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
					);

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Docencia";
      $menu = array ( 

   	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "plan_trabajo_docente.xml", "title" => "Plan de trabajo docente" ),
        array ( "report" => "informacion_docente_planta.xml", "title" => "Información docentes planta" ),
        array ( "report" => "info_basica_docentes_xfacultad_y_proyecto.xml", "title" => "Docentes por Facultad y Proyecto Curricular" ),
        array ( "report" => "horario_docentes_clases_y_atencion_estudiantes.xml", "title" => "Horario de clases y atención a estudiantes" ),
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

   	array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horario_x_estudiante.xml", "title" => "Horario de estudiantes" ),
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
	)


?>
