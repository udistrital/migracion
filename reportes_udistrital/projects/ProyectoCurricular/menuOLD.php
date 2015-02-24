<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Estudiantes",
                        "items" => array (
                            array ( "reportfile" => "historico_notas_estudiante.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Docentes",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Inscripciones",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Cursos",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Horarios",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),
                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Admisiones",
                        "items" => array (
                            array ( "reportfile" => "listado_admitidos_por_proyecto.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        ),                    array (
                        "project" => "ProyectoCurricular",
                        "title" => "Log",
                        "items" => array (
                            //array ( "reportfile" => "ejemplo1.xml" ),
                            //array ( "reportfile" => "ejemplo2.xml" )
                            )
                        )

                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Académicos";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "Estudiantes" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "historico_notas_estudiante.xml", "title" => "Histórico de Notas de estudiante" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Estudiantes - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "Docentes" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Docentes - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Docentes - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "Inscripciones" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Inscripciones - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Inscripciones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

    	array ( "report" => "HEADER", "title" => "Cursos" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Cursos - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "Horarios" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Horarios - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Horarios - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	array ( "report" => "HEADER", "title" => "Admisiones" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "listado_admitidos_por_proyecto.xml", "title" => "Listado de admitidos por proyecto" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
   
    	array ( "report" => "HEADER", "title" => "Log Eventos" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 1" ), 
        //array ( "report" => "ejemplo1.xml", "title" => "Admisiones - Ejemplo 2" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 
      
	)


?>
