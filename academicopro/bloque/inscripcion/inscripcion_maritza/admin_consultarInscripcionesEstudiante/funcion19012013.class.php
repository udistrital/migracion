
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
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminConsultarInscripcionesEstudiante extends funcionGeneral {

  private $configuracion;
  private $ano;
  private $periodo;
  private $parametros;
  private $datosEstudiante;
  private $clasificaciones;


  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
  
    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->procedimientos = new procedimientos();
    //$this->tema = $tema;
    $this->sql = new sql_adminConsultarInscripcionesEstudiante($configuracion);
    $this->log_us = new log();
    $this->parametros=array();
    $this->formulario = "admin_consultarInscripcionesEstudiante";

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
      
    $permiso='consulta';
    if (is_null($this->usuario)||$this->usuario==''||!isset($this->usuario))
        {
            $this->iniciarTabla();
            echo "No se encontraron datos<strong>" . $codEstudiante . "</strong>.<br>Por favor ingrese nuevamente.";
            $this->cerrarTabla();
            exit;
        }
    $codEstudiante = $this->usuario;
    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante); 
    if (isset($this->datosEstudiante)&&!is_null($this->datosEstudiante[0]['CODIGO']))
    {
        if (trim($this->datosEstudiante[0]['ESTADO'])=='A'||trim($this->datosEstudiante[0]['ESTADO'])=='B')
        {
            $datosEstudiante['ano']=$this->ano;
            $datosEstudiante['periodo']=$this->periodo;
            $datosEstudiante['codProyectoEstudiante']=$this->datosEstudiante[0]['COD_CARRERA'];
            
            //verifica permisos de adicion, cancelacion, consulta
            $arreglo_permisos = $this->fechas->validar_fechas_inscripciones_estudiante($this->configuracion, $datosEstudiante);
            if(is_array($arreglo_permisos))
            {
                $permiso=$arreglo_permisos['EVENTO'];
                $preinscripcion=$arreglo_permisos['PREINSCRIPCION'];
                $fechas=$arreglo_permisos['FECHAS'];
                //verifica si no hay preinscripcion
                if ($preinscripcion=='S')
                {
                            $variablesInscritos = array('codEstudiante' => $codEstudiante,
                                                        'ano' => $this->ano,
                                                        'periodo' => $this->periodo,
                                                        'codProyectoEstudiante' => $this->datosEstudiante[0]['COD_CARRERA'],
                                                        'planEstudioEstudiante' => $this->datosEstudiante[0]['PLAN_ESTUDIO']);
                            $registroEstudiante = array_merge($this->datosEstudiante, $variablesInscritos);
                            $resultadoInscritos=$this->buscarEspaciosInscritos($variablesInscritos);
                            
                            $creditos = $this->calcularInscritos($resultadoInscritos);
                              if(isset($this->datosEstudiante))
                              {
                                  $this->parametros=$this->consultarParametros();                                
                                 
                                  if (!is_array($this->parametros))
                                  {
                                      $permiso='consulta';
                                  }
                              }
                            $this->presentardatosEstudiante();
                            //permite adicionar si hay fechas habilitadas
                            if ($permiso == 'adicion' && (trim($this->datosEstudiante[0]['ESTADO'])=='A'||trim($this->datosEstudiante[0]['ESTADO'])=='B'))
                            {
                                $maxSemestres=(isset($this->parametros['maxniveles'])?$this->parametros['maxniveles']:'');
                                if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE'])=='S')
                                {
                                    ?>
                                        <div class="enlace">
                                    <?
                                    $this->adicionar($creditos, $maxSemestres,'');
                                    if(trim($this->datosEstudiante[0]['ESTADO'])=='A')
                                    {
                                        //$this->adicionar($creditos,$maxSemestres,'EE');
                                    }
                                    ?>
                                        </div>
                                    <?
                                }else
                                    {
                                        ?>
                                            <div class="enlace">
                                        <?
                                        $this->adicionar($creditos, $maxSemestres,'');
                                        ?>
                                            </div>
                                        <?
                                    }
                            }
                              $this->clasificaciones=$this->consultarClasificaciones();
                            //muestra el horario del estudiante
                            $this->presentarHorarioEstudiante($resultadoInscritos, $permiso);
                            //calcula creditos inscritos
 ?><div class="numeroEspacios"><?
                if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
                {
                    $nombre='Cr&eacute;ditos';
                }else
                    {
                        $nombre='Espacios';
                    }
                                            
                                            if ($creditos == 0) {
                                            echo $nombre." Inscritos: 0";
                                            } else {
                                            echo "Total de ".$nombre." Inscritos:" . $creditos . "";
                                            }
                                            ?>

     </div>
                       <?
                            if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
                            {
                                $this->mostrarTablaPorcentajes();
                            }
                            //muestra pie de pagina
                            $this->mostrarFinalTabla($creditos,$fechas);
                }elseif($preinscripcion=='N')
                {
                    $this->mostrarMensajePreinscripcion($fechas);
                }
                //verifica si hay fechas

                //verifica evento

            }else
                {
                    echo "No hay fechas ni permisos";
                }
            
    }
    else{
            $this->iniciarTabla();
            echo "El estado del estudiante <strong>".$this->datosEstudiante[0]['ESTADO']."</strong> no le permite realizar proceso de inscripción.<br> Comuníquese con el Proyecto Curricular.";
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

  function mostrarMensajePreinscripcion($fechas)
    {
        ?><div class="sigma" align="center" width="70%">
          <div>
              <div class="centrar" colspan="12">En este momento el sistema no registra preinscripci&oacute;n de su PROYECTO CURRICULAR, por lo tanto esta opci&oacute;n se habilitar&aacute; cuando este proceso se haya realizado.<br>
                  <b>Para mayor informaci&oacute;n consulte con su PROYECTO CURRICULAR.</b>
              </div>
          </div>
        <?
               ?><div class="sigma" align="center" width="70%">
        <?
         //$this->mostrar_convenciones_clasificacion();
        ?></div><?
        $this->mostrarFechasActivas($fechas);
        ?></div><?
    }
  /**
   * Funcion que presenta la informacion del estudiante
   * @param <array> $registro (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS
   */
  function presentardatosEstudiante() {

    if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S') {
      $modalidad = 'CR&Eacute;DITOS';
    } else {
      $modalidad = 'HORAS';
    }
    //echo $this->datosEstudiante[0]['ACUERDO'];exit;
?>
   <div class="tablaEspacios" align="left">
   <div class="espacios_horario" ><b><?echo "Datos Estudiante";?></b></div>
    <div class="columna0">
        
     <div class="columna_1">
       <div class="datos"><? echo  "Nombre: <strong>".htmlentities(utf8_decode($this->datosEstudiante[0]['NOMBRE']))."</strong>"; ?></div>
       <div class="datos"><? echo "Plan de Estudios: <strong>".htmlentities($this->datosEstudiante[0]['PLAN_ESTUDIO'])."</strong><br>"; ?></div>
       <div class="datos"><? echo "Acuerdo: <strong>".substr($this->datosEstudiante[0]['ACUERDO'], 4)." de ".substr($this->datosEstudiante[0]['ACUERDO'], 0, 4)."</strong>";?></div>
     </div>
     
     <div class="columna_2">
     <div class="datos"><? echo "C&oacute;digo: <strong>".$this->datosEstudiante[0]['CODIGO']."</strong>"; ?></div>
     <div class="datos"><? echo "Proyecto Curricular: <strong>".$this->datosEstudiante[0]['COD_CARRERA'] . " - " . htmlentities(utf8_decode($this->datosEstudiante[0]['NOMBRE_CARRERA']))."</strong><br>"; ?></div>
     </div>
     
     <div class="columna_3">
     <div class="datos"><? echo "Estado: <strong>".htmlentities($this->datosEstudiante[0]['ESTADO_DESCRIPCION'])."</strong>"; ?></div>
     <div class="datos"></div>
     <div class="datos"><? echo "Modalidad: <strong>".$modalidad."</strong><br>"; ?></div>
     </div>
   </div>
   </div>
<?
  }

  /**
   * Funcion que presenta el horario del estudiante y permite consultar espacios académicos
   * @param <array> $resultadoInscritos (CODIGO, NOMBRE, CREDITOS, ELECTIVA, GRUPO)
   * @param <array> $datosEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $vista
   */
  function presentarHorarioEstudiante($resultadoInscritos, $vista) {
    ?>
      <?
      if (is_array($resultadoInscritos)) {
      ?>
       <div class="Horario centrar" ><b><?echo "Horario de clases"; ?></b></div>
        <table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                <thead class='sigma'>
                <th class='niveles centrar' width="30">Cod.</th>
                <th class='niveles centrar' width="150">Nombre Espacio<br>Acad&eacute;mico</th>
                <th class='niveles centrar' width="25">Grupo</th>
                <th class='niveles centrar' width="25">Clasificaci&oacute;n</th>
                <?if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S') {?>
                <th class='niveles centrar' width="25">Cr&eacute;ditos</th>
                <?}?>
                <th class='niveles centrar' width="60">Lun</th>
                <th class='niveles centrar' width="60">Mar</th>
                <th class='niveles centrar' width="60">Mie</th>
                <th class='niveles centrar' width="60">Jue</th>
                <th class='niveles centrar' width="60">Vie</th>
                <th class='niveles centrar' width="60">S&aacute;b</th>
                <th class='niveles centrar' width="60">Dom</th>
              <?
              if ($vista == 'adicion') {
              ?>
                <th class='niveles centrar' width="60">Cambiar Grupo </th>
              <?
              if($vista == 'adicion'&&trim($this->datosEstudiante[0]['ESTADO'])=='A')
                  {
              ?>
                <th class='niveles centrar' width="60">Cancelar </th>
              <?
              }
              } else if ($vista == 'cancelacion' &&trim($this->datosEstudiante['ESTADO'])=='A') {
              ?>
                <th class='niveles centrar' width="60">Cancelar </th>
              <?
              }
              ?>
              </thead>
              <?
              //recorre cada uno del los grupos
              for ($j = 0; $j < count($resultadoInscritos); $j++) {
                $clasificacion=$this->buscarClasificacion($resultadoInscritos[$j]);                

                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                $resultadoInscritos[$j]['ano'] = $this->ano;
                $resultadoInscritos[$j]['periodo'] = $this->periodo;
                $resultado_horarios=$this->consultarHorario($resultadoInscritos[$j]);
              ?>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['CODIGO']; ?></td>
                  <td class='cuadro_plano '><? echo htmlentities($resultadoInscritos[$j]['NOMBRE']); ?></td>
                  <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['GRUPO']; ?></td>
                  <td class='cuadro_plano centrar'><? echo $clasificacion; ?></td>
                <?if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S') {?>
                  <td class='cuadro_plano centrar'><? echo (isset($resultadoInscritos[$j]['CREDITOS'])?$resultadoInscritos[$j]['CREDITOS']:''); ?></td>
                <?}
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
                      $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$l]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$l]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$l]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'')) {
                      $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
                      echo $dia . "<br>";
                      unset($dia);
                      $k++;
                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA'])?$resultado_horarios[$k + 1]['DIA']:'') && (isset($resultado_horarios[$k + 1]['SALON'])?$resultado_horarios[$k + 1]['SALON']:'') != ($resultado_horarios[$k]['SALON'])) {
                      $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
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
                  $parametros = "&codProyecto=" . $this->datosEstudiante[0]['COD_CARRERA'];
                  $parametros.="&planEstudio=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
                  $parametros.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
                  $parametros.="&estado_est=" . trim($this->datosEstudiante[0]['ESTADO']);
                  $parametros.="&codEspacio=" . $resultadoInscritos[$j]['CODIGO'];
                  $parametros.="&nombreEspacio=" . $resultadoInscritos[$j]['NOMBRE'];
                  $parametros.="&creditos=" . (isset($resultadoInscritos[$j]['CREDITOS'])?$resultadoInscritos[$j]['CREDITOS']:'');
                  $parametros.="&grupo=" . $resultadoInscritos[$j]['GRUPO'];

                if ($vista == 'adicion') {
                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#E0ECF8'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=admin_buscarGruposProyectosEstudiante";
                  $variables.="&opcion=cambiar";
                  $destino = "&parametro==";
                  $destino.="&destino=registro_cambiarGrupoInscripcionesEstudiante";
                  $destino.="&retorno=admin_consultarInscripcionesEstudiante";
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
                  </td>
               <?
                }
                if ($vista == 'adicion' || $vista == 'cancelacion') {
                    if(trim($this->datosEstudiante[0]['ESTADO'])=='A')
                  {

                ?>
                  <td class='cuadro_plano centrar' onmouseover="this.style.background='#F8E0E0'" onmouseout="this.style.background=''">
                  <?
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_cancelarEspacioInscripcionesEstudiante";
                  $variables.="&opcion=cancelarInscripcion";
                  $destino="&retorno=admin_consultarInscripcionesEstudiante";
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
                }
                ?>
              </tr>
              <?
              }
            } else {
              ?>
              <table ><hr>
              <tr>
                <td class='sin_inscripciones' >
                  El estudiante no tiene inscripciones para el per&iacute;odo acad&eacute;mico actual
                </td>
              </tr>
              
              <? } ?>
        </table>
      <?
          }

  /**
   * Funcion que presenta enlace para adicionar espacios al estudiante cuando estan habilitadas las fechas de adiciones
   * @param <array> $registroEstudiante (CODIGO,NOMBRE,LETRA_ESTADO,ESTADO,CODIGO_CARRERA,NOMBRE_CARRERA,PLAN_ESTUDIO,INDICA_CREDITOS,
   *                                   codEstudiante,ano,periodo,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante)
   * @param <int> $creditos
   */
  function adicionar($creditos, $maxSemestres,$tipo) {      
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
    $this->cripto = new encriptar();
  
                        $variable = "pagina=admin_buscarEspacio".$tipo."InscripcionesEstudiante";
                        $variable.="&opcion=espacios";
                        $variable.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
                        $variable.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
                        $variable.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
                        $variable.="&tipoEstudiante=" . $this->datosEstudiante[0]['TIPO_ESTUDIANTE'];
                        $variable.="&creditosInscritos=" . $creditos;
                        $variable.="&estado_est=" . trim($this->datosEstudiante[0]['ESTADO']);
                        $variable.="&numeroSemestres=" . trim($maxSemestres);
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                    if ($tipo=='')
                    {
                        $nombreEnlace='Adicionar Espacios Acad&eacute;micos';
                    }else
                        {
                            $nombreEnlace='Adicionar Electivos Extr&iacute;nsecos';
                        }
                    echo $this->crear_enlace_adicionar($pagina, $variable, $nombreEnlace);
    }

    /**
   * Esta funcion crea el vinculo para dirigir a adicionar espacios academicos
   * @param <varchar> $pagina
   * @param <varchar> $variable
   * @param <varchar> $etiqueta_opcion
   * @return <varchar>
   */
    function crear_enlace_adicionar($pagina, $variable, $etiqueta_opcion) {
        if($this->datosEstudiante[0]['TIPO_ESTUDIANTE']=='S'){
        $enlace = "<button class='botonEnlaceInscripcionHoras' onclick='window.location=\"".$pagina.$variable."\"'>";
        $enlace.= "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/clean.png' width='25' height='25' border='0'> ".$etiqueta_opcion;
        $enlace.= "</button>";
        }else{
        $enlace = "<button class='botonEnlaceInscripcionHoras' onclick='window.location=\"".$pagina.$variable."\"'>";
        $enlace.= "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/clean.png' width='25' height='25' border='0'> ".$etiqueta_opcion;
        $enlace.= "</button>";
        }
        return $enlace;
    }//fin funcion crear_enlace_adicionar
    
    /**
     * 
     */
    function mostrarTablaPorcentajes() {
        $codEstudiante=$this->usuario;
        list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros();
         ?>
               <div class="tablet">
                   <div class='tablaCreditos'>
<!--                <table align='center' width='600' cellspacing='0' cellpadding='2'>
                    <tr class="centrar">
                        <td class=''>-->
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    echo $valor5;
                                    echo $valor6;
                                    ?>
<!--                        </td>
                    </tr>
                    <tr class="centrar">
                        <td>-->
                            <?$this->mostrar_convenciones_clasificacion();?>
<!--                        </td>
                    </tr>
                </table>-->
                   </div>
               </div> <?       
    }
    
/**
   * Funcion que muestra tabla con totales y porcentajes de créditos del estudiante
   * @param <array> $datosEstudiante
   * @return <array>
   */
  function porcentajeParametros() {

        $cadenaCreditosPlan=json_decode($this->datosEstudiante[0]['PARAMETROS'],true);       
        $totalCreditos=$cadenaCreditosPlan['total'];
        $OB=$cadenaCreditosPlan['OB'];
        $OC=$cadenaCreditosPlan['OC'];
        $EI=$cadenaCreditosPlan['EI'];
        $EE=$cadenaCreditosPlan['EE'];
        $CP=$cadenaCreditosPlan['CP'];
        $cadenaCreditosAprobados=json_decode($this->datosEstudiante[0]['CREDITOS_APROBADOS'],true);
        $totalCreditosEst=$cadenaCreditosAprobados['total'];
        $OBEst=$cadenaCreditosAprobados['OB'];
        $OCEst=$cadenaCreditosAprobados['OC'];
        $EIEst=$cadenaCreditosAprobados['EI'];
        $EEEst=$cadenaCreditosAprobados['EE'];
        $CPEst=$cadenaCreditosAprobados['CP'];
        //echo $totalCreditosEst;exit;
  
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
$columna1=15;
$columna2=16;
$columna3=16;
$columna4=16;
$columna5=37;
 if($totalCreditos>0) {
            $vista="
            <div class='fechas' ><b>Cr&eacute;ditos Ac&aacute;demicos</b></div>
            <table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                    <tr>
                          <th class='creditos centrar' width='".$columna1."%'>Clasificaci&oacute;n</th>
                          <th class='creditos centrar' width='".$columna2."%'>Total</th>
                          <th class='creditos centrar' width='".$columna3."%'>Aprobados</th>
                          <th class='creditos centrar' width='".$columna4."%'>Por Aprobar</th>
                          <th class='creditos centrar' width='".$columna5."%'>% Cursado</th>
                      </tr></table>";

            $vistaOB="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                   <tr>
                      <td class='centrar' width='".$columna1."%'>OB
                      </td>
                      <td class='centrar' width='".$columna2."%'>".$OB."
                      </td>
                      <td class='centrar' width='".$columna3."%'>".$OBEst."
                      </td>
                      <td class='centrar' width='".$columna4."%'>".$FaltanOB=$OB-$OBEst."
                      </td>
                      <td class='centrar' width='".$columna5."%'>";
            if($porcentajeOBCursado==0) {
                $vistaOB.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='porcentajes' colspan='2'> 0%
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
                           <td class='porcentajes' width='".$TotalOB=100-$porcentajeOBCursado."%'>
                           </td>
                           </table>";
            }


            $vistaOB.="</td>
                        </tr></table>
                      ";


            $vistaOC="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                   <tr>
                      <td class='centrar' width='".$columna1."%'>OC
                      </td>
                      <td class='centrar' width='".$columna2."%'>".$OC."
                      </td>
                      <td class='centrar' width='".$columna3."%'>".$OCEst."
                      </td>
                      <td class='centrar' width='".$columna4."%'>".$FaltanOC=$OC-$OCEst."
                      </td>
                      <td class='centrar' width='".$columna5."%'>";
            if($porcentajeOCCursado==0) {
                $vistaOC.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='porcentajes' colspan='2'> 0%
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
                           <td class='porcentajes' width='".$TotalOC=100-$porcentajeOCCursado."%'>
                           </td>
                           </table>";
            }
            $vistaOC.="</td>
                        </tr></table>";



            $vistaEI="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                    <tr>
                      <td class='centrar' width='".$columna1."%'>EI</td>
                      <td class='centrar' width='".$columna2."%'>".$EI."</td>
                      <td class='centrar' width='".$columna3."%'>".$EIEst."</td>
                      <td class='centrar' width='".$columna4."%'>".$FaltanEI=$EI-$EIEst."</td>
                      <td class='centrar' width='".$columna5."%'>";
            if($porcentajeEICursado==0) {
                $vistaEI.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='porcentajes' colspan='2'> 0%</td>
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
                           <td class='porcentajes' width='".$TotalEI=100-$porcentajeEICursado."%'></td>
                           </table>";
            }
            $vistaEI.="</td>
                        </tr></table>";

            $vistaEE="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                      <tr>
                      <td class='centrar' width='".$columna1."%'>EE</td>
                      <td class='centrar' width='".$columna2."%'>".$EE."</td>
                      <td class='centrar' width='".$columna3."%'>".$EEEst."</td>
                      <td class='centrar' width='".$columna4."%'>".$FaltanEE=$EE-$EEEst."</td>
                      <td class='centrar' width='".$columna5."%'>";
            if($porcentajeEECursado==0) {
                $vistaEE.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='porcentajes' colspan='2'> 0%
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
                           <td class='porcentajes' width='".$TotalEE=100-$porcentajeEECursado."%'>
                           </td>
                           </table>";
            }
            $vistaEE.="</td>
                        </tr></table>";

            $vistaTotal="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                         <tr>
                          <td class='centrar' width='".$columna1."%'>Total</td>
                          <td class='centrar' width='".$columna2."%'>".$totalCreditos."</td>
                          <td class='centrar' width='".$columna3."%'>".$totalCreditosEst."</td>
                          <td class='centrar' width='".$columna4."%'>".$Faltan=$totalCreditos-$totalCreditosEst."</td>
                          <td class='centrar' width='".$columna5."%'>";
            if($porcentajeCursado==0) {
                $vistaTotal.="
                               <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='porcentajes' colspan='2'> 0%</td>
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
                                   <td class='porcentajes' width='".$Total=100-$porcentajeCursado."%'></td>
                                </table>";
            }
            $vistaTotal.="</td>
        </tr>
                      </table>";
       
    }
        else {
            $vista="
            <table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='6'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna1."%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna2."%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna3."%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna4."%'>Por Aprobar
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna5."%'>% Cursado
                      </td>
                   </tr>
                   </table>";

            $vistaOB="<table align='center' width='100%' cellspacing='0' cellpadding='2' >
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna1."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna2."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna3."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna4."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='".$columna5."%'> 0%
                                </td>
                                </tr>
                           </table>";
                             }

        return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }//fin funcion porcentajeParametros     
  /**
   * Funcion que presenta el pie de la pagina: creditos inscritos y observaciones
   * @param <int> $creditos
   */
  function mostrarFinalTabla($creditos,$fecha) {

  ?>
<!--    <tr>
      <table class="sigma" align="center" width="80%">-->
            <?
        $this->mostrarFechasActivas($fecha,$this->datosEstudiante);
        ?>
<!--      </table>
    </tr>-->
  <?
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
     * @param type $fechas 
     */
    function mostrarFechasActivas($fechas) {
        $mes=array('01'=>'ENE','02'=>'FEB','03'=>'MAR','04'=>'ABR','05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AGO','09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DIC');
?>    
            <? if (is_array($fechas)){ ?>
            <div class="div_fechas">
            <div class="contenedor_fechas">
            <div class="tablaEspacios" align="left"><div class="fechas" ><b><?echo "FECHAS";?></b></div>
            <div style="font-size:10px; padding:5px;"><strong>Recuerde: </strong>Fechas para realizar Adiciones y Cancelaciones <?echo $this->ano."-".$this->periodo;?>:</div>
            <div class="contenedor_fechas_titulo">
            <div class="fecha_inicio"><b>Inicio</b></div>
            <div class="separador"></div>
            <div class="fecha_fin"><b>Fin</b></div>
            </div>
            <div class="contenedor_fechas_fechas">
            <?
                foreach ($fechas as $key => $fecha) {
                    if ($fecha['EVENTO']==15)
                    {
                        $tiempo = $fecha['FIN']-date('YmdHis');
                        if($tiempo<0)
                        {
                            $color='#848484';
                        }else
                            {
                                $color='#000000';
                            }
                        $inicio=substr($fecha['INICIO'], 6,2)."-".$mes[substr($fecha['INICIO'], 4,2)]."-".substr($fecha['INICIO'], 0,4)." a las ".substr($fecha['INICIO'], 8,2).":".substr($fecha['INICIO'], 10,2).":".substr($fecha['INICIO'], 12);
                        $fin=substr($fecha['FIN'], 6,2)."-".$mes[substr($fecha['FIN'], 4,2)]."-".substr($fecha['FIN'], 0,4)." a las ".substr($fecha['FIN'], 8,2).":".substr($fecha['FIN'], 10,2).":".substr($fecha['FIN'], 12);
                        ?>
                <div class="fecha_inicio" style="padding:2px; color: <?echo $color;?>"><?=$inicio?></div>
                <div class="separador"></div>
                <div class="fecha_fin" style="padding:2px;color: <?echo $color;?>"><?=$fin?></div>
                        <?
                    }
                }
                ?>
            </div>
            </div>
                <?
              }
              else
              {
                ?>
        <div><strong>** No hay fechas definidas para Adiciones **</strong></div>
                <?
              }
        ?>
        </div>
              
        <div class="contenedor_observaciones"><div class="tablaEspacios" align="left"><div class="observaciones" ><b>OBSERVACIONES</b></div>
        <?
        if (is_array($this->parametros)&&(isset($this->parametros['maximo'])||(isset($this->parametros['maxespacios']))))
        {
        $maximo=(isset($this->parametros['maxespacios'])?$this->parametros['maxespacios']:'')." espacios";
        $maxCreditos=(isset($this->parametros['maximo'])?$this->parametros['maximo']:'')." cr&eacute;ditos";
        $semestres=(isset($this->parametros['maxniveles'])?$this->parametros['maxniveles']:'');

        if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
        {?>
            <div style="font-size:11px; padding:5px;">* Puede inscribir un m&aacute;ximo de <?echo $maxCreditos?> para el per&iacute;odo.</div>
        <?}else
            {?>
            <div style="font-size:11px; padding:5px;">* Su Proyecto Curricular le permite inscribir un m&aacute;ximo de <?echo $maximo?> de hasta <?echo $semestres?> semestres.</div>
            <?}
    }
        else
        {
        ?>
        <div style="font-size:11px;padding:5px;">* No se han definido parámetros para la inscripción. Por favor, comuníquese con el Proyecto Curricular.</div>
        <?}
        ?>
        <div style="font-size:11px; padding:5px;">* Si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual (Según reglamento estudiantil).</div>
        <div style="font-size:11px; padding:5px;">* Verifique el cruce de horarios de los espacios académicos.</div>
        <div style="font-size:11px; padding:5px;">* Si el grupo no cumple con el cupo mínimo, podrá ser cancelado.</div>
        </div>
        </div>
        </div>
        <?
    }
    
  /**
   * Funcion que calcula el numero de espacios inscritos al estudiante en el periodo actual
   * @param <array> $registroGrupo (CODIGO,NOMBRE,CREDITOS,ELECTIVA,GRUPO)
   * @return <int> $suma
   */
  function calcularInscritos($datosInscritos) {
      $resultado=0;
      if(is_array($datosInscritos))
      {
        if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
        {
            foreach ($datosInscritos as $espacio)
            {
                $resultado+=$espacio['CREDITOS'];
            }
        }else
            {
                $resultado=count($datosInscritos);
            }
      }else
          {
            
          }
      return $resultado;
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
     * Funcion que retorna los datos de un espacio si existe
     * @param <array> $datos ()
     * @return <array/string> 
     */
    function buscarEspaciosInscritos($datosEstudiante) {
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritos($datosEstudiante);
        return $espaciosInscritos;
    }      

  /**
   * Funcion que permite consultar la clasificacion del espacio en el proyecto del estudiante
   * @param <int> $codEspacio
   * @return <array>
   */
  function buscarClasificacion($inscrito) {

    if(is_array ($this->datosEstudiante))
        {
            $espacios=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);
            if(trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE'])=='N')
            {
                $clasificacion=$this->asignarClasificacion($espacios,'CODIGO', $inscrito,'CODIGO');
                if($clasificacion=='')
                {
                    $espacios=json_decode($this->datosEstudiante[0]['EQUIVALECIAS'],true);
                    $clasificacion=  $this->asignarClasificacion($espacios,'CODIGO_EQUIVALENCIA', $inscrito,'CODIGO');
                }
            }elseif(trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE'])=='S')
                {
                    $letras=array('OB','OC','EI','EE','CP');
                    $numeros=array(1,2,3,4,5);
                    $clasificacion=str_replace($numeros,$letras,trim(isset($inscrito['CLASIFICACION'])?$inscrito['CLASIFICACION']:''));
                }
           
          return $clasificacion;
      }
  }
  
  function asignarClasificacion($espacios,$campo1,$inscrito,$campo2) {
        foreach ($espacios as $espacio)
        {
            if ($espacio[$campo1]==$inscrito[$campo2])
            {
                $electiva=$espacio['ELECTIVA'];
                break;
            }
            if(!isset($electiva))
            {
                $electiva='';
            }
        }
        if (trim($electiva) == 'S') //CAMBIAR POR 'S'
        {
          $clasificacion = 'Electivo';
        } elseif (trim($electiva) == 'N'){
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
      return $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }

    /**
     * Funcion que consulta los parametros para preinscripcion de plan de estudios de horas
     * @param type $datosEstudiante
     * @return type 
     */
    function consultarParametros() {
        if($this->datosEstudiante[0]['TIPO_ESTUDIANTE']== 'N')
        {
            $cadenaParametros=json_decode($this->datosEstudiante[0]['PARAMETROS'],true);     
            $parametrosHorasEspacios=$cadenaParametros['maxespacios'];
            $parametrosHorasNiveles=$cadenaParametros['maxniveles'];
            $result=array('maxespacios'=>$parametrosHorasEspacios,'maxniveles'=>$parametrosHorasNiveles);
            return $result;

        }else
            {
                $cadenaParametros=json_decode($this->datosEstudiante[0]['PARAMETROS'],true);  
                $totalCreditos=$cadenaParametros['total'];        
                $maxPeriodo=$cadenaParametros['maxcreditos'];        
                $OB=$cadenaParametros['OB'];
                $OC=$cadenaParametros['OC'];      
                $EI=$cadenaParametros['EI'];
                $EE=$cadenaParametros['EE'];
                $CP=$cadenaParametros['CP']; 
                $resultado=array('total'=>$totalCreditos, 'maximo'=>$maxPeriodo, 'OBLIGATORIOS'=>$OB, 'COMPLEMENTARIOS'=>$OC, 'ELECTIVOS-I'=>$EI, 'ELECTIVOS-E'=>$EE, 'COMPONENTE'=>$CP);
                return $resultado;
            }
}
  
    function consultarDatosEstudiante($codEstudiante){        
    $variables =array('codEstudiante'=>$codEstudiante,
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo);
    $cadena_sql=$this->sql->cadena_sql("carga", $variables);
    return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

}
  
    function consultarClasificaciones() {
        $cadena_sql=$this->sql->cadena_sql("clasificacion",'');
        $resultado_clasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado_clasificacion;
    }

    /**
     *Funcion que muestra las convenciones de los espacios de los estudiantes de créditos 
     */
     function mostrar_convenciones_clasificacion(){
    ?>

    <div class="contenedor_abreviaturas">
        <div class="tablaClasificaciones" align="left">
            <div class="observaciones" ><b>CONVENCIONES</b></div>
                <div>
                    <div class="abreviatura"><b>Abreviatura</b></div>
                    <div class="nombre"><b>Nombre</b></div>
                </div>
                <?
                foreach ($this->clasificaciones as $clasificacion)
                {
                ?>
                <div>
                    <div class="abreviatura" style="font-size:11px;text-align: center;"><?echo $clasificacion['ABREV_CLASIF']?></div>
                    <div style="font-size:11px;text-align: center;"><?echo $clasificacion['NOMBRE_CLASIF']?></div>
                </div>
                <?
                }
                ?>
        </div>
    </div>
            <?
    }//fin funcion mostrar_convenciones_clasificacion



}
?>
