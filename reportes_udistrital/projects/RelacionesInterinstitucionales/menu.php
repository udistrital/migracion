<?php

    
$dropdown_menu = array(
                   
                    array (
                        "project" => "RelacionesInterinstitucionales",
                        "title" => "Estudiantes",
                        "items" => array (
                            //array ( "reportfile" => "estudiantes_por_estado.xml" ),
                            //array ( "reportfile" => "total_inscritos_xgrupo.xml"),
                            )
                        ),

                    array (
                        "project" => "RelacionesInterinstitucionales",
                        "title" => "Docentes",
                        "items" => array (
                            array ( "reportfile" => "plan_trabajo_docente.xml" ),
                            array ( "reportfile" => "carga_docente_inscritos.xml" ),
                            array ( "reportfile" => "horario_docentes_clases_y_atencion_estudiantes.xml" ),                            
                            array ( "reportfile" => "info_basica_docentes_xfacultad_y_proyecto.xml" ),

                            )
                        ),
                    array (
                        "project" => "RelacionesInterinstitucionales",
                        "title" => "Horarios",
                        "items" => array (
                                array ( "reportfile" => "horario_x_grupo_espacio.xml"),
                               
                            )
                        ),
                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Centro de Relaciones Interinstitucionales";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>ESTUDIANTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "estudiantes_por_estado.xml", "title" => "Estudiantes por estado" ), 
//        array ( "report" => "total_inscritos_xgrupo.xml", "title" => "Total inscritos por grupo" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

        array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "plan_trabajo_docente.xml", "title" => "Plan de trabajo docente" ),
        array ( "report" => "carga_docente_inscritos.xml", "title" => "Carga docente y estudiantes inscritos" ),
        array ( "report" => "horario_docentes_clases_y_atencion_estudiantes.xml", "title" => "Horario de clases y atención a estudiantes" ),
        array ( "report" => "info_basica_docentes_xfacultad_y_proyecto.xml", "title" => "Docentes por Facultad y Proyecto Curricular" ),

        array ( "report" => "", "title" => "BLANKLINE" ), 
          
          array ( "report" => "HEADER", "title" => "<b>HORARIOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "horario_x_grupo_espacio.xml", "title" => "Horarios por espacio academico" ),
        array ( "report" => "", "title" => "BLANKLINE" ), 


	)


?>
