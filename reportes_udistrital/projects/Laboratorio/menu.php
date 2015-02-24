<?php

    
$dropdown_menu = array(
                    array (
                        "project" => "Laboratorio",
                        "title" => "Financiera",
                        "items" => array (
                            array ( "reportfile" => "listado_deudores.xml"),
                            )
                        ),
                    array (
                        "project" => "Laboratorio",
                        "title" => "Cursos",
                        "items" => array (
                            array ( "reportfile" => "total_inscritos_xgrupo.xml"),
                            )
                        ),

                    array (
                        "project" => "Laboratorio",
                        "title" => "Docentes",
                        "items" => array (
                            //array ( "reportfile" => "plan_trabajo_docente_periodo_actual.xml" ),
                            )
                        )
                    
                );

      $menu_title = "Sistema de Administración de Reportes<br>Reportes Laboratorio";
      $menu = array ( 

        array ( "report" => "HEADER", "title" => "<b>FINANCIERA<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "listado_deudores.xml", "title" => "Listado deudores" ),
        array ( "report" => "", "title" => "BLANKLINE" ),
          
        array ( "report" => "HEADER", "title" => "<b>CURSOS<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        array ( "report" => "total_inscritos_xgrupo.xml", "title" => "Total inscritos por grupo" ),
        array ( "report" => "", "title" => "BLANKLINE" ),

     	array ( "report" => "HEADER", "title" => "<b>DOCENTES<b>" ), 
        array ( "report" => "", "title" => "LINE" ), 
        //array ( "report" => "plan_trabajo_docente_periodo_actual.xml", "title" => "Plan de trabajo docente período actual" ), 
        array ( "report" => "", "title" => "BLANKLINE" ), 

     	
	)


?>
