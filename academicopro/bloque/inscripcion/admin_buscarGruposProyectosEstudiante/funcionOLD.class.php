<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

//@ Esta clase presenta los grupos diponibles en los proyectos curriculares para un espacio academico

class funcion_adminBuscarGruposProyectosEstudiante extends funcionGeneral {

  //Crea un objeto tema y un objeto SQL.
  private $configuracion;
  private $ano;
  private $periodo;
  private $fechas;
  private $datosEstudiante;
  private $horarioEstudiante;

  /**
   * Funcion constructor que define elementos basicos para la clase
   * @param <type> $configuracion
   * @param <type> $sql
   */
  function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/administrarModulo.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->validacion = new validarInscripcion();
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->procedimientos=new procedimientos();
    //$this->tema = $tema;
    $this->sql = $sql;

     //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
    
    if ($configuracion['dbdistribuida']==1)
        {
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }
        else
            {
                $this->accesoMyOracle = $this->accesoOracle;
            }

    //Datos de sesion
    $this->formulario = "registro_inscribirEspacioInscripcionesEstudiante";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

  /**
   * Esta funcion permite presentar horarios de grupos para adicionar.
   * Utiliza los metodos buscarGrupo y enlacesAdicion
   */
  function adicionar() {
    $this->buscarGrupo();
  }

  /**
   * Esta funcion permite presentar horarios de grupos para cambio de grupo por estudiante.
   * Utiliza los metodos buscarGrupo y enlacesCambio
   */
  function cambiar() {
    $this->buscarGrupo();
  }

  /**
   * Esta función presenta grupos disponibles en proyectos curriculares para un espacio academico.
   * cuando el parametro es = presenta los del mismo proyecto; cuando es != presenta los de otros proyecots.
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,
   *                          codEspacio,nombreEspacio,creditos,estado_est,parametro,destino,retorno,opcionRetorno)
   */
  function buscarGrupo() {
?><a name="1"></a><?
      
      $_REQUEST['parametro']='=';
    if (isset($_REQUEST['grupo'])) {
      $grupo = $_REQUEST['grupo'];
    } else {
      $grupo = 0;
    }

    switch ($_REQUEST['opcion']) {
      case "validar":
        $enlace = "Adicionar";
        $icono = "clean";
        break;
      case "cambiar":
        $enlace = "Cambiar";
        $icono = "reload";
        break;
      case "cambiarGrupo":
        $enlace = "Cambiar";
        $icono = "reload";
        break;
      case "procesar":
        $enlace = "Cambiar";
        $icono = "reload";
        break;
      default:
        break;
    }
    $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);
    
    $variables = array('codEspacio' => $_REQUEST['codEspacio'],
                        'codEstudiante' => $this->datosEstudiante[0]['CODIGO'],
                        'codProyectoEstudiante' => $this->datosEstudiante[0]['COD_CARRERA'],
                        'planEstudioEstudiante' => (isset($this->datosEstudiante[0]['PLAN_ESTUDIO'])?$this->datosEstudiante[0]['PLAN_ESTUDIO']:''),
                        'estado_est' => $this->datosEstudiante[0]['ESTADO'],
                        'tipoEstudiante' => (isset($this->datosEstudiante[0]['TIPO_ESTUDIANTE'])?$this->datosEstudiante[0]['TIPO_ESTUDIANTE']:''),
                        'grupo' => $grupo,
                        'parametro' => $_REQUEST['parametro'],
                        'ano' => $this->ano,
                        'periodo' => $this->periodo);
    $espaciosInscritos=$this->buscarEspaciosInscritos();
    if(is_array($espaciosInscritos))
    {
        $espaciosInscritos=$this->restarEspacioActual($espaciosInscritos,$_REQUEST['codEspacio']);
        $this->horarioEstudiante=$this->buscarHorarioEstudiante($espaciosInscritos);
    }
    $resultado_grupos=$this->consultarGrupos($variables);
    $resultado_horarios=$this->consultarHorarios($variables);
    //enlace grupos proyectos
    ?>
<BR><BR><a class="scroll" href="#2">Ver grupos de otros proyectos</a><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/down2.png" width="15" height="10" border="0">
 <div class="tablaEspacios" align="left"><div class="nombreEspacio" ><b><? echo $_REQUEST['codEspacio'] . " - " . $_REQUEST['nombreEspacio']; ?></b></div>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
      <tr class="bloquelateralcuerpo">
      <?
      if (is_array($resultado_grupos)) {
        $estilo = "cuadro_plano sigma centrar";
        ?>
        <table width="100%" border="0" align="center" >
          <tbody>
            <tr>
              <td>
              <?
                $this->presentarHorario($resultado_grupos, $resultado_horarios,$estilo, $enlace, $variables, $icono);
              ?>
              </td>
            </tr>
              <?
        }
        else
          {
              $mensaje = "No existen grupos registrados en el Proyecto.<br> Por favor, comuníquese con la coordinación.";
              $this->crearMensaje($mensaje);
          }
              ?>
          </tbody>
        </table>
 </div>
    
        <?
    }

  /**
   * funcion que genera el horario para cada grupo y proyecto
   * @param <array> $resultado_grupos ()
   * @param <string> $estilo
   * @param <string> $enlace
   * @param <array> $estudiantes (codigo=>reporte)
   * @param <array> $variables
   * @param <string> $icono
   */
  function presentarHorario($resultado_grupos,$resultado_horarios,$estilo,$enlace,$variables,$icono) {
      $l=0;
    $totalGrupos=count($resultado_grupos);
    foreach ($resultado_grupos as $grupo) {
        if($grupo['CARRERA']==$this->datosEstudiante[0]['COD_CARRERA'])
        {
            $gruposProyecto[]=$grupo;
        }else
            {
                $gruposOtros[]=$grupo;
            }
        
    }
    if(isset($gruposProyecto)&&is_array($gruposProyecto))
    {
        $totalGruposProyecto=count($gruposProyecto);
        for ($j = 0; $j < $totalGruposProyecto; $j++)
        {
            $permiso='adicion';
          if ($permiso=='adicion')
            {
            if ((isset($gruposProyecto[$j-1]['CARRERA'])?$gruposProyecto[$j-1]['CARRERA']:'')!=$gruposProyecto[$j]['CARRERA'])
              {
              $carrera=$this->consultarNombreCarrera($gruposProyecto[$j]['CARRERA']);
              ?>
            <div width="100%" border="0" align="center" cellpadding="2px" cellspacing="1px" ><div class="nombrecarrera" ><b><? echo "PROYECTO CURRICULAR: " . $carrera ?> </div>
                <tr>
                  <td>
                    <table class='contenidotabla'>
                      <?$this->crearEncabezadoHorario($estilo,$enlace);
              }
              if (is_array($resultado_horarios))
              {
                  foreach ($resultado_horarios as $horarios) {
                      if($horarios['GRUPO']==$gruposProyecto[$j]['GRUPO'])
                      {
                          $horarioGrupo[]=$horarios;
                      }
                  }
              }else
                {
                  $horarioGrupo='';
                }
              //var_dump($horarioGrupo);
            if ((isset($gruposProyecto[$j-1]['GRUPO'])?$gruposProyecto[$j-1]['GRUPO']:'') != $gruposProyecto[$j]['GRUPO'])
            {
                $variables['grupo'] = $gruposProyecto[$j]['GRUPO'];
                $variables['carrera'] = $gruposProyecto[$j]['CARRERA'];
                $this->generarHorario($gruposProyecto[$j],$variables,$estilo,$icono,$horarioGrupo);
            }
            unset($horarioGrupo);
            if ($gruposProyecto[$j]['CARRERA'] != (isset($gruposProyecto[$j + 1]['CARRERA'])?$gruposProyecto[$j + 1]['CARRERA']:''))
              {
              ?>
                    </table>
                  </div>
      
             <?
              }
              else
                {
                }
            }
            else
              {
                $l++;
              }
        }
        if($l==$j)
          {
            ?>
            <tr>
              <td class="cuadro_plano centrar">
                <? echo "No existen grupos registrados en el Proyecto."; ?>
              </td>
            </tr>
            <?
          }
          $l=0;
    }
    if(isset($gruposOtros)&&is_array($gruposOtros))
    {
    $totalGruposOtros=count($gruposOtros);
    ?><a name="2"></a><br><div class="columna4"><center>GRUPOS DE OTROS PROYECTOS</center></div><?
        for ($j = 0; $j < $totalGruposOtros; $j++)
        {
            $permiso='adicion';
          if ($permiso=='adicion')
            {
            if ((isset($gruposOtros[$j-1]['CARRERA'])?$gruposOtros[$j-1]['CARRERA']:'')!=$gruposOtros[$j]['CARRERA'])
              {
              $carrera=$this->consultarNombreCarrera($gruposOtros[$j]['CARRERA']);
              ?>
             <div width="100%" border="0" align="center" cellpadding="2px" cellspacing="1px" ><div class="nombrecarrera" ><b><? echo "PROYECTO CURRICULAR: " . $carrera ?> </div>
              <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                <tr>
                  <td>
                    <table class='contenidotabla'>
                      <?$this->crearEncabezadoHorario('niveles centrar',$enlace);
              }
              if(is_array($resultado_horarios))
              {
                  foreach ($resultado_horarios as $horarios) {
                      if($horarios['GRUPO']==$gruposOtros[$j]['GRUPO'])
                      {
                          $horarioGrupo[]=$horarios;
                      }
                  }
              }else
                {
                  $horarioGrupo='';
                }
              //var_dump($horarioGrupo);
            if ((isset($gruposOtros[$j-1]['GRUPO'])?$gruposOtros[$j-1]['GRUPO']:'') != $gruposOtros[$j]['GRUPO'])
            {
                $variables['grupo'] = $gruposOtros[$j]['GRUPO'];
                $variables['carrera'] = $gruposOtros[$j]['CARRERA'];
                $this->generarHorario($gruposOtros[$j],$variables,$estilo,$icono,$horarioGrupo);
            }
            unset($horarioGrupo);
            if ($gruposOtros[$j]['CARRERA'] != (isset($gruposOtros[$j + 1]['CARRERA'])?$gruposOtros[$j + 1]['CARRERA']:''))
              {
              ?>
                    </table>
                  </td>
                </tr>
              </table> </div><?
              }
              else
                {
                }
            }
            else
              {
                $l++;
              }
        }
        if($l==$j)
          {
            ?>
            <tr>
              <td class="cuadro_plano centrar">
                <? echo "No existen grupos registrados en el Proyecto."; ?>
              </td>
            </tr>
            <?
          }
    }
      ?><img src="<? echo $this->configuracion['site'].$this->configuracion['grafico'] ?>/up2.png" width="15" height="10" border="0"><a class="scroll" href="#1">Volver Arriba</a><?
  }

  /**
   * Funcion que genera el horario para cada grupo con sus respectivos enlaces
   * @param <array> $gruposProyecto (GRUPO,CARRERA)
   * @param array $variables (codEspacio,codProyecto,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
   * @param <string> $icono
   * @param <array> $estudiantes
   */
  function generarHorario($gruposProyecto,$variables,$estilo,$icono,$horario) {
    $noHorario=0;
    ?>
    <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
      <td class='<? echo $estilo ?>'>
        <? echo $gruposProyecto['GRUPO'];?>
      </td>
      <?
        if (is_array($horario))
        {
            $this->mostrarHorarioGrupo($horario,$estilo);
        }else
            {
                $this->mostrarNoHorario($estilo);
                $noHorario=1;
            }
        $cupo = $gruposProyecto['CUPO'];
      ?>
      <td class='<? echo $estilo ?>'>
        <? echo $cupo ?>
      </td>
      <?
        $inscritos = $gruposProyecto['INSCRITOS'];
        $disponibles = $cupo - $inscritos;
      ?>
      <td class='<? echo $estilo ?>'>
        <? if($disponibles>0){echo $disponibles; } else {echo "0";} ?>
      </td>
      <?if(is_array($this->horarioEstudiante))
      {
        $cruce=$this->validacion->verificarCruceHorarios($this->horarioEstudiante,$horario);
      }else
        {
          $cruce='ok';
        }
        if($noHorario==0)
        {
            $this->crearEnlaceCambioUno($estilo, $disponibles, $cruce, $gruposProyecto, $icono);
        }else
            {
                $this->crearEnlaceCambioUno($estilo, 'a', 'ok', $gruposProyecto, $icono);
            }
      ?>
    </tr><?
  }

  /**
   * Funcion que presenta el encabezado del horario para los grupos del proyecto
   * @param <string> $estilo
   * @param <string> $mensajeEnlace
   */
  function crearEncabezadoHorario($estilo, $mensajeEnlace) {
    ?>
      <div class='sigma'>
        <th class='<? echo $estilo ?>' width="50">Grupo</th>
        <th class='<? echo $estilo ?>' width="60">Lun</th>
        <th class='<? echo $estilo ?>' width="60">Mar</th>
        <th class='<? echo $estilo ?>' width="60">Mie</th>
        <th class='<? echo $estilo ?>' width="60">Jue</th>
        <th class='<? echo $estilo ?>' width="60">Vie</th>
        <th class='<? echo $estilo ?>' width="60">S&aacute;b</th>
        <th class='<? echo $estilo ?>' width="60">Dom</th>
        <th class='<? echo $estilo ?>' width="40">Cupo</th>
        <th class='<? echo $estilo ?>' width="80">Disponibles</th>
        <th class='<? echo $estilo ?>' ><? echo $mensajeEnlace ?></th>
      </div>
    <?
  }

  /**
   * Funcion que presenta mensaje cuando no hay grupos
   * @param <string> $mensaje
   */
  function crearMensaje($mensaje) {
  ?>
    <tr>
      <td class="cuadro_plano centrar">
        <? echo $mensaje; ?>
      </td>
    </tr>
  <?
  }


  /**
   * Esta funcion presenta las observaciones que van al final de la pagina de los grupos
                 */
  function crearObservaciones() {
    ?>

        <div class="tablaobservaciones" align="center"><div class="nombreobservacion" ><b><?echo "OBSERVACIONES";?></b></div> 
        * Si el fondo del enlace est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta cruce o sobrecupo.

        </div>
    <?
  }

  /**
   * Funcion que presenta el horario del grupo del proyecto curricular
   * @param <array> $datosGrupo (codEspacio,codProyecto,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
   * @param <int> $columnas
   */
  function mostrarHorarioGrupo($resultado_horarios, $estilo) {
      for ($i = 1; $i < 8; $i++) {
        ?><td class='<? echo $estilo ?>'><?
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
                  }
  }

  /**
   * Funcion que presenta mensaje cuando no hay horario registrado
   * @param <string> $estilo
   */
  function mostrarNoHorario($estilo) {
        ?><td class='<? echo $estilo ?>' colspan="7">NO HAY HORARIO REGISTRADO</td><?
  }

  /**
   * Funcion que presenta el boton de enlace para cambiar o adicionar
   * @param <string> $estilo
   * @param <int> $disponibles
   * @param <string/int> $cruce
   * @param <array> $resultado_grupos (GRUPO,CARRERA)
   * @param <string> $icono
   */
  function crearEnlaceCambioUno($estilo, $disponibles, $cruce, $resultado_grupos, $icono) {
      if(isset($_REQUEST['grupo'])&&$_REQUEST['grupo']!=0)
      {
          $mensajeEnlace='cambiar';
      }else
            {
                $mensajeEnlace='adicionar';
                $_REQUEST['grupo']=0;
            }
      ?><td class='<? echo $estilo ?>'<? if ($disponibles <= '0' || $cruce != 'ok') {?>bgColor='#F8E0E0'<? } ?>>
      <?
      if ($cruce == 'ok') {
            //si no hay cupo en grupo
            if($disponibles==='a')
            {
              ?>No se puede <?echo $mensajeEnlace;
            }elseif($disponibles<=0)
              {
                ?>No hay cupos disponibles<?
              }
              elseif($disponibles>0)
                {
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variables = "pagina=".$_REQUEST['destino'];
                    $variables.="&opcion=inscribirEspacio";
                    $variables.="&action=".$_REQUEST['destino'];
                    $variables.="&retorno=".$_REQUEST['retorno'];
                    $variables.="&opcionRetorno=".$_REQUEST['opcionRetorno'];

                    $parametros = "&codProyecto=" .  $_REQUEST['codProyecto'];
                    $parametros.="&planEstudio=" . $_REQUEST['planEstudio'];
                    $parametros.="&codEstudiante=" . $_REQUEST['codEstudiante'];
                    $parametros.="&codProyectoEstudiante=" . isset($_REQUEST['codProyectoEstudiante']);
                    $parametros.="&planEstudioEstudiante=" . isset($_REQUEST['planEstudioEstudiante']);
                    $parametros.="&estado_est=" . trim($_REQUEST['estado_est']);
                    $parametros.="&codEspacio=" . $_REQUEST['codEspacio'];
                    $parametros.="&nombreEspacio=" . $_REQUEST['nombreEspacio'];
                    $parametros.="&creditos=" . (isset($_REQUEST['creditos'])?$_REQUEST['creditos']:'');
                    $parametros.="&grupo=" . $resultado_grupos['GRUPO'];
                    $parametros.="&cupo=" . $resultado_grupos['CUPO'];
                    $parametros.="&carrera=" . $resultado_grupos['CARRERA'];
                    $parametros.="&grupoAnterior=".$_REQUEST['grupo'];

                    $variable = $variables . $parametros;

                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable= $this->cripto->codificar_url($variable, $this->configuracion);

                    ?>     <button class="botonEnlacePreinscripcion" onclick="window.location = 
                                            '<?
                                                echo $pagina . $variable;
                                            ?>'
                                            "><center><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/clean.png" ?>" border="0" width="25" height="25">
                                        </button>					
                      <?
                }else
                    {
                        ?>No se puede <?echo $mensajeEnlace;
                    }
            ?>
      </td>
        <?
      } else {
        ?>
        No se puede <?echo $mensajeEnlace;?>. Presenta cruce.
    </td>
    <? }
  }

   function consultarDatosEstudiante($codEstudiante){        
    $variables =array('codEstudiante'=>$codEstudiante,
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo);
    $cadena_sql=$this->sql->cadena_sql("carga", $variables);
    return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    
    }
  
  /**
   * Funcion que permite consultar el nombre del proyecto curricular
   * @param <int> $codProyecto Codigo del proyecto curricular
   * @return <string> $resultado_carrera Nombre del proyecto
   */
   function consultarNombreCarrera($codProyecto) {
    $cadena_sql = $this->sql->cadena_sql("nombre_carrera", $codProyecto);
    $resultado_carrera = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    return $resultado_carrera[0]['NOMBRE'];
    }

  /**
   * Funcion para consultar grupos registrados del proyecto o de otros proyectos
   * @param <array> $datos (codEspacio,parametro,codProyecto,grupo,ano.periodo)
   * @return <array> $gruposProyecto (GRUPO, CARRERA)
   */
  function consultarGrupos($datos) {
    $cadena_sql = $this->sql->cadena_sql("grupos_proyecto", $datos);
    $gruposProyecto = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    return $gruposProyecto;
  }

  /**
   * Funcion para consultar horarios de grupos registrados del proyecto o de otros proyectos
   * @param <array> $datos (codEspacio,parametro,codProyecto,grupo,ano.periodo)
   * @return <array> $gruposProyecto (GRUPO, CARRERA)
   */
  function consultarHorarios($datos) {
    $cadena_sql = $this->sql->cadena_sql("horarios_proyecto", $datos);
    $gruposProyecto = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    return $gruposProyecto;
  }

  /**
   * Funcion para consultar los horarios de un grupo
   * @param type $datosGrupo
   * @return type 
   */
  function consultarHorario($datosGrupo) {
    $cadena_sql_horarios = $this->sql->cadena_sql("horario_grupos", $datosGrupo);
    return $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql_horarios, "busqueda");
}  
  
  function buscarHorarioEstudiante($espaciosInscritos) {
        $horarioEstudiante=$this->procedimientos->buscarHorario($espaciosInscritos);
        return $horarioEstudiante;
  }
    
    /**
     * Funcion que retorna los datos de un espacio si existe
     * @param <array> $datos ()
     * @return <array/string> 
     */
   function buscarEspaciosInscritos() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO']);                           
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritos($datosEstudiante); 
        return $espaciosInscritos;
    }

    /**
     * Funcion que quita el espacio actual de los inscritos
     * @param array $espaciosInscritos
     * @return array
     */
    function restarEspacioActual($espaciosInscritos,$codEspacio) {
        foreach ($espaciosInscritos as $fila => $espacio)
        {
            if($espacio['CODIGO']==$codEspacio)
            {
                unset ($espaciosInscritos[$fila]);
            }
        }
        return $espaciosInscritos;
    }
  }
?>