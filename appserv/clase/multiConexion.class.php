<?PHP

require_once("funcionGeneral.class.php");

// Deshabilitar todo reporte de errores
//error_reporting(0);


class multiConexion extends funcionGeneral
{

    public function estableceConexion($nivelUsuario)
    {


	    require_once("../clase/config.class.php");

	    $esta_configuracion=new config();
	    $configuracion=$esta_configuracion->variable("../"); 

	  if(!isset($nivelUsuario)){
		  //Por defecto rescata 	
		  session_name($configuracion["usuarios_sesion"]);
		  session_start();

	  }
	  else{
		  switch($nivelUsuario)
		  {
		  	//	echo "<br>->$nivelUsuario<-";
		  	case 'default':
				$acceso_db=$this->conectarDB($configuracion,"default");
			//  echo "Me estoy conectando como conexion";
				break;
			case 16:
				$acceso_db=$this->conectarDB($configuracion,"decano");
			//  echo "Me estoy conectando como Decano";
				break;
			case 4:
			case 28:
				$acceso_db=$this->conectarDB($configuracion,"coordinador");
			//  echo "Me estoy conectando como Coordinador";
				break;
			case 30:
				$acceso_db=$this->conectarDB($configuracion,"docente");
			//  echo "Me estoy conectando como Docente";
				break;
			case 51:
				//echo $nivelUsuario;
				$acceso_db=$this->conectarDB($configuracion,"estudiante");

				break;
			case 52:
				//echo $nivelUsuario;
				$acceso_db=$this->conectarDB($configuracion,"estudiante");

				break;
			case 20:
				$acceso_db=$this->conectarDB($configuracion,"admin");
			//  echo "Me estoy conectando como administrador";
				break;
			case 24:
				$acceso_db=$this->conectarDB($configuracion,"funcionario");
			//  echo "Me estoy conectando como funcionario";
				break;
			case 25:
				$acceso_db=$this->conectarDB($configuracion,"jefedependencia");
			//  echo "Me estoy conectando como funcionario";
				break;				
			case 31:
				$acceso_db=$this->conectarDB($configuracion,"rector");
			//  echo "Me estoy conectando como rector";
				break;
			case 32:
				$acceso_db=$this->conectarDB($configuracion,"vicerrector");
			//  echo "Me estoy conectando como vicerrector";
				break;
			case 33:
				$acceso_db=$this->conectarDB($configuracion,"admisiones");
				//  echo "Me estoy conectando como vicerrector";
				break;
			case 34:
				$acceso_db=$this->conectarDB($configuracion,"asesor");
				//  echo "Me estoy conectando como asesor";
				break;
			case 37:
				$acceso_db=$this->conectarDB($configuracion,"juradovotacion");
				//  echo "Me estoy conectando como jurado de votacion";
				break;				
			case 50:
				$acceso_db=$this->conectarDB($configuracion,"aspirante");
				//  echo "Me estoy conectando como aspirante";
				break;
			case 68:
				  $acceso_db=$this->conectarDB($configuracion,"bienestar");
				//  echo "Me estoy conectando como asesor";
				  break;
			case 75:
				  $acceso_db=$this->conectarDB($configuracion,"admin_sga");
				//  echo "Me estoy conectando como asesor";
				  break;
			case 80:
				  $acceso_db=$this->conectarDB($configuracion,"soporteoas");
				//  echo "Me estoy conectando como asesor";
				  break;
			  
			case 'evaldocente':
				$acceso_db=$this->conectarDB($configuracion,"autoevaluadoc");
				//echo "Me estoy conectando como evaldocente";
				  break;
				  
			case 83:
				$acceso_db=$this->conectarDB($configuracion,"secretarioacad");
				//echo "Me estoy conectando como secretario academico";
				  break;

                        case 28:
				  $acceso_db=$this->conectarDB($configuracion,"coordinadorcred");
				//  echo "Me estoy conectando como Coordinador creditos";
				  break;
			 case 88:
				  $acceso_db=$this->conectarDB($configuracion,"docencia");
				//  echo "Me estoy conectando como Docencia";
				  break;
      			case 'cambio_claveMY':
				$acceso_db=$this->conectarDB($configuracion,"cambio_claveMY");
				//echo "Me estoy conectando como evaldocente";
				  break;
			 case 104:
				  $acceso_db=$this->conectarDB($configuracion,"aspu");
				//  echo "Me estoy conectando como Coordinador creditos";
				  break;
			 case 109:
				  $acceso_db=$this->conectarDB($configuracion,"asistenteCont");
				//  echo "Me estoy conectando como asistente";
				  break;
			 case 110:
				  $acceso_db=$this->conectarDB($configuracion,"asistente");
				//  echo "Me estoy conectando como asistente";
				  break;
			 case 111:
				  $acceso_db=$this->conectarDB($configuracion,"asistente");
				//  echo "Me estoy conectando como asistente";
				  break;
			 case 112:
				  $acceso_db=$this->conectarDB($configuracion,"asistente");
				//  echo "Me estoy conectando como asistente";
				  break;
                         case 113:
                                  $acceso_db=$this->conectarDB($configuracion,"secgeneral");
                                  break;
			 case 114:
				  $acceso_db=$this->conectarDB($configuracion,"secretario");
				//  echo "Me estoy conectando como secretario";
				  break;
			 case 115:
				  $acceso_db=$this->conectarDB($configuracion,"secretario");
				//  echo "Me estoy conectando como secretario";
				  break;
			 case 116:
				  $acceso_db=$this->conectarDB($configuracion,"secretario");
				//  echo "Me estoy conectando como secretario";
				  break;
			
                        case 117:
                                $acceso_db=$this->conectarDB($configuracion,"asistenteCeri");
                                break;

                        case 118:
                                $acceso_db=$this->conectarDB($configuracion,"laboratorio");
                                break;
                            
                        case 119:
                                $acceso_db=$this->conectarDB($configuracion,"asistenteILUD");
                                break;
                            
                        case 120:
                                $acceso_db=$this->conectarDB($configuracion,"consultor");
                                break;
                         case 121:
				$acceso_db=$this->conectarDB($configuracion,"egresado");
			break;      


                         case 999://conexion para mysql registro de log de eventos
			      $acceso_db=$this->conectarDB($configuracion,"mysqlsga");
			  break;
                              
                        default:
				$acceso_db=false;
			break;
		  }

	  }
	  if(!isset($acceso_db)){
//		  include ("../err/fuera_de_servicio.php");
//		   echo "<script>location.replace(' Location: ../../err/aviso.php')</script>";
	  }
    
    return $acceso_db;
  
    }

}

?>
 
