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
* @revision      Última revisión 25 de Octubre
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.3
* @author        Jairo Lavado
* @link		
* @description  Clase que realiza la verificación de logueo y registro de sesiones
*
******************************************************************************/

require_once('funcionGeneral.class.php');

class verifica extends funcionGeneral
{   private $configuracion;
    private $acceso_OCI;
    private $acceso_MY;
    private $acceso_Est;
    private $acceso_Est2;
    private $acceso_Est3;
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
    private $ingresosEst;
    private $expira;
    private $verificador;
    private $captcha;
    private $varIndex;
    private $veces;
    private $retardo;
    private $semaf_id;
    private $semaforo;
    private $redirLogueo;
    private $varNombres;
    private $histSemilla;

    public function __construct()
                {   
                    require_once('sesion.class.php');
                    require_once('encriptar.class.php');
                    require_once('config.class.php');
                    require_once('accesos.class.php');
                    require_once('expirar_sesiones.class.php');
                    
                    $esta_configuracion=new config();
                    $this->configuracion=$esta_configuracion->variable('../');
                    $this->cripto=new encriptar();
                    if (isset($_REQUEST['index']))
                        {$this->cripto->decodificar_url($_REQUEST['index'], $this->configuracion);}
                    
                    $this->nueva_sesion=new sesiones($this->configuracion);
                    $this->reg_acceso=new acceso($this->configuracion);
                    $this->expira = new expira_sesion();
                    $this->acceso_MY = '';
                    /*Variables para el control de accesos y errores al index*/     
                    $this->varIndex['verificador']=date("YmdH");
                    $this->varIndex['enlace']=$this->configuracion['enlace'];     
                    $this->retardo=4;
                    /*rescarta los nombres y valores de la variables de logueo*/
                    $control=array();
                    foreach ($_REQUEST as $key => $value) 
                        { $control[$key]=trim($this->cripto->decodificar_variable($key, $this->varIndex['verificador']));  
                          //echo "<br>".$control[$key];
                          switch ($control[$key])
                            { case 'usuario': $this->usser=$_REQUEST[$key]; $this->varNombres['usuario']=$key; break;
                              /*case 'contrasena': $this->pwd=$_REQUEST[$key]; $this->varNombres['contrasena']=$key; break; //linea original*/
                              case 'contrasena': $this->pwd=sha1(strtolower($_REQUEST[$key])); $this->varNombres['contrasena']=$key; break;
                              case 'numero': $this->numero=$_REQUEST[$key]; $this->varNombres['numero']=$key; break;
                              case 'verificador': $this->verificador=$_REQUEST[$key]; $this->varNombres['verificador']=$key; break;
                              case 'oas_captcha': $this->captcha=$_REQUEST[$key];break;
                              case 'acceso': $this->veces=$_REQUEST[$key]; $this->varNombres['acceso']=$key; break;
                            }
                          
                            
                        }
                        !isset($this->varNombres['verificador'])?$this->varNombres['verificador']=$this->cripto->codificar_variable('verificador', $this->varIndex['verificador']):'';  
                        //echo $this->verificador;
                   /*
                    if(strlen($this->usser)==11)
                        {    /*asigna identificacion del semaforo e inici ael semaforo
                             * sem_get ( int $key [, int $max_acquire = 1 [, int $perm = 0666 [, int $auto_release = 1 ]]] )
                             * key:
                             * max_acquire:El número de procesos que puede adquirir el semáforo simultáneamente está establecido por max_acquire. 
                             * perm:Los permisos del semáforo. En realidad este valor se establece sólo si el proceso que lo encuentra es el único proceso actualmente adjunto al semáforo. 
                             * auto_release:Especifica si el semáforo debería ser liberado automáticamente al cierre de la petición. 
                             /
                         //echo "<br>llego  ".date ('H:i:s'); 
                         $key=date ('Ymd');  
                         $this->semaf_id=sem_get($key,1,0666);
                         //$semid=sem_get(0xee3,1,0666);//para memoria compartida
                         //$shm_id = shmop_open(0xff3, "c", 0644, 100);//para memoria compartida
                         $this->semaforo=sem_acquire($this->semaf_id);
                         }*/
                   
                   /*variables para controlar las sesiones*/
                   $this->CarpetaSesion=$this->configuracion['raiz_documento'].'/clase/sesiones/';
                   $this->NumSesionEst = 'sesionesEstudiante.txt';
                   $this->NumSesionEst2 = 'sesionesEstudiante2.txt';
                   $this->NumSesionEst3 = 'sesionesEstudiante3.txt';
                   $this->NumSesionFun = 'sesionesFuncionario.txt';
                   $this->TimeSesionEst = 'tiempoSesionesEstudiante.txt';
                   $this->TimeSesionFun = 'tiempoSesionesFuncionario.txt';
                   $this->ingresosEst = 'ingresosEstudiante.txt';
                   $this->histSemilla='historialSemillas.txt';
                   $this->redirLogueo=$this->configuracion['host_logueo'].$this->configuracion['site'].'/index.php';
                }
	
    function action()
                {   //echo "<br>tomo semaforo".  $this->semaf_id." ".  $this->semaforo." ".date ('H:i:s');
                    // usleep(1000000);
                    $indice=$this->configuracion['host'].$this->configuracion['site'].'/index.php';
                    /*verifica sesiones expiradas y las borra*/
                    $this->expirar_sesiones();  
                    //echo $indice; exit;
                    if(isset($_SERVER['HTTP_REFERER']))
                        {$url = explode("?",$_SERVER['HTTP_REFERER']);}
                   //$redirLogueo = $url[0]; //variable para redireccion
                  /*revisa si viene direccionado de servidor de logueo*/
                 
                  if(isset($this->verificador))
                    {  
                       if($this->verificador==$this->configuracion['verificador']) 
                            { $this->acceso_MY = $this->conectarDB($this->configuracion,'logueo');
                              $cod_consul = $this->cadena_sql('busca_us',$this->usser);
                              //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                              $registro= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,'busqueda');
                              //var_dump($registro);
                              ob_start();//habilita el buffer de salida  
                              session_cache_limiter('nocache,private');
                              session_name($this->configuracion['usuarios_sesion']);
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
                            {   $variable='msgIndex=115';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }  
                    }  
                  else
                    {   //*realiza la validacion del capcha y destruye la sesion*/
                        if(isset($this->captcha))
                            {   require_once 'captchaphp/securimage.php';
                                $securimage = new Securimage();
                                /*se borra toda la sesion de capcha para que no intervenga con la sesion de condor*/
                                session_destroy();
                            }
                        if($_SERVER['HTTP_REFERER'] == "")
                            {   $variable='msgIndex=115';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }
                        elseif(empty($this->usser))
                            {   /*invoca la funcion que cuenta los accesos errados simultaneos*/
                                $this->contarError();
                                $variable='msgIndex=4';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }
                        elseif(!is_numeric($this->usser))
                            {   /*invoca la funcion que cuenta los accesos errados simultaneos*/
                                $this->contarError();
                                $variable='msgIndex=4';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }
                        elseif(isset($this->captcha) && empty($this->captcha)) 
                            {   //*invoca la funcion que cuenta los accesos errados simultaneos/
                                $this->contarError();
                                $variable='msgIndex=117';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }    
                        elseif(isset($this->captcha) && $securimage->check($this->captcha) == false) 
                            {   //*invoca la funcion que cuenta los accesos errados simultaneos/
                                $this->contarError();
                                $variable='msgIndex=118';	
                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                $this->direccionar($this->redirLogueo,$variable);
                                exit;
                            }
                        elseif(isset($this->usser) && isset($this->pwd)) 
                                    {/*ejecuta la restriccion de acceso adicional que se quiera presentar */
                             
                                     if(strtoupper($this->configuracion['activar_otras_restricciones_estudiante'])=='S' && strlen(trim($this->usser))==11)
                                          {$this->restriccion_acceso();}   
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
                                    /*genera las conexiones a BD mysql*/
                                    $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");  

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
                                                        $this->acceso_Est3 = $this->conectarDB($this->configuracion,"sesiones_estudiante3");  
                                                   }               
                                    if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' )
                                                   {  /*realiza la conexion a la bd del servidor de Funcionarios*/
                                                       $this->acceso_Fun = $this->conectarDB($this->configuracion,"sesiones_funcionario");  
                                                   }      
                                    /*consulta los datos del usuario*/     
                                    $cod_consul = $this->cadena_sql('busca_usMY',$this->usser);
                                    //$registro=  $this->ejecutarSQL($this->configuracion,  $this->acceso_OCI, $cod_consul,"busqueda");
                                    $registro= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                                
                                                                                  
                                    if(is_array($registro))
                                        {      if(strtolower($this->numero.$registro[0]['PWD'])!=strtolower($this->pwd))/*redirecciona si la contraseña no coincide*/
                                                      { /*invoca la funcion que cuenta los accesos errados simultaneos*/
                                                        $this->contarError();
                                                        $variable='msgIndex=3';	
                                                        $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                        $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                        $this->direccionar($this->redirLogueo,$variable);
                                                        exit;
                                                      }
                                                elseif($registro[0]['EST'] <> 'A')/*redirecciona si el usuario no esta activo*/
                                                      { /*invoca la funcion que cuenta los accesos errados simultaneos*/
                                                        $this->contarError();
                                                        $variable='msgIndex=7';	
                                                        $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                        $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                        $this->direccionar($this->redirLogueo,$variable);
                                                        exit;
                                                      }
                                                elseif($registro[0]['COD']==stripslashes($this->usser) && (strtolower($this->numero.$registro[0]['PWD'])==strtolower($this->pwd)))
                                                      { $this->tipoUser=$registro[0]['TIP_US'];
                                                        //invocala funcion de actualizar la semilla de codificacion
                                                        $this->actualiza_semilla();
                                                        //*ejecuta la restriccion de acceso segun franjas - nuevo */
                                                        if(strtoupper($this->configuracion['activar_franjas'])=='S' && $registro[0]['NIVEL']=='PREGRADO' && ($this->tipoUser=='51' || $this->tipoUser=='52'))
                                                              { $usser=array('cod'=>$registro[0]['COD'],
                                                                             'facultad'=>$registro[0]['FAC'],
                                                                             'proyecto'=>$registro[0]['PROY']   
                                                                            );
                                                                $this->restriccion_franjas($usser);
                                                               }
                                                        //*ejecuta la restriccion de acceso adicional que se quiera presentar para los usuarios estudiante que el tamaño sea menos a 11 */
                                                        if(strtoupper($this->configuracion['activar_otras_restricciones_estudiante'])=='S' && ($this->tipoUser=='51' || $this->tipoUser=='52'))
                                                              {$this->restriccion_acceso();}
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
                                          $this->contarError();
                                          $variable='msgIndex=4';	
                                          $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                          $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                          $this->direccionar($this->redirLogueo,$variable);
                                          exit;
                                        }
                                    }
                    }  
                  exit;
                }                
                
    /**
     * @name direccionar
     * @param type $url
     * @param type $var
     * @desc  funcion que redirecciona a alguna pagina especifica 
     */
               
    function direccionar($url,$var)
                { /*
                 if(isset($this->semaf_id))
                    {//libera el semaforo iniciado en el constructor/
                     $this->liberaSemaforo();
                    }*/
                  echo "<script type='text/javascript'> window.location='$url?$var';</script>";
                  exit;
                }// fin funcion direccionar    
             
    function contarError()
                {    if(isset($this->veces))
                         {sleep($this->veces*$this->retardo);
                          $veces=($this->veces+1);}
                     else{$veces=1;}
                    $this->veces=$veces;
                }      
                
    function liberaSemaforo()
                {    //*libera el semaforo despues de una espera 1000 milisegundos */
                     usleep(300);
                     //echo "<br>libera ". date ('H:i:s');
                     sem_release($this->semaf_id);
                }                  

   function actualiza_semilla()
                {  unset($sem_actual);
                   $sem_actual=date("m/d/Y");
                   $actual=strtotime($sem_actual);
                   $ultima_actualizacion=strtotime(substr($this->configuracion['fecha_actualizacion_verificador'],0,10));
                   
                   if($actual>$ultima_actualizacion && $this->configuracion['host']==$this->configuracion['host_logueo'])
                            { //genera la nueva clave segun la antigua
                             $nva_clave=md5(substr($this->configuracion['verificador'],0,10));
                             //guarda la nueva clave en las bases de datos registradas en la tabla dbms_bd
                             $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");        
                             if(strtoupper($this->configuracion['activar_redireccion_estudiante'])=='S' )
                                           {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                $this->acceso_Est = $this->conectarDB($this->configuracion,"sesiones_estudiante");  
                                           }
                             if(strtoupper($this->configuracion['activar_redireccion_estudiante2'])=='S' )
                                           {  /*realiza la conexion a la bd del servidor de Estudiantes2*/
                                                $this->acceso_Est2 = $this->conectarDB($this->configuracion,"sesiones_estudiante2");  
                                           }
                             if(strtoupper($this->configuracion['activar_redireccion_estudiante3'])=='S' )
                                           {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                $this->acceso_Est3 = $this->conectarDB($this->configuracion,"sesiones_estudiante3");  
                                           }              
                             if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' )
                                           {  /*realiza la conexion a la bd del servidor de Funcionarios*/
                                               $this->acceso_Fun = $this->conectarDB($this->configuracion,"sesiones_funcionario");  
                                           }   
                                           
                             $cod_consul = $this->cadena_sql('busca_db','');
                             $registro= $this->ejecutarSQL($this->configuracion, $this->acceso_MY, $cod_consul,"busqueda");
                             if(is_array($registro))
                                 { $i=0;
                                   while(isset($registro[$i][0]))
                                        {        $variable=array('DB'=>$registro[$i][0],
                                                                'PREF'=>$registro[$i][2],
                                                                'vl'=>$nva_clave,
                                                                'param'=>'verificador');
                                                 $sql_actualiza = $this->cadena_sql('actualizar_configuracion',$variable);
                                                 $actualiza_sem=$this->ejecutarSQL($this->configuracion, $this->acceso_MY, $sql_actualiza,"");      
                                                 if(isset($actualiza_sem))
                                                 {
                                                     if(strtoupper($this->configuracion['activar_redireccion_estudiante'])=='S' && isset($this->acceso_Est))
                                                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                                        $this->ejecutarSQL($this->configuracion, $this->acceso_Est, $sql_actualiza,"");
                                                                   }
                                                     if(strtoupper($this->configuracion['activar_redireccion_estudiante2'])=='S' && isset($this->acceso_Est2))
                                                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                                        $this->ejecutarSQL($this->configuracion, $this->acceso_Est2, $sql_actualiza,"");
                                                                   }                                                                   
                                                     if(strtoupper($this->configuracion['activar_redireccion_estudiante3'])=='S' && isset($this->acceso_Est3))
                                                                   {  /*realiza la conexion a la bd del servidor de Estudiantes*/
                                                                        $this->ejecutarSQL($this->configuracion, $this->acceso_Est3, $sql_actualiza,"");
                                                                   }                                                                   
                                                     if(strtoupper($this->configuracion['activar_redireccion_funcionario'])=='S' && isset($this->acceso_Fun))
                                                                   {  /*realiza la conexion a la bd del servidor de Funcionarios*/
                                                                       $this->ejecutarSQL($this->configuracion, $this->acceso_Fun, $sql_actualiza,"");
                                                                   }     
                                                 }                 
                                                 $i++;			
                                        }		
                                $variable=array('DB'=>'dbms',
                                        'PREF'=>'dbms_',
                                        'vl'=>$h_actual=date("m/d/Y H:i:s"),
                                        'param'=>'fecha_actualizacion_verificador');
                                $sql_actualiza = $this->cadena_sql('actualizar_configuracion',$variable);
                                $this->ejecutarSQL($this->configuracion, $this->acceso_MY, $sql_actualiza,"");      
                                }

                                //guardar el historial de semillas
                                $ultima_sem = fopen($this->CarpetaSesion.$this->histSemilla,"a");
                                fwrite($ultima_sem, "$nva_clave \t $h_actual" . PHP_EOL);
                                fclose($ultima_sem);
                            }
                            
                            
                }// fin funcion actualizar semilla
                
     /**
      * @name verifica_sesiones 
      * @param type $t_us 
      * @desc Funcion verifica que el numero de sesiones activas por tipo de usuario, direccionando cuando todas las sesiones estan ocupadas
      */           
   function verifica_sesiones($t_us)
                {  $valida=$this->excepciones(); 
                   if($valida!=$this->usser) 	
                         {    /*lee los archivos de conteo de sesiones*/
                             if($t_us=='estudiante')
                                    {  $ingresos=$this->leer_archivo($this->NumSesionEst);
                                    
                                       if($this->configuracion['activar_redireccion_estudiante2']=='S')
                                            {$ingresos2=$this->leer_archivo($this->NumSesionEst2);}
                                       else {$ingresos2=$this->configuracion['maximo_sesiones_estudiante2']+1;}    
                                       if($this->configuracion['activar_redireccion_estudiante3']=='S')
                                            {$ingresos3=$this->leer_archivo($this->NumSesionEst3);}    
                                       else {$ingresos3=$this->configuracion['maximo_sesiones_estudiante3']+1;}   

                                       //echo $ingresos." ".$ingresos2." ".$ingresos3;exit;
                                       
                                       if($this->configuracion['maximo_sesiones_'.$t_us]==0)
                                                {     $this->contarError();
                                                      $variable='msgIndex=114';	
                                                      $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                      $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                      $this->direccionar($this->redirLogueo,$variable);
                                                      exit;
                                                }
                                        elseif($ingresos>=$this->configuracion['maximo_sesiones_estudiante'] && $ingresos2>$this->configuracion['maximo_sesiones_estudiante2'] && $ingresos3>$this->configuracion['maximo_sesiones_estudiante3'] )
                                                {  /*verifica que se puedan usar sesiones de funcionario para estudiantes*/
                                                   if($t_us=='estudiante' && strtoupper($this->configuracion['recibir_sesiones_estudiantexfuncionario'])=='S')
                                                         { /*verifica que no se esten usando el minimo de sesiones de funcionarios, y asignarlas a estudiantes*/
                                                           $ingresosFun=$this->leer_archivo($this->NumSesionFun);
                                                           if($ingresosFun>=$this->configuracion['min_sesiones_estudiantexfuncionario'])
                                                               {  $this->contarError();
                                                                  $variable='msgIndex=112';	
                                                                  $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                                  $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                                  $this->direccionar($this->redirLogueo,$variable);
                                                                  exit;
                                                               }
                                                            //echo "<br>permitio sesion";    
                                                         }
                                                    else
                                                        { $this->contarError();
                                                          $variable='msgIndex=112';	
                                                          $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                          $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                          $this->direccionar($this->redirLogueo,$variable);
                                                          exit;
                                                        }     
                                                }
                                       
                                    }//fin tipo usuario estudiante  
                             elseif($t_us=='funcionario')
                                    { $ingresos=$this->leer_archivo($this->NumSesionFun);
                                      
                                      if($this->configuracion['maximo_sesiones_'.$t_us]==0)
                                            {     $this->contarError();
                                                  $variable='msgIndex=114';	
                                                  $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                  $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                  $this->direccionar($this->redirLogueo,$variable);
                                                  exit;
                                            }
                                       elseif($ingresos>=$this->configuracion['maximo_sesiones_'.$t_us])
                                            {  /*verifica que se puedan usar sesiones de funcionario para estudiantes*/
                                                $this->contarError();
                                                $variable='msgIndex=112';	
                                                $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                                $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                                $this->direccionar($this->redirLogueo,$variable);
                                                exit;
                                            }
                                    }//fin tipo usuario funcionario     
                         }
                }// fin funcion verificar usuario                
                
                
  function excepciones()
                {  $datos=array('9310518','20052005009');
                   foreach($datos as $key => $value )
                            { if($datos[$key]==trim($this->usser))
                                 { 
                                  return($datos[$key]);exit;}
                                 }
                }// fin funcion verificar usuario                             
     
 function restriccion_franjas($user)
                { 
                    /*unset($h_actual);
                    $d_actual=date("m/d/Y");
                    $dia_actual=strtotime($d_actual);
                    $h_actual=date("m/d/Y H:i:s");
                    $hora_actual=strtotime($h_actual);*/
                    $user['fec_actual']=date("Y-m-d H:i:s");
                    $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");
                    $Franja_consul = $this->cadena_sql('verFranja',$user);
                    $regFranja= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $Franja_consul,"busqueda");
                    if(!$regFranja)
                        {   $this->contarError();
                            $variable='msgIndex=110';	
                            $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                            $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                            $this->direccionar($this->redirLogueo,$variable);
                            exit;
                        }
                    
                }// fin funcion franjas
                
 function restriccion_acceso()
                { unset($h_actual);
                $h_actual=date("m/d/Y H:i:s");
                $dias_actual=strtotime($h_actual);
                $restriccion_ini=strtotime(substr($this->configuracion['restriccion_acceso_est'],0,19));
                $restriccion_fin=strtotime(substr($this->configuracion['restriccion_acceso_est'],20,19));
                if($dias_actual>$restriccion_ini && $dias_actual<$restriccion_fin)
                    {   $this->contarError();
                        $variable='msgIndex=116';	
                        $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                        $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                        $this->direccionar($this->redirLogueo,$variable);
                        exit;
                    }
                }// fin funcion restricciones                         
            
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
                                { $consulta_borra = $this->cadena_sql('borrar_sesion',$reg_ses[$key][0]);
                                  $borra_ses=$this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $consulta_borra,"");
                                }
                        }
                 if($this->configuracion['activar_redireccion_funcionario']=='S')
                     { $reg_sesFun= $this->ejecutarSQL($this->configuracion,  $this->acceso_Fun, $cod_consul,"busqueda");
                      if($reg_sesFun)
                        { //borra las sesiones antiguas guardadas en servidor de funcionarios
                         foreach ($reg_sesFun as $key => $value) 
                                { $consulta_borra_sesFun = $this->cadena_sql('borrar_sesion',$reg_sesFun[$key][0]);
                                  $borra_sesFun=$this->ejecutarSQL($this->configuracion,  $this->acceso_Fun, $consulta_borra_sesFun,"");
                                }
                        }
                     }
                     
                 if($this->configuracion['activar_redireccion_estudiante']=='S')
                     {$reg_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est, $cod_consul,"busqueda");
                      if($reg_sesEst)
                        { //borra las sesiones antiguas guardadas en servidor de estudiantes
                          foreach ($reg_sesEst as $key => $value) 
                                { $consulta_borra_sesEst = $this->cadena_sql('borrar_sesion',$reg_sesEst[$key][0]);
                                  $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est, $consulta_borra_sesEst,"");
                                }
                        }
                     }  
                //nuevo para los nuevos servidores borrar la sesion activa
                 if($this->configuracion['activar_redireccion_estudiante2']=='S')
                     {$reg_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est2, $cod_consul,"busqueda");
                      if($reg_sesEst)
                        { //borra las sesiones antiguas guardadas en servidor de estudiantes
                          foreach ($reg_sesEst as $key => $value) 
                                { $consulta_borra_sesEst = $this->cadena_sql('borrar_sesion',$reg_sesEst[$key][0]);
                                  $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est2, $consulta_borra_sesEst,"");
                                }
                        }
                     }     
                 if($this->configuracion['activar_redireccion_estudiante3']=='S')
                     {$reg_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est3, $cod_consul,"busqueda");
                      if($reg_sesEst)
                        { //borra las sesiones antiguas guardadas en servidor de estudiantes
                          foreach ($reg_sesEst as $key => $value) 
                                { $consulta_borra_sesEst = $this->cadena_sql('borrar_sesion',$reg_sesEst[$key][0]);
                                  $borra_sesEst= $this->ejecutarSQL($this->configuracion,  $this->acceso_Est3, $consulta_borra_sesEst,"");
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
                                     {     $visitas=$this->leer_archivo($this->NumSesionFun); 
                                           $visitas++;
                                           $fd = fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                                           fwrite($fd, $visitas);
                                           fclose($fd);
                                           $_SESSION['contador'] = $visitas;
                                           unset($visitas);
                                           if($this->configuracion['activar_redireccion_funcionario']=='S')
                                               {/*registra la sesion en la db*/
                                                $this->registrar_sesion($this->acceso_Fun,$nva_sesion,$aplicacion,'funcionario');
                                                /*redirecciona al servidor de atencion a funcionarios*/
                                                //$variable="user=".$this->usser;
                                                $variable=$this->varNombres['usuario'].'='.$this->usser;
                                                //$variable.="&pass=".$this->pwd;
                                                //$variable.="&numero=".$this->numero;
                                                //$variable.="&verificador=".$this->configuracion['verificador'];
                                                $variable.='&'.$this->varNombres['verificador'].'='.$this->configuracion['verificador'];
                                                $variable=  $this->cripto->codificar_url($variable,$this->configuracion);
                                                $this->direccionar($this->configuracion['host_redireccion_funcionario'].$this->configuracion['site']."/clase/verifica.class.php",$variable);
                                               exit;
                                               }
                                           else
                                               {$this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion,'funcionario');
                                               //echo "se registro";
                                               }
                                     }//echo "<br>permitio sesion";    
                                  else
                                      {/*no permite el logueo porque exede el maximo de sesiones*/
                                        $redirLogueo=$this->configuracion['host_logueo'].$this->configuracion['site']."/index.php";
                                        $this->contarError();
                                        $variable='msgIndex=112';	
                                        $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                        $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                        $this->direccionar($this->redirLogueo,$variable);
                                        exit;
                                      }
                               }
                        else
                               {
                                //echo " sesiones estudiante ";
                               $servidor='N/A';
                               $controlSesionEst='';
                               $redirecciona='N';
                               $visitas=$this->leer_archivo($this->NumSesionEst); 

                               //nuevo verifica las sesiones en los archivos de control  de sesiones por cada servidor
                               
                               if($this->configuracion['activar_redireccion_estudiante']=='S' && $visitas<$this->configuracion['maximo_sesiones_estudiante'])
                                   { $servidor=$this->configuracion['host_redireccion_estudiante'];
                                     $conexionEst=$this->acceso_Est; 
                                     $controlSesionEst=$this->NumSesionEst;
                                     $redirecciona='S';
                                   }
                                   
                               if($this->configuracion['activar_redireccion_estudiante2']=='S' && $servidor=='N/A')
                                   { $visitas=$this->leer_archivo($this->NumSesionEst2);   
                                     $redirecciona='S';   
                                     if($visitas<$this->configuracion['maximo_sesiones_estudiante2'])
                                            { $servidor=$this->configuracion['host_redireccion_estudiante2'];
                                              $conexionEst=$this->acceso_Est2;
                                              $controlSesionEst=$this->NumSesionEst2;
                                            }
                                   }
                               if($this->configuracion['activar_redireccion_estudiante3']=='S' && $servidor=='N/A')
                                   { $visitas=$this->leer_archivo($this->NumSesionEst3);   
                                     $redirecciona='S';
                                     if($visitas<$this->configuracion['maximo_sesiones_estudiante3'] )
                                            { $servidor=$this->configuracion['host_redireccion_estudiante3'];
                                              $conexionEst=$this->acceso_Est3;
                                              $controlSesionEst=$this->NumSesionEst3;
                                            }
                                   }    
                                 if($servidor=='N/A' && $controlSesionEst=='' && $redirecciona=='S')
                                    {     $variable='msgIndex=112';	
                                          $variable.='&'.$this->varNombres['acceso'].'='.$this->veces;	
                                          $variable=$this->cripto->codificar_url($variable,$this->varIndex);
                                          $this->direccionar($this->redirLogueo,$variable);
                                          exit;
                                    }
                                 elseif($redirecciona=='N')
                                    {    $servidor=$this->configuracion['host'];
                                         $conexionEst=$this->acceso_Est; 
                                         $controlSesionEst=$this->NumSesionEst;
                                    }    
                               
                               //AUMENTA EL CONTORL DE LAS SESIONES         
                               $visitas++;
                               $fd = fopen($this->CarpetaSesion.$controlSesionEst, "w");
                               fwrite($fd, $visitas);
                               fclose($fd);
                               unset($visitas);
                               
                               if($this->configuracion['activar_redireccion_estudiante']=='S' || $this->configuracion['activar_redireccion_estudiante2']=='S' || $this->configuracion['activar_redireccion_estudiante3']=='S')
                                   {/*registra la sesion en la db*/
                                    $this->registrar_sesion($conexionEst,$nva_sesion,$aplicacion,'estudiante');
                                    /*redirecciona al servidor de atencion a funcionarios*/
                                    //$variable="user=".$this->usser;
                                    $variable=$this->varNombres['usuario'].'='.$this->usser;
                                    //$variable.="&pass=".$this->pwd;
                                    //$variable.="&numero=".$this->numero;
                                    //$variable.="&verificador=".$this->configuracion['verificador'];
                                    $variable.='&'.$this->varNombres['verificador'].'='.$this->configuracion['verificador'];
                                    $variable=  $this->cripto->codificar_url($variable,$this->configuracion);
                                    $this->direccionar($servidor.$this->configuracion['site']."/clase/verifica.class.php",$variable);
                                    exit;
                                   }
                               else
                                   {$this->registrar_sesion($this->acceso_MY,$nva_sesion,$aplicacion,'estudiante');
                                    //echo "se registro";
                                   }
                                   
                               } 
                           unset($visitas);
                       /*fin grabar archivo*/
                      }
                 else
                      {$visitas=$this->leer_archivo($this->NumSesionFun); 
                       $visitas++;
                       $fd=fopen($this->CarpetaSesion.$this->NumSesionFun, "w");
                       fwrite($fd, $visitas);
                       fclose($fd);
                       $_SESSION['contador'] = $visitas;
                       unset($visitas);
                       if($this->configuracion['activar_redireccion_funcionario']=='S')
                               { /*registra la sesion en la db*/
                                $this->registrar_sesion($this->acceso_Fun,$nva_sesion,$aplicacion,'funcionario');
                                /*redirecciona al servidor de atencion a funcionarios*/
                                //$variable="user=".$this->usser;
                                $variable=$this->varNombres['usuario'].'='.$this->usser;
                                //$variable.="&pass=".$this->pwd;
                                //$variable.="&numero=".$this->numero;
                                //$variable.="&verificador=".$this->configuracion['verificador'];
                                $variable.='&'.$this->varNombres['verificador'].'='.$this->configuracion['verificador'];
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
            
            
/**
 * 
 * @param type $usuario
 * @param type $nivel_acceso
 * @return type
 */            
function crear_sesion($usuario,$nivel_acceso)
    	{//Identificador de sesion
                $fecha=explode (" ",microtime());
                $rand=rand();
                $sesion_id=md5($fecha[1].substr($fecha[0],2).$usuario.$rand.$nivel_acceso);
         /*Actualizar la cookie*/
                setcookie("aplicativo",$sesion_id,(time()+$this->configuracion['expiracion']),"/");
                return $sesion_id;
        }//Fin del método crear_sesion

        
function registrar_sesion($conexion,$sesion,$aplicacion,$tipo_ses)
            { $variable['ses']=$sesion;
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
              {   if (!file_exists($this->CarpetaSesion.$doc)) 
                      {$dato = 0;}
                  else
                      {$dato = file_get_contents($this->CarpetaSesion.$doc);}
                   //echo "<br>dato ".$dato;
                   return($dato);
                   exit;
              }//fin funcion leer_archivo    
              
   function expirar_sesiones()
              { //VERIFICA Y REALIZA LA EXPIRACION DE SESIONES DE ESTUDIANTES
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
                //VERIFICA Y REALIZA LA EXPIRACION DE SESIONES DE FUNACIONARIOS
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
             { /*if(isset($this->semaf_id))
                    {//libera el semaforo iniciado en el constructor/
                     $this->liberaSemaforo();
                    }*/
               $dir=$this->configuracion['host'].$this->configuracion['site']."/index.php";
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
			case 68: $this->direccionar('../bienestarInstitucional/bienestar.php','');
			    break;
			case 72: $this->direccionar('../divRecursosHumanos/divRecursosHumanos.php','');
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
			case 105: $this->direccionar('../funcionario_planeacion/funcionario_planeacion.php','');
                            break;
			case 109: $this->direccionar('../asistenteContabilidad/asistenteCont.php','');
			    break;
			case 110: $this->direccionar('../asistenteProyecto/asistente.php','');
			    break;
			case 111: $this->direccionar('../asistenteDecanatura/asistente.php','');
			    break;
			case 112: $this->direccionar('../asistenteSecretaria/asistente.php','');
			    break;
			case 113: $this->direccionar('../secretarioGeneral/secgeneral.php','');
			    break;
			case 114: $this->direccionar('../secretarioProyecto/secretario.php','');
			    break;
			case 115: $this->direccionar('../secretarioDecanatura/secretario.php','');
			    break;
			case 116: $this->direccionar('../secretarioSecretaria/secretario.php','');
			    break;
			case 117: $this->direccionar('../asistenteRelInterinstitucionales/asistenteRelInterinstitucionales.php','');
			    break;
			case 118: $this->direccionar('../laboratorios/laboratorios.php','');
			    break;
			case 119: $this->direccionar('../asistenteILUD/asistenteILUD.php','');
			    break;	
			case 120: $this->direccionar('../consultor/consultor.php','');
			    break;
			case 121: $this->direccionar('../egresado/egresado.php','');
			    break;
			case 122: $this->direccionar('../asistenteTesoreria/asistenteTesoreria.php','');
			    break;	
                        default:
                            break;
                    }  
            }
   
   function cadena_sql($tipo,$variable)
		{  switch($tipo)
		      { case "busca_us":
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "cla_codigo COD, ";
                             $cadena_sql.= "cla_clave PWD, ";
                             $cadena_sql.= "cla_tipo_usu TIP_US, ";
                             $cadena_sql.= "cla_estado EST ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $this->configuracion["sql_tabla1"]." ";
                             $cadena_sql.= "WHERE ";
                             $cadena_sql.= "cla_codigo='".$this->usser."' ";
                             $cadena_sql.= "ORDER BY cla_estado,cla_tipo_usu";
                        break;
                        case "busca_usMY":
                                 $cadena_sql = "SELECT ";
                                 $cadena_sql.= "cla_codigo COD, ";
                                 $cadena_sql.= "cla_clave PWD, ";
                                 $cadena_sql.= "cla_tipo_usu TIP_US, ";
                                 $cadena_sql.= "cla_estado EST, ";
                                 $cadena_sql.= "cla_facultad FAC, ";
                                 $cadena_sql.= "cla_proyecto PROY, ";
                                 $cadena_sql.= "cla_cod_nivel COD_NIVEL, ";
                                 $cadena_sql.= "cla_nivel NIVEL ";
                                 $cadena_sql.= "FROM ";
                                 $cadena_sql.= $this->configuracion["sql_tabla1"]." ";
                                 $cadena_sql.= "WHERE ";
                                 $cadena_sql.= "cla_codigo='".$this->usser."' ";
                                 $cadena_sql.= "ORDER BY cla_estado,cla_tipo_usu";
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
                        case "actualizar_configuracion":
                            $cadena_sql="UPDATE ";
                            $cadena_sql.=$variable['DB'].".".$variable['PREF']."configuracion ";
                            $cadena_sql.="SET ";
                            $cadena_sql.="valor= '".$variable['vl']."' ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.=$variable['PREF']."configuracion.`parametro` ='".$variable['param']."'; ";
                        break; 
                        case "busca_db":
                            $cadena_sql="SELECT "; 
			    $cadena_sql.="`nombre`, ";	
			    $cadena_sql.="`tabla_sesion`, ";						 
                            $cadena_sql.="`prefijo` ";						 
			    $cadena_sql.="FROM "; 
			    $cadena_sql.=$this->configuracion["prefijo"]."bd ";
                        break;
                        case "verFranja":
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="AAC_COD_FRANJA COD_FRANJA, ";
                            $cadena_sql.="AAC_FECHA_INI FEC_INI, ";
                            $cadena_sql.="AAC_FECHA_FIN FEC_FIN ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$this->configuracion["prefijo"]."franjas ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="AAC_CRA_COD=".$variable['proyecto']." ";
                            $cadena_sql.="AND AAC_DEP_COD=".$variable['facultad']." ";
                            $cadena_sql.="AND AAC_ESTADO='A' ";
                            $cadena_sql.="AND '".$variable['fec_actual']."' BETWEEN AAC_FECHA_INI AND AAC_FECHA_FIN ";
                            
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
