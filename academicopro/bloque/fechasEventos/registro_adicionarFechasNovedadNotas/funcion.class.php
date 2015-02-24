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
class funcion_registroAdicionarFechasNovedadNotas extends funcionGeneral {
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

        
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_adicionarFechasNovedadNotas";
        $this->bloque="fechasEventos/registro_adicionarFechasNovedadNotas";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($configuracion, "asesvice");
        
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
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
     * Funcion que valida los datos para inscribir el registro en la tabla de calendario
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistro()
    {        
        $mensaje="";
        $adicionado='';
        $valida_fecha='';
        $variablesRegistro='';
        $nivel='';
        $codFacultad=(isset($_REQUEST['codFacultad'])?$_REQUEST['codFacultad']:'');
        $codProyecto=(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        $pregrado=(isset($_REQUEST['pregrado'])?$_REQUEST['pregrado']:'');
        $posgrado=(isset($_REQUEST['posgrado'])?$_REQUEST['posgrado']:'');
        $doctorado=(isset($_REQUEST['doctorado'])?$_REQUEST['doctorado']:'');
        $maestria=(isset($_REQUEST['maestria'])?$_REQUEST['maestria']:'');
        $extension=(isset($_REQUEST['extension'])?$_REQUEST['extension']:'');
        $fechahora_inicio=(isset($_REQUEST['fechahora_inicio'])?$_REQUEST['fechahora_inicio']:'');
        $fechahora_fin=(isset($_REQUEST['fechahora_fin'])?$_REQUEST['fechahora_fin']:'');
        $codEvento=89;
        
        if($pregrado==1){
            if(!$nivel){
                $nivel=$pregrado;
            }else{
                $nivel.=",".$pregrado;
            }
        }
        if($posgrado==2){
            if(!$nivel){
                $nivel=$posgrado;
            }else{
                $nivel.=",".$posgrado;
            }
        }
        if($maestria==3){
            if(!$nivel){
                $nivel=$maestria;
            }else{
                $nivel.=",".$maestria;
            }
        }
        if($doctorado==4){
            if(!$nivel){
                $nivel=$doctorado;
            }else{
                $nivel.=",".$doctorado;
            }
        }
        if($extension==5){
            if(!$nivel){
                $nivel=$extension;
            }else{
                $nivel.=",".$extension;
            }
        }
        
        if($codProyecto && $codEvento && $fechahora_inicio && $fechahora_fin && $nivel){
            if(is_numeric($codFacultad) && is_numeric($codProyecto) && is_numeric($codEvento)){
                
                if($codProyecto!=999){
                        
                         if($this->nivel==61 ){
                                    $datosProyecto = $this->consultarDatosProyecto($codProyecto,$nivel);
                                    $valida_fecha=$this->validarFechas($fechahora_inicio,$fechahora_fin);

                                    if($valida_fecha=='ok' && $datosProyecto){

                                            $datosRegistro=array('anio'=>$this->ano,
                                                                    'periodo'=>  $this->periodo,
                                                                    'codProyecto'=>$codProyecto,
                                                                    'codEvento'=>$codEvento,
                                                                    'fechahora_inicio'=>$fechahora_inicio,
                                                                    'fechahora_fin'=>$fechahora_fin,
                                                                    'tipoProyecto'=>$datosProyecto[0]['CRA_TIP_CRA'],
                                                                    'codFacultad'=>$datosProyecto[0]['CRA_DEP_COD'],
                                                                    'estado'=>'A',
                                                                    'habilitar'=>'N'
                                                    );
                                            $existe_evento = $this->consultarExisteEventoProyecto($datosRegistro);
                                            if(!$existe_evento){
                                                $existe_inactivo = $this->consultarExisteEventoInactivoProyecto($datosRegistro);
                                                if($existe_inactivo){
                                                    $adicionado= $this->actualizarFechasCalendario($datosRegistro);//exit;
                                                }else{
                                                    $adicionado= $this->adicionarFechasCalendario($datosRegistro);
                                                }
                                            }else{
                                                $mensaje="Ya se encuentra registrado el evento para el proyecto curricular, en el período académico activo.";
                                            }

                                    }else{
                                        if(!$datosProyecto){
                                            $mensaje='Proyecto curricular no encontrado en el nivel seleccionado';
                                        }else{
                                            $mensaje=$valida_fecha;
                                        }
                                    }
                                    //verificamos que se halla realizado la insercion
                                    if($adicionado){
                                            $mensaje="Fechas registradas con exito";

                                            $variablesRegistro=array('usuario'=>$this->usuario,
                                                                              'evento'=>'85',
                                                                              'descripcion'=>'Registro fecha Novedad nota-'.$codProyecto,
                                                                              'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                                              'afectado'=>$codProyecto);

                                           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                           $variable="pagina=admin_consultarFechasNovedadNotas";
                                           $variable.="&opcion=consultar";
                                           $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                    }else{
                                           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                           $variable="pagina=admin_consultarFechasNovedadNotas";
                                           $variable.="&opcion=nuevo";
                                           foreach ($_REQUEST as $key => $value) {
                                               if($key!='opcion' && $key!='pagina'){
                                                    $variable.="&".$key."=".$value;
                                               }

                                           }
                                           $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                                    }
                         }else{
                                ?>
                                    <table class="contenidotabla centrar">
                                      <tr>
                                        <td class="cuadro_brownOscuro centrar">
                                            <?echo "El perfil no tiene permisos para este modulo";?>
                                        </td>
                                      </tr>
                                    </table>
                                <?                                    
                                $this->mostrarEnlaceRetorno();
                                exit;

                         }
                       }elseif($codProyecto==999){
                           
                            $valida_fecha=$this->validarFechas($fechahora_inicio,$fechahora_fin);
                            if($valida_fecha=='ok' ){
                                    $datos=array(   'codFacultad'=>$codFacultad,
                                                    'codProyecto'=>$codProyecto,
                                                    'nivel'=>$nivel
                                        );
                                    $listado_proyectos = $this->consultarProyectosFacultad($datos,$nivel);
                                    //var_dump($listado_proyectos);exit;
                                    foreach ($listado_proyectos as $key => $proyecto) {
                                        $adicionado='';
                                        $existe_evento='';
                                        $existe_inactivo='';
                                        $datosProyecto = $this->consultarDatosProyecto($proyecto['COD_PROYECTO'],$nivel);
                                        if(is_array($datosProyecto)){
                                            $datosRegistro=array('anio'=>$this->ano,
                                                                    'periodo'=>  $this->periodo,
                                                                    'codProyecto'=>$proyecto['COD_PROYECTO'],
                                                                    'codEvento'=>$codEvento,
                                                                    'fechahora_inicio'=>$fechahora_inicio,
                                                                    'fechahora_fin'=>$fechahora_fin,
                                                                    'tipoProyecto'=>$datosProyecto[0]['CRA_TIP_CRA'],
                                                                    'codFacultad'=>$datosProyecto[0]['CRA_DEP_COD'],
                                                                    'estado'=>'A',
                                                                    'habilitar'=>'N'
                                                    );
                                            $existe_evento = $this->consultarExisteEventoProyecto($datosRegistro);
                                            
                                            if(!$existe_evento){
                                                $existe_inactivo = $this->consultarExisteEventoInactivoProyecto($datosRegistro);
                                                if($existe_inactivo){
                                                    $adicionado= $this->actualizarFechasCalendario($datosRegistro);//exit;
                                                }else{
                                                    $adicionado= $this->adicionarFechasCalendario($datosRegistro);
                                                }
                                                
                                            }else{
                                                $adicionado= $this->actualizarFechasCalendario($datosRegistro);
                                            }
                                            //verificamos que se halla realizado la insercion
                                                if($adicionado){
                                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                          'evento'=>'85',
                                                                                          'descripcion'=>'Registro fecha Novedad nota-'.$proyecto['COD_PROYECTO'],
                                                                                          'registro'=>'codProyecto->'.$proyecto['COD_PROYECTO'].', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                                                          'afectado'=>$proyecto['COD_PROYECTO']);

                                                       $this->procedimientos->registrarEvento($variablesRegistro);
                                                }else{
                                                       $mensaje.="Error al registrar fechas para el proyecto curricular ".$datosProyecto[0]['CRA_COD']."- ".$datosProyecto[0]['CRA_NOMBRE'].", en el período académico activo. ";
                                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                          'evento'=>'85',
                                                                                          'descripcion'=>'Error al registrar fecha Novedad nota-'.$proyecto['COD_PROYECTO'],
                                                                                          'registro'=>'codProyecto->'.$proyecto['COD_PROYECTO'].', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                                                          'afectado'=>$proyecto['COD_PROYECTO']);

                                                       $this->procedimientos->registrarEvento($variablesRegistro);

                                                }
                                        }
                                    }
                                    
                            }else{

                                            $mensaje=$valida_fecha;

                                }
                                    $variablesRegistro='';
                                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                    $variable="pagina=admin_consultarFechasNovedadNotas";
                                    $variable.="&opcion=nuevo";
                                    
                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                    if(!$mensaje){
                                        $mensaje.="Fechas registradas con exito para el período académico activo.";
                                    }
                                    $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                            }
                    }else{
                        if(!is_numeric($codEvento)){$mensaje="El Evento no es valido";}
                        if(!is_numeric($codProyecto)){$mensaje="El Proyecto curricular no es valido";}
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'85',
                                                          'descripcion'=>'Error al registrar fechas de evento -'.$mensaje,
                                                          'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                          'afectado'=>$codProyecto);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarFechasNovedadNotas";
                       $variable.="&opcion=nuevo";
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                    }
            
                
        }else{
            $mensaje="Por favor ingrese completamente los campos requeridos";
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'85',
                                              'descripcion'=>'Error al registrar fechas de evento -'.$mensaje,
                                              'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                              'afectado'=>$codProyecto);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_consultarFechasNovedadNotas";
           $variable.="&opcion=nuevo";
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
        }
    }
    
    /**
     * Función para validar la actualización de unas fechas
     */
    function validarRegistroActualizar()
    {        
        $mensaje="";
        $adicionado='';
        $valida_fecha='';
        $variablesRegistro='';
        $codProyecto=(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        $codEvento=89;
        $fechahora_inicio=(isset($_REQUEST['fechahora_inicio'])?$_REQUEST['fechahora_inicio']:'');
        $fechahora_fin=(isset($_REQUEST['fechahora_fin'])?$_REQUEST['fechahora_fin']:'');
        
        if($codProyecto && $codEvento && $fechahora_inicio && $fechahora_fin){
            if(is_numeric($codProyecto) && is_numeric($codEvento)){
                
                if($this->nivel==61 ){
                 
                        $valida_fecha=$this->validarFechas($fechahora_inicio,$fechahora_fin);

                        if($valida_fecha=='ok' ){

                                $datosRegistro=array('anio'=>$this->ano,
                                                        'periodo'=>  $this->periodo,
                                                        'codProyecto'=>$codProyecto,
                                                        'codEvento'=>$codEvento,
                                                        'fechahora_inicio'=>$fechahora_inicio,
                                                        'fechahora_fin'=>$fechahora_fin

                                        );
                                $adicionado= $this->actualizarFechasCalendario($datosRegistro);//exit;

                        }else{

                                $mensaje=$valida_fecha;

                        }
                        //verificamos que se halla realizado la insercion
                        if($adicionado){
                                $mensaje="Fechas actualizadas con exito";

                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                                  'evento'=>'85',
                                                                  'descripcion'=>'Actualiza fecha Novedad nota-'.$codProyecto,
                                                                  'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                                  'afectado'=>$codProyecto);

                               $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                               $variable="pagina=admin_consultarFechasNovedadNotas";
                               $variable.="&opcion=consultar";
                               $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                               $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                        }else{
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                               $variable="pagina=admin_consultarFechasNovedadNotas";
                               $variable.="&opcion=actualizar";
                               foreach ($_REQUEST as $key => $value) {
                                   if($key!='opcion' && $key!='pagina'){
                                        $variable.="&".$key."=".$value;
                                   }

                               }
                               $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                               $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                        }
                }else{
                        ?>
                            <table class="contenidotabla centrar">
                              <tr>
                                <td class="cuadro_brownOscuro centrar">
                                    <?echo "El perfil no tiene permisos para este modulo";?>
                                </td>
                              </tr>
                            </table>
                        <?                                    
                        $this->mostrarEnlaceRetorno();
                        exit;
            
                 }
            }else{
                if(!is_numeric($codEvento)){$mensaje="El Evento no es valido";}
                if(!is_numeric($codProyecto)){$mensaje="El Proyecto curricular no es valido";}
                $variablesRegistro=array('usuario'=>$this->usuario,
                                                  'evento'=>'85',
                                                  'descripcion'=>'Error al actualizar fechas de evento -'.$mensaje,
                                                  'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                                  'afectado'=>$codProyecto);

               $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
               $variable="pagina=admin_consultarFechasNovedadNotas";
               $variable.="&opcion=actualizar";
               $variable=$this->cripto->codificar_url($variable,$this->configuracion);

               $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
            }
        }else{
            $mensaje="Por favor ingrese completamente los campos requeridos";
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'85',
                                              'descripcion'=>'Error al actualizar fechas de evento -'.$mensaje,
                                              'registro'=>'codProyecto->'.$codProyecto.', codEvento'.$codEvento.', fechahora_inicio->'.$fechahora_inicio.', fechahora_fin'.$fechahora_fin,
                                              'afectado'=>$codProyecto);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_consultarFechasNovedadNotas";
           $variable.="&opcion=actualizar";
           foreach ($_REQUEST as $key => $value) {
                                   if($key!='opcion' && $key!='pagina'){
                                        $variable.="&".$key."=".$value;
                                   }

                               }
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
        }
        
        
        
    }

        
    /**
     * Función para registrar un evento con las respectivas fechas
     * @param array $datos
     * @return int
     */
    function adicionarFechasCalendario($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_fechas_calendario",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para actualizar fechas de un evento
     * @param type $datos
     * @return type
     */
    function actualizarFechasCalendario($datos) {
        
        $cadena_sql=$this->sql->cadena_sql("actualizar_fechas_calendario",$datos); 
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }

    
     /**
     * Funcion que consulta los datos de un proyecto curricular
     * @param 
     *  
     */

    
    public function consultarDatosProyecto($codProyecto,$nivel='') {
        $datos=array('codProyecto'=>$codProyecto,
                        'nivel'=>$nivel);
        $cadena_sql = $this->sql->cadena_sql("datos_proyectos", $datos);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
     * Función para buscar si ya existe un evento activo para una carrera en un período
     * @param type $datosRegistro
     * @return type
     */
    function consultarExisteEventoProyecto($datosRegistro){
         $cadena_sql = $this->sql->cadena_sql("consultarEventoProyecto", $datosRegistro);//echo "<br>cadena ".$cadena_sql;
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     * Función para buscar si ya existe un evento Inactivo para una carrera en un período
     * @param type $datosRegistro
     * @return type
     */
    function consultarExisteEventoInactivoProyecto($datosRegistro){
         $cadena_sql = $this->sql->cadena_sql("consultarEventoInactivoProyecto", $datosRegistro);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    
    

    /**
     * Funcion que genera el mensaje de solicitud de confirmacion
     *
     */
    function solicitarConfirmacion() {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes();
          $this->formularioConfirmacion();
        ?>
        </table>
        <?
    }

      /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de cancelacion
     *
     */
    function celdaMensajes() {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion();
              ?></td>
          </tr>
      <?
    }

      /**
     * Funcion que genera el formulario para confirmar o cancelar la cancelacion
     */
    function formularioConfirmacion() {
        
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $opciones=$this->variablesCancelar();
                    $enlace=$this->encriptarEnlace($opciones,"confirmarInactivacion");
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
        
    }


    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     *
     */
    function mensajesConfirmacion() {
            ?>
            <br>
            <br> ¿Esta seguro de inactivar el registro de las fechas del evento?<?
        
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
     * Esta funcion se utiliza para retornar cuando se cancela la inactivacion
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
        $variable="pagina=admin_consultarFechasNovedadNotas";
        $variable.="&opcion=consultar";
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
     * Función para inactivar fechas
     * @param int $idSolicitud
     * @return int
     */  
    function inactivarFechas($datos) {
        
        $cadena_sql=$this->sql->cadena_sql("inactivar_fechas",$datos);
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
        $valida_cadena1=  $this->validarDatoFecha($fecha_inicio);
        $valida_cadena2=  $this->validarDatoFecha($fecha_fin);
        $valida_fecha1= $this->validarFormatoFecha($fecha_inicio);
        $valida_fecha2= $this->validarFormatoFecha($fecha_fin);
        if($fecha_uno>$fecha_dos || $fechasIguales!='ok' || $valida_cadena1==false || $valida_cadena2==false || $valida_fecha1==false || $valida_fecha2==false){
            $valida="Fechas no validas";

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
            $valida="Fechas de evento no validas";

        }else{
            $valida='ok';
        }
             
        return $valida;
    }
    
     /**
     * Función para validar que el dato de la fecha tenga caracteres validos
     * @param type $cadena
     * @return boolean
     */
    function validarDatoFecha($cadena){
        $permitidos = ":-/1234567890 ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
  
       
    /**
     * Función para validar el formato de una fecha
     * @param type $date
     * @param type $format
     * @return type
     */
    function validarFormatoFecha($date, $format = 'Y-m-d H:i')
   {
       $d = DateTime::createFromFormat($format, $date);
       return $d && $d->format($format) == $date;
   }
  
    
    /**
     * Función para inactivar un registro de fechas
     */
    function inactivarRegistroFechas(){
         if($this->nivel==61 ){
               
                $datos=array('anio'=>(isset($_REQUEST['anio'])?$_REQUEST['anio']:''),
                              'periodo'=>(isset($_REQUEST['periodo'])?$_REQUEST['periodo']:''),
                              'codEvento'=>89,
                              'codProyecto'=>(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:''),
                              'fechaInicial'=>(isset($_REQUEST['fechaInicial'])?$_REQUEST['fechaInicial']:''),
                              'fechaFinal'=>(isset($_REQUEST['fechaFinal'])?$_REQUEST['fechaFinal']:'')
                    );
                if(is_numeric($datos['anio']) && is_numeric($datos['periodo']) && is_numeric($datos['codEvento']) && is_numeric($datos['codProyecto']) ){
                    $modificada = $this->inactivarFechas($datos);
                
                    if($modificada){
                        $mensaje="Fechas Inactivadas con exito.";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                              'evento'=>'85',
                                                              'descripcion'=>'Inactivar fechas de novedad de nota- proyecto '.$datos['codProyecto'],
                                                              'registro'=>"año-> ".$datos['anio'].", periodo->".$datos['periodo'].", codEvento->".$datos['codEvento'].", codProyecto->".$datos['codProyecto'].", fechaInicial->".$datos['fechaInicial'].", fechaFinal->".$datos['fechaFinal'],
                                                              'afectado'=>$datos['codProyecto']);
                    }else{
                        $mensaje="Error al Inactivar las fechas.";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'85',
                                                          'descripcion'=>'Error al inactivar las fechas de novedad de nota -'.$mensaje,
                                                          'registro'=>"año-> ".$datos['anio'].", periodo->".$datos['periodo'].", codEvento->".$datos['codEvento'].", codProyecto->".$datos['codProyecto'].", fechaInicial->".$datos['fechaInicial'].", fechaFinal->".$datos['fechaFinal'],
                                                          'afectado'=>$datos['codProyecto']);


                    }
                }else{
                    if(!is_numeric($datos['anio'])){$mensaje="El año no es valido";}
                    if(!is_numeric($datos['periodo'])){$mensaje="El periodo no es valido";}
                    if(!is_numeric($datos['codEvento'])){$mensaje="El Evento no es valido";}
                    if(!is_numeric($datos['codProyecto'])){$mensaje="El Proyecto curricular no es valido";}
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                      'evento'=>'85',
                                                      'descripcion'=>'Error al inactivar fechas de novedad de nota -'.$mensaje,
                                                      'registro'=>"año-> ".$datos['anio'].", periodo->".$datos['periodo'].", codEvento->".$datos['codEvento'].", codProyecto->".$datos['codProyecto'].", fechaInicial->".$datos['fechaInicial'].", fechaFinal->".$datos['fechaFinal'],
                                                      'afectado'=>$datos['codProyecto']);

                   
                }
         }else{
                        ?>
                            <table class="contenidotabla centrar">
                              <tr>
                                <td class="cuadro_brownOscuro centrar">
                                    <?echo "El perfil no tiene permisos para este modulo";?>
                                </td>
                              </tr>
                            </table>
                        <?                                    
                        $this->mostrarEnlaceRetorno();
                        exit;
            
                 }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarFechasNovedadNotas";
        $variable.="&opcion=consultar";
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
    }
    
    /**
     * Función para validar si un proyecto corresponde a una facultad
     * @param type $codProyecto
     * @param type $codDecano
     * @return string
     */
    function validarProyectoFacultadDecano($codProyecto, $codDecano,$nivel='')
    {
        if(is_numeric($codProyecto) && is_numeric($codDecano))
        {
                $resultado_proyectos=  $this->consultarProyectosFacultadDecano($codDecano,$nivel);
                $tipo=0;
                $total=count($resultado_proyectos);
                for($i=0;$i<$total;$i++)
                {
                    if($codProyecto==$resultado_proyectos[$i]['PROYECTO'])
                    {
                        $tipo=1;
                    }
                }
                if($tipo==1)
                {
                    $mensaje='ok';
                }else{
                    $mensaje="El proyecto curricular no pertenece a la facultad";
                }
        }else
            {
                $mensaje="Valor de código del Proyecto o identificación del Decano no validos";
            }
            return $mensaje;
    }  
    
    /**
     * Función para consultar los proyectos de una facultad
     * @param type $codDecano
     * @return type
     */   
    function consultarProyectosFacultadDecano($codDecano,$nivel=''){
        $datos=array('codDecano'=>$codDecano,
                    'nivel'=>$nivel);
        $cadena_sql = $this->sql->cadena_sql("proyectos_facultad_decano", $codDecano);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     * Función para consultar los proyectos de una facultad
     * @param type $codDecano
     * @return type
     */   
    function consultarProyectosFacultad($datos,$nivel=''){
        
        $cadena_sql = $this->sql->cadena_sql("proyectos_facultad", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     * Función para mostrar el enlace de retorno
     */
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_consultarFechasNovedadNotas";
            $variable2.="&opcion=consultar";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
}

?>