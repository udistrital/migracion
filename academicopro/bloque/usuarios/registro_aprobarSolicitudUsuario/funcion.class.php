<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//@ Clase que permite realizar la inscripcion de un espacio academico por busqueda a un estudiante
class funcion_registroaprobarSolicitudUsuario extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $mensaje;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"administrador");
       
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
        $this->accesoGestionClave=$this->conectarDB($configuracion,"logueo");

        //Datos de sesion
        $this->formulario="registro_adicionarSolicitudUsuario";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
        <?

    }

    /**
     * Funcion que valida los datos para aprobar las solicitudes
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistro()
    {
        $mensaje="";
        $adicionado="";
        $idSolicitud=(isset($_REQUEST['idSolicitud'])?$_REQUEST['idSolicitud']:'');
        
            if(is_numeric($idSolicitud) && $idSolicitud){
                $this->solicitud=  $this->consultarDetalleSolicitud($idSolicitud);

                   $datosRegistro=array('solicitante'=>$this->solicitud[0]['SOL_NRO_IDEN_SOLICITANTE'],
                                        'identificacion'=>$this->solicitud[0]['SOL_CODIGO'],
                                        'correo'=>$this->solicitud[0]['CORREO_ELECTRONICO'],
                                        'tipo_documento'=>$this->solicitud[0]['UWD_TIPO_IDEN'],
                                        'tipo_contrato'=>$this->solicitud[0]['SOL_TIPO_VINCULACION'],
                                        'fecha_inicio'=>$this->solicitud[0]['SOL_FECHA_INICIO'],
                                        'fecha_fin'=>(isset($this->solicitud[0]['SOL_FECHA_FIN'])?$this->solicitud[0]['SOL_FECHA_FIN']:''),
                                        'tipo_cuenta'=> $this->solicitud[0]['SOL_USUWEBTIPO'],
                                        'tipo_usuario'=> $this->solicitud[0]['TIPO_USUARIO'],
                                        'estado_usuario'=> 'A',
                                        'secuencia'=>1,
                                        'anexo'=>''
                        );
                //busca si existe registro ya de usuario para rescatar la clave   
                $registro = $this->buscarRegistroUsuario($datosRegistro['identificacion']);
                if(is_array($registro)){
                    $datosRegistro['clave']=$registro[0]['CLA_CLAVE'];
                    
                }else{
                    $datosRegistro['clave']=$this->asignarClave();
                    $datosRegistro['asigna_clave']='ok';
                }
                
                $datosRegistro = $this->asignarDependencia($datosRegistro);
                if(is_array($datosRegistro)){
                    $usuario_existe = $this->buscarRegistroUsuarioYaExiste($datosRegistro[0]);
                    
                    if(is_array($usuario_existe) && $usuario_existe){
                        $adicionado=$this->actualizarUsuario($datosRegistro[0]);
                        $adicionadoMysql=$this->actualizarCuentaUsuarioMysql($datosRegistro[0]);
                        
                    }else{
                        $adicionado=$this->adicionarCuentaUsuario($datosRegistro[0]);
                        $adicionadoMysql=$this->adicionarCuentaUsuarioMysql($datosRegistro[0]);
                    }

                    foreach ($datosRegistro as $key => $registroUsuario) {
                                     
                        $usuarioWeb = $this->buscarRegistroUsuarioWeb($registroUsuario['identificacion'], $registroUsuario['tipo_cuenta'], $registroUsuario['dependencia']);
                                               
                      if(is_array($usuarioWeb)){
                            $actualizadoweb[$key]=$this->actualizarCuentaUsuarioWeb($registroUsuario);
                          
                        }else{
                            $adicionadoweb[$key]=$this->adicionarCuentaUsuarioWeb($registroUsuario);
                        
                        }
                    }
                }
                
                if($adicionado && $adicionadoMysql){
                    $modificado=$this->actualizarEstadoSolicitud($idSolicitud,3);
                    
                    $mensaje='Usuario creado con exito';
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'66',
                                              'descripcion'=>'Registro de usuario '.$datosRegistro[0]['identificacion'],
                                              'registro'=>" id_solicitud->".$idSolicitud.", usuario-> ".$datosRegistro[0]['identificacion'],
                                              'afectado'=>$datosRegistro[0]['identificacion']);

                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                   if((isset($datosRegistro[0]['asigna_clave'])?$datosRegistro[0]['asigna_clave']:'')=='ok'){
                        $variable="pagina=registro_aprobarSolicitudUsuario";
                        $variable.="&opcion=enlace";
                        $variable.="&mail=".$datosRegistro[0]['correo'];
                        $variable.="&usu=".$datosRegistro[0]['identificacion'];
                        $variable.="&tipoUsu=".$datosRegistro[0]['tipo_usuario'];
                   }else{
                        $variable="pagina=admin_consultarSolicitudesUsuario";
                        $variable.="&opcion=consultar";
                   }
                     
                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                   
                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                }else{
                    $mensaje='Error al crear Usuario. '.$this->mensaje;

                    $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'66',
                                              'descripcion'=>'Error al registrar usuario -'.$mensaje,
                                              'registro'=>" id_solicitud->".$idSolicitud.", usuario-> ".$this->solicitud[0]['SOL_CODIGO'],
                                              'afectado'=>$this->solicitud[0]['SOL_CODIGO']);

                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                   $variable="pagina=admin_consultarSolicitudesUsuario";
                   $variable.="&opcion=consultar";
                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                }
            }
    }

      /**
   * Función para consultar el detalle de una solicitud
   * @param int $idSolicitud
   * @return type
   */
  function consultarDetalleSolicitud($idSolicitud) {
      $cadena_sql = $this->sql->cadena_sql("detalle_solicitud_cuenta", $idSolicitud);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Función para buscar el registro de un usuario
   * @param type $codigo
   * @return type
   */
  function buscarRegistroUsuario($codigo) {
      $cadena_sql = $this->sql->cadena_sql("consultar_usuario", $codigo);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
   * Funcion para buscar el registro de la tabla geusuweb
   * @param type $codigo
   * @param type $tipo_cuenta
   * @return type
   */
  function buscarRegistroUsuarioWeb($codigo,$tipo_cuenta,$dependencia) {
      $datos=array('identificacion'=>$codigo,
                    'tipo_cuenta'=>$tipo_cuenta,
                    'dependencia'=>$dependencia
          );
      $cadena_sql = $this->sql->cadena_sql("consultar_usuarioweb", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Función para asignar dependencia o proyecto curricular relacionado al solicitante
   * @param type $datosRegistro
   * @return type
   */
  function asignarDependencia($datosRegistro){
      $registro='';
      if($datosRegistro['tipo_cuenta']==110 || $datosRegistro['tipo_cuenta']==114){
            $dependencias = $this->consultarProyectosCoordinador($datosRegistro['solicitante']);
            if(is_array($dependencias)){
                foreach ($dependencias as $key => $dependencia) {
                    $registro[$key] = $datosRegistro;
                    $registro[$key]['dependencia'] = $dependencia['CRA_COD'];
                    $registro[$key]['nombre_dependencia'] = $dependencia['CRA_NOMBRE'];
                }
            }else{
                $this->mensaje='El solicitante No tiene proyectos relacionados';
            }
      }elseif($datosRegistro['tipo_cuenta']==112 || $datosRegistro['tipo_cuenta']==116){
            $dependencia = $this->consultarFacultadSecretario($datosRegistro['solicitante']);
            if(is_array($dependencia)){
                    $registro[0] = $datosRegistro;
                    $registro[0]['dependencia']=$dependencia[0]['DEP_COD'];
                    $registro[0]['nombre_dependencia'] = $dependencia[0]['DEP_NOMBRE'];
                
            }else{
                $this->mensaje='El solicitante No tiene facultad relacionada';
            }
      }elseif($datosRegistro['tipo_cuenta']==111 || $datosRegistro['tipo_cuenta']==115){
            $dependencia = $this->consultarFacultadDecano($datosRegistro['solicitante']);
            if(is_array($dependencia)){
                    $registro[0] = $datosRegistro;
                    $registro[0]['dependencia']=$dependencia[0]['DEP_COD'];
                    $registro[0]['nombre_dependencia'] = $dependencia[0]['DEP_NOMBRE'];
                
            }else{
                $this->mensaje='El solicitante No tiene facultad relacionada';
            }
      }else{
            $dependencia = $this->consultarDependenciaSolicitante($datosRegistro['solicitante']);
            if(is_array($dependencia)){
                $registro[0]=$datosRegistro;
                $registro[0]['dependencia']=$dependencia[0]['DEP_COD'];
                $registro[0]['nombre_dependencia'] = $dependencia[0]['DEP_NOMBRE'];
                
            }else{
                $this->mensaje='El solicitante No tiene dependencias relacionadas';
            }
            
            
      }
      return $registro;
  }

  /**
   * Función para consultar proyectos curriculares relacionados a un coordinador
   * @param type $identificacion
   * @return type
   */
  function consultarProyectosCoordinador($identificacion) {
      $cadena_sql = $this->sql->cadena_sql("proyectos_coordinador", $identificacion);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
   * Función para consultar las facultades relacionadas a un secretario
   * @param type $identificacion
   * @return type
   */
  function consultarFacultadSecretario($identificacion) {
      $cadena_sql = $this->sql->cadena_sql("facultad_secretario", $identificacion);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  /**
   * Función para consultar las facultades relacionadas a un decano
   * @param type $identificacion
   * @return type
   */
  function consultarFacultadDecano($identificacion) {
      $cadena_sql = $this->sql->cadena_sql("facultad_decano", $identificacion); 
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Función para registrar una cuenta de usuario
   * @param type $datos
   * @return type
   */ 
  function adicionarCuentaUsuario($datos) {

        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_cuenta_usuario",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }
   
    /**
     * Función para registrar el usuario en mysql
     * @param type $datos
     * @return type
     */
    function adicionarCuentaUsuarioMysql($datos) {

        $cadena_sql=$this->sql->cadena_sql("adicionar_cuenta_usuario_mysql",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestionClave, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoGestionClave);

    }
    
   /**
    * Función para registrar relacion de usuario con la cuenta
    * @param type $datos
    * @return type
    */
    function adicionarCuentaUsuarioWeb($datos) {

        $cadena_sql=$this->sql->cadena_sql("adicionar_cuenta_usuarioweb",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }

    /**
     * Función para actualizar el estado de la solicitud
     * @param type $idSolicitud
     * @param type $estado
     * @return type
     */
    function actualizarEstadoSolicitud($idSolicitud,$estado){
        $datos=array('idSolicitud'=>$idSolicitud,
                        'estado'=>$estado);
        $cadena_sql_adicionar=$this->sql->cadena_sql("actualizar_estado_solicitud",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }
    
    /**
     * Función para actualizar la cuenta de un usuario
     * @param type $datos
     * @return type
     */
    function actualizarCuentaUsuarioWeb($datos){
        $cadena_sql=$this->sql->cadena_sql("actualizar_usuarioweb",$datos);

        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");//exit;
        //return $resultado;
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }

    /**
     * Función para asignar Clave
     * @return string
     */
    function asignarClave(){
        $clave='01*23*45*67*89*01';
        return $clave;
    }


    /**
     * Funcion que permite retornar a la pagina especificada
     * Cuando existe mensaje de error, lo presenta
     * @param <string> $pagina
     * @param <string> $variable
     * @param <array> $variablesRegistro (usuario,evento,descripcion,registro,afectado)
     * @param <string> $mensaje
     * Utiliza el metodo enlaceParaRetornar
     */
    function retornar($pagina,$variable,$variablesRegistro,$mensaje=""){
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        if($variablesRegistro){
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que retorna a una pagina 
     * @param <string> $pagina
     * @param <string> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    /**
     * Función para consultar la dependencia de un solicitante
     * @param type $identificacion
     * @return type
     */
    function consultarDependenciaSolicitante($identificacion){
        $cadena_sql = $this->sql->cadena_sql("dependencia_solicitante", $identificacion);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    	//Envia el enlace por correo electrónico para que coloque la clave
    function enviaEnlace()
	{ 
		unset($_REQUEST['action']);
		$indice=$this->configuracion["host"]."/weboffice/index.php?";

		$valor[0]=$_REQUEST['mail'];
		$valor[1]=$_REQUEST['usu'];
		$valor[2]=$_REQUEST['tipoUsu'];
		
		$variable="pagina=adminClaves";
		$variable.="&opcion=cambiarClave";
		$variable.="&mail=".$valor[0];
		$variable.="&usu=".$valor[1];
		$variable.="&tipoUsu=".$valor[2];
		
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		$enlace=$indice.$variable; 

		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/mail/class.phpmailer.php");
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/mail/class.smtp.php");
		
		$mail = new PHPMailer();     

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $crypto=new encriptar();
                $semilla='condor';
                $cadena_clave=$crypto->decodificar_variable($this->configuracion["clave_envios"], $semilla);
                
		//configuracion de cuenta de envio
		$mail->Host     = "mail.udistrital.edu.co";
		$mail->Mailer   = "smtp";
		$mail->SMTPAuth = true;
		$mail->Username = $this->configuracion["correo_envios"];
		$mail->Password = $cadena_clave;
		$mail->Timeout  = 1200;
		$mail->Charset  = "utf-8";
		$mail->IsHTML(false);
		
		//remitente
		$fecha = date("d-M-Y g:i:s A");
		$mail->From     = $this->configuracion["correo_envios"];
		$mail->FromName = 'OFICINA ASESORA DE SISTEMAS';
		$contenido= "Fecha de envio: " . $fecha . "\n";
		$contenido.= "Señor usuario, se ha creado una cuenta del usuario ".$valor[1].", con el perfil ".$valor[2]."  si tiene alguna inquietud con respecto a este correo, por favor comunicarse con la Oficina Asesora de Sistemas. \n";
		$contenido.= "\nPara establecer su contraseña, haga clic en el enlace siguiente (o copie y pegue la URL en su navegador):.\n";
		$contenido.= $enlace;
		$contenido.= "\nEste enlace caduca a las 11:59:59 p.m. del día que fue enviado este correo.\n \n";
		$contenido.= isset($_REQUEST["contenido"])?$_REQUEST["contenido"]:'' . "\n \nEste correo ha sido generado automáticamente. Favor no responder.";
		$mail->Body    = $contenido;
		$mail->Subject = " Establecer contraseña del sistema de Gestión Académica CÓNDOR";
		
		$to_mail1 = $valor[0]; //Correo institucional ; 
		//$to_mail2 = $registro[$i][16];//Correo personal
		//$to_mail3 = 'fmcallejasc@correo.udistrital.edu.co';//Correo 
		$mail->AddAddress($to_mail1);
		//$mail->AddCC($to_mail2);
		//$mail->AddBCC($to_mail3);
		
		if(!$mail->Send())
		{
			?>
			<script language='javascript'>
			alert('Error! El mensaje no pudo ser enviado!');
			</script>
			<?
			$mensaje='';
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=admin_consultarSolicitudesUsuario";
                        $variable.="&opcion=consultar";
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        $this->retornar($pagina,$variable,'',$mensaje);
		}
		else
		{
			
			?>
			<script language='javascript'>
			alert('<?echo 'Fue enviado un enlace al correo electronico, para el cambio de clave!'?>');
			</script>                   
			<?
                        $mensaje='';
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=admin_consultarSolicitudesUsuario";
                        $variable.="&opcion=consultar";
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        $this->retornar($pagina,$variable,'',$mensaje);
		}
		
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

	}
        
    /**
     * Función para buscar si un registro de usuario ya existente
     * @param <array> $datosRegistro
     * @return <array>
     */
    function buscarRegistroUsuarioYaExiste($datosRegistro){
            $cadena_sql = $this->sql->cadena_sql("buscarUsuarioYaExiste", $datosRegistro);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
    }
 
    
    /**
     * Función para actualizar un usuario en Oracle
     * @param <array> $datos
     * @return <array>
     */
    function actualizarUsuario($datos){
        $cadena_sql=$this->sql->cadena_sql("actualizar_usuario",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }
	
    /**
     * Función para actualizar un usuario en Mysql
     * @param type $datos
     * @return int|boolean
     */
    function actualizarCuentaUsuarioMysql($datos){
        $cadena_sql=$this->sql->cadena_sql("actualizar_usuario_mysql",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestionClave, $cadena_sql,"");
        if((isset($resultado))){
            if($this->totalAfectados($this->configuracion, $this->accesoGestionClave)>0){
                return $this->totalAfectados($this->configuracion, $this->accesoGestionClave);
            }else{
                return 1;
            }
        }else{
            return false;
        }
        

    }
	
    
    
}

?>