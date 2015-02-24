<?
//inicio desde condor
    
    if(isset($_REQUEST['informes'])){
    
    $proyecto=$_REQUEST['informes'];    	

    switch ($proyecto)
    {
    case "soporte":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Soporte';
	$_REQUEST['project_password']= 'reporteSoporte';
	$_REQUEST['submit_menu_project']='Ejecutar';
	//var_dump($_REQUEST);
      break;

    case "proyecto":
     	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='ProyectoCurricular';
	$_REQUEST['project_password']= 'reporteProyecto';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;

    case "facultad":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Facultad';
	$_REQUEST['project_password']= 'reporteFacultad';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;

    case "bienestar":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='BienestarInstitucional';
	$_REQUEST['project_password']= 'reporteBInstitucionalUD';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;

    case "viceacademica":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='VicerrectoriaAcademica';
	$_REQUEST['project_password']= 'reporteViceAcademica';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;

    case "secacademica":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='SecretariaAcademica';
	$_REQUEST['project_password']= 'reporteSecAcademica';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
  
    case "laboratorio":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Laboratorio';
	$_REQUEST['project_password']= 'reporteLaboratorio';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
  
    case "ilud":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Ilud';
	$_REQUEST['project_password']= 'reporteILUD';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
  
    case "posgrado":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Posgrados';
	$_REQUEST['project_password']= 'reportePosgrado';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
    
    case "ceri":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='RelacionesInterinstitucionales';
	$_REQUEST['project_password']= 'reporteCERI';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
            
    case "tesoreria":
        $_REQUEST['jump_to_language']='es_es';
        $_REQUEST['jump_to_menu_project']='Tesoreria';
        $_REQUEST['project_password']= 'reporteTesoreria';
        $_REQUEST['submit_menu_project']='Ejecutar';
        break;

    case "docencia":
    	$_REQUEST['jump_to_language']='es_es';
	$_REQUEST['jump_to_menu_project']='Docencia';
	$_REQUEST['project_password']= 'reporteDocencia';
	$_REQUEST['submit_menu_project']='Ejecutar';
      break;
  

    default:
      
    }    
	
       }
       else{}

?>
