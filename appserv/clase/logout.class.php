<?PHP
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          verifica.class.php 
* @author        Jairo Lavado
* @revision      Última revisión 22 de Diciembre de 2011
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author        Jairo Lavado
* @link		
* @description  
*
******************************************************************************/


require_once("funcionGeneral.class.php");

class logout extends funcionGeneral
{  
    private $configuracion;
    private $acceso_OCI;
    private $acceso_MY;
    private $acceso_Est;
    private $acceso_Est2;
    private $acceso_Est3;
    private $acceso_logueo;
    private $cripto;
    private $usser;
    private $tipoUser;
    private $CarpetaSesion;
    private $NumSesionEst;
    private $NumSesionEst2;
    private $NumSesionEst3;
    private $NumSesionFun;
    private $verificador;
    private $varIndex;
    
    public function __construct()
                {   
                    require_once("config.class.php");
                    require_once("encriptar.class.php");
                    
                    $esta_configuracion=new config();
                    $this->configuracion=$esta_configuracion->variable("../");
                    
                    $this->cripto=new encriptar();
                    if (isset($_REQUEST['index']))
                        {$this->cripto->decodificar_url($_REQUEST['index'], $this->configuracion);}
                    /*RESCATA DATOS DE USUARIO*/    
                    session_name($this->configuracion["usuarios_sesion"]);
                    session_start();    
                    if (isset($_REQUEST['usuario']))
                         { $this->usser=$_REQUEST['usuario'];
                         }
                    else{  $this->usser=$_SESSION['usuario_login'];
                        }                    
                        
                    $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");
                    //$this->acceso_OCI = $this->conectarDB($this->configuracion,"default");  
                    
                    if(strtoupper($this->configuracion['activar_redireccion_estudiante'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                        $this->acceso_Est = $this->conectarDB($this->configuracion,"sesiones_estudiante");  
                                   }
                    if(strtoupper($this->configuracion['activar_redireccion_estudiante2'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                        $this->acceso_Est2 = $this->conectarDB($this->configuracion,"sesiones_estudiante2");  
                                   }                                   
                    if(strtoupper($this->configuracion['activar_redireccion_estudiante3'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                        $this->acceso_Est3 = $this->conectarDB($this->configuracion,"sesiones_estudiante2");  
                                   }               
                    if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Funcionarios*/
                                       $this->acceso_Fun = $this->conectarDB($this->configuracion,"sesiones_funcionario");  
                                   }
                   /*variables para controlar las sesiones*/
                   $this->CarpetaSesion=$this->configuracion['raiz_documento']."/clase/sesiones/";
                   $this->NumSesionEst = "sesionesEstudiante.txt";
                   $this->NumSesionEst2 = "sesionesEstudiante2.txt";
                   $this->NumSesionEst3 = "sesionesEstudiante3.txt";
                   $this->NumSesionFun = "sesionesFuncionario.txt";
                   
                   /*variables para envio al index*/
                   $this->varIndex['verificador']=date("YmdH");
                   $this->varIndex['enlace']=$this->configuracion['enlace']; 
                   $this->varNombres['acceso']=$this->cripto->codificar_variable('acceso', $this->varIndex['verificador']);  
                   
                }
	

    function action()
                { //verifica el tipo de usuario
                  $cod_consul = $this->cadena_sql('busca_us',$this->usser);
                  //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                  $registro= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                  $this->tipoUser=$registro[0]['TIP_US'];
                  
                  $this->cerrar_sesion();
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
                    session_destroy();
                   $dir=$this->configuracion['host_logueo'].$this->configuracion['site'];
                   $variable=$this->varNombres['acceso'].'=1';                       
                   if(isset($_REQUEST['error_login'])){$variable.="&msgIndex=".$_REQUEST['error_login'];}
                   elseif(isset($_REQUEST['msgIndex'])){$variable.="&msgIndex=".$_REQUEST['msgIndex'];}
                   $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                   $this->direccionar($dir,$variable);
                   exit;
                  
                }                
                
                
    function direccionar($url,$var)
                { $url.='/index.php'; 
                  echo "
                   <script type='text/javascript'>
                        window.location='$url?$var';
                   </script>";
                exit;
                }// fin funcion direccionar    

            
   /**
    *   
    * @param type $acceso
    * @param type $aplicacion
    * @return type 
    * @name cerrar_sesion
    * @desc Funcion que verifica datos y cierra sesiones.
    */             
   function cerrar_sesion()
            {   //inicia la busqueda de sesiones antiguas guardadas
                
                $user['var']='id_usuario';
                $user['vl']=$this->usser;
                
                /*actualiza al contador, registra la sesion y redireciona si es el caso*/
                
                if($this->tipoUser=='51' || $this->tipoUser=='52' )
                      {
                       if(strtoupper($this->configuracion['recibir_sesiones_estudiantexfuncionario'])=='S')
                               { //verifica que no se esten usando el minimo de sesiones de funcionarios, y asignarlas a estudiantes*/
                                  $variable=array('DB'=>'dbms',
                                      'TABLA'=>$this->configuracion["prefijo"]."valor_sesion ",
                                      'USER'=>$this->usser);
                               //  var_dump($variable);
                                  $consulta_ses = $this->cadena_sql('rescatar_id_sesion',$variable);
                                 
                                  $aux=1;
                                  while (!isset($registroSesion) && $aux<4)
                                    {
                                      switch($aux)
                                          {case 1:
                                                  if(isset($this->acceso_Est))
                                                            {
                                                            $registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_Est, $consulta_ses,"busqueda"); 
                                                            if(isset($registroSesion))
                                                                    {$conexion_db=$this->acceso_Est;}
                                                            }            
                                                  if(isset($this->acceso_Est2))
                                                            {
                                                            $registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_Est2, $consulta_ses,"busqueda"); 
                                                            if(isset($registroSesion))
                                                                    {$conexion_db2=$this->acceso_Est2;}
                                                            }
                                                  if(isset($this->acceso_Est3))
                                                            {
                                                            $registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_Est3, $consulta_ses,"busqueda"); 
                                                            if(isset($registroSesion))
                                                                    {$conexion_db2=$this->acceso_Est3;}
                                                            }          
                                               break;
                                           case 2:
                                               if(isset($this->acceso_Fun))
                                                           {$registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_Fun, $consulta_ses,"busqueda");
                                                           if(isset($registroSesion))
                                                                    {$conexion_db=$this->acceso_Fun;}
                                                           }
                                               break;
                                           case 3:
                                               $registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_MY, $consulta_ses,"busqueda"); 
                                                if(isset($registroSesion))
                                                        {$conexion_db=$this->acceso_MY;}
                                               break;
                                          }
                                      $aux++;    
                                    }
                                  
                                  //var_dump($registroSesion);
                                  if(isset($registroSesion))
                                      {  
                                      $cod_consul = $this->cadena_sql('rescatar_tipo_sesion',$registroSesion[0]['id_sesion']);
                                      $reg_ses= $this->ejecutarSQL($this->configuracion,  $conexion_db, $cod_consul,"busqueda");

                                      //var_dump($reg_ses);exit;


                                     if($reg_ses[0]['tipo_ses']=='funcionario')
                                         {    
                                               if($this->configuracion['activar_redireccion_funcionario']=='S')
                                                   { $cerrado=$this->Db_sesiones($this->acceso_Fun); }
                                               else
                                                   { $cerrado=$this->Db_sesiones($this->acceso_MY); }

                                               if($cerrado>0)
                                                        {  $visitas=$this->leer_archivo($this->NumSesionFun); 
                                                           $visitas--;
                                                           $fd = fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                                                           fwrite($fd, $visitas);
                                                           fclose($fd);
                                                           unset($visitas);   
                                                        }

                                         }
                                      elseif($reg_ses[0]['tipo_ses']=='estudiante')
                                         {
                                          //cierra la sesion cuando la sesion es de estudiante
                                           if($this->configuracion['activar_redireccion_estudiante']=='S' || $this->configuracion['activar_redireccion_estudiante2']=='S' || $this->configuracion['activar_redireccion_estudiante3']=='S')
                                                { if($this->configuracion['activar_redireccion_estudiante']=='S')
                                                         { $cerrado=$this->Db_sesiones($this->acceso_Est);
                                                           $controlSesionEst=$this->NumSesionEst;   
                                                         }
                                                  if($this->configuracion['activar_redireccion_estudiante2']=='S' && $cerrado==0)
                                                         { $cerrado=$this->Db_sesiones($this->acceso_Est2);
                                                           $controlSesionEst=$this->NumSesionEst2;   
                                                         }
                                                  if($this->configuracion['activar_redireccion_estudiante3']=='S' && $cerrado==0)
                                                         { $cerrado=$this->Db_sesiones($this->acceso_Est3);
                                                           $controlSesionEst=$this->NumSesionEst3;   
                                                         }  
                                                }         
                                           else
                                               { $cerrado=$this->Db_sesiones($this->acceso_MY); 
                                                 $controlSesionEst=$this->NumSesionEst;   
                                               }

                                           if($cerrado>0)
                                                {  $visitas=$this->leer_archivo($controlSesionEst); 
                                                   $visitas--;
                                                   $fd = fopen($this->CarpetaSesion.$controlSesionEst, "w");
                                                   fwrite($fd, $visitas);
                                                   fclose($fd);
                                                   unset($visitas);   
                                                }

                                         }
                                     }
                                 else
                                    { //si no existen datos redirecciona
                                      $dir=$this->configuracion['host_logueo'].$this->configuracion['site'];
                                      $variable="msgIndex=113";
                                      $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                      $this->direccionar($dir,$variable);
                                      exit;
                                    }
                                 
                               }
                        else
                               {
                               //cierra la sesion cuando la sesion es de estudiante
                               if($this->configuracion['activar_redireccion_estudiante']=='S' || $this->configuracion['activar_redireccion_estudiante2']=='S' || $this->configuracion['activar_redireccion_estudiante3']=='S')
                                   { if($this->configuracion['activar_redireccion_estudiante']=='S')
                                            { $cerrado=$this->Db_sesiones($this->acceso_Est);
                                              //$controlSesionEst=$this->NumSesionEst;   
                                              
                                                if($cerrado>0)
                                                    {  $visitas=$this->leer_archivo($this->NumSesionEst); 
                                                       $visitas--;
                                                       $fd = fopen($this->CarpetaSesion.$this->NumSesionEst, "w");
                                                       fwrite($fd, $visitas);
                                                       fclose($fd);
                                                       unset($visitas);   
                                                    }
                                            }
                                     if($this->configuracion['activar_redireccion_estudiante2']=='S'/* && $cerrado==0*/)
                                            { $cerrado=$this->Db_sesiones($this->acceso_Est2);
                                              //$controlSesionEst=$this->NumSesionEst2;   
                                                if($cerrado>0)
                                                    {  $visitas=$this->leer_archivo($this->NumSesionEst2); 
                                                       $visitas--;
                                                       $fd2 = fopen($this->CarpetaSesion.$this->NumSesionEst2, "w");
                                                       fwrite($fd2, $visitas);
                                                       fclose($fd2);
                                                       unset($visitas);   
                                                    }
                                            }
                                     if($this->configuracion['activar_redireccion_estudiante3']=='S' /*&& $cerrado==0*/)
                                            { $cerrado=$this->Db_sesiones($this->acceso_Est3);
                                              //$controlSesionEst=$this->NumSesionEst3;   
                                                if($cerrado>0)
                                                    {  $visitas=$this->leer_archivo($this->NumSesionEst3); 
                                                       $visitas--;
                                                       $fd3 = fopen($this->CarpetaSesion.$this->NumSesionEst3, "w");
                                                       fwrite($fd3, $visitas);
                                                       fclose($fd3);
                                                       unset($visitas);   
                                                    }
                                            }       
                                   }
                               else
                                   { $cerrado=$this->Db_sesiones($this->acceso_MY); 
                                     //$controlSesionEst=$this->NumSesionEst;      
                                     
                                            if($cerrado>0)
                                             {  $visitas=$this->leer_archivo($this->NumSesionEst); 
                                                $visitas--;
                                                $fd = fopen($this->CarpetaSesion.$this->NumSesionEst, "w");
                                                fwrite($fd, $visitas);
                                                fclose($fd);
                                                unset($visitas);   
                                             }
                                   }
                    /*
                               if($cerrado>0)
                                    {  $visitas=$this->leer_archivo($controlSesionEst); 
                                       $visitas--;
                                       $fd = fopen($this->CarpetaSesion.$controlSesionEst, "w");
                                       fwrite($fd, $visitas);
                                       fclose($fd);
                                       unset($visitas);   
                                    }*/

                               }
                    /*fin grabar archivo*/
                       }
                 else
                      {
                       
                        if($this->configuracion['activar_redireccion_funcionario']=='S')
                               { $cerrado=$this->Db_sesiones($this->acceso_Fun); }
                           else
                               { $cerrado=$this->Db_sesiones($this->acceso_MY); }
                    
                       if($cerrado>0)
                            {  $visitas=$this->leer_archivo($this->NumSesionFun); 
                               $visitas--;
                               $fd = fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                               fwrite($fd, $visitas);
                               fclose($fd);
                               unset($visitas);   
                            }
                        else
                            { //si no existen datos redirecciona
                              $dir=$this->configuracion['host_logueo'].$this->configuracion['site'];
                              $variable="msgIndex=113";
                              $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                              $this->direccionar($dir,$variable);
                              
                              exit;
                            }
                               
                    /*fin grabar archivo*/
                       }  
                  
       
            }//fin funcion cerrar_sesion  
            
      function Db_sesiones($conexion)
              {   
          	 $borrados=0;
                 $cod_consul = $this->cadena_sql('busca_db','');
                 $registro= $this->ejecutarSQL($this->configuracion, $conexion, $cod_consul,"busqueda");
                 //var_dump($registro);
			
			if(is_array($registro)){

				$i=0;

				while(isset($registro[$i][0])){
				
                                        $variable=array('DB'=>$registro[$i][0],
                                                        'TABLA'=>$registro[$i][1],
                                                        'USER'=>$this->usser);
                                        
                                        $consulta_ses = $this->cadena_sql('rescatar_id_sesion',$variable);
                                        $registroSesion= $this->ejecutarSQL($this->configuracion, $conexion, $consulta_ses,"busqueda");
                                        //var_dump($registroSesion);
						$j=0;											
						while(isset($registroSesion[$j][0])){
                                                        $variable['SES']=$registroSesion[$j][0];
                                                    
                                                        $consulta_borra_ses = $this->cadena_sql('borrar_sesion',$variable);
                                                        $resultado=$this->ejecutarSQL($this->configuracion, $conexion, $consulta_borra_ses,"");
                                                        if($resultado)
                                                            {$borrados=$borrados+1;}
							//$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");
							
						$j++;	
						}
			
					$i++;			
				}		
			
			}
                     else
                        { //si no existen datos redirecciona
                          $dir=$this->configuracion['host_logueo'].$this->configuracion['site'];
                          $variable="msgIndex=113";
                          $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                          $this->direccionar($dir,$variable);
                          
                          exit;
                        }
			//exit;    
                   return($borrados);
              }//fin funcion Db_sesiones
            
   
   function leer_archivo($doc)
              {   
                  //echo "<br> documento ".$this->CarpetaSesion.$doc;
                  if (!file_exists($this->CarpetaSesion.$doc)) 
                      {$dato = 0;}
                  else
                      {$dato = file_get_contents($this->CarpetaSesion.$doc);
                      }
                   //echo "<br>dato ".$dato;
                   return($dato);
                   exit;
                   
                   
              }//fin funcion leer_archivo    
              
              
   function expirar_sesiones()
              { 
                /*VERIFICA Y REALIZA LA EXPIRACION DE SESIONES DE ESTUDIANTES*/
                $tiempoEst=$this->leer_archivo($this->TimeSesionEst);
                $expiraEst_ini=$tiempoEst+$this->configuracion['tiempo_revisar_sesiones_estudiante'];
                $expiraEst_fin=time();
                //$this->expira->verificarExpiracion();
                if($expiraEst_ini<$expiraEst_fin)
                    {$this->expira->verificarExpiracion('estudiante'); }
               
                $fsesEst = fopen($this->CarpetaSesion.$this->TimeSesionEst, "w");
                fwrite($fsesEst, $expiraEst_fin);
                fclose($fsesEst);  
               
                /*VERIFICA Y REALIZA LA EXPIRACION DE SESIONES DE ESTUDIANTES*/
                $tiempoFun=$this->leer_archivo($this->NumSesionFun);
                $expiraFun_ini=$tiempoFun+$this->configuracion['tiempo_revisar_sesiones_funcionario'];
                $expiraFun_fin=time();
                //$this->expira->verificarExpiracion();
                if($expiraFun_ini<$expiraFun_fin)
                    {$this->expira->verificarExpiracion('funcionario'); }
               
                $fsesFun = fopen($this->CarpetaSesion.$this->TimeSesionFun, "w");
                fwrite($fsesFun, $expiraFun_fin);
                fclose($fsesFun); 
                
                /*espera 3 segundos mientras borra los registros*/
                //set_time_limit(10); 
                 
              }           
              
   
   function cadena_sql($tipo,$variable)
		{
			
			switch($tipo)
			{

                            
			case "busca_us":
                                                        
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "cla_codigo COD, ";
                             $cadena_sql.= "cla_clave PWD, ";
                             $cadena_sql.= "cla_tipo_usu TIP_US, ";
                             $cadena_sql.= "cla_estado EST ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $this->configuracion["sql_tabla1"]." ";
                             $cadena_sql.= "WHERE ";
                             $cadena_sql.= "cla_codigo='".$this->usser."' ";
                             $cadena_sql.= "ORDER BY EST";
                            
                            break;
                        
                        case "sesiones":
                                                        
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "count(distinct id_sesion) NUM_SES ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $this->configuracion["prefijo"]."valor_sesion ";
                                                        
                            break;
                        
                        case "busca_db":
                            
                            $cadena_sql="SELECT "; 
			    $cadena_sql.="`nombre`, ";	
			    $cadena_sql.="`tabla_sesion` ";						 
			    $cadena_sql.="FROM "; 
			    $cadena_sql.=$this->configuracion["prefijo"]."bd ";
                            
                            break;
                        
                        case "rescatar_id_sesion":

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="id_sesion ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$variable['DB'].".".$variable['TABLA']." ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='usuario' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="valor ='".$variable['USER']."' ";
                                                        
                            break;                        
                        
                        case "rescatar_tipo_sesion":

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="valor tipo_ses ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$this->configuracion["prefijo"]."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='tipo_sesion' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="id_sesion='".$variable."' ";                            
                            break;
                        
                        
                        case "borrar_sesion":
                                                        
                            $cadena_sql="DELETE  ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$variable['DB'].".".$variable['TABLA']." ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_sesion='".$variable['SES']."' ";
                                
                            break;                        
     
                        case "guardar_valor_sesion":
                                                        
                            $cadena_sql="INSERT INTO ";
                            $cadena_sql.=$this->configuracion["prefijo"]."valor_sesion (id_sesion,variable,valor) ";
                            $cadena_sql.="VALUES (";
                            $cadena_sql.="'".$variable['ses']."', ";
                            $cadena_sql.="'".$variable['vr']."', ";
                            $cadena_sql.="'".$variable['vl']."' ";
                            $cadena_sql.=");";
                        
                            break;                        

                        case "actualizar_valor_sesion":
                                                        
                            $cadena_sql="UPDATE ";
                            $cadena_sql.=$this->configuracion["prefijo"]."valor_sesion ";
                            $cadena_sql.="SET ";
                            $cadena_sql.="valor='".$variable['vl']."' ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_sesion='".$variable['ses']."' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="variable='".$variable['vr']."' ";
                                                    
                            break;                                                
                        
 
                        default    :
                            $cadena_sql= ""; 
                            break;        
			}
			
			
		
			return $cadena_sql;
		
		}              
                
                
                
                
	
	
}// fin clase bloqueAdminUsuario

// @ Crear un objeto bloque especifico

$esteBloque = new logout();
$esteBloque->action();


?>
