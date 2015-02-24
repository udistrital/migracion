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

class funcion_registroCancelarInscripcionCreditosEstudiante extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;

//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->validacion= new validarInscripcion();
        $this->procedimiento=new procedimientos();
        $this->tema=$tema;
        $this->sql=$sql;

         //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");

        //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
        //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
        if($configuracion["dbdistribuida"]==1){
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }else{
            $this->accesoMyOracle = $this->accesoOracle;
        }
        //Datos de sesion
        $this->formulario="registro_cancelarInscripcionCreditosEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
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
    
    /*
     * Esta funcion permite realizar la verificacion y confirmacion de cancelar un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                            estado_est,codEspacio,nombreEspacio,creditos,grupo,retorno,opcionRetorno)
     */
    function verificarCancelacionEspacio()
    {
      $mensaje=isset($mensaje)?$mensaje:'';
      $_REQUEST['ano']=$this->ano;
      $_REQUEST['periodo']=$this->periodo;
      //verificar si es estudiante de primer semestre
      $primiparo = $this->validar_primer_semestre($_REQUEST);
      if($primiparo =='ok')
        {
            $mensaje="<font color='red'><b>El Espacio Académico ".$_REQUEST['nombreEspacio']." no se puede cancelar. El estudiante es de primer semestre</b></font>";
        }else{
              //verificar cancelado
              $cancelado=$this->validacion->validarCancelado($_REQUEST);
              if ($cancelado!='ok'&& is_array($cancelado))
                {
                    $mensaje.="El Espacio Acad&eacute;mico que va a cancelar ya ha sido cancelado en este per&iacute;odo.";
                }
              //echo var_dump( $_REQUEST);exit();

              //verificar reprobado
              $reprobado=$this->validacion->validarReprobado($_REQUEST);
              if ($reprobado=='ok')
              { 
                 echo "<script>alert ('El Espacio Académico ".$_REQUEST['nombreEspacio']." no se puede cancelar porque fue reprobado el semestre anterior');</script>";
                  $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                  $variable="pagina=admin_consultarInscripcionCreditosEstudiante";
                  $variable.="&opcion=mostrarConsulta";
                  $variable.="&codProyectoEstudiante=".$_REQUEST['codProyectoEstudiante'];
                  $variable.="&planEstudioEstudiante=".$_REQUEST['planEstudioEstudiante'];
                  $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                  //include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                  //$this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                  echo "<script>location.replace('".$pagina.$variable."')</script>";
                  exit;
              }
              //solicitar confirmacion
              $this->solicitarConfirmacion($mensaje);
              
        }

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
        if (isset($mensaje))
          {
          echo $mensaje."<br><br>";
          }
          else
            {
            }?>
          <b>Recuerde que si cancela un Espacio Acad&eacute;mico <br>&eacute;ste no se puede volver a inscribir en el presente semestre. <br>(Según Reglamento Estudiantil)</br></b>
          <br>
          <br> ¿Est&aacute; seguro que desea cancelar <b><? echo $_REQUEST['nombreEspacio'] ?></b>?<?
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
        $_REQUEST['action']=$this->formulario;
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
                //evalua si existen BD distribuida, para realizar el registro en MySUDD
                if($this->configuracion["dbdistribuida"]==1){
                    $canceladoMyOracle=$this->cancelarEspacioEstudianteMyOracle();
                    $_REQUEST['nvo_inscritos']=$this->consultarCupoInscritos();
                    $actualizaCupo = $this->actualizarCupoMyOracle();
                }

                //inserta registro de evento
                $variablesRegistro=array('usuario'=>$this->usuario,
                          'evento'=>'2',
                          'descripcion'=>'Cancela Espacio académico',
                          'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",".$_REQUEST['grupo'].", 0, ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                          'afectado'=>$_REQUEST['codEstudiante']);
               //busca el registro en MySql
               $existe_espacio_mysql=$this->consultarInscritoMysql();
               if (is_array($existe_espacio_mysql))
                {   //actualiza el registro en MySql con la cancelacion
                    $registros_actualizados = $this->actualizarRegistroEspacioEstudianteMysql();
                }
                else
                {   //inserta el registro en MySql con la cancelacion
                    $registros_insertados = $this->insertarRegistroCancelarEspacioEstudianteMysql();
                }
                $retorno['mensaje']='El Espacio Académico '.$_REQUEST['nombreEspacio'].' ha sido cancelado';

        }
        else
          {
              $retorno['mensaje']='La base de datos se encuentra ocupada. El Espacio Académico '.$_REQUEST['nombreEspacio'].' no ha sido cancelado';
              $variablesRegistro=array('usuario'=>$this->usuario,
                      'evento'=>'50',
                      'descripcion'=>'Conexion Error Oracle cancelacion',
                      'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                      'afectado'=>$_REQUEST['codEstudiante']);
          }
            //inserta en log de eventos
          $this->procedimiento->registrarEvento($variablesRegistro);
          $this->cancelar($retorno['mensaje']);
    }
    

    function consultarCarreraGrupo() {
        $cadena_sql=$this->sql->cadena_sql("consultar_carrera_grupo", $_REQUEST);//echo $cadena_sql;exit;
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['CARRERA'];
    }
    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean>
     */
    function cancelarEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $_REQUEST);//echo "<br>cadena cancelacion ".$cadena_sql;exit;
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

 /**
     * Funcion que consulta los inscritos
     * @return <int>
     */

    function consultarCupoInscritos() {
        $cadena_sql=$this->sql->cadena_sql("consultar_cupo_inscritos", $_REQUEST);//echo $cadena_sql;exit;
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['INSCRITOS'];
    }

    /**
     * Funcion que actualiza el cupo en un grupo
     * @return <boolean>
     */
    function actualizarCupoMyOracle() {
        $cadena_sql=$this->sql->cadena_sql("actualiza_cupo", $_REQUEST);//echo "<br>cadena actualiza cupo ".$cadena_sql;//exit;
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
    }


    /**
     * Funcion que cancela espacio en MyOracle
     * @return <boolean>
     */
    function cancelarEspacioEstudianteMyOracle() {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $_REQUEST);//echo "<br>cadena cancelacion ".$cadena_sql;//exit;
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
    }

    /**
     * Funcion que consulta si el espacio esta inscrito en Mysql
     * @return <array>
     */
    function consultarInscritoMysql() {
        $cadena_sql=$this->sql->cadena_sql("buscar_espacio_mysql", $_REQUEST);//echo "<br>cadena consulta cupo ".$cadena_sql;
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
        $variable.="&codProyectoEstudiante=".$_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=".$_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
        $variable.="&codEspacio=".$_REQUEST["codEspacio"];
        $variable.="&grupo=".$_REQUEST['grupo'];
        
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
     function validar_primer_semestre($datosInscripcion)
    {

        if ($datosInscripcion['periodo']==3)
        {
          $inicioCodigoEstudiante=$datosInscripcion['ano'].'2';
        }else
        {
          $inicioCodigoEstudiante=$datosInscripcion['ano'].$datosInscripcion['periodo'];
        }
        if ($inicioCodigoEstudiante==substr($datosInscripcion['codEstudiante'], '0', '5'))
        {
            $primiparo = 'ok';
        }
        else{
            $primiparo = 'no';
        }
        return $primiparo;
    }//fin funcion validarEstudiantePrimerSemestre



}


?>

