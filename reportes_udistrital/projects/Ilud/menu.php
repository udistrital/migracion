<?php

    
$dropdown_menu = array(
                   
                    array (
                        "project" => "Ilud",
                        "title" => "Estudiantes",
                        "items" => array (
                            array ( "reportfile" => "estudiantes_por_estado.xml" ),
                            //array ( "reportfile" => "total_inscritos_xgrupo.xml"),
                            )
                        ),

                    array (
                        "project" => "Ilud",
                        "title" => "Docentes",
                        "items" => array (
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        ),
                    array (
                        "project" => "Ilud",
                        "title" => "Cursos",
                        "items" => array (
                            array ( "reportfile" => "estudiantes_x_grupo_firma.xml" ),
                            array ( "reportfile" => "datos_estudiantes_x_grupo.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),                    
                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes ILUD";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "estudiantes_por_estado.xml", "title" => "Estudiantes por estado" ), 
//        array ( "report" => "total_inscritos_xgrupo.xml", "title" => "Total inscritos por grupo" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ),
        array ( "report" => "estudiantes_x_grupo_firma.xml", "title" => "Listado estudiantes por grupo para firma" ),
        array ( "report" => "datos_estudiantes_x_grupo.xml", "title" => "Información estudiantes por grupo" ),
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ),
 	)


?>
