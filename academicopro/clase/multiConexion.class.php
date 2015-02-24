<?PHP

require_once("funcionGeneral.class.php");

// Deshabilitar todo reporte de errores
//error_reporting(0);


class multiConexion extends funcionGeneral
{
	
   function nuevoRegistro($configuracion,$tema,$acceso_db){}
   function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable){}
   function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario){}
   function corregirRegistro(){}

    public function estableceConexion($nivelUsuario,$configuracion)
    {

	  if(!isset($nivelUsuario)){
		  //Por defecto rescata 	
		  session_name($configuracion["usuarios_sesion"]);
		  //echo "valores=".$configuracion["usuarios_sesion"];
                  //var_dump($configuracion["usuarios_sesion"]);
                  //exit;
                  session_start();

	  }
	  else{
		  switch($nivelUsuario)
		  {
             
			  case 16:
				  $acceso_db=$this->conectarDB($configuracion,"default");
				//  echo "Me estoy conectando como Decano";
				  break;
			  case 99:
				  $acceso_db=$this->conectarDB($configuracion,"mysqlsga");
                                // echo "Me estoy conectando como Decano";
				  break;
			  case 4:
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
			  case 31:
				  $acceso_db=$this->conectarDB($configuracion,"rector");
				//  echo "Me estoy conectando como rector";
				  break;
			  case 32:
				  $acceso_db=$this->conectarDB($configuracion,"vicerrector");
				//  echo "Me estoy conectando como vicerrector";
				  break;
			  case 34:
				  $acceso_db=$this->conectarDB($configuracion,"asesor");
				//  echo "Me estoy conectando como asesor";
				  break;

                          case 61:
			         $acceso_db=$this->conectarDB($configuracion,"asistente");
			  break;

                          case 83:
			       $acceso_db=$this->conectarDB($configuracion,"secretarioacad");

			  break;
			  
			  case 75:
				 $acceso_db=$this->conectarDB($configuracion,"oraclesga");
			  break;

                          case 28:
				 $acceso_db=$this->conectarDB($configuracion,"coordinadorCred");
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