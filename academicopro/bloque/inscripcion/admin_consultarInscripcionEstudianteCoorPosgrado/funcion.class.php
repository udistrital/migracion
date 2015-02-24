
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar inscripcion agil, enlaces para cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminConsultarInscripcionEstudianteCoorPosgrado extends funcionGeneral {

  private $configuracion;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->tema = $tema;
    $this->sql = new sql_adminConsultarInscripcionEstudianteCoorPosgrado();
    $this->log_us = new log();
    $this->formulario = "admin_consultarInscripcionEstudianteCoorPosgrado";
    $this->bloque = "inscripcion/admin_consultarInscripcionEstudianteCoorPosgrado";
    $this->validacion = new validarInscripcion();
    $this->verificar="control_vacio(".$this->formulario.",'codEspacioAgil')";


    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

  /**
   * Esta funcion presenta el horario del estudiante
   * Utiliza los metodos datosEstudiante, validar_fechas_estudiante_coordinador, validarEstadoEstudiante, registroAgil,
   *  horarioEstudianteConsulta, calcularCreditos, adicionar, finTabla
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
   */
  function mostrarHorarioEstudiante() {   
    $codEstudiante = $_REQUEST['codEstudiante'];    
    $registroEstudiante=$this->consultarDatosEstudiante($codEstudiante);   
    if (isset($registroEstudiante)) {
      $this->datosEstudiante($registroEstudiante);
      $variablesInscritos = array('codEstudiante' => $codEstudiante,
                                  'ano' => $this->ano,
                                  'periodo' => $this->periodo,
//                                  'codProyecto' => $_REQUEST['codProyecto'],
//                                  'planEstudio' => $_REQUEST['planEstudio'],
                                  'codProyectoEstudiante' => $registroEstudiante[0]['CODIGO_CARRERA'],
                                  'planEstudioEstudiante' => $registroEstudiante[0]['PLAN_ESTUDIO']);                                                         
      $registroEstudiante = array_merge($registroEstudiante[0], $variablesInscritos);      
      $resultadoInscritos=$this->consultaEspaciosInscritos($variablesInscritos); 
      //verifica permisos de adicion, cancelacion, consulta
      $registro_permisos = $this->fechas->validar_fechas_estudiante_coordinador($this->configuracion, $registroEstudiante['CODIGO']);
      switch ($registro_permisos) {
        case 'adicion':
          $permiso = 1;
          break;

        case 'cancelacion':
          $permiso = 2;
          break;

        case 'consulta':
          $permiso = 0;
          break;

        default:
          $permiso = 0;
          break;
      }
      //valida si el estado del estudiante permite adicionar
      $estado = $this->validacion->validarEstadoEstudiante($_REQUEST);
      if ($estado != 'ok')
        {
          $permiso = 0;
        }
      //permite adicionar si hay fechas habilitadas
      if ($permiso == 1)
      {
        $this->registroAgil($registroEstudiante);
      }
      //muestra el horario del estudiante
      $this->horarioEstudianteConsulta($resultadoInscritos, $registroEstudiante, $permiso);
      //calcula creditos inscritos
?>
      <br><table class="cuadro_plano" align='center' width='70%' cellspacing='0' cellpadding='2' >
  <?
      $creditos = $this->calcularCreditos($resultadoInscritos);
      //permite adicionar si hay fechas habilitadas
      if ($permiso == 1)
        {
          $this->adicionar($registroEstudiante, $creditos);
        }
      //muestra pie de pagina
      $this->finTabla($creditos);
  ?>
    </table>
<?
    }
    else {
      echo "El código de estudiante: <strong>" . $codEstudiante . "</strong> no está inscrito en Créditos.";
    }
  }

  /**
   * Funcion que presenta la informacion del estudiante
   * @param <array> $registro (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS
   */
  function datosEstudiante($registro) {

    if (trim($registro[0]['INDICA_CREDITOS']) == 'S') {
      $modalidad = 'CR&Eacute;DITOS';
    } else {
      $modalidad = 'HORAS';
    }
?>
    <table class="contenidotabla centrar">
      <tr>
        <td>Nombre:<? echo "<strong>" . htmlentities($registro[0]['NOMBRE']) . "</strong>"; ?>
        </td>
        <td>C&oacute;digo:<? echo "<strong>" . $registro[0]['CODIGO'] . "</strong>"; ?>
        </td>
        <td>Estado:<? echo "<strong>" . htmlentities($registro[0]['ESTADO']) . "</strong>"; ?>
        </td>
      </tr>
      <tr>
        <td>Plan de Estudios:<? echo "<strong>" . htmlentities($registro[0]['PLAN_ESTUDIO']) . "</strong><br>"; ?>
        </td>
        <td>Proyecto Curricular:<? echo "<strong>" . $registro[0]['CODIGO_CARRERA'] . " - " . htmlentities($registro[0]['NOMBRE_CARRERA']) . "</strong><br>"; ?>
        </td>
        <td>Modalidad:<? echo "<strong>" . $modalidad . "</strong><br>"; ?>
        </td>
      </tr>
    </table>
<?
  }

  /**
   * Funcion que presenta el horario del estudiante y permite consultar espacios académicos
   * @param <array> $resultadoInscritos (CODIGO, NOMBRE, CREDITOS, ELECTIVA, GRUPO)
   * @param <array> $datosEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                  codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $vista
   */
  function horarioEstudianteConsulta($resultadoInscritos, $datosEstudiante, $vista) {
           
    ?>
    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
      <tr>
        <td>
      <?
      if (is_array($resultadoInscritos)) {
      ?>
        <table class="sigma contenidotabla" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
          <caption class="sigma centrar"><? echo "Horario de Clases"; ?></caption>
          <tr>
            <td>
              <table class='contenidotabla sigma' width="100%">
                <thead class='sigma'>
                <th class='cuadro_plano sigma centrar'>Cod.</th>
                <th class='cuadro_plano sigma centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </th>
                <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos</th>
                <th class='cuadro_plano sigma centrar' width="25">Clasificaci&oacute;n</th>
                <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                <th class='cuadro_plano sigma centrar' width="60">Dom </th>
              <?
              if ($vista == 1) {
              ?>
                <th class='cuadro_plano sigma centrar' width="60">Cambiar Grupo </th>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
              } else if ($vista == 2) {
              ?>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
              }
              ?>
              </thead>
              <?
              //recorre cada uno del los grupos
              for ($j = 0; $j < count($resultadoInscritos); $j++) {
                  if (isset($resultadoInscritos[$j]['ELECTIVA']))
                      {
                if (trim($resultadoInscritos[$j]['ELECTIVA']) == 'S') {
                  $clasificacion = 'Electivo';
                } else {
                  $clasificacion = 'Obligatorio';
                }
              }else{$clasificacion ='';}

                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                $resultadoInscritos[$j]['ano'] = $this->ano;
                $resultadoInscritos[$j]['periodo'] = $this->periodo;
                $resultado_horarios=$this->consultaHorario($resultadoInscritos[$j]);
              ?>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['CODIGO']; ?></td>
                  <td class='cuadro_plano '><? echo htmlentities($resultadoInscritos[$j]['NOMBRE']); ?></td>
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['GRUPO']; ?></td>
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['CREDITOS']; ?></td>
                  <td class='cuadro_plano centrar'><? echo $clasificacion; ?></td>
                <?
                //recorre el numero de dias del la semana 1-7 (lunes-domingo) #F4F4EA
                for ($i = 1; $i < 8; $i++) {
                ?><td class='cuadro_plano centrar'><?
                  //Recorre el arreglo del resultado de los horarios
                for ($k = 0; $k < count($resultado_horarios); $k++) {

                    if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                        $l = $k;
                        while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                            $m = $k;
                            $m++;
                            $k++;
                        }
                        $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                        echo $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                        $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                        echo $dia . "<br>";
                        unset($dia);
                        $k++;
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                        $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                        echo $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                    }

                ?></td><?
                  #F8E0E0
                }
                  $parametros = "&codProyecto=" . (isset($datosEstudiante['codProyecto'])?$datosEstudiante['codProyecto']:$datosEstudiante['codProyectoEstudiante']);
                  $parametros.="&planEstudio=" . (isset($datosEstudiante['planEstudio'])?$datosEstudiante['planEstudio']:'');
                  $parametros.="&codEstudiante=" . $datosEstudiante['CODIGO'];
                  $parametros.="&codProyectoEstudiante=" . $datosEstudiante['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosEstudiante['planEstudioEstudiante'];
                  $parametros.="&estado_est=" . trim($datosEstudiante['LETRA_ESTADO']);
                  $parametros.="&codEspacio=" . $resultadoInscritos[$j]['CODIGO'];
                  $parametros.="&nombreEspacio=" . $resultadoInscritos[$j]['NOMBRE'];
                  $parametros.="&creditos=" . $resultadoInscritos[$j]['CREDITOS'];
                  $parametros.="&grupo=" . $resultadoInscritos[$j]['GRUPO'];
                  $parametros.="&id_grupo=" . $resultadoInscritos[$j]['ID_GRUPO'];
                  $parametros.="&nivel=" . (isset($resultadoInscritos[$j]['NIVEL'])?$resultadoInscritos[$j]['NIVEL']:'');

                if ($vista == 1) {
                  ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#E0ECF8'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=admin_buscarGruposProyectoPosgrado";
                  $variables.="&opcion=cambiar";
                  $destino = "&parametro==";
                  $destino.="&destino=registro_cambiarGrupoEstudianteCoorPosgrado";
                  $destino.="&retorno=admin_consultarInscripcionEstudianteCoorPosgrado";
                  $destino.="&opcionRetorno=mostrarConsulta";

                  $variable = $variables . $parametros . $destino;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  ?>
                  <a href="<?= $pagina . $variable ?>" >
                    <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/reload.png" ?>" border="0" width="25" height="25">
                  </a>
                </td>
                <?
                }
                if ($vista == 1 || $vista == 2) {
                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#F8E0E0'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_cancelarInscripcionEstudianteCoorPosgrado";
                  $variables.="&opcion=verificarCancelacion";
                  $destino="&retorno=admin_consultarInscripcionEstudianteCoorPosgrado";
                  $destino.="&opcionRetorno=mostrarConsulta";

                  $variable = $variables . $parametros.$destino;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  ?>
                  <a href="<?= $pagina . $variable ?>" >
                    <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/x.png" ?>" border="0" width="25" height="25">
                  </a>
                </td>
                <?
                }
                ?>
              </tr>
              <?
              }
            } else {
              ?>
              <hr>
              <tr>
                <td class='sigma centrar'>
                  El estudiante no tiene inscripciones para el per&iacute;odo acad&eacute;mico actual
                </td>
              </tr>
              <? } ?>
          </td>
        </tr>
      </table>
      <?
          }

  /**
   * Funcion que presenta enlace para adicionar espacios al estudiante cuando estan habilitadas las fechas de adiciones
   * @param <array> $registroEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $creditos
   */
  function adicionar($registroEstudiante, $creditos) {
    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
    $variable = "pagina=admin_buscarEspacioEstudianteCoorPosgrado";
    $variable.="&opcion=espacios";
    $variable.="&codProyecto=" . (isset($registroEstudiante['codProyecto'])?$registroEstudiante['codProyecto']:'');
    $variable.="&planEstudio=" . (isset($registroEstudiante['planEstudio'])?$registroEstudiante['planEstudio']:'');
    $variable.="&codProyectoEstudiante=" . $registroEstudiante['codProyectoEstudiante'];
    $variable.="&planEstudioEstudiante=" . $registroEstudiante['planEstudioEstudiante'];
    $variable.="&codEstudiante=" . $registroEstudiante['CODIGO'];
    $variable.="&creditosInscritos=" . $creditos;
    $variable.="&estado_est=" . trim($registroEstudiante['LETRA_ESTADO']);

    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    $this->cripto = new encriptar();
    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
        <tr>
          <td align='center' width='85%'>
            <a href="<?= $pagina . $variable ?>" on><u>Adicionar Espacios Acad&eacute;micos</u></a>
          </td>
          <td align='center' width='15%'>
            <a href="<?= $pagina . $variable ?>" on>
              <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/clean.png" width="30" height="30" border="0">
            </a>
          </td>
        </tr>
      <?
    }

  /**
   * Funcion que presenta el pie de la pagina: creditos inscritos y observaciones
   * @param <int> $creditos
   */
  function finTabla($creditos) {

  $fecha=$this->buscarFechas();
  ?>
    <tr>
      <table class="sigma" align="center" width="70%">
        <tr>
          <td colspan="2" class="sigma derecha">
            <b>
            <?
            if ($creditos == 0) {
              echo "Cr&eacute;ditos Inscritos: 0";
            } else {
              echo "Total Cr&eacute;ditos Inscritos: " . $creditos . "";
            }
            ?>
            </b>
          </td>
        </tr>
        <tr class="sigma centrar">
          <th class="sigma centrar" colspan="2">
            Observaciones
          </th>
        </tr>
        <tr class="sigma">
          <td class="sigma" colspan="2">
            <b>Recuerde:</b>
            <?
            if (is_array($fecha))
              {
                ?>
                <h4>El proceso de adiciones inicia el <?echo $fecha[0]['FECHAINICIO']?> y finaliza el <?echo $fecha[0]['FECHAFIN']?>.</h4>
                <?
              }
              else
              {
                ?>
                <br><b>** No hay fechas definidas para adiciones **</b><br>
                <?
              }
            ?>
            * Si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual.
            <br>
            * Verificar el cruce de horarios de los espacios académicos.
            <br>
            * Si el grupo no cumple con el cupo mínimo, puede ser cancelado.
          </td>
        </tr>
      </table>
    </tr>
  <?
  }

  function buscarFechas() {
      $_REQUEST['ano']=$this->ano;
      $_REQUEST['periodo']=$this->periodo; 
      $conteo_fechas=$this->contarFechas($_REQUEST);
      if (is_array($conteo_fechas) && $conteo_fechas[0]['FECHAS']>0)
      {
        return $this->consultarFechas($_REQUEST);
      }else
      {
        return FALSE;
      }
    }

    
  function consultarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('fechas_activas_coordinador', $datosFechas);
      return $fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
  }

  function contarFechas($datosFechas) {     
      $cadena_sql=$this->sql->cadena_sql('contar_fechas', $datosFechas);
      return $contar_fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
  }

  /**
   * Funcion que calcula el numero de creditos inscritos al estudiante en el periodo actual
   * @param <array> $registroGrupo (CODIGO,NOMBRE,CREDITOS,ELECTIVA,GRUPO)
   * @return <int> $suma
   */
  function calcularCreditos($espaciosInscritos) {
    $suma = 0;
    for ($i = 0; $i < count($espaciosInscritos); $i++) {
      $suma+=$espaciosInscritos[$i][2];
    }
    return $suma;
  }

  /**
   * registroAgil
   * Funcion que permite inscribir de manera agil espacios academicos al estudiante
   * @param <array> $registroEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   */

  /*
   * buscarHorario
   * Funcion que presenta el horario del grupo y el boton de registrar al seleccionar un grupo
   * @param <array> arreglo (0=>GRUPO,1=>CUPO, 2=>INSCRITOS,3=>DIA,4=>HORA,5=>SEDE,6=>SALON,7=>NOMBRE,8=>CARRERA)
   * @param <int> codEspacio
   * @param <int> codProyecto
   */

  function registroAgil($registroEstudiante) {
  ?>
    <style type="text/css">
      #toolTipBox {
        display: none;
        position:absolute;
        width:200px;
        background:#000;
        border:4px double #fff;
        text-align:left;
        padding:5px;
        -moz-border-radius:6px;
        z-index:1000;
        margin:0;
        padding:0;
        color:#fff;
        font:11px/12px verdana,arial,serif;
        margin-top:3px;
        font-style:normal;
        font-weight:bold;
        opacity:0.85;
      }
    </style>
    <script type="text/javascript" language="javascript">
      function buscarHorario(arreglo,codEspacio,codProyecto)
      {
        var horario=new Array();
        var fila=0;
        var columna=0;
        horario[fila]=new Array();

        var resultado=arreglo.split("|");

        for(var j=0;j<resultado.length;j++)
        {
          if(j%11==0)
          {
            fila++;
            horario[fila]=new Array();
            columna=0;
          }
          horario[fila][columna]=new Array(resultado[j]);
          columna++;
          //alert("Fila:"+fila+" Evento: "+horario[fila]+" Valor J: "+j);
        }
        var tx="";

        tx="<table class='contenidotabla centrar'>";
        tx+="<tr class='sigma centrar'>";
        tx+="<td width='25%'>Dia</td><td width='25%'>Hora</td><td width='25%'>Sede</td><td width='25%'>Salon</td>";
        tx+="</tr>";
        for(var q=1;q<fila;q++)
        {
          tx+="<tr>";
          tx+="<td>"+horario[q][3]+"</td><td class='sigma centrar'>"+horario[q][4]+"</td><td class='centrar'>"+horario[q][5]+"</td><td class='centrar'>"+horario[q][6]+"</td>";
          tx+="</tr>";
        }
        tx+="<tr><td colspan='4'><b>Cupo: "+horario[1][1]+" <font color=#ffffff>__</font> Inscritos: "+horario[1][2]+" <font color=#ffffff>__</font> Disponibles: "+(horario[1][1]-horario[1][2])+"</b></td></tr>";
        tx+="</table>";

        document.getElementById('div_horario').innerHTML=tx;
        var inp="";

        inp="<input type='hidden' name='id_grupo' value='"+horario[1][9]+"'>"
        inp+="<input type='hidden' name='grupo' value='"+horario[1][0]+"'>"
        inp+="<input type='hidden' name='hor_alternativo' value='"+horario[1][10]+"'>"
        inp+="<input type='hidden' name='carrera' value='"+horario[1][8]+"'>"
        inp+="<input class='boton' type='button' style='cursor:pointer;' onclick='submit()' name='registrar' value='Registrar'>"

        document.getElementById('div_registrar').innerHTML=inp;

        var infGrup="";

        infGrup="<font size='1px' color='#FFFFFF'><b>"+horario[1][7]+"</b></font>";

        document.getElementById('div_InfoGrupo').innerHTML=infGrup;
      }
    </script>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
      <table class="contenidotabla centrar">
        <caption class="sigma">
          REGISTRO AGIL DE ESPACIOS ACAD&Eacute;MICOS
        </caption>
        <tr class="sigma">
          <th width="33%" class="sigma centrar">Espacio Acad&eacute;mico</th>
          <th width="33%" class="sigma centrar">Grupos</th>
          <th width="33%" class="sigma centrar">Horarios</th>
        </tr>
        <tr>
          <td width="33%" class="cuadro_plano centrar">
            <div style='width: 100%; height: 150px;border:0px solid #000000;'>
              <input type="text" name="codEspacioAgil" style=" background-color: #ABB0BE;" id="codEspacioAgil" size="6" maxlength="8" onblur="this.style.backgroundColor='#ABB0BE'" onfocus="this.style.backgroundColor='#A7C3DF'"onchange="xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<? echo $registroEstudiante['planEstudioEstudiante'] ?>,<? echo $registroEstudiante['codProyectoEstudiante'] ?>)" onkeypress="javascript:if(event.keyCode==13){xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<? echo $registroEstudiante['planEstudioEstudiante'] ?>,<? echo $registroEstudiante['codProyectoEstudiante'] ?>);return false;}" >
              <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/viewrel.png" ?>" style="cursor:pointer" border="0" onclick="if(<? echo $this->verificar; ?>){xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<? echo $registroEstudiante['planEstudioEstudiante'] ?>,<? echo $registroEstudiante['codProyectoEstudiante'] ?>)}else{false}"><br>
              <div id="div_infoea" style='width: 100%; height: 120px;'>
                <table class='contenidotabla centrar'>
                  <tr>
                    <td rowspan="4" colspan="4" class="centrar">
                      Digite el c&oacute;digo del espacio acad&eacute;mico
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </td>
          <td width="33%" class="cuadro_plano centrar">
            <div style='width: 100%; height: 150px;border:0px solid #000000;'>
              <div id="div_grupos">
                <select class='sigma' style='width: 100%; height: 120px;' size='7' >
                  <optgroup label="<&mdash; Digite el c&oacute;digo del E.A."></optgroup>
                </select>
              </div>
              <div id="div_InfoGrupo" style="background-color:ABB0BE">

              </div>
            </div>

          </td>
          <td width="33%" class="cuadro_plano centrar">
            <div style='width: 100%; height: 150px;border:0px solid #000000;'>
              <div id="div_horario" style="width: 100%; height: 120px;border:0px solid #000000;">
                <&minus;&minus; Seleccione el grupo para ver el horario
              </div>
            </div>
            <div class='centrar'><span id='toolTipBox' width='10' ></span></div>
          </td>
        </tr>
        <tr>
          <td colspan="3" class="centrar">
            <input type="hidden" name="codEstudiante" value="<? echo $registroEstudiante['CODIGO'] ?>">
            <input type="hidden" name="codProyecto" value="<? echo (isset($registroEstudiante['codProyecto'])?$registroEstudiante['codProyecto']:'') ?>">
            <input type="hidden" name="planEstudio" value="<? echo (isset($registroEstudiante['planEstudio'])?$registroEstudiante['planEstudio']:'') ?>">
            <input type="hidden" name="codProyectoEstudiante" value="<? echo $registroEstudiante['codProyectoEstudiante'] ?>">
            <input type="hidden" name="planEstudioEstudiante" value="<? echo $registroEstudiante['planEstudioEstudiante'] ?>">
            <input type="hidden" name="estado_est" value="<? echo $registroEstudiante['LETRA_ESTADO'] ?>">
            <input type="hidden" name="action" value="<? echo $this->bloque ?>">
            <input type="hidden" name="opcion" value="registroAgil">
            <div id="div_registrar"></div>
          </td>
        </tr>
      </table>
    </form>
<?
  }

  /**
   * Funcion que no sirve para nada pero se necesita
   */

  function nuevoRegistro() {

  }

  /**
   * Funcion que permite consultar datos del estudiante
   * @param <int> $codEstudiante
   * @return <array> 
   */
  function consultarDatosEstudiante($codEstudiante) {
    $cadena_sql = $this->sql->cadena_sql("consultaEstudiante", $codEstudiante);
    return $registroEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los espacios inscritos para el estudiante
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  function consultaEspaciosInscritos($variablesInscritos) {
      $cadena_sql = $this->sql->cadena_sql("consultaEspaciosInscritos", $variablesInscritos);
      return $resultadoInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * 
   * @param <array> $datosGrupo (CODIGO,ano,periodo,GRUPO)
   * @return <array>
   */
  function consultaHorario($datosGrupo) {
      $this->cadena_sql = $this->sql->cadena_sql("horario_grupos", $datosGrupo);
      return $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
  }




}
?>
