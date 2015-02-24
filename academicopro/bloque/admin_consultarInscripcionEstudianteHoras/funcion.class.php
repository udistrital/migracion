
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

class funcion_adminConsultarInscripcionEstudianteHoras extends funcionGeneral {

  private $configuracion;
  private $ano;
  private $periodo;
  private $parametrosHoras;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->tema = $tema;
    $this->sql = new sql_adminConsultarInscripcionEstudianteHoras();
    $this->log_us = new log();
    $this->parametrosHoras=array();
    $this->formulario = "admin_consultarInscripcionEstudianteHoras";


    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
    
    //conexion distribuida 1 conecta a MySUDD de lo contrario conecta a ORACLE
    if ($configuracion['dbdistribuida']==1)
        {
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }
        else
            {
                $this->accesoMyOracle = $this->accesoOracle;
            }
    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
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
    $permiso=0;
    $codEstudiante = $this->usuario;
    $registroEstudiante=$this->consultarDatosEstudiante($codEstudiante);
    $_REQUEST['codEstudiante']=$codEstudiante;
    $_REQUEST['codProyectoEstudiante']=$registroEstudiante[0]['CODIGO_CARRERA'];
    if (isset($registroEstudiante)&&!is_null($registroEstudiante[0]['CODIGO'])) {
        if (trim($registroEstudiante[0]['LETRA_ESTADO'])=='A'||trim($registroEstudiante[0]['LETRA_ESTADO'])=='B')
        {
      //verifica permisos de adicion, cancelacion, consulta
      $registro_permisos = $this->fechas->validar_fechas_estudiante($this->configuracion, $registroEstudiante[0]['CODIGO']);
      switch ($registro_permisos)
      {
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
      if($permiso==0)
        {
            ?><table class="sigma" align="center" width="70%">
              <tr>
                  <td class="centrar" colspan="12">En este momento el sistema no registra preinscripci&oacute;n de su PROYECTO CURRICULAR, por lo tanto esta opci&oacute;n se habilitar&aacute; cuando este proceso se haya realizado.<br>
                      <b>Para mayor informaci&oacute;n consulte con su PROYECTO CURRICULAR.</b>
                  </td>
              </tr>
            <?
            $fecha=$this->buscarFechas();
            $this->mostrarFechasActivas($fecha,$registroEstudiante);
            ?></table><?
        }else{
      $this->presentardatosEstudiante($registroEstudiante);
      $variablesInscritos = array('codEstudiante' => $codEstudiante,
                                  'ano' => $this->ano,
                                  'periodo' => $this->periodo,
                                  'codProyecto' => (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:''),
                                  'planEstudio' => (isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:''),
                                  'codProyectoEstudiante' => $registroEstudiante[0]['CODIGO_CARRERA'],
                                  'planEstudioEstudiante' => $registroEstudiante[0]['PLAN_ESTUDIO']);
      $registroEstudiante = array_merge($registroEstudiante[0], $variablesInscritos);
      $resultadoInscritos=$this->consultarEspaciosInscritos($variablesInscritos);
      //muestra el horario del estudiante
      $this->presentarHorarioEstudiante($resultadoInscritos, $registroEstudiante, $permiso);
      //calcula creditos inscritos
?>
      <br><table class="cuadro_plano" align='center' width='70%' cellspacing='0' cellpadding='2' >
  <?
      $creditos = $this->calcularInscritos($variablesInscritos);
      
        if(isset($registroEstudiante))
        {
            $this->parametrosHoras=$this->consultarParametrosHoras($variablesInscritos['codProyectoEstudiante']);
            if (!is_array($this->parametrosHoras))
            {
                $permiso=0;
            }
        }
      //permite adicionar si hay fechas habilitadas
      if ($permiso == 1 && trim($registroEstudiante['LETRA_ESTADO'])=='A')
        {
          $registroEstudiante['NUMERO_SEMESTRES']=$this->parametrosHoras[0]['NUMERO_SEMESTRES'];
          $this->adicionar($registroEstudiante, $creditos);
        }
      //muestra pie de pagina
      $this->mostrarFinalTabla($creditos,$registroEstudiante);
  ?>
    </table>
<?}
    }
    else{
            $this->iniciarTabla();
            echo "El estado del estudiante <strong>".$registroEstudiante[0]['ESTADO']."</strong> no le permite realizar proceso de inscripción.<br> Comuníquese con el Proyecto Curricular.";
            $this->cerrarTabla();
        }
  }
    else
        {
            $this->iniciarTabla();
            echo "No se encontraron datos para el código de estudiante: <strong>" . $codEstudiante . "</strong>.<br>Por favor ingrese nuevamente.";
            $this->cerrarTabla();
        }
  }

  /**
   * Funcion que presenta la informacion del estudiante
   * @param <array> $registro (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS
   */
  function presentardatosEstudiante($registro) {

    if (trim($registro[0]['INDICA_CREDITOS']) == 'S') {
      $modalidad = 'CR&Eacute;DITOS';
    } else {
      $modalidad = 'HORAS';
    }
?>
    <table class="contenidotabla centrar">
      <tr>
        <td>Nombre:<? echo "<strong>" . htmlentities(utf8_decode($registro[0]['NOMBRE'])) . "</strong>"; ?>
        </td>
        <td>C&oacute;digo:<? echo "<strong>" . $registro[0]['CODIGO'] . "</strong>"; ?>
        </td>
        <td>Estado:<? echo "<strong>" . htmlentities($registro[0]['ESTADO']) . "</strong>"; ?>
        </td>
      </tr>
      <tr>
        <td>Plan de Estudios:<? echo "<strong>" . htmlentities($registro[0]['PLAN_ESTUDIO']) . "</strong><br>"; ?>
        </td>
        <td>Proyecto Curricular:<? echo "<strong>" . $registro[0]['CODIGO_CARRERA'] . " - " . htmlentities(utf8_decode($registro[0]['NOMBRE_CARRERA'])) . "</strong><br>"; ?>
        </td>
        <td>Modalidad:<? echo "<strong>" . $modalidad . "</strong><br>"; ?>
        </td>
      </tr>
      <tr>
        <td>Acuerdo:<?echo "<strong>".substr($registro[0]['ACUERDO'], -3)." de ".substr($registro[0]['ACUERDO'], 0, 4)."</strong>";?>
        </td>
        <td>
        </td>
        <td>
        </td>
      </tr>
    </table>
<?
  }

  /**
   * Funcion que presenta el horario del estudiante y permite consultar espacios académicos
   * @param <array> $resultadoInscritos (CODIGO, NOMBRE, CREDITOS, ELECTIVA, GRUPO)
   * @param <array> $datosEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $vista
   */
  function presentarHorarioEstudiante($resultadoInscritos, $datosEstudiante, $vista) {
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
              <?
              if($vista == 1&&trim($datosEstudiante['LETRA_ESTADO'])=='A')
                  {
              ?>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
              }
              } else if ($vista == 2 &&trim($datosEstudiante['LETRA_ESTADO'])=='A') {
              ?>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
              }
              ?>
              </thead>
              <?
              //recorre cada uno del los grupos
              for ($j = 0; $j < count($resultadoInscritos); $j++) {
                $clasificacion=$this->consultarClasificacion($resultadoInscritos[$j]['CODIGO'],$datosEstudiante);

                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                $resultadoInscritos[$j]['ano'] = $this->ano;
                $resultadoInscritos[$j]['periodo'] = $this->periodo;
                $resultado_horarios=$this->consultarHorario($resultadoInscritos[$j]);
              ?>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['CODIGO']; ?></td>
                  <td class='cuadro_plano '><? echo htmlentities(utf8_decode($resultadoInscritos[$j]['NOMBRE'])); ?></td>
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['GRUPO']; ?></td>
                  <td class='cuadro_plano centrar'><? echo $clasificacion; ?></td>
                <?
                //recorre el numero de dias del la semana 1-7 (lunes-domingo) #F4F4EA
                if (is_array($resultado_horarios)){
                for ($i = 1; $i < 8; $i++) {
                ?><td class='cuadro_plano centrar'><?
                  //Recorre el arreglo del resultado de los horarios
                  for ($k = 0; $k < count($resultado_horarios); $k++) {
                    if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'') && (isset($resultado_horarios[$k + 1]['HORA'])?$resultado_horarios[$k + 1]['HORA']:'') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['SALON'])?$resultado_horarios[$k + 1]['SALON']:'') == ($resultado_horarios[$k]['SALON'])) {
                      $l = $k;
                      while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'') && (isset($resultado_horarios[$k + 1]['HORA'])?$resultado_horarios[$k + 1]['HORA']:'') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['SALON'])?$resultado_horarios[$k + 1]['SALON']:'') == ($resultado_horarios[$k]['SALON'])) {
                        $m = $k;
                        $m++;
                        $k++;
                      }
                      $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$l]['SEDE'] . "<br>" . $resultado_horarios[$l]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'')) {
                      $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['SEDE'] . "<br>" . $resultado_horarios[$k]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                      $k++;
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'') && (isset($resultado_horarios[$k + 1]['SALON'])?$resultado_horarios[$k + 1]['SALON']:'') != ($resultado_horarios[$k]['SALON'])) {
                      $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['SEDE'] . "<br>" . $resultado_horarios[$k]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] != $i) {

                    }
                  }
                ?></td><?
                  #F8E0E0
                }
                }else
                    {
                ?><td class='cuadro_plano centrar' colspan="7">NO HAY HORARIO REGISTRADO</td><?

                    }
                  $parametros = "&codProyecto=" . $datosEstudiante['codProyecto'];
                  $parametros.="&planEstudio=" . $datosEstudiante['planEstudio'];
                  $parametros.="&codEstudiante=" . $datosEstudiante['CODIGO'];
                  $parametros.="&codProyectoEstudiante=" . $datosEstudiante['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosEstudiante['planEstudioEstudiante'];
                  $parametros.="&estado_est=" . trim($datosEstudiante['LETRA_ESTADO']);
                  $parametros.="&codEspacio=" . $resultadoInscritos[$j]['CODIGO'];
                  $parametros.="&nombreEspacio=" . $resultadoInscritos[$j]['NOMBRE'];
                  $parametros.="&creditos=" . (isset($resultadoInscritos[$j]['CREDITOS'])?$resultadoInscritos[$j]['CREDITOS']:'');
                  $parametros.="&grupo=" . $resultadoInscritos[$j]['GRUPO'];

                if ($vista == 1) {
                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#E0ECF8'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=admin_buscarGruposEstudianteHoras";
                  $variables.="&opcion=cambiar";
                  $destino = "&parametro==";
                  $destino.="&destino=registro_cambiarGrupoEstudianteHoras";
                  $destino.="&retorno=admin_consultarInscripcionEstudianteHoras";
                  $destino.="&opcionRetorno=mostrarConsulta";

                  $variable = $variables . $parametros . $destino;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  ?>
                      
                    <button class="botonEnlacePreinscripcion" onclick="window.location = 
                    '<?
                        echo $pagina . $variable;
                    ?>'
                    "><center><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/reload.png" ?>" border="0" width="25" height="25">
                    <br>
                   </button>
               <?
                }
                if ($vista == 1 || $vista == 2) {
                    if(trim($datosEstudiante['LETRA_ESTADO'])=='A')
                  {

                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#F8E0E0'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_cancelarInscripcionEstudianteHoras";
                  $variables.="&opcion=verificarCancelacion";
                  $destino="&retorno=admin_consultarInscripcionEstudianteHoras";
                  $destino.="&opcionRetorno=mostrarConsulta";

                  $variable = $variables . $parametros.$destino;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  ?>
                        <button class="botonEnlacePreinscripcion" onclick="window.location = 
                            '<?

                                echo $pagina . $variable;
                            ?>'
                        "><center><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/x.png" ?>" border="0" width="25" height="25">
                        </button>
               
                <?
                }
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
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
    $this->cripto = new encriptar();
  
      ?>
        <tr>
        <td>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                        $variable = "pagina=admin_buscarEspacioEstudianteHoras";
                        $variable.="&opcion=espacios";
                        $variable.="&codProyecto=" . $registroEstudiante['codProyecto'];
                        $variable.="&planEstudio=" . $registroEstudiante['planEstudio'];
                        $variable.="&codProyectoEstudiante=" . $registroEstudiante['codProyectoEstudiante'];
                        $variable.="&planEstudioEstudiante=" . $registroEstudiante['planEstudioEstudiante'];
                        $variable.="&codEstudiante=" . $registroEstudiante['CODIGO'];
                        $variable.="&tipoEstudiante=" . $registroEstudiante['INDICA_CREDITOS'];
                        $variable.="&creditosInscritos=" . $creditos;
                        $variable.="&estado_est=" . trim($registroEstudiante['LETRA_ESTADO']);
                        $variable.="&numeroSemestres=" . trim($registroEstudiante['NUMERO_SEMESTRES']);
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo $pagina . $variable;
                ?>'
                "><center>Adicionar Espacios Acad&eacute;micos
              <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/clean.png" width="30" height="30" border="0">
            </button>					
          </td>
        </tr>
       
      <?
    }

  /**
   * Funcion que presenta el pie de la pagina: creditos inscritos y observaciones
   * @param <int> $creditos
   */
  function mostrarFinalTabla($creditos,$registroEstudiante) {

  $fecha=$this->buscarFechas();
  ?>
    <tr>
      <table class="sigma" align="center" width="80%">
        <tr>
          <td colspan="2" class="sigma derecha">
            <b>
            <?
            if ($creditos == 0) {
              echo "Espacios Inscritos: 0";
            } else {
              echo "Total de Espacios Inscritos: " . $creditos . "";
            }
            ?>
            </b>
          </td>
        </tr>
        <?
        $this->mostrarFechasActivas($fecha,$registroEstudiante);
        ?>
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

    /**
     * Esta funcion permite iniciar la tabla donde se presenta el plan de estudios
     */
    function iniciarTabla() {
      ?>
        <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
            <tr class="cuadro_centrar">
              <td  align="center">
      <?
    }

    /**
     * Esta funcion permite cerrar la tabla donde se presenta el plan de estudios
     */
    function cerrarTabla() {
      ?>
              </td>
            </tr>
        </table>
      <?
    }
    
    /**
     * Funcion que presenta las fechas activas de adiciones y cancelaciones
     * @param type $fecha 
     */
    function mostrarFechasActivas($fecha,$datosEstudiante) {
?>    
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
                ?>Fechas para realizar Adiciones y Cancelaciones <?echo $this->ano."-".$this->periodo;?>:
                    <table width="100%">
                        <tr>
                            <td class="sigma centrar"><b>Inicio</b></td>
                            <td class="sigma centrar"><b></b></td>
                            <td class="sigma centrar"><b>Fin</b></td>
                        </tr>
                        <?
                foreach ($fecha as $key => $value) {
                    ?>
                        <tr>
                            <td class="sigma derecha"><?echo $value['FECHAINICIO']?></td>
                            <td class="sigma centrar"> - - - - - - </td>
                            <td class="sigma izquierda"><?echo $value['FECHAFIN'];?></td>
                        </tr>
                    <?
                }
                ?>
                </table>
                <?
              }
              else
              {
                ?>
                <br><b>** No hay fechas definidas para Adiciones **</b><br>
                <?
              }
              
        if (is_array($this->parametrosHoras)&&isset($this->parametrosHoras[0]['MAXIMO']))
        {
        $maximo=$this->parametrosHoras[0]['MAXIMO']." espacios.";
        $semestres=$this->parametrosHoras[0]['NUMERO_SEMESTRES'];
        if(trim($this->parametrosHoras[0]['CONSECUTIVOS'])=='S')
            {
                $consecutivos=' consecutivos';
            }else
                {
                    $consecutivos='';
                }
        if(trim($this->parametrosHoras[0]['SEMESTRES_SUPERIORES'])=='S')
            {
                $superiores='si';
            }else
                {
                    $superiores='no';
                }
        if(trim($this->parametrosHoras[0]['MAS_ASIGNATURAS'])=='S')
            {
                $masAsignaturas='si';
            }else
                {
                    $masAsignaturas='no';
                }
        ?><br>* Su Proyecto Curricular le permite inscribir un m&aacute;ximo de <?echo $maximo?> de hasta <?echo $semestres?> semestres<?echo $consecutivos?>.
        <br>* Su Proyecto Curricular <?echo $superiores?> le permite inscribir espacios acad&eacute;micos de semestres superiores al que est&aacute; cursando.
        <br>* Si usted tiene 3 o m&aacute;s espacios acad&eacute;micos perdidos, su Proyecto Curricular <?echo $masAsignaturas?> le permite cursar espacios acad&eacute;micos adicionales.<?
                ?><br><?
        }else
            {?>
                <br>* No se han definido parámetros para la inscripción. Por favor, comuníquese con el Proyecto Curricular.<br>
          <?}?>
            * Si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual (Según reglamento estudiantil).
            <br>
            * Verifique el cruce de horarios de los espacios académicos.
            <br>
            * Si el grupo no cumple con el cupo mínimo, podrá ser cancelado.
          </td>
        </tr><?
    }
    
  /**
   * Funcion que permite consultar las fechas de franjas de inscripciones para estudiante de horas
   * @param type $datosFechas
   * @return type 
   */  
  function consultarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('fechas_activas_estudiante', $datosFechas);
      return $fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
  }

  /**
   * Funcion que verifica que existan fechas de inscripciones para estudiante de horas.
   * @param type $datosFechas
   * @return type 
   */
  function contarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('contar_fechas', $datosFechas);
      return $contar_fechas=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda");
  }

  /**
   * Funcion que calcula el numero de espacios inscritos al estudiante en el periodo actual
   * @param <array> $registroGrupo (CODIGO,NOMBRE,CREDITOS,ELECTIVA,GRUPO)
   * @return <int> $suma
   */
  function calcularInscritos($datosInscritos) {
      $cadena_sql=$this->sql->cadena_sql('consultaNumeroEspaciosInscritos', $datosInscritos);
      $contar_inscritos=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda");
      return $contar_inscritos[0][0];
  }


  /**
   * Funcion para valor default del bloque
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
    return $registroEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los espacios inscritos para el estudiante
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  function consultarEspaciosInscritos($variablesInscritos) {
      $cadena_sql = $this->sql->cadena_sql("consultaEspaciosInscritos", $variablesInscritos);
      return $resultadoInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar la clasificacion del espacio en el proyecto del estudiante
   * @param <int> $codEspacio
   * @return <array>
   */
  function consultarClasificacion($codEspacio, $datosEstudiante) {
    $codEspacio=array('codEspacio'=>$codEspacio);
    $datos=array_merge($codEspacio, $datosEstudiante);
      $cadena_sql = $this->sql->cadena_sql("clasificacion",$datos);
      $resultadoClasificacion = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
      if (trim($resultadoClasificacion[0]['CLASIFICACION']) == 'S') {
        $clasificacion = 'Electivo';
      } elseif (trim($resultadoClasificacion[0]['CLASIFICACION']) == 'N'){
        $clasificacion = 'Obligatorio';
      } else{
        $clasificacion = '';
      }
      return $clasificacion;
  }

  /**
   * 
   * @param <array> $datosGrupo (CODIGO,ano,periodo,GRUPO)
   * @return <array>
   */
  function consultarHorario($datosGrupo) {
      $cadena_sql = $this->sql->cadena_sql("horario_grupos", $datosGrupo);
      return $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que consulta los parametros para preinscripcion de plan de estudios de horas
   * @param type $datosEstudiante
   * @return type 
   */
  function consultarParametrosHoras($proyectoEstudiante) {
      $variables=array('ano'=>  $this->ano,
                        'periodo'=>  $this->periodo,
                        'codProyectoEstudiante'=>$proyectoEstudiante
                        );
    $cadena_sql=$this->sql->cadena_sql("buscarParametrosHoras",$variables);
    return $registroParametros=$this->ejecutarSQL($this->configuracion,$this->accesoOracle,$cadena_sql,"busqueda" );
  }
  

}
?>
