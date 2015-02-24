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

class funcion_registroCancelarInscripcionEstudianteCoorHoras extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;

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

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Datos de sesion
        $this->formulario="registro_cancelarInscripcionEstudianteCoorHoras";
        $this->bloque="inscripcion/registro_cancelarInscripcionEstudianteCoorHoras";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
    }
    
    /*
     * Esta funcion permite realizar la verificacion y confirmacion de cancelar un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                            estado_est,codEspacio,nombreEspacio,creditos,grupo,retorno,opcionRetorno)
     */
    function verificarCancelacionEspacio()
    {
        $mensaje='';
        
      //verificar cancelado
      $cancelado=$this->validacion->validarCancelado($_REQUEST);
      
      if ($cancelado!='ok'&& is_array($cancelado))
        {
            $mensaje="El Espacio Acad&eacute;mico que va a cancelar ya ha sido cancelado".$cancelado['VECES']."en este per&iacute;odo.";
        }
      $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
      //verificar reprobado
      $reprobado=$this->validacion->validarReprobado($_REQUEST);
      if ($reprobado=='ok')
      {
        $mensaje.="<font color='red'><b>El espacio acad&eacute;mico ha sido reprobado por el estudiante.</b></font>";
      }
      //solicitar confirmacion
        $this->solicitarConfirmacion($mensaje);
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
          <b>Recuerde que si cancela un Espacio Acad&eacute;mico <br>&eacute;ste no se puede volver a inscribir en el presente semestre. </b>
          <br>
          <br> ¿Est&aacute; seguro que desea cancelar <b><? echo $_REQUEST['nombreEspacio'] ?></b> a <? echo $nombreEstudiante[0]['NOMBRE']?>?<?
    }

    /**
     * Funcion que permite consultar datos del estudiante
     * @param <int> $codEstudiante
     * @return <array>
     */
    function consultarDatosEstudiante($codEstudiante) {
      $cadena_sql=$this->sql->cadena_sql("consultaEstudiante", $codEstudiante);//echo $cadena_sql;exit;
      return $registroEstudiante=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    /**
     * Funcion que genera la celda con de se colocan mensajes de confirmacion de cancelacion
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
                    $this->variablesCancelar();
                    $array=array('tipo'=>'image','nombre'=>'aceptar','icono'=>'clean.png','texto'=>'Si');
                    $this->botonEnlace($array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $array=array('tipo'=>'image','nombre'=>'cancelar','icono'=>'x.png','texto'=>'No');
                    $this->botonEnlace($array)
                  ?>
              </td>
            </tr>
          </form>
      <?
    }

    /**
     * Funcion que genera las variables para los enlaces de confirmacion y cancelacion
     */
    function variablesCancelar() {
        unset ($_REQUEST['pagina']);
        $_REQUEST['opcion']="cancelarCreditos";
        $_REQUEST['action']=$this->bloque;
        foreach ($_REQUEST as $key => $value)
          {?>
            <input type="hidden" name="<?echo $key?>" value="<?echo $value?>"><?
          }
    }

    /**
     * Funcion que genera botones de enlace
     * @param <array> $array 
     */
    function botonEnlace($array) {
      ?>
          <input type="<?echo $array['tipo']?>" name="<?echo $array['nombre']?>" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/<?echo $array['icono']?>" width="30" height="30"><br><?echo $array['texto']?>
      <?
    }

   /*
    * Esta funcion realiza la cancelacion de un espacio academico.
    * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
    *                           codEspacio,grupo,creditos,nombreEspacio,estado_est,periodo,ano,retorno,opcionRetorno)
    */
    function cancelarCreditos() {
        //verifica si el espacio esta inscrito
        $inscrito=$this->validacion->validarEspacioInscrito($_REQUEST);
        $retorno['mensaje']='';
        if($inscrito=='ok')
        {
          $retorno['mensaje']="El registro ha sido eliminado. No se puede cancelar ".$_REQUEST['nombreEspacio'].".";
          $this->cancelar($retorno['mensaje']);
        }
        $_REQUEST['carrera']=$this->consultarCarreraGrupo();
        $canceladoOracle=$this->cancelarEspacioEstudianteOracle();
        //si se puede cancelar en ORACLE busca el registro en MySQL
        if($canceladoOracle==1)
        {
                $this->procedimiento->actualizarCupo($_REQUEST);
                //inserta registro de evento
                $variablesRegistro=array('usuario'=>$this->usuario,
                          'evento'=>'2',
                          'descripcion'=>'Cancela Espacio académico',
                          'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",".$_REQUEST['id_grupo'].", 0, ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                          'afectado'=>$_REQUEST['codEstudiante']);
        }
        else
          {
              $retorno['mensaje']='La base de datos se encuentra ocupada. El Espacio Académico '.$_REQUEST['nombreEspacio'].' no ha sido cancelado';
              $variablesRegistro=array('usuario'=>$this->usuario,
                      'evento'=>'50',
                      'descripcion'=>'Conexion Error Oracle cancelacion',
                      'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                      'afectado'=>$_REQUEST['codEstudiante']);
          }
          $this->procedimiento->registrarEvento($variablesRegistro);
          $this->cancelar($retorno['mensaje']);
    }
    

    function consultarCarreraGrupo() {
        $cadena_sql=$this->sql->cadena_sql("consultar_carrera_grupo", $_REQUEST);//echo $cadena_sql;exit;
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['CARRERA'];
    }
    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean>
     */
    function cancelarEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $_REQUEST);//echo $cadena_sql;exit;
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

    /**
     * Esta funcion se utiliza para retornar cuando se cancela la cancelacion del espacio academico
     * @param <string> $mensaje Mensaje que presenta al no poder realizar la cancelacion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
    *                           codEspacio,grupo,retorno,opcionRetorno)
     */
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
        $variable="pagina=".$_REQUEST['retorno'];
        $variable.="&opcion=".$_REQUEST['opcionRetorno'];
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
        $variable.="&codProyectoEstudiante=".$_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=".$_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
        $variable.="&codEspacio=".$_REQUEST["codEspacio"];
        $variable.="&id_grupo=".$_REQUEST['id_grupo'];
        $variable.="&grupo=".$_REQUEST['grupo'];
        
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
}


?>

