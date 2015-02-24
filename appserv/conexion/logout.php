<?

require_once('conexion.php');
include_once("../clase/dbms.class.php");
require_once("../clase/config.class.php");

		$esta_configuracion=new config();
		$configuracion=$esta_configuracion->variable("../"); 

		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		
		if (is_resource($enlace)){	
		
				$cadena_sql="SELECT "; 
				$cadena_sql.="`nombre`, ";	
				$cadena_sql.="`tabla_sesion` ";						 
				$cadena_sql.="FROM "; 
				$cadena_sql.=$configuracion["prefijo"]."bd ";
                                
                        $acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
                        
                        //var_dump($registro);exit;
			
			if(is_array($registro)){

				$i=0;

				while(isset($registro[$i][0])){
				
					$cadena_sql="SELECT DISTINCT "; 
					$cadena_sql.="`id_sesion` ";						 
					$cadena_sql.="FROM "; 
					$cadena_sql.=$registro[$i][0].".".$registro[$i][1]." ";	
					$cadena_sql.="WHERE valor=".$_SESSION['usuario_login']; 
					
                                        //echo $cadena_sql;
					$acceso_db->registro_db($cadena_sql,0); 
					$registroSesion=$acceso_db->obtener_registro_db();
                                        //var_dump($registroSesion);exit;    
						$j=0;											
						while(isset($registroSesion[$j][0])){
							$cadena_sql = "DELETE FROM ".$registro[$i][0].".".$registro[$i][1]." WHERE id_sesion='".$registroSesion[$j][0]."'";
							$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");
							
						$j++;	
						}
			
					$i++;			
				}		
			
			}
			
		}		


session_name($usuarios_sesion);
session_start();

ob_start();
unset($_SESSION['Condorizado']);
unset($_SESSION['usuario_login']);
unset($_SESSION['usuario_password']);
unset($_SESSION['usuario_nivel']);
unset($_SESSION['carrera']);
unset($_SESSION['codigo']);
unset($_SESSION['tipo']);
unset($_SESSION["A"]);
unset($_SESSION["G"]);
unset($_SESSION["C"]);
unset($_SESSION['ccfun']);
unset($_SESSION["fun_cod"]);
unset($_SESSION['fac']);
unset($_SESSION['u1']);
unset($_SESSION['c2']);
unset($_SESSION['b3']);

session_unregister($_SESSION['Condorizado']);
session_unregister($_SESSION['usuario_login']);
session_unregister($_SESSION['usuario_password']);
session_unregister($_SESSION['usuario_nivel']);
session_unregister($_SESSION['carrera']);
session_unregister($_SESSION['codigo']);
session_unregister($_SESSION['aplicacion']);
session_unregister($_SESSION['contador']);
session_unregister($_SESSION['tipo']);
session_unregister($_SESSION["A"]);
session_unregister($_SESSION["G"]);
session_unregister($_SESSION["C"]);
session_unregister($_SESSION['ccfun']);
session_unregister($_SESSION["fun_cod"]);
session_unregister($_SESSION['fac']);
session_unregister($_SESSION['u1']);
session_unregister($_SESSION['c2']);
session_unregister($_SESSION['b3']);
	 
OCILogOff($oci_conecta);
/*lidea para contingencia*/
OCILogOff($oci_logueo);

/*actualiza el archivo de sesiones */
$ubica=$configuracion['raiz_documento']."/clase/";
$archivo="contador.txt";

if (file_exists($ubica.$archivo)) {
      $visitas = file_get_contents($ubica.$archivo);
   } else {
      $visitas = 0;   
   }
   $visitas--;

   $fd = fopen($ubica.$archivo, "w");
   fwrite($fd, $visitas);
   fclose($fd);
 /*termina la actualizacion*/  

session_destroy();
header("Location: ../index.php");
ob_end_flush();
?>
