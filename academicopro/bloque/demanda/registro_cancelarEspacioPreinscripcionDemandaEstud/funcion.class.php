<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

class funcion_registro_cancelarEspacioPreinscripcionDemandaEstud extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;
  private $codEstudiante;

//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->validacion= new validarInscripcion();
        $this->procedimiento=new procedimientos();
        //$this->tema=$tema;
        $this->sql=$sql;
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion ORACLE
        if ($this->nivel==4||$this->nivel==28){
          $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
        }
        elseif ($this->nivel==51){
          $this->accesoOracle = $this->conectarDB($configuracion,"estudiante");
          $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
        }
        elseif ($this->nivel==52){
          $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");
          $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
        }

        $this->formulario="registro_cancelarEspacioPreinscripcionDemandaEstud";
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
    }
    
    /*
     * Esta funcion permite realizar la verificacion y confirmacion para cancelar un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                            estado_est,codEspacio,nombreEspacio,creditos,grupo,retorno,opcionRetorno)
     */
    function confirmar()
    {
      $_REQUEST['ano']=$this->ano;
      $_REQUEST['periodo']=$this->periodo;
      //verificar si el espacio ha sido cancelado
      $cancelado=$this->verificarCancelado($_REQUEST);
      if ($cancelado=='ok')
      {
        $retorno['mensaje']="El espacio academico ya ha sido cancelado. No se puede cancelar de nuevo";
        $this->enlaceNoCancelar($retorno);
      }
      $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
      //verificar si el espacio ha sido reprobado
      $reprobado=$this->validacion->validarReprobado($_REQUEST);
      if ($reprobado=='ok')
      {
        $retorno['mensaje']="El espacio academico ha sido reprobado. No se puede cancelar.";
       $this->enlaceNoCancelar($retorno);
      }
      //solicitar confirmacion de la cancelacion
        $this->solicitarConfirmacion('');
    }


    /**
     * Funcion que genera el mensaje de solicitud de confirmacion
     * @param <string> $mensaje 
     */
    function solicitarConfirmacion($mensaje) {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes($mensaje);
          $this->formularioConfirmacion();
        ?>
        </table>
        <?
    }

    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     * @param <string> $mensaje 
     */
    function mensajesConfirmacion($mensaje="") {
        $nombreEstudiante=$this->consultarDatosEstudiante($_REQUEST['codEstudiante']);
        if (isset($mensaje))
          {
          echo $mensaje."<br><br>";
          }
          else
            {
            }?>
          <b>Recuerde que si cancela un Espacio Acad&eacute;mico <br>&eacute;ste no se puede volver a preinscribir para el semestre <?echo $this->ano?>-<?echo $this->periodo?>. </b>
          <br>
          <br> ¿Est&aacute; seguro que desea cancelar <b><? echo $_REQUEST['nombreEspacio'] ?></b><?/** echo $nombreEstudiante[0]['NOMBRE']**/?>?<?
    }

    /**
     * Funcion que permite consultar datos del estudiante
     * @param <int> $codEstudiante
     * @return <array>
     */
    function consultarDatosEstudiante($codEstudiante) {
      $cadena_sql=$this->sql->cadena_sql("consultaEstudiante", $codEstudiante);
      return $registroEstudiante=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de cancelacion
     * @param <string> $mensaje 
     */
    function celdaMensajes($mensaje) {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion($mensaje);
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
                    $opcion='confirmarCancelacion';
                    $cancelar=$this->variablesCancelar($opcion);
                    $array=array('icono'=>'clean.png','texto'=>'Si');
                    $this->botonEnlace($cancelar,$array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $opcion='cancelar';
                    $cancelar=$this->variablesCancelar($opcion);
                    $array=array('icono'=>'x.png','texto'=>'No');
                    $this->botonEnlace($cancelar,$array)
                  ?>
              </td>
            </tr>
          </form>
      <?
    }

    /**
     * Funcion que genera las variables para los enlaces de confirmacion y cancelacion
     */
    function variablesCancelar($opcion) {
        $parametros='';
                  $variables = "pagina=registro_cancelarEspacioPreinscripcionDemandaEstud";
                  $variables.="&opcion=".$opcion;
                  unset ($_REQUEST['opcion']);
                  foreach ($_REQUEST as $key => $value) {
                    $parametros.="&".$key."=".$value;
                  }
                  $variable = $variables. $parametros;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  return $variable;
    }

    /**
     * Funcion que genera botones de enlace
     * @param <array> $array 
     */
    function botonEnlace($variable,$array) {
      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      ?>
        <a href="<?echo $pagina.$variable ?>" >
          <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/".$array['icono']; ?>" border="0" width="25" height="25"><br><?echo $array['texto'];?>
        </a>
      <?
    }

   /*
    * Esta funcion realiza la cancelacion de un espacio academico.
    * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
    *                           codEspacio,grupo,creditos,nombreEspacio,estado_est,periodo,ano,retorno,opcionRetorno)
    */
    function confirmarCancelacion() {

      //verifica si el espacio esta inscrito
        $retorno='';
      $inscrito=$this->verificarPreinscrito($_REQUEST);
      if($inscrito=='ok')
      {
        $canceladoOracle=$this->cancelarEspacioEstudianteOracle();
        //si se puede cancelar en ORACLE busca el registro en MySQL
        if($canceladoOracle==1)
        {
        $datosEstudiante=array('codEstudiante'=>$_REQUEST['codEstudiante'],
                                'codProyectoEstudiante'=>  $_REQUEST['codProyectoEstudiante'],
                                'planEstudioEstudiante'=>  $_REQUEST['planEstudioEstudiante'],
                                'ano'=>  $this->ano,
                                'periodo'=>  $this->periodo);
        $this->actualizarPreinscripcionesSesion($datosEstudiante);
          //inserta registro de evento
          $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'53',
                                  'descripcion'=>'Cancela Preinscripcion',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['codProyectoEstudiante'],
                                  'afectado'=>$_REQUEST['codEstudiante']);
        }
        else
          {
            $retorno['mensaje']='La base de datos se encuentra ocupada. El Espacio Académico '.$_REQUEST['nombreEspacio'].' no ha sido cancelado';
            $variablesRegistro=array('usuario'=>$this->usuario,
                                    'evento'=>'50',
                                    'descripcion'=>'Conexion Error Oracle cancelacion',
                                    'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['codProyectoEstudiante'],
                                    'afectado'=>$_REQUEST['codEstudiante']);
          }
          $this->procedimiento->registrarEvento($variablesRegistro);
          $this->enlaceNoCancelar($retorno);
      }else
        {
          $retorno['mensaje']="El registro ha sido eliminado. No se puede cancelar ".$_REQUEST['nombreEspacio'].".";
          $this->enlaceNoCancelar($retorno);
        }
    }

   /*
    * Esta funcion realiza la cancelacion de un espacio academico.
    * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
    *                           codEspacio,grupo,creditos,nombreEspacio,estado_est,periodo,ano,retorno,opcionRetorno)
    */
    function desbloquearInscripcion() {
      $this->codEstudiante=$_REQUEST['codEstudiante'];
      $_REQUEST['ano']=$this->ano;
      $_REQUEST['periodo']=$this->periodo;
      //verifica si el espacio esta inscrito y cancelado
      $cancelado=$this->verificarCancelado($_REQUEST);
      if($cancelado=='ok')
      {
        $canceladoOracle=$this->desbloquearRegistroInscripcion();
        //si se puede desbloquear en ORACLE busca el registro en MySQL
        if($canceladoOracle==1)
        {
          //inserta registro de evento
          $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'54',
                                  'descripcion'=>'Desbloquea Cancelacion de Preinscripcion',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['codProyectoEstudiante'],
                                  'afectado'=>$_REQUEST['codEstudiante']);
        }
        else
          {
            $retorno['mensaje']='La base de datos se encuentra ocupada. El Espacio Académico '.$_REQUEST['nombreEspacio'].' no ha sido cancelado';
            $variablesRegistro=array('usuario'=>$this->usuario,
                                    'evento'=>'50',
                                    'descripcion'=>'Conexion Error Oracle cancelacion',
                                    'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['codProyectoEstudiante'],
                                    'afectado'=>$_REQUEST['codEstudiante']);
          }
          $this->procedimiento->registrarEvento($variablesRegistro);
          $this->enlaceNoCancelar($retorno);
      }else
        {
          $retorno['mensaje']="El registro ha sido eliminado. No se puede desbloquear ".$_REQUEST['nombreEspacio'].".";
          $this->enlaceNoCancelar($retorno);
        }
    }

    /**
     * Funcion que verifica si un espacio que se solicita cancelar ya esta cancelado
     * @param <type> $datosInscripcion
     */
    function verificarCancelado($datosInscripcion) {
      //verificar cancelado
      $cancelado=$this->validacion->consultarEspaciosPreinscritosCancelados($datosInscripcion);
      if(is_array($cancelado) && $cancelado[0]['ASI_CODIGO']==$datosInscripcion['codEspacio'])
      {
        return 'ok';
      }else{
        return $cancelar['mensaje']='No se encuentra el registro';
      }
    }

    /**
     * Funcion que verifica si un espacio que se solicita cancelar esta preinscrito
     * @param <type> $datosGenerales
     * @return <type> 
     */
    function verificarPreinscrito($datosGenerales) {
      //verifica si el espacio ya ha sido preinscrito
      $preinscrito=$this->validacion->consultarEspaciosPreinscritos($datosGenerales);
      if(is_array($preinscrito) && $preinscrito[0]['ASI_CODIGO']==$datosGenerales['codEspacio'])
      {
        return 'ok';
      }else{
          $preinsCancelado=$this->validacion->consultarEspaciosPreinscritosCancelados($datosGenerales);
          if(is_array($preinsCancelado) && $preinsCancelado[0]['ASI_CODIGO']==$datosGenerales['codEspacio'])
        {
          $retorno['mensaje']="El espacio academico ya ha sido cancelado. No se puede cancelar de nuevo";
          $this->enlaceNoCancelar($retorno);
          }else{
              $retorno['mensaje']="El registro ha sido eliminado. No se puede cancelar.";
              $this->enlaceNoCancelar($retorno);
            }
      }
    }

    /**
     * Esta funcion se utiliza para retornar después de cancelar un espacio o cuando no se realiza la cancelacion.
     * @param <array> $retorno mensaje: Mensaje que presenta al no poder realizar la cancelacion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEspacio,grupo,retorno,opcionRetorno)
     */
    function enlaceNoCancelar($retorno) {
        if (isset($retorno['mensaje'])&&$retorno['mensaje']!='')
        {
          echo "<script>alert ('".$retorno['mensaje']."');</script>";
        }
        else
            {

            }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
        $variable.="&opcion=consultar";
        $variable.="&codEstudiante=".$this->codEstudiante;
        //$variable.=$retorno['parametros'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que realiza el retorno a la pagina $pagina con las opciones $variables.
     * @param <type> $pagina
     * @param <type> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    function consultarCarreraGrupo() {
        $cadena_sql=$this->sql->cadena_sql("consultar_carrera_grupo", $_REQUEST);
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['CARRERA'];
    }
    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean>
     */
    function cancelarEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $_REQUEST);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que actualiza el registro de preinscripciones de la sesion del estudiante
     */
    function actualizarPreinscripcionesSesion($datosEstudiante) {
        $espaciosInscritos=$this->procedimiento->actualizarPreinscritosSesion($datosEstudiante);
    }
    
    
    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean>
     */
    function desbloquearRegistroInscripcion() {
      $cadena_sql=$this->sql->cadena_sql("desbloquear_espacio_oracle", $_REQUEST);
      $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que consulta si el espacio esta inscrito en Mysql
     * @return <array>
     */
    function consultarInscritoMysql() {
      $cadena_sql=$this->sql->cadena_sql("buscar_espacio_mysql", $_REQUEST);
      return $resultado_EspacioMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que actualiza registro con estado de cancelado
     * @return <int>
     */
    function actualizarRegistroEspacioEstudianteMysql() {
      $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_mysql", $_REQUEST);
      $resultado_actualizarRegistroCancelado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

    /**
     * Funcion que inserta registro de cancelacion en Mysql
     * @return <boolean>
     */
    function insertarRegistroCancelarEspacioEstudianteMysql() {
      $cadena_sql=$this->sql->cadena_sql("registrar_cancelar_espacio_mysql", $_REQUEST);
      $resultado_insertarRegistroCambio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

    function cancelar($mensaje='')
    {
        if ($mensaje!='')
        {
            echo "<script>alert ('$mensaje');</script>";
        }
        else
            {

            }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
        $variable.="&opcion=consultar";
        
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
}


?>

