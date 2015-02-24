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

class funcion_registroInscripcionAgilEstudianteCoorHoras extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;

  function __construct($configuracion, $sql) {
    
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->configuracion=$configuracion;

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;
        $this->validacion=new validarInscripcion();
        $this->procedimientos= new procedimientos();

        

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroInscripcionAgilEstudianteCoorHoras";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        ///Conexion ORACLE
        //
        if($this->nivel==4){
        	$this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        }elseif($this->nivel==110){
        	$this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }
        
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];


    }

    /*
     * Funcion que permite inscribir un espacio a estudiante por inscripcion agil
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                          codEspacio,grupo,carrera)
     */

    function validaciones()
    {
        $retorno=$this->crearVariablesRetorno();
        $continuar=$this->crearVariablesContinuar();
        $retorno['nombreEspacio']=$this->consultarNombreEspacio();
        
        if (trim($_REQUEST['estado_est'])=='B'||trim($_REQUEST['estado_est'])=='J')
        {
          if(isset($_REQUEST['reprobado'])||isset($_REQUEST['confirmaPlan'])||isset($_REQUEST['cancelado']))
          {

          }else
            {
                $this->verificarEspacioReprobado($datosInscripcion);
            }
        }else
          {

          }

        if (isset($_REQUEST['confirmaPlan'])&&$_REQUEST['confirmaPlan']==1)
        {
            $this->registrarEstudiante($_REQUEST);
        }
        else
            {
                if(isset($_REQUEST['cancelado'])&&$_REQUEST['cancelado']==1)
                {
                    $this->verificarEspacioPlan();
                }
                else
                    {
                        $this->verificarInscrito($retorno);
                        $this->verificarCruce($retorno);
                        $this->verificarAprobado($retorno);
                        $this->verificarRequisitos($retorno);
                        $this->verificarSobrecupo($retorno);
                        $this->verificarCancelado();
                        $this->verificarEspacioPlan();
                    }
                    $this->registrarEstudiante($_REQUEST);
            }
    }

    /**
     * Funcion que verifica si el espacio ya ha sido inscrito
     * @param array $retorno
     */
    function verificarInscrito($retorno) {
        $inscrito=$this->validacion->validarEspacioInscrito($_REQUEST);
        if($inscrito!='ok' && is_array($inscrito))
        {
            $carrera=$this->consultarNombreCarrera($inscrito[0]['PROYECTO']);
            $retorno['mensaje']="El espacio académico ya esta inscrito en el grupo ".$inscrito[0]['GRUPO']." de ".$carrera." para el periodo actual. No se puede inscribir de nuevo.";
            $this->noAdicionar($retorno);
        }
    }

    /**
     * Funcion que verifica si hay cruce de horario
     * @param string $retorno 
     */
    function verificarCruce($retorno) {
        $datos=$_REQUEST;
        $datos['codProyecto']=$datos['carrera'];
        $cruce=$this->validacion->validarCruceHorario($datos);
        if($cruce!='ok'&&is_array($cruce))
        {
            $retorno['mensaje']="El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado la inscripción";
            $this->noAdicionar($retorno);
        }
    }

    /**
     * Funcion que verifica si el espacio ya ha sido aprobado
     * @param string $retorno 
     */
    function verificarAprobado($retorno) {
        $aprobado=$this->validacion->validarAprobado($_REQUEST);
        if($aprobado!='ok'&&is_array($aprobado))
        {
            $retorno['mensaje']="El espacio academico ya ha sido aprobado por el estudiante en ".$aprobado[0]['ANO']."-".$aprobado[0]['PERIODO'].". No se ha realizado la inscripción";
            $this->noAdicionar($retorno);
        }
    }

    /**
     * Funcion que verifica si se han aprobado los requisitos
     * @param <type> $retorno 
     */
    function verificarRequisitos($retorno) {
        $requisitos=$this->validacion->validarRequisitos($_REQUEST);
        if($requisitos!='ok'&& is_array($requisitos))
        {
            $retorno['mensaje']="El estudiante no ha aprobado requisitos de ".$retorno['nombreEspacio'].":";
            foreach ($requisitos as $key => $value) {
                $retorno['mensaje'].="\\n".$requisitos[$key]['NOMBRE']." Codigo:".$requisitos[$key]['REQUISITO']."  ";
            }
            $this->noAdicionar($retorno);
        }
    }

    /**
     * Funcion que verifica cupo en el grupo
     * @param string $retorno 
     */
    function verificarSobrecupo($retorno) {
        $sobrecupo=$this->validacion->validarSobrecupo($_REQUEST);
        if($sobrecupo!='ok' && is_array($sobrecupo))
        {
            $retorno['mensaje']="El grupo presenta sobrecupo: Cupo:".$sobrecupo['cupo']." Inscritos:".$sobrecupo['inscritos']." Disponibles:".$sobrecupo['disponibles'].". No se ha realizado la inscripción";
            $this->noAdicionar($retorno);
        }
    }

    /**
     * Funcion que verifica si el espacio ha sido cancelado
     */
    function verificarCancelado() {
        $cancelado=$this->validacion->validarCancelado($_REQUEST);
        if($cancelado!='ok' && is_array($cancelado))
        {
            $_REQUEST['confirmacion']="cancelado";
            $mensaje="El Espacio Acad&eacute;mico ha sido cancelado".$cancelado['VECES']."en el per&iacute;odo actual. ¿Desea inscribirlo nuevamente?";
            $this->solicitarConfirmacion($mensaje,$_REQUEST);
        }
    }

    /**
     * Funcion que verifica si el espacio pertenece al plan de estudios del estudiante
     */
    function verificarEspacioPlan() {
      $planEstudio=$this->validacion->validarEspacioPlan($_REQUEST);
      if($planEstudio!='ok')
      {
          $_REQUEST['confirmacion']="confirmaPlan";
          $mensaje=$planEstudio[0][0];
          $mensaje.=" ¿Desea inscribirlo al estudiante?";
          $this->solicitarConfirmacion($mensaje,$_REQUEST);
      }
    }

    /**
     * Funcion que permite verificar si un estudiante ha reprobado un espacio
     * @param <array> $datosInscripcion
     */
    function verificarEspacioReprobado() {
      $reprobado=$this->validacion->validarReprobado($_REQUEST);
      if ($reprobado!='ok' && is_array($reprobado))
      {
        $_REQUEST['confirmacion']="reprobado";
        $nombreEspacio=$this->consultarNombreEspacio();
        $mensaje="El estudiante en <b>Prueba Acad&eacute;mica</b> no ha reprobado <b>".$nombreEspacio."</b> c&oacute;digo <b>".$reprobado['espacio']."</b><br>";
        $mensaje.=" ¿Desea inscribirlo al estudiante?";
        $this->solicitarConfirmacion($mensaje,$_REQUEST);
      }
    }

    /**
     * Funcion que permite crear variables para la pagina de retorno
     * @return array
     */
    function crearVariablesRetorno() {
        $retorno['pagina']="admin_consultarInscripcionEstudianteCoorHoras";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codProyecto=".$_REQUEST['codProyecto'];
        $retorno['parametros'].="&planEstudio=".$_REQUEST['planEstudio'];
        $retorno['parametros'].="&codEstudiante=".$_REQUEST['codEstudiante'];
        $retorno['parametros'].="&codProyectoEstudiante=".$_REQUEST['codProyectoEstudiante'];
        $retorno['parametros'].="&planEstudioEstudiante=".$_REQUEST['planEstudioEstudiante'];
        return $retorno;
    }

    /**
     * Funcion que permite crear variables para la pagina a la que continua
     * @return array 
     */
    function crearVariablesContinuar() {
        $continuar['pagina']="registro_inscripcionAgilEstudianteCoorHoras";
        $continuar['opcion']="validar";
        $continuar['parametros']="&codProyecto=".$_REQUEST['codProyecto'];
        $continuar['parametros'].="&planEstudio=".$_REQUEST['planEstudio'];
        $continuar['parametros'].="&codEstudiante=".$_REQUEST['codEstudiante'];
        $continuar['parametros'].="&codProyectoEstudiante=".$_REQUEST['codProyectoEstudiante'];
        $continuar['parametros'].="&planEstudioEstudiante=".$_REQUEST['planEstudioEstudiante'];
        $continuar['parametros'].="&codEspacio=".$_REQUEST['codEspacio'];
        $continuar['parametros'].="&id_grupo=".$_REQUEST['id_grupo'];
        $continuar['parametros'].="&hor_alternativo=".$_REQUEST['hor_alternativo'];
        $continuar['parametros'].="&carrera=".$_REQUEST['carrera'];
        $continuar['parametros'].="&".(isset($_REQUEST['confirmacion'])?$_REQUEST['confirmacion']:'A')."=1";
        return $continuar;
    }

    /**
     * Funcion que genera el mensaje de solicitud de confirmacion
     * @param <string> $mensaje
     */
    function solicitarConfirmacion($mensaje) {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->crearCeldaMensajes($mensaje);
          $this->presentarBotonesConfirmacion();
        ?>
        </table>
        <?exit;
    }

    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     * @param <string> $mensaje
     */
    function presentarMensajesConfirmacion($mensaje="") {
        if (isset($mensaje))
          {
            echo $mensaje;
          }
          else
            {
            }
    }

    /**
     * Funcion que genera la celda con de se colocan mensajes de confirmacion de cancelacion
     * @param <string> $mensaje
     */
    function crearCeldaMensajes($mensaje) {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->presentarMensajesConfirmacion($mensaje);
              ?></td>
          </tr>
      <?
    }

    /**
     * Funcion que genera botones en formulario
     */
    function presentarBotonesConfirmacion() {
      ?>
          <tr class="texto_subtitulo">
            <?
            $continuar=$this->crearVariablesContinuar();
            $icono='clean.png';
            $texto='S&iacute;';
            $this->crearCeldaBoton($continuar,$icono,$texto);
            $retornar=$this->crearVariablesRetorno();
            $icono='x.png';
            $texto='No';
            $this->crearCeldaBoton($retornar,$icono,$texto);
            ?>
          </tr>
      <?
    }

    /**
     * Funcion que permite crear la celda de botones para confirmacion
     * @param <array> $variables
     * @param <string> $icono
     * @param <string> $texto 
     */
    function crearCeldaBoton($variables,$icono,$texto) {
      ?>
        <td class="centrar"><?
          $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
          $variable="pagina=".$variables['pagina'];
          $variable.="&opcion=".$variables['opcion'];
          $variable.=$variables['parametros'];
          include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
          $this->cripto=new encriptar();
          $variable=$this->cripto->codificar_url($variable,$this->configuracion);
          ?>
          <a href="<?echo $pagina.$variable?>">
            <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']."/".$icono;?>" width="35" height="35" border="0"><br><?echo $texto;?>
          </a>
        </td>
      <?
    }

    /*
     * Funcion que permite inscribir un espacio a estudiante por inscripcion agil
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                          codEspacio,grupo,carrera)
     */
    function registrarEstudiante($datosInscripcion)
    {

        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=registro_adicionarEspacioEstudianteCoorHoras";
        $variable.="&opcion=inscribirEstudiante";
        $variable.="&action=inscripcion/registro_adicionarEspacioEstudianteCoorHoras";
        $variable.="&codProyecto=".$datosInscripcion['codProyecto'];
        $variable.="&planEstudio=".$datosInscripcion['planEstudio'];
        $variable.="&codEstudiante=".$datosInscripcion["codEstudiante"];
        $variable.="&planEstudioEstudiante=".$datosInscripcion["planEstudioEstudiante"];
        $variable.="&codProyectoEstudiante=".$datosInscripcion['codProyectoEstudiante'];
        $variable.="&codEspacio=".$datosInscripcion["codEspacio"];
        $variable.="&id_grupo=".$datosInscripcion["id_grupo"];
        $variable.="&hor_alternativo=".$datosInscripcion["hor_alternativo"];
        $variable.="&carrera=".$datosInscripcion["carrera"];
        //$variable.="&datosInscripcion=".$datosInscripcion;
        $variable.="&retornoPagina=admin_consultarInscripcionEstudianteCoorHoras";
        $variable.="&retornoOpcion=mostrarConsulta";
        $variable.="&retornoParametros[codProyecto]=".$datosInscripcion['codProyecto'];
        $variable.="&retornoParametros[planEstudio]=".$datosInscripcion["planEstudio"];
        $variable.="&retornoParametros[codEstudiante]=".$datosInscripcion["codEstudiante"];
        $variable.="&retornoParametros[codProyectoEstudiante]=".$datosInscripcion['codProyectoEstudiante'];
        $variable.="&retornoParametros[planEstudioEstudiante]=".$datosInscripcion["planEstudioEstudiante"];
        $this->retornar($pagina, $variable);
    }

    /**
     *  Funcion que permite crear el enlace para no adicionar
     * @param <array> $retorno 
     */
    function noAdicionar($retorno) {
        echo "<script>alert ('".$retorno['mensaje']."');</script>";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        $variable.=$retorno['parametros'];
        $this->retornar($pagina, $variable);
    }

    /**
     * Funcion que permite generar el enlace para retornar
     * @param <string> $pagina
     * @param <array> $variable 
     */
    function retornar($pagina,$variable) {
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    /**
     * Funcion que permite consultar el nombre de un espacio academico
     * @return string
     */
    function consultarNombreEspacio() {
        $cadena_sql=$this->sql->cadena_sql("nombreEspacio",$_REQUEST);//echo $cadena_sql;exit;
        $resultado_nombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_nombreEspacio[0]['NOMBREESPACIO'];
    }

    /**
    * Funcion que permite consultar el nombre del proyecto curricular
    * @param <int> $codProyecto Codigo del proyecto curricular
    * @return <string> $resultado_carrera Nombre del proyecto
    */
    function consultarNombreCarrera($codProyecto) {
        $cadena_sql = $this->sql->cadena_sql("nombre_carrera", $codProyecto);
        $resultado_carrera = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado_carrera[0]['NOMBRE'];
    }

    
}

?>