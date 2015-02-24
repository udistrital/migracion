
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

class funcion_adminConsultarInscripcionCreditosEstudiante extends funcionGeneral {

  private $configuracion;
  private $datosEstudiante;
  private $espaciosPlan;
  private $creditosPlan;
  private $espaciosCursados;
  private $espaciosAprobados;

  //@ Método costructor que crea el objeto
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->procedimientos=new procedimientos();
    $this->tema = $tema;
    $this->sql = new sql_adminConsultarInscripcionCreditosEstudiante($this->configuracion);
    $this->log_us = new log();
    $this->formulario = "admin_consultarInscripcionCreditosEstudiante";
    $this->datosEstudiante=array();
    

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");

    //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
    //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
    if($configuracion["dbdistribuida"]==1){
        $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
    }else{
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
   * Esta funcion presenta el horario del estudiante
   * Utiliza los metodos consultarDatosEstudiante, datosEstudiante, consultaEspaciosInscritos,validar_fechas_estudiante
   *  horarioEstudianteConsulta, calcularCreditos, finTabla
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
   */
    function mostrarHorarioEstudiante() {
        $codEstudiante = $this->usuario;
        $datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);

        if (isset($datosEstudiante))
        {
            $variablesInscritos = array('codEstudiante' => $codEstudiante,
                                        'ano' => $this->ano,
                                        'periodo' => $this->periodo,
                                        'codProyectoEstudiante' => $datosEstudiante[0]['CODIGO_CARRERA'],
                                        'planEstudioEstudiante' => $datosEstudiante[0]['PLAN_ESTUDIO']);
            $this->datosEstudiante=array('codProyectoEstudiante'=>$datosEstudiante[0]['CODIGO_CARRERA'],
                                        'planEstudioEstudiante'=>$datosEstudiante[0]['PLAN_ESTUDIO'],
                                        'codEstudiante'=>$codEstudiante,
                                        'estado_est'=>$datosEstudiante[0]['LETRA_ESTADO'],
                                        'tipoEstudiante'=>$datosEstudiante[0]['INDICA_CREDITOS']);
            $registroEstudiante = array_merge($datosEstudiante[0], $variablesInscritos);

         
    //verifica permisos de adicion, cancelacion, consulta
            $registro_permisos = $this->fechas->validar_fechas_estudiante($this->configuracion, $registroEstudiante['CODIGO']);
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
                $fecha=$this->buscarFechas($registroEstudiante['codProyectoEstudiante']);
                $this->mostrarFechasActivas($fecha);
                ?></table><?
            }else
                {
                    $resultadoInscritos=$this->consultaEspaciosInscritos($variablesInscritos);
                    $this->presentarDatosEstudiante($datosEstudiante);
                    //valida si el estado del estudiante permite adicionar
                    if ($registroEstudiante['LETRA_ESTADO'] != 'A' && $registroEstudiante['LETRA_ESTADO'] != 'B' )
                    {
                        $permiso = 0;
                    }
                
                    //muestra el horario del estudiante
                    $this->horarioEstudianteConsulta($resultadoInscritos, $registroEstudiante, $permiso);

                ?>
                    <br><table class="sigma" align='center' width='70%' cellspacing='0' cellpadding='2' >
                <?
                    //calcula creditos inscritos
                    $creditos = $this->calcularCreditos($resultadoInscritos);

                    //muestra pie de pagina
                    $this->finTabla($creditos,$variablesInscritos['codProyectoEstudiante']);
                ?>
                    </table>

                <?
                }  
        }else
            {
                echo "El código de estudiante: <strong>" . $codEstudiante . "</strong> no está inscrito en Créditos.";
            }
        

  }//fin funcion mostrarHorarioEstudiante

  /**
   * Funcion que presenta la informacion del estudiante
   * @param <array> $registro (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS
   */
  function presentarDatosEstudiante($registro) {

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
               <td colspan="3">Acuerdo: <? echo "<strong>" . htmlentities(utf8_decode(substr($registro[0]['ACUERDO'], -3)." de ".substr($registro[0]['ACUERDO'], 0, 4))) . "</strong>"; ?>
                   <hr>
            </td>
          </tr>

        </table>
    <?

  }//fin funcion datosEstudiante

  /**
   * Funcion que presenta el horario del estudiante y permite consultar espacios académicos
   * Utiliza los métodos validar_primer_semestre, consultaHorario, calcularCreditos, codificar_url
   * @param <array> $resultadoInscritos (CODIGO, NOMBRE, GRUPO,CREDITOS, CLASIFICACION )
   * @param <array> $datosEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $vista
   */
  function horarioEstudianteConsulta($resultadoInscritos, $datosEstudiante, $vista) {
     
   ?>
    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
      <tr>
        <td>
      <?
      //verifica si es estudiante nuevo y restringir permisos de adicion y cancelacion
      $primiparo = $this->validar_primer_semestre($datosEstudiante);
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
                <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos </th>
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
                if ($primiparo!='ok'){
                ?>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
                }
              } else if ($vista == 2 && $primiparo!='ok') {
              ?>
                <th class='cuadro_plano sigma centrar' width="60">Cancelar </th>
              <?
              }
              ?>
              </thead>
              <?
               
              //recorre cada uno del los grupos
              for ($j = 0; $j < count($resultadoInscritos); $j++) {
                
                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                $resultadoInscritos[$j]['ano'] = $this->ano;
                $resultadoInscritos[$j]['periodo'] = $this->periodo;
                $resultado_horarios=$this->consultaHorario($resultadoInscritos[$j]);
              ?>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['CODIGO']; ?></td>
                  <td class='cuadro_plano '><? echo htmlentities(utf8_decode($resultadoInscritos[$j]['NOMBRE'])); ?></td>
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['GRUPO']; ?></td>
                  <td class='cuadro_plano centrar'><? echo (isset($resultadoInscritos[$j]['CREDITOS'])?$resultadoInscritos[$j]['CREDITOS']:''); ?></td>
                  <td class='cuadro_plano centrar'><? echo (isset($resultadoInscritos[$j]['CLASIFICACION'])?$resultadoInscritos[$j]['CLASIFICACION']:''); ?></td>
                <?
                //recorre el numero de dias del la semana 1-7 (lunes-domingo) #F4F4EA
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
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'') && (isset($resultado_horarios[$k + 1]['SALON'])?$resultado_horarios[$k + 1]['DIA']:'') != ($resultado_horarios[$k]['SALON'])) {
                      $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['SEDE'] . "<br>" . $resultado_horarios[$k]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] != $i) {

                    }
                  }
                ?></td><?
                }
                  $creditosInscritos=$this->calcularCreditos($resultadoInscritos);
                  $parametros = "&codEstudiante=" . $datosEstudiante['CODIGO'];
                  $parametros.="&codProyectoEstudiante=" . $datosEstudiante['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosEstudiante['planEstudioEstudiante'];
                  $parametros.="&tipoEstudiante=" . $datosEstudiante['INDICA_CREDITOS'];
                  $parametros.="&estado_est=" . trim($datosEstudiante['LETRA_ESTADO']);
                  $parametros.="&codEspacio=" . $resultadoInscritos[$j]['CODIGO'];
                  $parametros.="&nombreEspacio=" . $resultadoInscritos[$j]['NOMBRE'];
                  $parametros.="&creditos=" . (isset($resultadoInscritos[$j]['CREDITOS'])?$resultadoInscritos[$j]['CREDITOS']:'');
                  $parametros.="&creditosInscritos=". $creditosInscritos;
                  $parametros.="&grupo=" . $resultadoInscritos[$j]['GRUPO'];

                if ($vista == 1) {
                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#E0ECF8'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=admin_buscarGruposEstudianteCreditos";
                  $variables.="&opcion=cambiar";
                  $destino = "&parametro==";
                  $destino.="&destino=registro_cambiarGrupoInscripcionCreditosEstudiante";
                  $destino.="&retorno=admin_consultarInscripcionCreditosEstudiante";
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
                        </button>
                </td>
                <?
                }
                if (($vista == 1 || $vista == 2) && $primiparo!='ok') {
                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#F8E0E0'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_cancelarInscripcionCreditosEstudiante";
                  $variables.="&opcion=verificarCancelacion";
                  $destino="&retorno=admin_consultarInscripcionCreditosEstudiante";
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
                        $codEstudiante=$this->usuario;
                        list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($datosEstudiante);
                        ?>
                <table align='center' width='600' cellspacing='0' cellpadding='2' >
                    <tr class="centrar">
                        <td class='cuadro_plano centrar'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                        </td>
                        <td width="15%" class='centrar'>
                        <?

                        //enlace para adicionar espacios academicos
                                if (($vista == 1 || $vista == 2))
                                {
                                    $creditosInscritos=$this->calcularCreditos($resultadoInscritos);
                                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                    $variable="pagina=admin_buscarEspacioCreditosEstudiante";
                                    $variable.="&opcion=espacios";
                                    //$variable.="&planEstudio=" . $datosEstudiante['planEstudioEstudiante'];
                                    $variable.="&planEstudioEstudiante=" . $datosEstudiante['planEstudioEstudiante'];
                                    $variable.="&codProyectoEstudiante=" . $datosEstudiante['codProyectoEstudiante'];
                                    $variable.="&codEstudiante=" . $datosEstudiante['CODIGO'];
                                    $variable.="&creditosInscritos=". $creditosInscritos;
                                    $variable.="&estado_est=". trim($datosEstudiante['LETRA_ESTADO']);
                                    $variable.="&tipoEstudiante=". trim($datosEstudiante['INDICA_CREDITOS']);

                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                    echo $this->crear_enlace_adicionar($pagina, $variable, 'Adicionar');
                                }

                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?
                                    echo $valor5;
                                    echo $valor6;
                                    ?>
                        </td>
                        <td width="15%" class='centrar'>
                        <?      //enlace para adicionar Electivos Extrinsecos
                                if (($vista == 1 || $vista == 2) && trim($datosEstudiante['LETRA_ESTADO'])=='A')
                                {//no permite adicionar para estudiantes nuevos
                                            $creditosInscritos=$this->calcularCreditos($resultadoInscritos);
                                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                            $variable="pagina=admin_buscarEspacioEECreditosEstudiante";
                                            $variable.="&opcion=espacios";
                                            $variable.="&planEstudioEstudiante=" . $datosEstudiante['planEstudioEstudiante'];
                                            $variable.="&codProyectoEstudiante=" . $datosEstudiante['codProyectoEstudiante'];
                                            $variable.="&codEstudiante=".$datosEstudiante['CODIGO'];
                                            $variable.="&creditosInscritos=".$creditosInscritos;
                                            $variable.="&estado_est=".trim($datosEstudiante['LETRA_ESTADO']);
                                            $variable.="&tipoEstudiante=".trim($datosEstudiante['INDICA_CREDITOS']);

                                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                            echo $this->crear_enlace_adicionar($pagina, $variable, 'Adicionar<br>Electivos Extrinsecos');
                                }
                                            ?>
                        </td>
                    </tr>
                    
                    
                </table>

      <?
          }//fin funcion horarioEstudianteConsulta


  /**
   * Funcion que presenta el pie de la pagina: créditos, tabla convenciones de clasificacion y observaciones
   * Utiliza el metodo buscarFechas
   * @param <int> $creditos
   * @param <int> $codProyecto
   */
  function finTabla($creditos,$codProyecto) {
    $fecha=$this->buscarFechas($codProyecto);
  
  ?>
     <tr>
                      <td colspan="2" class="sigma derecha">
                        <b>
                          <?
                                if ($creditos==0) {
                                                echo "<font size='2'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                                }
                                else if($creditos>18) {
                                                echo "<font size='2' color='red'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                                }else if($creditos<=18) {
                                                echo "<font size='2' color='green'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                                }
                                            ?>
                        </b>
                      </td>
      </tr>
      <tr>
                        <td>
                        <?
                              $this->mostrar_convenciones_clasificacion();
                         ?>
                        </td>
      </tr>
      <tr>
          <?  $this->mostrarFechasActivas($fecha)?>
    </tr>
  <?
}//fin funcion finTabla

  /**
   * Funcion que busca las fechas para adiciones y cancelaciones de un proyecto curricular
   * Utiliza los metodos contarFechas, consultarFechas
   * @param <int> $codProyecto
   * @return <array>/<boolean>
*/
  function buscarFechas($codProyecto) {
      $_REQUEST['ano']=$this->ano;
      $_REQUEST['periodo']=$this->periodo;
      $_REQUEST['codProyecto']=$codProyecto;

      $conteo_fechas=$this->contarFechas($_REQUEST);
      
      if (is_array($conteo_fechas) && $conteo_fechas[0]['FECHAS']>0)
      {
        return $this->consultarFechas($_REQUEST);
      }else
      {
        return FALSE;
      }
     
    }//fin funcion buscarFechas

    function mostrarFechasActivas($fecha) {
    ?>
    <table class="sigma" align="center" width="70%">
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
                foreach ($fecha as $key => $value)
                {
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
            }else
                {
                    ?>
                    <br><b>** No hay fechas definidas para adiciones **</b><br>
                    <?
                }
            if (is_array($this->creditosPlan))
            {
                ?><br>* Puede inscribir un m&aacute;ximo de <?echo $this->creditosPlan[0]['MAXIMO']?> cr&eacute;ditos.<?
                ?><br><?
            }else
                {
                    ?>
                    <br>* No se han definido parámetros para la Inscripción. Por favor, comuníquese con el Proyecto Curricular.<br>
                    <?
                }
            ?>
            * Si cancela un espacio académico, no podr&aacute; adicionarlo de nuevo para el periodo actual<br> (Según Reglamento Estudiantil).
            <br>
            * Verificar el cruce de horarios de los espacios académicos.
            <br>
            * Si el grupo no cumple con el cupo mínimo, podrá ser cancelado.
          </td>
        </tr>
      </table>
    <?
    }

 /**
   * Funcion que muestra tabla con totales y porcentajes de créditos del estudiante
   * @param <array> $datosEstudiante
   * @return <array>
   */
  function porcentajeParametros($datosEstudiante) {
        
        $notaAprobatoria='';
        $notaAprobatoria=$this->consultarNotaAprobatoria();
        
         $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "cursados");
            if($cadena==''){
                $this->espaciosCursados=$this->consultarEspaciosCursados();
                $this->procedimientos->registrarArreglo($this->espaciosCursados,'cursados');
            }else
                { 
                    $this->espaciosCursados=$this->procedimientos->stringToArray($cadena[0][0]);                      
                }
         $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "espaciosPlan");
            if($cadena==''){
                $this->espaciosPlan=$this->consultarEspaciosPlan();                
                $this->procedimientos->registrarArreglo($this->espaciosPlan,'espaciosPlan');
            }else
                { 
                    $this->espaciosPlan=$this->procedimientos->stringToArray($cadena[0][0]);           
                }
        $this->creditosPlan=$this->consultarCreditosPlan();
        $this->espaciosAprobados=$this->espaciosAprobados($notaAprobatoria);
        
        $totalCreditos= $this->creditosPlan[0]['CREDITOS_PLAN'];
        $OB= $this->creditosPlan[0]['OB'];
        $OC= $this->creditosPlan[0]['OC'];
        $EI= $this->creditosPlan[0]['EI'];
        $EE= $this->creditosPlan[0]['EE'];
        $CP= $this->creditosPlan[0]['CP'];
        $totalCreditosEst=0;
        $OBEst=0;
        $OCEst=0;
        $EIEst=0;
        $EEEst=0;
        $CPEst=0;
        $numeroAprobados=count($this->espaciosAprobados);
        if(is_array($this->espaciosAprobados))
        {
            for($i=0;$i<=$numeroAprobados;$i++)
            {
                switch(isset($this->espaciosAprobados[$i]['CLASIFICACION'])?$this->espaciosAprobados[$i]['CLASIFICACION']:"")
                {
                    case 1:
                        $OBEst=$OBEst+$this->espaciosAprobados[$i]['CREDITOS'];
                        break;

                    case 2:
                        $OCEst=$OCEst+$this->espaciosAprobados[$i]['CREDITOS'];
                        break;

                    case 3:
                        $EIEst=$EIEst+$this->espaciosAprobados[$i]['CREDITOS'];
                        break;

                    case 4:
                        $EEEst=$EEEst+$this->espaciosAprobados[$i]['CREDITOS'];
                        break;

                    case 5:
                        $CPEst=$CPEst+$this->espaciosAprobados[$i]['CREDITOS'];
                        break;

                    case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                        break;

                }
            }
        }
        $OBEst=$OBEst+$CPEst;
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

            if($totalCreditos==0){$porcentajeCursado=0;}
            else{$porcentajeCursado=$totalCreditosEst*100/$totalCreditos;}
            if($OB==0){$porcentajeOBCursado=0;}
            else{$porcentajeOBCursado=$OBEst*100/$OB;}
            if($OC==0){$porcentajeOCCursado=0;}
            else{$porcentajeOCCursado=$OCEst*100/$OC;}
            if($EI==0){$porcentajeEICursado=0;}
            else{$porcentajeEICursado=$EIEst*100/$EI;}
            if($EE==0){$porcentajeEECursado=0;}
            else{$porcentajeEECursado=$EEEst*100/$EE;}

        if($totalCreditos>0) {
            $vista="
            <table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa' >
                 <caption class='sigma'>Cr&eacute;ditos Ac&aacute;demicos</caption>
                      <tr>
                          <th class='sigma centrar' width='16%'>Clasificaci&oacute;n</th>
                          <th class='sigma centrar' width='10%'>Total</th>
                          <th class='sigma centrar' width='14%'>Aprobados</th>
                          <th class='sigma centrar' width='14%'>Por Aprobar</th>
                          <th class='sigma centrar' width='46%'>% Cursado</th>
                      </tr></table>";

            $vistaOB="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>OB
                      </td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$OB."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$OBEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanOB=$OB-$OBEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeOBCursado==0) {
                $vistaOB.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $OBEst=0;
            }else if($porcentajeOBCursado>=100) {
                $vistaOB.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeOBCursado>0 AND $porcentajeOBCursado<100) {
                $vistaOB.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOBCursado."%' class='sigma centrar' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalOB=100-$porcentajeOBCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }


            $vistaOB.="</td>
                        </tr></table>
                      ";


            $vistaOC="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>OC
                      </td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$OC."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$OCEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanOC=$OC-$OCEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeOCCursado==0) {
                $vistaOC.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $OCEst=0;
            }else if($porcentajeOCCursado>=100) {
                $vistaOC.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeOCCursado>0 AND $porcentajeOCCursado<100) {
                $vistaOC.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOCCursado."%' class='sigma centrar' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalOC=100-$porcentajeOCCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }
            $vistaOC.="</td>
                        </tr></table>";



            $vistaEI="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                    <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>EI</td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$EI."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$EIEst."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanEI=$EI-$EIEst."</td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeEICursado==0) {
                $vistaEI.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%</td>
                       </table>";
                $EIEst=0;
            }else if($porcentajeEICursado>=100) {
                $vistaEI.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%</td>
                           </table>";
            }else if($porcentajeEICursado>0 AND $porcentajeEICursado<100) {
                $vistaEI.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEICursado."%' class='sigma centrar' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%</td>
                           <td class='sigma centrar' width='".$TotalEI=100-$porcentajeEICursado."%' bgcolor='#fffcea'></td>
                           </table>";
            }
            $vistaEI.="</td>
                        </tr></table>";

            $vistaEE="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                      <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>EE</td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$EE."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$EEEst."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanEE=$EE-$EEEst."</td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeEECursado==0) {
                $vistaEE.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $EEEst=0;
            }else if($porcentajeEECursado>=100) {
                $vistaEE.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeEECursado>0 AND $porcentajeEECursado<100) {
                $vistaEE.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEECursado."%' class='sigma centrar' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalEE=100-$porcentajeEECursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }
            $vistaEE.="</td>
                        </tr></table>";

            $vistaTotal="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                         <tr>
                          <td class='sigma centrar cuadro_plano' width='16%'>Total</td>
                          <td class='sigma centrar cuadro_plano' width='10%'>".$totalCreditos."</td>
                          <td class='sigma centrar cuadro_plano' width='14%'>".$totalCreditosEst."</td>
                          <td class='sigma centrar cuadro_plano' width='14%'>".$Faltan=$totalCreditos-$totalCreditosEst."</td>
                          <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeCursado==0) {
                $vistaTotal.="
                               <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%</td>
                               </table>";
                $totalCreditosEst=0;
            }else if($porcentajeCursado>=100) {
                $vistaTotal.="
                           <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='sigma centrar' colspan='2' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%</td>
                           </table>";
            }else if($porcentajeCursado>0 AND $porcentajeCursado<100) {
                $vistaTotal.="<table align='center' width='100%' cellspacing='0'>
                                   <td width='".$porcentajeCursado."%' class='sigma centrar' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%</td>
                                   <td class='sigma centrar' width='".$Total=100-$porcentajeCursado."%' bgcolor='#fffcea'></td>
                                </table>";
            }
            $vistaTotal.="</td>
                            </tr>
                      </table>";

        }
        else {
            $vista="
            <table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='6'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='16%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='10%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Por Aprobar
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>% Cursado
                      </td>
                   </tr>
                   </table>";

            $vistaOB="<table align='center' width='100%' cellspacing='0' cellpadding='2' bgcolor='#fffffa'>
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='46%'> 0%
                                </td>
                                </tr>
                             </table>";
            $vistaOC=$vistaOB;
            $vistaEI=$vistaOB;
            $vistaEE=$vistaOB;
            $vistaTotal=$vistaOB;
        }
        return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }//fin funcion porcentajeParametros 
        
  /**
   * Funcion que ejecuta consulta de busqueda de las fechas para adiciones y cancelaciones de un proyecto curricular
   * @param <array> $datosFechas(ano,periodo,codProyecto)
   * @return <array>
  */
  function consultarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('fechas_activas_estudiante', $datosFechas);
      return $fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
   }//fin funcion consultarFechas

  /**
   * Funcion que ejecuta consulta de conteo de fechas existentes para adiciones y cancelaciones de un proyecto curricular
   * @param <array> $datosFechas(ano,periodo,codProyecto)
   * @return <array>
  */
  function contarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('contar_fechas', $datosFechas);
      return $contar_fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
  }//fin funcion contarFechas


  /**
   * Funcion que permite consultar datos del estudiante
   * @param <int> $codEstudiante
   * @return <array> 
   */
  function consultarDatosEstudiante($codEstudiante) {
    $cadena_sql = $this->sql->cadena_sql("consultaEstudiante", $codEstudiante);
    return $registroEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
  }//fin funcion consultarDatosEstudiante


  /**
   * Funcion que permite consultar los espacios inscritos para el estudiante
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  function consultaEspaciosInscritos($variablesInscritos) {
      $cadena_sql = $this->sql->cadena_sql("consultaEspaciosInscritos", $variablesInscritos);
      return $resultadoInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
  }//fin funcion consultarEspaciosInscritos
  

  /**
   * 
   * @param <array> $datosGrupo (CODIGO,ano,periodo,GRUPO)
   * @return <array>
   */
  function consultaHorario($datosGrupo) {
        $this->cadena_sql = $this->sql->cadena_sql("horario_grupos", $datosGrupo);
      return $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $this->cadena_sql, "busqueda");
  }//fin funcion consultaHorario



  
  function consultarEspaciosPlan() {
        $variables=array('planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante'],
                        'codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante']
                        );
        $cadena_sql = $this->sql->cadena_sql("espacios_plan_estudio", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }   

  function consultarCreditosPlan(){
        
        $planEstudiante =$this->datosEstudiante['planEstudioEstudiante'];
        $cadena_sql=$this->sql->cadena_sql("creditosPlan",$planEstudiante);
        return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }
    
 
     function consultarEspaciosCursados() {

        $variables=array('codEstudiante'=>$this->datosEstudiante['codEstudiante'],
                         'codProyectoEstudiante'=>  $this->datosEstudiante['codProyectoEstudiante']);
                       
          $cadena_sql = $this->sql->cadena_sql("espacios_cursados", $variables); 
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
            }
            
     function consultarNotaAprobatoria() {
 
      $variables=array('codProyectoEstudiante'=>  $this->datosEstudiante['codProyectoEstudiante']            
                      );
 
       $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $variables);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          
              return $resultado[0][0];
    }
    
       function espaciosAprobados($notaAprobatoria){
        $aprobados=array();
        if (is_array($this->espaciosCursados))
        {
            if (trim($this->datosEstudiante['tipoEstudiante'])=='S')
            {
            foreach ($this->espaciosCursados as $value1) {
                    if ($value1['NOTA']>=$notaAprobatoria){
                        $aprobados[]=$value1;
                    }
                }
            }elseif(trim($this->datosEstudiante['tipoEstudiante'])=='N')
                {
                foreach ($this->espaciosCursados as $value1) {
                        if ($value1['NOTA']>=$notaAprobatoria){
                            $aprobados[]=$value1;
                        }
                    }
                }
                else{}
       }else
            {
                $aprobados='';
            }
            return $aprobados;
    }
    
  /**
   * Esta funcion calcula el total de creditos
   * @param <array> $registroGrupo (CODIGO, NOMBRE, GRUPO,CREDITOS, CLASIFICACION )
   * @return <int>
   */
    function calcularCreditos($registroGrupo) {
        $suma=0;
        for($i=0;$i<count($registroGrupo);$i++) {
            $suma+=(isset($registroGrupo[$i]['CREDITOS'])?$registroGrupo[$i]['CREDITOS']:'');
        }

        return $suma;

    }//fin funcion calcularCreditos

/**
   * Esta funcion crea el vinculo para dirigir a adicionar espacios academicos
   * @param <varchar> $pagina
   * @param <varchar> $variable
   * @param <varchar> $etiqueta_opcion
   * @return <varchar>
   */
    function crear_enlace_adicionar($pagina, $variable, $etiqueta_opcion) {
        $enlace = "<button class='botonEnlacePreinscripcion2' onclick='window.location.href=\"".$pagina.$variable."\"'>";
        $enlace.= "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/clean.png' width='30' height='30' border='0'><br><font size='1'>".$etiqueta_opcion."<br></font>";
        $enlace.= "</button>";

        return $enlace;
    }//fin funcion crear_enlace_adicionar

    function mostrar_convenciones_clasificacion(){
        ?>
    <table align="center" width="100%" >
                <tr>
                <th class="sigma centrar">
                    Abreviatura
                </th>
                <th class="sigma centrar">
                    Nombre
                </th>
            </tr>
                        <?

                        $cadena_sql=$this->sql->cadena_sql("clasificacion",'');
                        $resultado_clasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");


                        for($k=0;$k<count($resultado_clasificacion);$k++) {
                            ?>
            <tr>
                <td class="sigma centrar">
                                    <?echo $resultado_clasificacion[$k][1]?>
                </td>
                <td class="sigma ">
                                    <?echo $resultado_clasificacion[$k][2]?>
                </td>
            </tr>
                            <?
                }

                ?>
            </table>
            <?
    }//fin funcion mostrar_convenciones_clasificacion

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
