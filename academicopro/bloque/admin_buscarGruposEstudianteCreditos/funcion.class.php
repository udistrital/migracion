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

class funcion_adminBuscarGruposEstudianteCreditos extends funcionGeneral {

  //Crea un objeto tema y un objeto SQL.
  private $configuracion;
  private $ano;
  private $periodo;
  private $fechas;

  /**
   * Funcion constructor que define elementos basicos para la clase
   * @param <type> $configuracion
   * @param <type> $sql
   */
  function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");

    $this->configuracion = $configuracion;
    $this->validacion = new validarInscripcion();
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->tema = $tema;
    $this->sql = $sql;

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");

    //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
    //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
    if($configuracion["dbdistribuida"]==1){
        $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
    }else{
        $this->accesoMyOracle = $this->accesoOracle;
    }

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "registro_adicionarInscripcionCreditosEstudiante";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    $cadena_sql = $this->sql->cadena_sql("periodoActivo", ''); 
    $resultado_periodo = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
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
   * Esta funcion permite presentar horarios de grupos para adicionar.
   * Utiliza los metodos buscarGrupo y enlacesAdicion
   */
  function adicionar() {
    $this->buscarGrupo();
    $this->crearEnlacesAdicion();
  }

  /**
   * Esta funcion permite presentar horarios de grupos para cambio de grupo por estudiante.
   * Utiliza los metodos  buscarGrupo y enlacesCambio
   */
  function cambiar() {
    $this->buscarGrupo();
    $this->crearEnlacesCambio();
  }


  /**
   * Esta función presenta grupos disponibles en proyectos curriculares para un espacio academico.
   * cuando el parametro es = presenta los del mismo proyecto; cuando es != presenta los de otros proyecots.
   * Utiliza los metodos consultarGrupos, crearEnlaceBuscarGrupos, presentarHorario, crearMensaje
   */
  function buscarGrupo() {
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

    $variables = array('codEspacio' => $_REQUEST['codEspacio'],
                        'codEstudiante' => $_REQUEST['codEstudiante'],
                        'codProyectoEstudiante' => $_REQUEST['codProyectoEstudiante'],
                        'planEstudioEstudiante' => $_REQUEST['planEstudioEstudiante'],
                        'estado_est' => $_REQUEST['estado_est'],
                        'grupo' => $grupo,
                        'parametro' => $_REQUEST['parametro'],
                        'ano' => $this->ano,
                        'periodo' => $this->periodo);
    $resultado_grupos=$this->consultarGrupos($variables);
    //validamos para las carreras de convenio 174
    

     if($resultado_grupos==NULL) {
            $carrera=0;
            switch ($_REQUEST['codProyectoEstudiante']) {
                case "472": $carrera='72';
                    break;
                case "473": $carrera='73';
                    break;
                case "474": $carrera='74';
                    break;
                case "477": $carrera='77';
                    break;
                case "478": $carrera='78';
                    break;
                case "479": $carrera='79';
                    break;
                case "481": $carrera='81';
                    break;
                case "485": $carrera='85';
                    break;
            }
            if($carrera != 0){
                $variables['codProyectoEstudiante']=$carrera;
                $resultado_grupos=$this->consultarGrupos($variables);
            }
        }
    //enlace grupos proyectos
    $this->crearEnlaceBuscarGrupos($variables);
    ?>

    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
      <th class='sigma_a centrar'>
        <? echo $_REQUEST['codEspacio'] . " - " . $_REQUEST['nombreEspacio']; ?>
      </th>
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
                $this->presentarHorario($resultado_grupos, $estilo, $enlace, $variables, $icono);
              ?>
              </td>
            </tr>
              <?
        }
        else
          {
            if ($_REQUEST['parametro'] == '=')
            {
              $mensaje = "No existen grupos registrados en el Proyecto.<br>Por favor comuníquese con la Coordinación.<br><font size='2' color='red'>Por favor consulte Grupos en otros Proyectos Curriculares.</font>";
            }
            else
            {
              $mensaje="No existen grupos registrados en otros Proyectos.";
            }
            $this->crearMensaje($mensaje);
          }
              ?>
          </tbody>
        </table>
        <?
    }

  /**
   * funcion que genera el horario para cada grupo y proyecto
   * Utiliza los metodos consultarNombreCarrera, crearEncabezadoHorario, generarHorario
   * @param <array> $resultado_grupos (GRUPO, CARRERA, CUPO, INSCRITOS)
   * @param <string> $estilo
   * @param <string> $enlace
   * @param <array> $variables(codEspacio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,grupo,parametro,ano,periodo)
   * @param <string> $icono
   */
  function presentarHorario($resultado_grupos,$estilo,$enlace,$variables,$icono) {
      
    $l=(isset($l)?$l:'');
    $totalGrupos=count($resultado_grupos);
    for ($g = 0; $g < $totalGrupos; $g++) {
        $horarioGrupo[$resultado_grupos[$g]['GRUPO']][]=$resultado_grupos[$g];
    }
    for ($j = 0; $j < $totalGrupos; $j++) {
      if((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'') != $resultado_grupos[$j]['CARRERA']&&$_REQUEST['parametro']=='!='){
        $permiso=$this->fechas->validar_fechas_estudiantes_otros_grupos($this->configuracion, $resultado_grupos[$j]['CARRERA']);
      }
      if ((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'') !=$resultado_grupos[$j]['CARRERA']&&$_REQUEST['parametro']=='='){
        $permiso=$this->fechas->validar_fechas_estudiante($this->configuracion, $_REQUEST['codEstudiante']);
      }
      if ($permiso=='adicion')
        {
        if ((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'')!=$resultado_grupos[$j]['CARRERA'])
          { 
          $carrera=$this->consultarNombreCarrera($resultado_grupos[$j]['CARRERA']);

          ?>
          <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
            <?
            if ($_REQUEST['parametro']=='='){//verifica si los grupos son del mismo proyecto curricular al que pertenece el estudiante
            ?>
                  <tr>
                      <td class='sigma_a centrar'><b>
                        <? echo "PROYECTO CURRICULAR: " . $carrera ?></b>
                      </td>
                  </tr>
            <?
                }
            ?>
            <tr>
              <td>
                <table class='contenidotabla'>
                  <?$this->crearEncabezadoHorario($estilo,$enlace);
          }
        if ((isset($resultado_grupos[$j-1]['GRUPO'])?$resultado_grupos[$j-1]['GRUPO']:'') != $resultado_grupos[$j]['GRUPO'])
        {
            $variables['grupo'] = $resultado_grupos[$j]['GRUPO'];
            $variables['carrera'] = $resultado_grupos[$j]['CARRERA'];
            $this->generarHorario($resultado_grupos[$j],$variables,$estilo,$icono,$horarioGrupo[$resultado_grupos[$j]['GRUPO']]);
        }
        if ($resultado_grupos[$j]['CARRERA'] != (isset($resultado_grupos[$j + 1]['CARRERA'])?$resultado_grupos[$j + 1]['CARRERA']:''))
          {
          ?>
                </table>
              </td>
            </tr>
          </table><?
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
            <? echo "No existen grupos registrados en otros Proyectos."; ?>
          </td>
        </tr>
        <?
      }
  }

  /**
   *  Funcion que presenta el enlace para buscar grupos del proyecto o de otros proyectos
   * @param <type> $datos (codEspacio,grupo,parametro,ano,periodo)
   */
  function crearEnlaceBuscarGrupos($datos) {
    ?>
  <table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
      <td class="centrar" width="50%">
        <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $ruta = "pagina=admin_buscarGruposEstudianteCreditos";
        $ruta.="&opcion=" . $_REQUEST['opcion'];
        $ruta.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $ruta.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $ruta.="&codEstudiante=" . $_REQUEST['codEstudiante'];
        $ruta.="&estado_est=" . $_REQUEST['estado_est'];
        $ruta.="&tipoEstudiante=" . $_REQUEST['tipoEstudiante'];
        $ruta.="&creditos=" . $_REQUEST['creditos'];
        $ruta.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
        $ruta.="&codEspacio=" . $_REQUEST['codEspacio'];
        $ruta.="&nombreEspacio=" . $_REQUEST['nombreEspacio'];
        $ruta.="&grupo=" . $datos['grupo'];
        $ruta.="&codProyecto=" . (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        $ruta.="&planEstudio=" . (isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'');
        $ruta.="&destino=" . $_REQUEST['destino'];
        $ruta.="&retorno=" . $_REQUEST["retorno"];
        if(isset($_REQUEST["opcionRetorno"]))$_REQUEST["opcionRetorno"];else $_REQUEST["opcionRetorno"]='';
        $ruta.="&opcionRetorno=" . $_REQUEST["opcionRetorno"];
        $variable = $ruta;
        $variable.="&parametro==";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>
            <button class="botonEnlacePreinscripcion2" onclick="window.location = 
                '<?
                    echo $pagina . $variable;
                ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos de mi Proyecto <br>Curricular</b>
            </button>
      </td>
      <td class="centrar" width="50%">
        <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = $ruta;
        $variable.="&parametro=!=";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>
            <button class="botonEnlacePreinscripcion2" onclick="window.location = 
                '<?
                    echo $pagina . $variable;
                ?>'
                    "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
                    
            </button>
      </td>
    </tr>
  </table>
    <?
  }

  /**
   * Funcion que genera el horario para cada grupo con sus respectivos enlaces
   * Utiliza los metodos consultarNombreCarrera, mostrarHorarioGrupo, crearEnlaceCambioUno
   * @param <array> $gruposProyecto (GRUPO, CARRERA, CUPO, INSCRITOS)
   * @param array $variables (codEspacio,codProyecto,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
   * @param <string> $icono
   */
  function generarHorario($gruposProyecto,$variables,$estilo,$icono,$horario) {
    ?>
    <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
      <?
      if ($_REQUEST['parametro']=='!='){//verifica si los grupos son del mismo proyecto curricular al que pertenece el estudiante
        
      ?>
            <td class='<? echo $estilo ?>'>
            <?
            $nombre_carrera=$this->consultarNombreCarrera($variables['carrera']);
            echo $nombre_carrera;?>
          </td>
      <?
      }
      ?>
      <td class='<? echo $estilo ?>'>
        <? echo $gruposProyecto['GRUPO'];?>
      </td>
      <?
        $this->mostrarHorarioGrupo($horario, $estilo);
        $cupo = $gruposProyecto['CUPO'];
      ?>
      <td class='<? echo $estilo ?>'>
        <? echo $cupo ?>
      </td>
      <?
        $inscritos = (isset($gruposProyecto['INSCRITOS'])?$gruposProyecto['INSCRITOS']:'');
        $disponibles = $cupo - $inscritos;
      ?>
      <td class='<? echo $estilo ?>'>
        <? if($disponibles>0){echo $disponibles; } else {echo "0";}  ?>
      </td>
      <?
        $variables['codProyecto']=$variables['carrera'];
        $cruce = $this->validacion->validarCruceHorario($variables);
        $this->crearEnlaceCambioUno($estilo, $disponibles, $cruce, $gruposProyecto, $icono);
      
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
      <thead class='sigma'>
        <?
        if ($_REQUEST['parametro']=='!='){//verifica si los grupos son del mismo proyecto curricular al que pertenece el estudiante
        ?>
          <th class='<? echo $estilo ?>' width="70">Proyecto<br>Curricular</th>

        <?
        }
        ?>
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
        <th class='<? echo $estilo ?>' width="80" ><? echo $mensajeEnlace ?></th>
      </thead>
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
   * Esta funcion presenta las opciones de enlaces al final de la pagina para adicion
   * Utiliza los metodos crearEnlaceHorario, crearEnlaceEspacio, crearObservaciones
   */
  function crearEnlacesAdicion() {
    ?>
      <table width="100%" border="0" align="center" >
        <tr class="bloquelateralcuerpo">
    <?
      $this->crearEnlaceHorario();
      $this->crearEnlaceEspacio();
    ?>
    </tr>
    <?
      $this->crearObservaciones();
    ?>
    </table>
    <?
  }

  /**
   * Esta funcion presenta las opciones de enlaces al final de la pagina para cambio de grupo
   * Utiliza los metodos crearEnlaceHorario, crearObservaciones
   */
  function crearEnlacesCambio() {
    ?>
          <table width="100%" border="0" align="center" >
            <tr class="bloquelateralcuerpo">
    <?
          $this->crearEnlaceHorario();
    ?>
        </tr>
    <?
          $this->crearObservaciones();
    ?>
        </table>
    <?
  }

 
  /**
   * Esta funcion presenta las observaciones que van al final de la pagina de los grupos
   */
  function crearObservaciones() {
    ?>
    <tr class="cuadro_plano centrar">
      <th colspan="2">
        <hr>Observaciones
      </th>
    </tr>
    <tr class="cuadro_plano">
      <td colspan="2">
        * Si el fondo del enlace est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta cruce o sobrecupo.
      </td>
    </tr>
    <?
 }

  /**
   * Funcion que presenta el enlace para regresar al horario del estudiante
   * @param <array> $_REQUEST (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante)
   */
  function crearEnlaceHorario() {
    ?>
    <td class="centrar" width="50%">
      <?
      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      $variable = "pagina=admin_consultarInscripcionCreditosEstudiante";
      $variable.="&opcion=mostrarConsulta";
      $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
      $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
      $variable.="&tipoEstudiante=" . $_REQUEST['tipoEstudiante'];
      $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
        <button class="botonEnlacePreinscripcion" onclick="window.location = 
            '<?
                echo $pagina . $variable;
            ?>'
            "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0">
        <br><b>Horario Estudiante</b>
        </button>
    </td>
    <?
  }

  /**
   * Funcion que presenta el enlace para cambiar el espacio seleccionado
   * @param <array> $_REQUEST (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,creditos,estado_est)
   */
  function crearEnlaceEspacio() {
  ?>
    <td class="centrar" width="50%">
      <?
      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      $variable = "pagina=".$_REQUEST['retorno'];
      $variable.="&opcion=espacios";
      $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];
      $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
      $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
      $variable.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
      $variable.="&estado_est=" . $_REQUEST['estado_est'];
      $variable.="&tipoEstudiante=" . $_REQUEST['tipoEstudiante'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
        <button class="botonEnlacePreinscripcion" onclick="window.location = 
            '<?
                echo $pagina . $variable;
            ?>'
            "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="35" height="35" border="0"><br><b>Seleccionar Otro Espacio</b>
            <br>
        </button>
     
    </td>
  <?
  }
 
  /**
   * Funcion que presenta el horario del grupo del proyecto curricular
   * @param <array> $datosGrupo (codEspacio,codProyecto,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
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
   * Funcion para crear el enlace para adicionar
   * @param <string> $estilo
   * @param <int> $disponibles
   * @param <string/int> $cruce
   * @param <array> $resultado_grupos (GRUPO, CARRERA, CUPO, INSCRITOS)
   * @param <string> $icono
   */
  function crearEnlaceCambioUno($estilo, $disponibles, $cruce, $resultado_grupos, $icono) {
      ?><td class='<? echo $estilo ?>'<? if ($disponibles <= '0' || $cruce != 'ok') {?>bgColor='#F8E0E0'<? } ?>>
      <?
      if ($cruce == 'ok') {
          
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variables = "pagina=".$_REQUEST['destino'];
                    $variables.="&opcion=inscribir";
                    $variables.="&action=".$_REQUEST['destino'];
                    $variables.="&retorno=".$_REQUEST['retorno'];
                    $variables.="&opcionRetorno=".$_REQUEST['opcionRetorno'];

                    $parametros = "&codProyecto=" .  (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
                    $parametros.="&planEstudio=" . (isset($_REQUEST['planEstudio'])? $_REQUEST['planEstudio']:'');
                    $parametros.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
                    $parametros.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
                    $parametros.="&codEstudiante=" . $_REQUEST['codEstudiante'];
                    $parametros.="&estado_est=" . trim($_REQUEST['estado_est']);
                    $parametros.="&tipoEstudiante=" . (isset($_REQUEST['tipoEstudiante'])?$_REQUEST['tipoEstudiante']:'');
                    $parametros.="&codEspacio=" . $_REQUEST['codEspacio'];
                    $parametros.="&creditos=" . (isset($_REQUEST['creditos'])?$_REQUEST['creditos']:'');
                    $parametros.="&grupo=" . $resultado_grupos['GRUPO'];
                    $parametros.="&carrera=" . $resultado_grupos['CARRERA'];
                    $parametros.="&grupoAnterior=".(isset($_REQUEST['grupo'])? $_REQUEST['grupo']:'');
                    $parametros.="&nombreEspacio=" . $_REQUEST['nombreEspacio'];

                    $variable = $variables . $parametros;

                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          
      ?>
            <?//si no hay cupo en grupos de otro proyecto
            $disponibles = $resultado_grupos['CUPO']-(isset($resultado_grupos['INSCRITOS'])?$resultado_grupos['INSCRITOS']:'');
            if ($disponibles <=0)
                $sobrecupo = array('cupo' => $resultado_grupos['CUPO'], 'inscritos' => (isset($resultado_grupos['INSCRITOS'])?$resultado_grupos['INSCRITOS']:''), 'disponibles' => $disponibles);
                else
                $sobrecupo= 'ok';
           
            if(is_array($sobrecupo))
              {
                ?>No hay cupos disponibles<?
              }
              elseif($sobrecupo=='ok')
                {
                ?>
                    <a href="<?echo $pagina.$variable ?>" >
                        <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/".$icono.".png" ?>" border="0" width="25" height="25">
                    </a>
                <?          
                }
                else
                {
                  ?>No se puede adicionar<?
                }
            ?>
          </form>
      </td>
        <?
      } else {
        ?>
        No puede adicionar por cruce con <? echo $cruce['ESPACIOCRUCE'] ?>
    </td>
    <? }
  }

  /**
   * Funcion para consultar grupos registrados del proyecto o de otros proyectos
   * @param <array> $datos (codEspacio,parametro,grupo,ano.periodo)
   * @return <array> $gruposProyecto (GRUPO, CARRERA)
   */
    function consultarGrupos($datos) {
        $cadena_sql = $this->sql->cadena_sql("grupos_proyecto", $datos);
        $gruposProyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $gruposProyecto;
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

}
?>