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
class funcion_registroAdicionarSolicitudUsuario extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;

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

        //Datos de sesion
        $this->formulario="registro_adicionarSolicitudUsuario";
        $this->bloque="usuarios/registro_adicionarSolicitudUsuario";
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
     * Funcion que valida los datos para inscribir el registro en la tabla de solicitudes
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistro()
    {
        $mensaje="";
        $adicionado='';
        $valida_fecha='';
        $variablesRegistro='';
        $solicitante=(isset($_REQUEST['solicitante'])?$_REQUEST['solicitante']:'');
        $solicitante=  substr($solicitante,0,11);
        $identificacion=(isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'');
        $identificacion=  substr($identificacion,0,11);
        $nombre=strtoupper(isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'');
        $nombre=  substr($nombre,0,100);
        $apellido=strtoupper(isset($_REQUEST['apellido'])?$_REQUEST['apellido']:'');
        $apellido=  substr($apellido,0,100);
        $tipo_documento=(isset($_REQUEST['tipo_documento'])?$_REQUEST['tipo_documento']:'');
        $correo=strtoupper(isset($_REQUEST['correo'])?$_REQUEST['correo']:'');
        $correo=  substr($correo,0,250);
        $telefono=(isset($_REQUEST['telefono'])?$_REQUEST['telefono']:'');
        $telefono=  substr($telefono,0,15);
        $celular=(isset($_REQUEST['celular'])?$_REQUEST['celular']:'');
        $celular=  substr($celular,0,15);
        $direccion=strtoupper(isset($_REQUEST['direccion'])?$_REQUEST['direccion']:'');
        $direccion=  substr($direccion,0,250);
        $tipo_contrato=(isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:'');
        $fecha_inicio=(isset($_REQUEST['fecha_inicio'])?$_REQUEST['fecha_inicio']:'');
        $fecha_fin=(isset($_REQUEST['fecha_fin'])?$_REQUEST['fecha_fin']:'');
        $tipo_cuenta=(isset($_REQUEST['tipo_cuenta'])?$_REQUEST['tipo_cuenta']:'');
        $confirmadoActualizaPerfil=(isset($_REQUEST['confirmadoActualizaPerfil'])?$_REQUEST['confirmadoActualizaPerfil']:'');
        
        if($solicitante && $identificacion && $nombre && $apellido && $tipo_documento && $correo && $fecha_inicio && $tipo_cuenta){
            if(is_numeric($solicitante) && is_numeric($identificacion) && is_numeric($tipo_documento)){
                if($tipo_contrato==9){
                    if($fecha_fin){
                        $valida_fecha=$this->validarFechas($fecha_inicio,$fecha_fin);
                    }else{
                        $valida_fecha="Los contratistas deben especificar fecha final del contrato";

                    }
                }else{
                    $valida_fecha=$this->validarFechasIguales($fecha_inicio,$fecha_fin);
                    $fecha_fin='';
                }
                $valida_solicitante=$this->validarSolicitante($solicitante,$tipo_cuenta);
                if($valida_fecha=='ok' && $valida_solicitante=='ok'){
                        $datosRegistro=array('solicitante'=>$solicitante,
                                                'fecha'=>date('d/m/Y'),
                                                'identificacion'=>$identificacion,
                                                'nombre'=>$nombre,
                                                'apellido'=>$apellido,
                                                'tipo_documento'=>$tipo_documento,
                                                'correo'=>$correo,
                                                'telefono'=>$telefono,
                                                'celular'=>$celular,
                                                'direccion'=>$direccion,
                                                'tipo_contrato'=>$tipo_contrato,
                                                'fecha_inicio'=>$fecha_inicio,
                                                'fecha_fin'=>$fecha_fin,
                                                'tipo_cuenta'=>$tipo_cuenta,
                                                'anexo'=>''
                                );
                        $registroPerfil = $this->buscarRegistroUsuarioConPerfilYaExiste($datosRegistro);
                        if(is_array($registroPerfil) && !$confirmadoActualizaPerfil){
                            $this->solicitarConfirmacion('existe_perfil');
                            exit;
                        }   
                        $registro = $this->buscarRegistroSolicitudYaExiste($datosRegistro);
                        if(is_array($registro)){
                            //solicitar confirmacion
                            $this->solicitarConfirmacion('existe_solicitud');
                            exit;
                        }else{
                            $registro_datos_usuario = $this->buscarRegistroDatosUsuario($datosRegistro['identificacion']);
                            if(!is_array($registro_datos_usuario)){
                                $this->adicionarDatosUsuario($datosRegistro);
                            }else{
                                $usuario_actualizado=$this->actualizarDatosBasicosUsuario($datosRegistro);
                            }
                            $adicionado= $this->adicionarSolicitudCuenta($datosRegistro);
                        }
                }else{
                    if($valida_fecha=='ok'){
                        $mensaje=$valida_solicitante;

                    }else{
                        $mensaje=$valida_fecha;
                    }
                }
                //verificamos que se halla realizado la insercion
                if($adicionado){
                        $mensaje="Solicitud registrada con exito, No.".$adicionado;

                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'65',
                                                          'descripcion'=>'Registro solicitud usuario-'.$adicionado,
                                                          'registro'=>"idSolicitud-> ".$adicionado.", usuario->".$datosRegistro['identificacion'].", solicitante->".$datosRegistro['solicitante'],
                                                          'afectado'=>$adicionado);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarSolicitudesUsuario";
                       $variable.="&opcion=consultar";
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                }else{
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarSolicitudesUsuario";
                       $variable.="&opcion=nuevo";
                       foreach ($_REQUEST as $key => $value) {
                           if($key!='opcion' && $key!='pagina'){
                                $variable.="&".$key."=".$value;
                           }

                       }
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                       
                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                }
               
            }
        }else{
            $mensaje="Por favor ingrese completamente los campos requeridos";
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'65',
                                              'descripcion'=>'Error al registrar solicitud de usuario -'.$mensaje,
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_consultarSolicitudesUsuario";
           $variable.="&opcion=nuevo";
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
        }
        
        
        
    }

    /**
     * Función para adicionar los datos basicos de un usuario web
     * @param type $datos
     * @return type
     */
    function adicionarDatosUsuario($datos) {
            foreach ($datos as $key => $value) {
                if($value=='')
                {
                    $datos[$key]='null';
                }
            }        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_tabla_datos_usuario",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para registrar una nueva solicitud de cuenta
     * @param array $datos
     * @return int
     */
    function adicionarSolicitudCuenta($datos) {
        
        $consecutivo = $this->ultimoIndiceSolicitud();        
        if (!$consecutivo)
            $consecutivo=1;
        else 
            $consecutivo++;
        $datos['identificador']= $consecutivo;
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_tabla_solicitud_cuenta",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        if($this->totalAfectados($this->configuracion, $this->accesoOracle)>=1)
            return $consecutivo;
        else
            return 0;
        
    }

    
     /**
     * Funcion que busca el ultimo identificador registrado de la tabla de solicitudes 
     * @param 
     *  
     */

    
    public function ultimoIndiceSolicitud() {

        $cadena_sql = $this->sql->cadena_sql("buscarUltimoIndiceTablaSolicitudCuenta", "");
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
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
     * Función para consultar los datos de un usuario
     * @param type $codigo
     * @return type
     */
    function buscarRegistroDatosUsuario($codigo){
         $cadena_sql = $this->sql->cadena_sql("buscarDatosUsuario", $codigo);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
 
    /**
     * Función para buscar si ya existe una solicitud anterior
     * @param type $datosRegistro
     * @return type
     */
    function buscarRegistroSolicitudYaExiste($datosRegistro){
         $cadena_sql = $this->sql->cadena_sql("buscarSolicitudYaExiste", $datosRegistro);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    
    /**
     * Función para buscar si ya existe un usuario con un perfil especifico
     * @param type $datosRegistro
     * @return type
     */
    function buscarRegistroUsuarioConPerfilYaExiste($datosRegistro){
         $cadena_sql = $this->sql->cadena_sql("buscarUsuarioConPerfilYaExiste", $datosRegistro);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
 

    /**
     * Funcion que genera el mensaje de solicitud de confirmacion
     *
     */
    function solicitarConfirmacion($tipo) {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes($tipo);
          $this->formularioConfirmacion($tipo);
        ?>
        </table>
        <?
    }

      /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de cancelacion
     *
     */
    function celdaMensajes($tipo) {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion($tipo);
              ?></td>
          </tr>
      <?
    }

      /**
     * Funcion que genera el formulario para confirmar o cancelar la cancelacion
     */
    function formularioConfirmacion($tipo) {
        if($tipo=='existe_solicitud'){
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $opciones=$this->variablesCancelar();
                    $enlace=$this->encriptarEnlace($opciones,"confirmarCreacion");
                    $array=array('tipo'=>'image','nombre'=>'aceptar','icono'=>'clean.png','texto'=>'Si','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $enlace=$this->encriptarEnlace($opciones,"cancelarConfirmacion");
                    $array=array('tipo'=>'image','nombre'=>'cancelar','icono'=>'x.png','texto'=>'No','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
            </tr>
          </form>
      <?
        }elseif($tipo=='existe_perfil'){
        ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $opciones=$this->variablesCancelar();
                    $enlace=$this->encriptarEnlace($opciones,"confirmarActualizacion");
                    $array=array('tipo'=>'image','nombre'=>'aceptar','icono'=>'clean.png','texto'=>'Si','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $enlace=$this->encriptarEnlace($opciones,"cancelarActualizacion");
                    $array=array('tipo'=>'image','nombre'=>'cancelar','icono'=>'x.png','texto'=>'No','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
            </tr>
          </form>
      <?
        }
    }


    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     *
     */
    function mensajesConfirmacion($tipo) {
        if($tipo=='existe_solicitud'){
            ?>
            <b>Ya existe una solicitud para ese usuario<br></b>
            <br>
            <br> ¿Desea inactivar la solicitud anterior y continuar con la actual?<?
        }elseif($tipo=='existe_perfil'){
            ?>
            <b>Ya existe el usuario con ese perfil<br></b>
            <br>
            <br> ¿Desea continuar con esta solicitud?, tenga en cuenta que se actualizará el usuario con los nuevos datos<?
        }
    }

     /**
     * Funcion que genera las variables para los enlaces de confirmacion y cancelacion
     */
    function variablesCancelar() {
        unset ($_REQUEST['pagina']);
        $variable="pagina=".$this->formulario;
        $_REQUEST['action']=$this->bloque;
        foreach ($_REQUEST as $key => $value)
        {
            $variable.="&".$key."=".$value;
        }
        return $variable;
    }

     /**
     * Funcion que permite encriptar un enlace
     * @param string $variable
     * @param string $opcion
     * @return string
     */
    function encriptarEnlace($variable,$opcion) {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable.="&opcion=".$opcion;
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        return $pagina.$variable;
    }

      /**
     * Funcion que genera botones de enlace
     * @param <array> $array
     */
    function botonEnlace($array) {
      ?>
        <a href="<?echo $array['opciones']; ?>" >
            <img src="<? echo $this->configuracion["site"].$this->configuracion["grafico"];?>/<?echo $array['icono']?>" border="0" width="30" height="30"><br><?echo $array['texto']?>
        </a>
      <?
    }
    
     /**
     * Funcion que permite retornar al cancelar la confirmacion
     */
    function cancelarConfirmacion() {
        $this->datosRegistro=$_REQUEST;
        $this->enlaceNoCancelar();
    }

    /**
     * Esta funcion se utiliza para retornar cuando se cancela la cancelacion del espacio academico
     * @param <string> $mensaje Mensaje que presenta al no poder realizar la cancelacion
     */
    function enlaceNoCancelar($mensaje='')
    {
        if ($mensaje!='')
        {
            echo "<script>alert ('$mensaje');</script>";
        }
        else
            {

            }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarSolicitudesUsuario";
        $variable.="&opcion=nuevo";
        foreach ($_REQUEST as $key => $value)
        {
            if($key!='opcion' && $key!='action' && $key!='pagina'){
                $variable.="&".$key."=".$value;
            }
        }

        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
    
    /**
     * Función para inactivar una solicitud anterior y crear la nueva
     */
    function inactivarSolicitudAnterior(){
        $solicitante=(isset($_REQUEST['solicitante'])?$_REQUEST['solicitante']:'');
        $identificacion=(isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'');
        $nombre=strtoupper(isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'');
        $apellido=strtoupper(isset($_REQUEST['apellido'])?$_REQUEST['apellido']:'');
        $tipo_documento=(isset($_REQUEST['tipo_documento'])?$_REQUEST['tipo_documento']:'');
        $correo=strtoupper(isset($_REQUEST['correo'])?$_REQUEST['correo']:'');
        $telefono=(isset($_REQUEST['telefono'])?$_REQUEST['telefono']:'');
        $celular=(isset($_REQUEST['celular'])?$_REQUEST['celular']:'');
        $direccion=strtoupper(isset($_REQUEST['direccion'])?$_REQUEST['direccion']:'');
        $tipo_contrato=(isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:'');
        $fecha_inicio=(isset($_REQUEST['fecha_inicio'])?$_REQUEST['fecha_inicio']:'');
        $fecha_fin=(isset($_REQUEST['fecha_fin'])?$_REQUEST['fecha_fin']:'');
        $tipo_cuenta=(isset($_REQUEST['tipo_cuenta'])?$_REQUEST['tipo_cuenta']:'');
        
        if($solicitante && $identificacion && $nombre && $apellido && $tipo_documento && $correo){
            if(is_numeric($solicitante) && is_numeric($identificacion) && is_numeric($tipo_documento)){
           
                $datosRegistro=array('solicitante'=>$solicitante,
                                        'fecha'=>date('d/m/Y'),
                                        'identificacion'=>$identificacion,
                                        'nombre'=>$nombre,
                                        'apellido'=>$apellido,
                                        'tipo_documento'=>$tipo_documento,
                                        'correo'=>$correo,
                                        'telefono'=>$telefono,
                                        'celular'=>$celular,
                                        'direccion'=>$direccion,
                                        'tipo_contrato'=>$tipo_contrato,
                                        'fecha_inicio'=>$fecha_inicio,
                                        'fecha_fin'=>$fecha_fin,
                                        'tipo_cuenta'=>$tipo_cuenta,
                                        'anexo'=>''
                        );
                $registros = $this->buscarRegistroSolicitudYaExiste($datosRegistro);
                if(count($registros)>0){
                    foreach ($registros as $registro) {
                        $modificada = $this->inactivarSolicitud($registro['SOL_ID']);
                    }
                }
                if($modificada){
                    $usuario_actualizado=$this->actualizarDatosBasicosUsuario($datosRegistro);
                    $adicionado= $this->adicionarSolicitudCuenta($datosRegistro);
                    if($adicionado){
                        $mensaje="Solicitud registrada con exito, No.".$adicionado;

                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'65',
                                                          'descripcion'=>'Registro solicitud usuario-'.$adicionado,
                                                          'registro'=>"idSolicitud-> ".$adicionado.", usuario->".$datosRegistro['identificacion'].", solicitante->".$datosRegistro['solicitante'],
                                                          'afectado'=>$adicionado);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarSolicitudesUsuario";
                       $variable.="&opcion=consultar";
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                    }
                }
            }
        }else{
            $mensaje="Por favor ingrese completamente los campos requeridos";
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'65',
                                              'descripcion'=>'Error al registrar solicitud de usuario -'.$mensaje,
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_consultarSolicitudesUsuario";
           $variable.="&opcion=nuevo";
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
        }
    }
    
    /**
     * Función para inactivar una solicitud
     * @param int $idSolicitud
     * @return int
     */  
    function inactivarSolicitud($idSolicitud) {
        
        $cadena_sql=$this->sql->cadena_sql("inactivar_solicitud",$idSolicitud);//cho "<br>".$cadena_sql;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para validar fechas
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return string
     */
    function validarFechas($fecha_inicio,$fecha_fin){
        $valida='';
        $fechasIguales=  $this->validarFechasIguales($fecha_inicio,$fecha_fin);
        $fecha_uno = str_replace('/', '-', $fecha_inicio);
        $fecha_uno = strtotime($fecha_uno);
        $fecha_dos = str_replace('/', '-', $fecha_fin);
        $fecha_dos = strtotime($fecha_dos);
        if($fecha_uno>$fecha_dos || $fechasIguales!='ok'){
            $valida="Fechas de contrato no validas";

        }else{
            $valida='ok';
        }
             
        return $valida;
    }
    
    
    /**
     * Función para validar que las fechas no sean iguales
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return string
     */
    function validarFechasIguales($fecha_inicio,$fecha_fin){
        $valida='';
        if($fecha_inicio==$fecha_fin){
            $valida="Fechas de contrato no validas";

        }else{
            $valida='ok';
        }
             
        return $valida;
    }
    
    /**
     * Función para validar que exista el solicitante
     * @param type $solicitante
     * @param type $tipo_solicitud
     * @return string
     */
    function validarSolicitante($solicitante,$tipo_solicitud){
        $band=0;
        $valida='';
        if($tipo_solicitud==110 || $tipo_solicitud==114){
              $dependencias = $this->consultarProyectosCoordinador($solicitante);
              if(is_array($dependencias)){
                  foreach ($dependencias as $key => $dependencia) {
                      if($dependencia['CRA_COD']>0){
                          $band=1;
                      }
                  }
                  if ($band==1){
                        $valida='ok';
                  }else{
                        $valida='El solicitante No tiene proyectos relacionados como Coordinador';
                    }
              }else{
                  $valida='El solicitante No tiene proyectos relacionados como Coordinador';
              }
        }elseif($tipo_solicitud==112 || $tipo_solicitud==116){
              $dependencias = $this->consultarFacultadSecretario($solicitante);
              if(is_array($dependencias)){
                  foreach ($dependencias as $key => $dependencia) {
                      if($dependencia['DEP_COD']>0){
                          $band=1;
                      }
                  }
                  if ($band==1){
                        $valida='ok';
                  }else{
                        $valida='El solicitante No tiene facultad relacionada como secretario académico';
                    }
              }else{
                  $valida='El solicitante No tiene facultad relacionada como secretario académico';
              }
        }elseif($tipo_solicitud==111 || $tipo_solicitud==115){
              $dependencias = $this->consultarFacultadDecano($solicitante);
              if(is_array($dependencias)){
                  foreach ($dependencias as $key => $dependencia) {
                      if($dependencia['DEP_COD']>0){
                          $band=1;
                      }
                  }
                  if ($band==1){
                        $valida='ok';
                  }else{
                        $valida='El solicitante No tiene facultad relacionada como decano';
                    }
              }else{
                  $valida='El solicitante No tiene facultad relacionada como decano';
              }
        }else{
              $dependencia = $this->consultarDependenciaSolicitante($solicitante);
              if(is_array($dependencia)){
                  if($dependencia[0]['DEP_COD']>0){
                          $band=1;
                      }
                  if ($band==1){
                        $valida='ok';
                  }else{
                        $valida='El solicitante No tiene proyectos relacionados';
                    }
              }else{
                  $valida='El solicitante No tiene dependencias relacionadas';
              }


        }
        return $valida;
    }
    
    /**
     * Funcion para consultar proyectos relacionados a un coordinador
     * @param int $identificacion
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
     * Función para consultar la dependencia de un solicitante
     * @param int $identificacion
     * @return type
     */
    function consultarDependenciaSolicitante($identificacion){
        $cadena_sql = $this->sql->cadena_sql("dependencia_solicitante", $identificacion);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
  
  /**
   * Función que actualiza los datos básicos del usuario si ya se encuentra registrado en geusuwebdatos
   * @param type $registro
   * @return type
   */
    function actualizarDatosBasicosUsuario($registro) {
            foreach ($registro as $key => $value) {
                if($value=='')
                {
                    $registro[$key]='null';
                }
            }
        $cadena_sql=$this->sql->cadena_sql("actualizarDatosBasicosUsuario",$registro);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para inactivar una solicitud
     */
    function inactivarRegistroSolicitud(){
        $idSolicitud=(isset($_REQUEST['idSolicitud'])?$_REQUEST['idSolicitud']:'');
        
        if(is_numeric($idSolicitud)){
            $modificada = $this->inactivarSolicitud($idSolicitud);
        }
               
        if($modificada){
            $mensaje="Solicitud Inactivada con exito, No.".$idSolicitud;
            $variablesRegistro=array('usuario'=>$this->usuario,
                                                  'evento'=>'65',
                                                  'descripcion'=>'Inactivar solicitud usuario-'.$idSolicitud,
                                                  'registro'=>"idSolicitud-> ".$idSolicitud,
                                                  'afectado'=>$idSolicitud);
        }else{
            $mensaje="Error al Inactivar la solicitud No.".$idSolicitud;
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'65',
                                              'descripcion'=>'Error al inactivar la solicitud de usuario -'.$mensaje,
                                               'registro'=>"idSolicitud-> ".$idSolicitud,
                                              'afectado'=>$idSolicitud);
           
           
        }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarSolicitudesUsuario";
        $variable.="&opcion=consultar";
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
    }
}

?>