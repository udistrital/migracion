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

class verifica extends funcionGeneral
{  
    private $configuracion;
    private $acceso_OCI;
    private $acceso_MY;
    private $acceso_Est;
    private $acceso_Fun;
    private $reg_acceso;
    private $cripto;
    private $usser;
    private $pwd;
    private $numero;
    private $nueva_sesion;
    private $nom_us;
    private $tipoUser;
    private $CarpetaSesion;
    private $NumSesionEst;
    private $NumSesionFun;
    private $TimeSesionEst;
    private $TimeSesionFun;
    private $expira;
    private $verificador;

    
    public function __construct()
                {       
                    require_once("sesion.class.php");
                    require_once("encriptar.class.php");
                    require_once("config.class.php");
                    require_once("accesos.class.php");
                    require_once("expirar_sesiones.class.php");
                    
                    $esta_configuracion=new config();
                    $this->configuracion=$esta_configuracion->variable("../");
                    
                    $this->cripto=new encriptar();
                    if (isset($_REQUEST['index']))
                        {$this->cripto->decodificar_url($_REQUEST['index'], $this->configuracion);}
                    
                    $this->nueva_sesion=new sesiones($this->configuracion);
                    $this->reg_acceso=new acceso($this->configuracion);
                                        
                    $this->expira = new expira_sesion();
                    
                     $this->acceso_MY = '';
                    //$this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");  
                    //$this->acceso_OCI = $this->conectarDB($this->configuracion,"default");  
                    
                    /*
                    if(strtoupper($this->configuracion['activar_redireccion_estudiante'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Estudiantes/
                                        $this->acceso_Est = $this->conectarDB($this->configuracion,"sesiones_estudiante");  
                                   }
                    if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' )
                                   {  /*realiza la conexion a la bd del servidor de Funcionarios/
                                       $this->acceso_Fun = $this->conectarDB($this->configuracion,"sesiones_funcionario");  
                                   }*/
                    
                    if(!isset($_POST['user']))
                        {
                         $this->usser=$_REQUEST['user'];
                         
                         if(isset($_REQUEST['pass']))
                               {$this->pwd=strtolower($_REQUEST['pass']);}
                         if(isset($_REQUEST['numero']))
                               {$this->numero = strtolower($_REQUEST['numero']);}      
                         if(isset($_REQUEST['verificador']))
                               {$this->verificador=$_REQUEST['verificador'];}
                        }
                    else{
                            $this->usser=$_POST['user'];
                            
                            if(isset($_POST['pass']))
                               {$this->pwd=strtolower($_POST['pass']);}
                            if(isset($_POST['numero']))
                                   {$this->numero = strtolower($_POST['numero']);} 
                            if(isset($_POST['verificador']))
                                   {$this->verificador=$_POST['verificador']; }
                        }
                   
                   /*variables para controlar las sesiones*/
                   $this->CarpetaSesion=$this->configuracion['raiz_documento']."/clase/sesiones/";
                   $this->NumSesionEst = "sesionesEstudiante.txt";
                   $this->NumSesionFun = "sesionesFuncionario.txt";
                   $this->TimeSesionEst = "tiempoSesionesEstudiante.txt";
                   $this->TimeSesionFun = "tiempoSesionesFuncionario.txt";
                
                }
	

    function action()
                { /*echo "<br>verificador = ".$this->configuracion['verificador'];
                  echo "<br>verificador_llega = ".$this->verificador;*/
                  
                  $indice=  $this->configuracion["host"].$this->configuracion["site"]."/index.php";
                        /*verifica sesiones expiradas y las borra*/
                        $this->expirar_sesiones();  
                        //echo $indice; exit;
                        if(isset($_SERVER['HTTP_REFERER']))
                             {$url = explode("?",$_SERVER['HTTP_REFERER']);}
                        
                        //$redir = $url[0];
                        $redir=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                        //$numero = $this->numero;
                        //echo "<br>pass ".$this->usser;
                        //echo "<br>pwd ".$this->pwd;
        
                  /*revisa si viene direccionado de servidor de logueo*/
                  if(isset($this->verificador))
                    {
                       if($this->verificador==$this->configuracion['verificador']) 
                            { $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");
                              $cod_consul = $this->cadena_sql('busca_us',$this->usser);
                              //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                              $registro= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                              //var_dump($registro);
                              
                              ob_start();//habilita el buffer de salida  
                              session_cache_limiter('nocache,private');
                              session_name($this->configuracion["usuarios_sesion"]);
                              session_start();

                              /*registra el inicio de la sesion en el sistema*/ 
                              $_SESSION["usuario_login"] = $registro[0]['COD'];
                              $_SESSION['usuario_password'] = $registro[0]['PWD'];
                              $_SESSION["usuario_nivel"] = $registro[0]['TIP_US'];
                              
                              $this->tipoUser=$registro[0]['TIP_US'];
                              $this->selecciona_pagina($this->tipoUser);
                              exit;
                            }
                       else
                            {
                            $this->direccionar($redir,'error_login=115');
                            exit;
                            }  
                      
                    }  
                  else
                    {//echo "<br>VERIFICADOR no trae nada";
                    
                        if($_SERVER['HTTP_REFERER'] == "")
                            {
                               die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
                               exit;
                            }
                            elseif(empty($this->usser))
                            {          $this->direccionar($redir,'error_login=4');
                                       exit;
                            }
                            elseif(!is_numeric($this->usser))
                            {          $this->direccionar($redir,'error_login=4');
                                       exit;
                            }

                            elseif(isset($this->usser) && isset($this->pwd)) 
                                    { 
                                      if(strlen(trim($this->usser))==11)
                                           { if(strtoupper($this->configuracion['activar_maximo_sesiones_estudiante'])=='S' )
                                                   {  /*VERIFICA la cantidad maxima de sesiones de estudiante que se han generado*/
                                                        $this->verifica_sesiones('estudiante');   
                                                   }
                                           }
                                       else
                                           { if(strtoupper($this->configuracion['activar_maximo_sesiones_funcionario'])=='S' )
                                                   {   /*VERIFICA la cantidad maxima de sesiones de funcionario que se han generado*/
                                                        $this->verifica_sesiones('funcionario');   
                                                   }
                                           }
                                   
                                    /*ejecuta la restriccion de acceso adicional que se quiera presentar */
                                    if(strtoupper($this->configuracion['restriccion_acceso_est'])=='S' )
                                          {$this->restriccion_acceso();}   
                                          
                                    /*genera las conexiones a BD mysql*/
                                    $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");        
                                    if(strtoupper($this->configuracion['activar_redireccion_estudiante'])=='S' )
                                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                        $this->acceso_Est = $this->conectarDB($this->configuracion,"sesiones_estudiante");  
                                                   }
                                    if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' )
                                                   {  /*realiza la conexion a la bd del servidor de Funcionarios*/
                                                       $this->acceso_Fun = $this->conectarDB($this->configuracion,"sesiones_funcionario");  
                                                   }      
                                          
                                    
                                    /*consulta los datos del usuario*/      
                                    $cod_consul = $this->cadena_sql('busca_us',$this->usser);
                                    //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                                    $registro= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                                    // var_dump($registro); 
                                    
                                     if(is_array($registro))
                                        { 

                                                if(strtolower($this->numero.$registro[0]['PWD'])!=strtolower($this->pwd))/*redirecciona si la contraseña no coincide*/
                                                      {
                                                        $this->direccionar($redir,'error_login=3');
                                                        exit;
                                                      }
                                                elseif($registro[0]['EST'] <> 'A')/*redirecciona si el usuario no esta activo*/
                                                      { $this->direccionar($redir,'error_login=7');
                                                         exit;
                                                      }
                                                elseif($registro[0]['COD']==stripslashes($this->usser) && strtolower($this->numero.$registro[0]['PWD'])==strtolower($this->pwd))
                                                      { $this->tipoUser=$registro[0]['TIP_US'];
                                                        ob_start();//habilita el buffer de salida  
                                                        session_cache_limiter('nocache,private');
                                                        session_name($this->configuracion["usuarios_sesion"]);
                                                        session_start();
                                                        
                                                        /*registra en el log el acceso del usuario de oracle geconexlog*/                                                
                                                        $this->reg_acceso->registrar($this->usser,'2');

                                                        /*registra el inicio de la sesion en el sistema*/ 
                                                        $_SESSION["usuario_login"] = $registro[0]['COD'];
                                                        $_SESSION['usuario_password'] = $registro[0]['PWD'];
                                                        $_SESSION["usuario_nivel"] = $registro[0]['TIP_US'];
                                                        
                                                        $sesion_id=$this->inicio_sesion('condor');
                                                        $this->selecciona_pagina($this->tipoUser);
                                                        exit;
                                                        
                                                        
                                                      }
                                        }
                                      else
                                        {/*si no existen registros devuelve error*/
                                          $this->direccionar($this->configuracion['host_logueo'].$this->configuracion['site']."/index.php",'error_login=4');
                                          exit;
                                        }
     
                                    }
                      
                    }  
                
                  exit;
        
        
                }                
                
                
                
    function direccionar($url,$var)
                { 
                  echo "
                   <script type='text/javascript'>
                        window.location='$url?$var';
                   </script>";
                exit;
                }// fin funcion direccionar    


   function verifica_usuario()
                {  
                  switch (strtolower($this->configuracion['procesos_ejecuta']))
                      { case 'estudiante':
                             $tamano=11;
                          break;
                      
                      }
        
                  if (strlen(trim($this->usser)) < $tamano )
                            { 

                            $indice=$this->configuracion['host_redireccion']."/clase/verifica.class.php";
                            
                            $variable="user=".$this->usser;
                            $variable.="&pass=".$this->pwd;
                            $variable.="&numero=".$this->numero;
                            //echo $dirl."?."$var
                            //var_dump($configuracion);
                            $variable= $this->cripto->codificar_url($variable,$this->configuracion);
                                                        
                            $this->direccionar($indice,$variable);
                            exit;
                            }
                }// fin funcion verificar usuario                    
                
                
     /**
      *
      * @name verifica_sesiones 
      * @param type $t_us 
      * @desc Funcion verifica que el numero de sesiones activas por tipo de usuario, direccionando cuando todas las sesiones estan ocupadas
      * 
      */           
    function verifica_sesiones($t_us)
                {  
                   $valida=$this->excepciones(); 
                   if($valida!=$this->usser) 	
                         {    /*lee los archivos de conteo de sesiones*/
                             if($t_us=='estudiante')
                                    {$ingresos=$this->leer_archivo($this->NumSesionEst);}//fin tipo usuario estudiante  
                             elseif($t_us=='funcionario')
                                    {$ingresos=$this->leer_archivo($this->NumSesionFun);}//fin tipo usuario funcionario     

                             if($this->configuracion['maximo_sesiones_'.$t_us]==0)
                                  { 
                                         $dir=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                                         $var="error_login=114";
                                         $this->direccionar($dir,$var);   
                                         exit;
                                  }
                             elseif($ingresos>=$this->configuracion['maximo_sesiones_'.$t_us])
                                  { 
                                     /*verifica que se puedan usar sesiones de funcionario para estudiantes*/
                                     if($t_us=='estudiante' && strtoupper($this->configuracion['recibir_sesiones_estudiantexfuncionario'])=='S')
                                           { /*verifica que no se esten usando el minimo de sesiones de funcionarios, y asignarlas a estudiantes*/
                                             $ingresosFun=$this->leer_archivo($this->NumSesionFun);
                                             if($ingresosFun>=$this->configuracion['min_sesiones_estudiantexfuncionario'])
                                                 {
                                                     $dir=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                                                     $var="error_login=112";
                                                     $this->direccionar($dir,$var);   
                                                     exit;
                                                 }
                                             //echo "<br>permitio sesion";    
                                           }
                                      else
                                          {
                                             $dir=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                                             $var="error_login=112";
                                             $this->direccionar($dir,$var);   
                                             exit;
                                          }     
                                  }
                         }
                }// fin funcion verificar usuario                
                
                
    function excepciones()
                {  
                 $datos=array('9310518','20052005009');
                 
                    foreach($datos as $key => $value )
                            { if($datos[$key]==trim($this->usser))
                                 { 
                                  return($datos[$key]);exit;}
                                 }
                        
                }// fin funcion verificar usuario                             
     
   function restriccion_acceso()
                {  
       
                //echo substr($this->usser,5,3);exit;
                  if(strlen(trim($this->usser))==11)
                      {  
                       if(substr(date("i"),-1)<4 && substr($this->usser,5,3)!='005')
                                {
                                 $dir=$this->configuracion['host'].$this->configuracion['site']."/index.php";
                                 $var="error_login=112";
                                 $this->direccionar($dir,$var);   
                                 exit;
                                 }
                      }          
       
                }// fin funcion direccionar             
                
            
   /**
    *   
    * @param type $acceso
    * @param type $aplicacion
    * @return type 
    * @name inicio_sesion
    * @desc Funcion que inicia y registra la sesion del usuario, borra las sesiones antiguas.
    */             
   function inicio_sesion($aplicacion)
            {   //inicia la busqueda de sesiones antiguas guardadas
                
                $user['var']='id_usuario';
                $user['vl']=$this->usser;
                
                
                 $cod_consul = $this->cadena_sql('rescatar_id_sesion',$user);
                //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                 $reg_ses= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                 
                 if($reg_ses)
                        { //borra las sesiones antiguas guardadas
                          foreach ($reg_ses as $key => $value) 
                                {
                                  $consulta_borra = $this->cadena_sql('borrar_sesion',$reg_ses[$key][0]);
                                  $borra_ses= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $consulta_borra,"");
                                }
                        }
                 
                 if($this->configuracion['activar_redireccion_funcionario']=='S')
                     {
                     $reg_sesFun= $this->ejecutarSQL($this->configuracion,  $this->acceso_Fun, $cod_consul,"busqueda");
                     
                     if($reg_sesFun)
                        { //borra las sesiones antiguas guardadas en servidor de funcionarios
                       
                        foreach ($reg_sesFun as $key => $value) 
                                {
                                  $consulta_borra_sesFun = $this->cadena_sql('borrar_sesion',$reg_sesFun[$key][0]);
                                  $borra_sesFun= $this->ejecutarSQL($this->configuracion,  $this->acceso_Fun, $consulta_borra_sesFun,"");
                                }
                        }
                     }
                     
                 if($this->configuracion['activar_redireccion_estudiante']=='S')
                     {
                     $reg_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est, $cod_consul,"busqueda");
                     
                     if($reg_sesEst)
                        { //borra las sesiones antiguas guardadas en servidor de estudiantes
                          foreach ($reg_sesEst as $key => $value) 
                                {
                                  $consulta_borra_sesEst = $this->cadena_sql('borrar_sesion',$reg_sesEst[$key][0]);
                                  $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est, $consulta_borra_sesEst,"");
                                }
                        }
                     
                     }  
                /*obtiene una nueva sesion*/
                $nva_sesion=$this->crear_sesion($this->usser, $this->tipoUser);
                     
                /*actualiza al contador, registra la sesion y redireciona si es el caso*/
                
                if($this->tipoUser=='51' || $this->tipoUser=='52' )
                      {$ingresosEst=$this->leer_archivo($this->NumSesionEst);
                      
                      //echo "estudiante ".$ingresosEst." ".$this->configuracion['maximo_sesiones_estudiante'];exit;
                       
                       if(strtoupper($this->configuracion['recibir_sesiones_estudiantexfuncionario'])=='S' && $ingresosEst>=$this->configuracion['maximo_sesiones_estudiante'])
                               {//echo "permite sesiones funcionario";exit;
                                 /*verifica que no se esten usando el minimo de sesiones de funcionarios, y asignarlas a estudiantes*/
                                 $ingresosFun=$this->leer_archivo($this->NumSesionFun);
                                
                                 if($ingresosFun<$this->configuracion['min_sesiones_estudiantexfuncionario'])
                                     {
                                           $visitas=$this->leer_archivo($this->NumSesionFun); 
                                           $visitas++;

                                           $fd = fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                                           fwrite($fd, $visitas);
                                           fclose($fd);
                                           $_SESSION['contador'] = $visitas;
                                           unset($visitas);
                                           //$this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion);
                                           //echo "se registro";
                                           
                                           if($this->configuracion['activar_redireccion_funcionario']=='S')
                                               {
                                                /*registra la sesion en la db*/
                                                $this->registrar_sesion($this->acceso_Fun,$nva_sesion,$aplicacion,'funcionario');
                                                /*redirecciona al servidor de atencion a funcionarios*/
                                                $variable="user=".$this->usser;
                                                //$variable.="&pass=".$this->pwd;
                                                //$variable.="&numero=".$this->numero;
                                                $variable.="&verificador=".$this->configuracion['verificador'];
                                                $variable=  $this->cripto->codificar_url($variable,$this->configuracion);
                                                $this->direccionar($this->configuracion['host_redireccion_funcionario'].$this->configuracion['site']."/clase/verifica.class.php",$variable);
                                               exit;
                                               }
                                           else
                                               {
                                               $this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion,'funcionario');
                                               //echo "se registro";
                                               }
                                           
                                     }//echo "<br>permitio sesion";    
                                  else
                                      {/*no permite el logueo porque exede el maximo de sesiones*/
                                         $dir=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                                         $var="error_login=112";
                                         $this->direccionar($dir,$var);   
                                         exit;
                                      }
                               }
                        else
                               {//echo " sesiones estudiante ";exit;
                               $visitas=$this->leer_archivo($this->NumSesionEst); 
                               $visitas++;

                               $fd = fopen($this->CarpetaSesion.$this->NumSesionEst, "w");
                               fwrite($fd, $visitas);
                               fclose($fd);
                               unset($visitas);
                               $_SESSION['contador'] = $visitas;
                               
                               if($this->configuracion['activar_redireccion_estudiante']=='S')
                                   { 
                                    /*registra la sesion en la db*/
                                    $this->registrar_sesion($this->acceso_Est,$nva_sesion,$aplicacion,'estudiante');
                                    /*redirecciona al servidor de atencion a funcionarios*/
                                    $variable="user=".$this->usser;
                                    //$variable.="&pass=".$this->pwd;
                                    //$variable.="&numero=".$this->numero;
                                    $variable.="&verificador=".$this->configuracion['verificador'];
                                   
                                    $variable=  $this->cripto->codificar_url($variable,$this->configuracion);
                                    $this->direccionar($this->configuracion['host_redireccion_estudiante'].$this->configuracion['site']."/clase/verifica.class.php",$variable);
                                    exit;
                                   }
                               else
                                   {
                                   $this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion,'estudiante');
                                   //echo "se registro";
                                   }
                               } 
                           unset($visitas);
                    /*fin grabar archivo*/
                       }
                 else
                      {
                       $visitas=$this->leer_archivo($this->NumSesionFun); 
                       $visitas++;

                       $fd = fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                       fwrite($fd, $visitas);
                       fclose($fd);

                       $_SESSION['contador'] = $visitas;
                       unset($visitas);
                       
                        if($this->configuracion['activar_redireccion_funcionario']=='S')
                               {
                                /*registra la sesion en la db*/
                                $this->registrar_sesion($this->acceso_Fun,$nva_sesion,$aplicacion,'funcionario');
                                /*redirecciona al servidor de atencion a funcionarios*/
                                $variable="user=".$this->usser;
                                //$variable.="&pass=".$this->pwd;
                                //$variable.="&numero=".$this->numero;
                                $variable.="&verificador=".$this->configuracion['verificador'];
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                $this->direccionar($this->configuracion['host_redireccion_funcionario'].$this->configuracion['site']."/clase/verifica.class.php",$variable);
                                exit;
                               
                               }
                           else
                               {
                               $this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion,'funcionario');
                               //echo "se registro";
                               }
                    
                    /*fin grabar archivo*/
                       }  
                   
                   
               // return($sesion);   
       
            }//fin funcion inicio_sesion  
            
            
            
function crear_sesion($usuario,$nivel_acceso)
    	{
		//Identificador de sesion
                $fecha=explode (" ",microtime());
                $rand=rand();
                $sesion_id=md5($fecha[1].substr($fecha[0],2).$usuario.$rand.$nivel_acceso);
                
                /*Actualizar la cookie*/
                setcookie("aplicativo",$sesion_id,(time()+$this->configuracion['expiracion']),"/");
                return $sesion_id;
	      
        }//Fin del método crear_sesion

        
function registrar_sesion($conexion,$sesion,$aplicacion,$tipo_ses)
    	{

        $variable['ses']=$sesion;
        
        $variable['vr']='usuario';
        $variable['vl']=$this->usser;
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
        
        $variable['vr']='id_usuario';
        $variable['vl']=$this->usser;
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
        
        $variable['vr']='acceso';
        $variable['vl']=$this->tipoUser;
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
        
        $variable['vr']='aplicacion';
        $variable['vl']=$aplicacion;
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
        
        $variable['vr']='expiracion';
        $variable['vl']=(time()+$this->configuracion["expiracion"]);
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
   
        $variable['vr']='tipo_sesion';
        $variable['vl']=$tipo_ses;
        $consulta_reg_ses = $this->cadena_sql('guardar_valor_sesion',$variable);
        $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $conexion, $consulta_reg_ses,"");
      
        }//Fin del método registrar_sesion        
            
   
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
                    {$this->expira->verificarExpiracion('estudiante'); 
                     $fsesEst = fopen($this->CarpetaSesion.$this->TimeSesionEst, "w");
                     fwrite($fsesEst, $expiraEst_fin);
                     fclose($fsesEst);
                    }
               
               
                /*VERIFICA Y REALIZA LA EXPIRACION DE SESIONES DE ESTUDIANTES*/
                $tiempoFun=$this->leer_archivo($this->NumSesionFun);
                $expiraFun_ini=$tiempoFun+$this->configuracion['tiempo_revisar_sesiones_funcionario'];
                $expiraFun_fin=time();
                //$this->expira->verificarExpiracion();
                if($expiraFun_ini<$expiraFun_fin)
                    {$this->expira->verificarExpiracion('funcionario'); 
                     $fsesFun = fopen($this->CarpetaSesion.$this->TimeSesionFun, "w");
                     fwrite($fsesFun, $expiraFun_fin);
                     fclose($fsesFun); 
                    }
               
                
                /*espera 3 segundos mientras borra los registros*/
                //set_time_limit(10); 
                
                
              }           
   
   function selecciona_pagina($nivel)
            { 
               switch ($nivel)
                       {
                        case 4: $this->direccionar('../coordinador/coordinador.php','');
                            break;
                        case 16: $this->direccionar('../decano/decano.php','');
                            break;
                        case 20: $this->direccionar('../administracion/adm_index.php','');
                            break;
                        case 24: $this->direccionar('../funcionario/funcionario.php','');
                            break;
                        case 26: $this->direccionar('../proveedor/proveedor.php','');
                            break;
                        case 28: $this->direccionar('../coordinadorcred/coordinadorcred.php','');
                            break;
                        case 30: $this->direccionar('../docentes/docente.php','');
                            break;
                        case 31: $this->direccionar('../rector/rector.php','');
                            break;
                        case 32: $this->direccionar('../vicerrector/vicerrector.php','');
                            break;
                        case 33: $this->direccionar('../registro/registro.php','');
                            break;
                        case 34: $this->direccionar('../asesor/asesor.php','');
                            break;
                        case 51: $this->direccionar('../estudiantes/estudiante.php','');
                            break;
                        case 52: $this->direccionar('../estudianteCreditos/estudianteCreditos.php','');
                            break;
                        case 61: $this->direccionar('../asisVicerrectoria/asisVicerrectoria.php','');
                            break;
                        case 75: $this->direccionar('../admin_sga/admin_sga.php','');
                            break;
                        case 80: $this->direccionar('../soporte/soporte.php','');
                            break;
                        case 83: $this->direccionar('../secacademico/secacademico.php','');
                            break;
                        case 84: $this->direccionar('../desarrolloOAS/desarrolloOAS.php','');
                            break;
                        case 87: $this->direccionar('../moodle/moodle.php','');
                            break;
                        case 88: $this->direccionar('../docencia/docencia.php','');
                            break;
                        default:
                            break;
                    }  
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
                             $cadena_sql.= "ORDER BY 4";
                            
                            break;
                        
                        case "sesiones":
                                                        
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "count(distinct id_sesion) NUM_SES ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $this->configuracion["prefijo"]."valor_sesion ";
                                                        
                            break;
                        
                        case "rescatar_id_sesion":
                                                        
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="id_sesion ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$this->configuracion["prefijo"]."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='".$variable['var']."' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="valor ='".$variable['vl']."' ";
                                                        
                            break;                        
                        
                        case "borrar_sesion":
                                                        
                            $cadena_sql="DELETE  ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$this->configuracion["prefijo"]."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_sesion='".$variable."' ";
                                                        
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

$esteBloque = new verifica();
$esteBloque->action();


?>
