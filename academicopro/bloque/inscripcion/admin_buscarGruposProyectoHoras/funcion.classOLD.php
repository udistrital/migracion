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

class funcion_adminBuscarGruposProyectoHoras extends funcionGeneral {

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
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");

    $this->configuracion = $configuracion;
    $this->validacion = new validarInscripcion();
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    //$this->tema = $tema;
    $this->sql = $sql;

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "registro_adicionarEspacioEstudianteCoorHoras";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    $cadena_sql = $this->sql->cadena_sql("periodoActivo", ''); 
    $resultado_periodo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

  /**
   * Esta funcion permite presentar horarios de grupos para adicionar.
   * Utiliza los metodos buscarGrupo y enlacesAdicion
   */
  function adicionar() {
    $this->buscarEstudiante();
    $this->buscarGrupo();
    $this->crearEnlacesAdicion();
  }

  /**
   * Esta funcion permite presentar horarios de grupos para cambio de grupo por estudiante.
   * Utiliza los metodos buscarGrupo y enlacesCambio
   */
  function cambiar() {
    $this->buscarEstudiante();
    $this->buscarGrupo();
    $this->crearEnlacesCambio();
  }

  /**
   * Esta funcion permite presentar horarios de grupos para cambio de grupo directo por grupo.
   * Utiliza los metodos buscarGrupo y enlacesCambioGrupo
   */
  function cambiarGrupo() {
//    foreach ($_REQUEST as $key => $value) {
//      echo $key."=>".$value."<br>";
//    }
//    exit;
    $this->buscarEstudiante();
    $this->buscarGrupo();
    $this->crearEnlacesCambioGrupo();
  }

  function buscarEstudiante() {
    $nombreEstudiante=$this->consultarDatosEstudiante($_REQUEST['codEstudiante']);
    ?>
<table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
  <tr class="bloquelateralcuerpo">
    <td align="center">
      Estudiante: <?echo $nombreEstudiante[0]['NOMBRE'];?>
    </td>
  </tr>
</table>
    <?
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
   * Esta función presenta grupos disponibles en proyectos curriculares para un espacio academico.
   * cuando el parametro es = presenta los del mismo proyecto; cuando es != presenta los de otros proyecots.
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,
   *                          codEspacio,nombreEspacio,creditos,estado_est,parametro,destino,retorno,opcionRetorno)
   */
  function buscarGrupo($estudiantes="") {
    if (isset($_REQUEST['grupo'])) {
      $grupo = $_REQUEST['grupo'];
    } else {
      $grupo = 0;
    }
    if (isset($_REQUEST['id_grupo'])) {
      $id_grupo = $_REQUEST['id_grupo'];
    } else {
      $id_grupo = 0;
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
                        'codProyecto' => $_REQUEST['codProyecto'],
                        'codEstudiante' => (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:''),
                        'codProyectoEstudiante' => (isset($_REQUEST['codProyectoEstudiante'])?$_REQUEST['codProyectoEstudiante']:''),
                        'planEstudioEstudiante' => (isset($_REQUEST['planEstudioEstudiante'])?$_REQUEST['planEstudioEstudiante']:''),
                        'estado_est' => (isset($_REQUEST['estado_est'])?$_REQUEST['estado_est']:''),
                        'grupo' => $grupo,
                        'id_grupo' => $id_grupo,
                        'parametro' => $_REQUEST['parametro'],
                        'ano' => $this->ano,
                        'periodo' => $this->periodo);
    $resultado_grupos=$this->consultarGrupos($variables);
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
                $this->presentarHorario($resultado_grupos, $estilo, $enlace, $estudiantes, $variables, $icono);
              ?>
              </td>
            </tr>
              <?
        }
        else
          {
            if ($_REQUEST['parametro'] == '=')
            {
              $mensaje = "No existen grupos registrados en el Proyecto.<br><font size='2' color='red'>Por favor consulte Grupos en otros Proyectos Curriculares.</font>";
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
   * @param <array> $resultado_grupos ()
   * @param <string> $estilo
   * @param <string> $enlace
   * @param <array> $estudiantes (codigo=>reporte)
   * @param <array> $variables
   * @param <string> $icono
   */
  function presentarHorario($resultado_grupos,$estilo,$enlace,$estudiantes,$variables,$icono) {
      $l=0;
    for ($j = 0; $j < count($resultado_grupos); $j++) {
      if((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'')!=$resultado_grupos[$j]['CARRERA']&&$_REQUEST['parametro']=='!='){
        $permiso=$this->fechas->validar_fechas_coordinador_otros_grupos($this->configuracion, $resultado_grupos[$j]['CARRERA']);
      }
      if ((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'')!=$resultado_grupos[$j]['CARRERA']&&$_REQUEST['parametro']=='='){
        $permiso=$this->fechas->validar_fechas_grupo_coordinador($this->configuracion, $resultado_grupos[$j]['CARRERA']);
      }
      if ($permiso=='adicion')
        {
        if ((isset($resultado_grupos[$j-1]['CARRERA'])?$resultado_grupos[$j-1]['CARRERA']:'')!=$resultado_grupos[$j]['CARRERA'])
          {
          $carrera=$this->consultarNombreCarrera($resultado_grupos[$j]['CARRERA']);
          ?>
          <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
            <tr>
              <td class='sigma_a centrar'><b>
                <? echo "PROYECTO CURRICULAR: " . $carrera ?></b>
              </td>
            </tr>
            <tr>
              <td>
                <table class='contenidotabla'>
                  <?$this->crearEncabezadoHorario($estilo,$enlace,$estudiantes);
          }
        $variables['grupo'] = $resultado_grupos[$j]['GRUPO'];
        $variables['id_grupo'] = $resultado_grupos[$j]['ID_GRUPO'];
        $variables['carrera'] = $resultado_grupos[$j]['CARRERA'];
        $this->generarHorario($resultado_grupos[$j],$variables,$estilo,$icono,$estudiantes);
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
   * Funcion para consultar grupos registrados del proyecto o de otros proyectos
   * @param <array> $datos (codEspacio,parametro,codProyecto,grupo,ano.periodo)
   * @return <array> $gruposProyecto (GRUPO, CARRERA)
   */
  function consultarGrupos($datos) {
    $cadena_sql = $this->sql->cadena_sql("grupos_proyecto", $datos);
    $gruposProyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    return $gruposProyecto;
  }

  /**
   *  Funcion que presenta el enlace para buscar grupos del proyecto o de otros proyectos
   * @param <type> $datos (codEspacio,codProyecto,grupo,parametro,ano,periodo)
   */
  function crearEnlaceBuscarGrupos($datos) {
    ?>
  <table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
      <td class="centrar" width="50%">
        <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $ruta = "pagina=admin_buscarGruposProyectoHoras";
        $ruta.="&opcion=" . $_REQUEST['opcion'];
        $ruta.="&codProyecto=" . $_REQUEST['codProyecto'];
        $ruta.="&planEstudio=" . $_REQUEST['planEstudio'];
        $ruta.="&codProyectoEstudiante=" . (isset($_REQUEST['codProyectoEstudiante'])?$_REQUEST['codProyectoEstudiante']:'');
        $ruta.="&planEstudioEstudiante=" . (isset($_REQUEST['planEstudioEstudiante'])?$_REQUEST['planEstudioEstudiante']:'');
        if((isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'')!="")
        {
          $ruta.="&codEstudiante=" . $_REQUEST['codEstudiante'];
        }
        else
        {
          $estudiantes=$this->verificarCruceGrupo($datos);
          $i=0;
          foreach ($estudiantes as $key => $value) {
            $ruta.="&codEstudiante".$i."=".$key;
            $i++;
          }
        }
        $ruta.="&estado_est=" . (isset($_REQUEST['estado_est'])?$_REQUEST['estado_est']:'');
        $ruta.="&creditos=" . (isset($_REQUEST['creditos'])?$_REQUEST['creditos']:'');
        $ruta.="&codEspacio=" . $_REQUEST['codEspacio'];
        $ruta.="&nombreEspacio=" . $_REQUEST['nombreEspacio'];
        $ruta.="&grupo=" . $datos['grupo'];
        $ruta.="&id_grupo=" . $datos['id_grupo'];
        $ruta.="&destino=" . $_REQUEST['destino'];
        $ruta.="&retorno=" . $_REQUEST["retorno"];
        $ruta.="&opcionRetorno=" . (isset($_REQUEST["opcionRetorno"])?$_REQUEST["opcionRetorno"]:'');
        $variable = $ruta;
        $variable.="&parametro==";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>
        <a href="<?= $pagina . $variable ?>" >
          <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del Proyecto <br>Curricular</b>
        </a>
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
        <a href="<?= $pagina . $variable ?>" >
          <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
        </a>
      </td>
    </tr>
  </table>
    <?
  }

  /**
   * Funcion que genera el horario para cada grupo con sus respectivos enlaces
   * @param <array> $gruposProyecto (GRUPO,CARRERA)
   * @param array $variables (codEspacio,codProyecto,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
   * @param <string> $icono
   * @param <array> $estudiantes
   */
  function generarHorario($gruposProyecto,$variables,$estilo,$icono,$estudiantes) {
      
    ?>
    <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
      <td class='<? echo $estilo ?>'>
        <? echo $gruposProyecto['GRUPO'];?>
      </td>
      <?
        $this->mostrarHorarioGrupo($variables, $estilo);
        $cupo = $this->validacion->buscarCupo($variables);
      ?>
      <td class='<? echo $estilo ?>'>
        <? echo $cupo ?>
      </td>
      <?
        $inscritos = $this->validacion->buscarInscritos($variables);
        $disponibles = $cupo - $inscritos;
      ?>
      <td class='<? echo $estilo ?>'>
        <? echo $disponibles ?>
      </td>
      <?
      $variables['codProyecto']=$variables['carrera'];
      if($estudiantes=="")
      {          
        $cruce = $this->validacion->validarCruceHorario($variables);
        $this->crearEnlaceCambioUno($estilo, $disponibles, $cruce, $gruposProyecto, $icono);
      }
      else
        {
        $cruce=$this->verificarCruceGrupo($variables);
        $numeroSeleccionados=$this->verificarSeleccionados();
        $numeroEstudiantesCruce=$this->contarNumeroEstudiantesCruce($cruce);
        if($numeroEstudiantesCruce>0)
        {
          $estudiantesCruce=$this->verificarEstudiantesCruce($cruce);
//                    $cruce=$this->verificarEstudiantesSinCruce($cruce);
          }else{
              $estudiantesCruce='';
          }
//                  if ($cruce == FALSE) {
            $this->crearEnlaceCambioSeleccion($estilo, $disponibles, $cruce, $gruposProyecto, $icono, $estudiantesCruce, $numeroEstudiantesCruce,$numeroSeleccionados);
//                  } else {
//                   }
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
      <thead class='sigma'>
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
   * Esta funcion presenta las opciones de enlaces al final de la pagina para cambio de grupo por grupo
   */
  function crearEnlacesCambioGrupo() {
    ?>
          <table width="100%" border="0" align="center" >
            <tr class="bloquelateralcuerpo">
    <?
          $this->crearEnlaceRegresar();
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
      $variable = "pagina=admin_consultarInscripcionEstudianteCoorHoras";
      $variable.="&opcion=mostrarConsulta";
      $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
//      $variable.="&nombreProyecto=" . $_REQUEST['nombreProyecto'];
      $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
      $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
      $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
      $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
      <a href="<?= $pagina . $variable ?>" >
        <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0">
        <br><b>Horario Estudiante</b>
      </a>
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
      $variable = "pagina=admin_buscarEspacioEstudianteCoorHoras";
      $variable.="&opcion=espacios";
      $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
      $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
      $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];
      $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
      $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
      $variable.="&creditosInscritos=" . $_REQUEST['creditos'];
      $variable.="&estado_est=" . $_REQUEST['estado_est'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
      <a href="<?= $pagina . $variable ?>" >
        <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="35" height="35" border="0"><br><b>Seleccionar Otro Espacio</b>
      </a>
    </td>
  <?
  }

  /**
   * Esta funcion presenta el enlace para regresar consulta de estudiantes inscritos en el grupo
   * @param <array> $_REQUEST (codProyecto,planEstudio,codEspacio,grupo)
   */
  function crearEnlaceRegresar() {
  ?>
    <td class="centrar" width="50%">
      <?
      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      $variable = "pagina=admin_estudiantesInscritosGrupoCoorHoras";
      $variable.="&opcion=verGrupo";
      $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
      $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
      $variable.="&codEspacio=" . $_REQUEST['codEspacio'];
      $variable.="&grupo=" . $_REQUEST['grupo'];
      $variable.="&id_grupo=" . $_REQUEST['id_grupo'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
      <a href="<?= $pagina . $variable ?>" >
        <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="35" height="35" border="0"><br><b>Regresar</b>
      </a>
    </td>
  <?
  }

  /**
   * Funcion que presenta el horario del grupo del proyecto curricular
   * @param <array> $datosGrupo (codEspacio,codProyecto,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,grupo,parametro,ano,periodo,carrera)
   * @param <string> $estilo
   * @param <int> $columnas
   */
  function mostrarHorarioGrupo($datosGrupo, $estilo) {

    $cadena_sql_horarios = $this->sql->cadena_sql("horario_grupos", $datosGrupo);
    $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horarios, "busqueda");
    for ($i = 1; $i < 8; $i++) {
      ?><td class='<? echo $estilo ?>'><?
                                    for ($k = 0; $k < count($resultado_horarios); $k++) {

                                        if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:'') . "<br>Edificio: " . (isset($resultado_horarios[$l]['EDIFICIO'])?$resultado_horarios[$l]['EDIFICIO']:'') . "<br>Sal&oacute;n:" . (isset($resultado_horarios[$l]['SALON'])?$resultado_horarios[$l]['SALON']:'');
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                                        }
    ?></td><?
                  }
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

  /**
    * Esta funcion permite presentar horarios de grupos para cambio de grupo por seleccion por grupo.
    */
   function validarCruce() {
      $this->verificarSeleccionados();
      $this->buscarGrupo($_REQUEST);
      $this->crearEnlacesCambioGrupo();
    }

  /**
   * Funcion que verifica si se han seleccionado estudiantes para cambio
   * @return int numero de estudaintes seleccionados
   */
  function verificarSeleccionados() {
    $i=0;
    foreach ($_REQUEST as $key => $value) {
      if (strstr($key,'codEstudiante'))
        {
          $i++;
        }
    }
    
      if($i<=0)
        {
          echo "<script>alert ('Debe seleccionar al menos un estudiante para el cambio de grupo');</script>";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable="pagina=admin_estudiantesInscritosGrupoCoorHoras";
          $variable.="&opcion=verGrupo";
          $variable.="&codProyecto=".$_REQUEST['codProyecto'];
          $variable.="&planEstudio=".$_REQUEST['planEstudio'];
          $variable.="&codEspacio=".$_REQUEST['codEspacio'];
          $variable.="&grupo=".$_REQUEST['grupo'];
          $variable.="&id_grupo=".$_REQUEST['id_grupo'];

          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('".$pagina.$variable."')</script>";
          exit;
        }
      else
        {
          return $i;
        }
  }

  /**
   *
   * @param <string> $estilo
   * @param <int> $disponibles
   * @param <string/int> $cruce
   * @param <array> $resultado_grupos (GRUPO,CARRERA)
   * @param <string> $icono
   */
  function crearEnlaceCambioUno($estilo, $disponibles, $cruce, $resultado_grupos, $icono) {
      ?><td class='<? echo $estilo ?>'<? if ($disponibles <= '0' || $cruce != 'ok') {?>bgColor='#F8E0E0'<? } ?>>
      <?
      if ($cruce == 'ok') {
         
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $_REQUEST['destino'] ?>'>
            <input type="hidden" name="codProyecto" value="<? echo $_REQUEST['codProyecto'] ?>">
            <input type="hidden" name="planEstudio" value="<? echo $_REQUEST['planEstudio'] ?>">
            <input type="hidden" name="codProyectoEstudiante" value="<? echo $_REQUEST['codProyectoEstudiante'] ?>">
            <input type="hidden" name="planEstudioEstudiante" value="<? echo $_REQUEST['planEstudioEstudiante'] ?>">
            <input type="hidden" name="codEstudiante" value="<? echo $_REQUEST['codEstudiante'] ?>">
            <input type="hidden" name="estado_est" value="<? echo $_REQUEST['estado_est'] ?>">
            <input type="hidden" name="codEspacio" value="<? echo $_REQUEST['codEspacio'] ?>">
            <input type="hidden" name="creditos" value="<? echo $_REQUEST['creditos'] ?>">
            <input type="hidden" name="nivel" value="<? echo $_REQUEST['nivel'] ?>">
            <input type="hidden" name="grupo" value="<? echo $resultado_grupos['GRUPO'] ?>">
            <input type="hidden" name="id_grupo" value="<? echo $resultado_grupos['ID_GRUPO'] ?>">
            <input type="hidden" name="hor_alternativo" value="<? echo (isset($resultado_grupos['HOR_ALTERNATIVA'])?$resultado_grupos['HOR_ALTERNATIVA']:0) ?>">
            <input type="hidden" name="carrera" value="<? echo $resultado_grupos['CARRERA'] ?>">
            <input type="hidden" name="id_grupoAnterior" value="<? echo (isset($_REQUEST['id_grupo'])?$_REQUEST['id_grupo']:0) ?>">
            <input type="hidden" name="grupoAnterior" value="<? echo (isset($_REQUEST['grupo'])?$_REQUEST['grupo']:0) ?>">
            <input type="hidden" name="opcion" value="inscribir">
            <input type="hidden" name="action" value="<? echo $_REQUEST['destino'] ?>">
            <input type="hidden" name="retorno" value="<? echo $_REQUEST['retorno'] ?>">
            <input type="hidden" name="opcionRetorno" value="<? echo $_REQUEST['opcionRetorno'] ?>">
            <?//si no hay cupo en grupos de otro proyecto
            $datos=$_REQUEST;
            $datos['id_grupo']=$resultado_grupos['ID_GRUPO'];
            $sobrecupo=$this->validacion->validarSobrecupo($datos);
            unset ($datos);
            if(is_array($sobrecupo))
              {
                ?>No se puede adicionar por Sobrecupo<?
              }
              elseif($sobrecupo=='ok')
                {
                  ?><input type="image" name="adicion" width="30" height="30" src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/<? echo $icono ?>.png" ><?
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
   *
   * @param <string> $estilo
   * @param <int> $disponibles
   * @param <array> $cruce ('codigo de cada estudiante seleccionado', 'valor del cruce:ok/codigo espacio')
   * @param <array> $resultado_grupos (GRUPO,CARRERA)
   * @param <string> $icono
   * @param <array> $estudiantesCruce ('codigo de cada estudiante con cruce', 'codigo del espacio')
   * @param <int> $numeroEstudiantesCruce
   */
  function crearEnlaceCambioSeleccion($estilo,$disponibles,$cruce,$resultado_grupos,$icono,$estudiantesCruce,$numeroEstudiantesCruce,$seleccionados) {
    ?><script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/overlib/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
      <td class='<? echo $estilo ?>'<?  $this->consultarSeleccionadosCruce($seleccionados,$numeroEstudiantesCruce,$estudiantesCruce,$disponibles);?>>
          <div class="centrar">
          <span id="overlibBox" width="200" ></span>
          </div>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $_REQUEST['destino'] ?>'>
          <input type="hidden" name="codProyecto" value="<? echo $_REQUEST['codProyecto'] ?>">
          <input type="hidden" name="planEstudio" value="<? echo $_REQUEST['planEstudio'] ?>">
          <input type="hidden" name="codEspacio" value="<? echo $_REQUEST['codEspacio'] ?>">
          <input type="hidden" name="creditos" value="<? echo (isset($_REQUEST['creditos'])?$_REQUEST['creditos']:'') ?>">
          <input type="hidden" name="grupo" value="<? echo $resultado_grupos['GRUPO'] ?>">
          <input type="hidden" name="id_grupo" value="<? echo $resultado_grupos['ID_GRUPO'] ?>">
          <input type="hidden" name="carrera" value="<? echo $resultado_grupos['CARRERA'] ?>">
          <input type="hidden" name="grupoAnterior" value="<? echo $_REQUEST['grupo'] ?>">
          <input type="hidden" name="id_grupoAnterior" value="<? echo $_REQUEST['id_grupo'] ?>">
          <input type="hidden" name="opcion" value="inscribir">
          <input type="hidden" name="action" value="<? echo $_REQUEST['destino'] ?>">
          <input type="hidden" name="retorno" value="<? echo $_REQUEST['retorno'] ?>">
          <input type="hidden" name="opcionRetorno" value="<? echo $_REQUEST['opcionRetorno'] ?>">
          <?$i=0;
          foreach ($cruce as $key => $value) {
              if(is_array($value)){
                ?><input type="hidden" name="codEstudiante<?echo $i;?>" value="<? echo $key.'-'.$value['ESPACIOCRUCE'];?>"><?
              }else{
                ?><input type="hidden" name="codEstudiante<?echo $i;?>" value="<? echo $key.'-'.$value;?>"><?
              }
            $i++;
          }
          $datos=$_REQUEST;
          $datos['grupo']=$resultado_grupos['GRUPO'];
          $this->crearEnlace($datos, $disponibles, $seleccionados, $numeroEstudiantesCruce,$icono);
          unset ($datos);
          ?>
        </form>

    </td>
      <?
  }

  /**
   * Funcion que define el enlace para cambio de acuerdo al cruce, cupo y seleccionados
   * @param <array> $resultado_grupos (GRUPO, CARRERA)
   * @param <int> $disponibles
   * @param <int> $seleccionados
   * @param <int> $numeroEstudiantesCruce
   */
  function crearEnlace($resultado_grupos,$disponibles,$seleccionados,$numeroEstudiantesCruce,$icono) {
    $sobrecupo=$this->validacion->validarSobrecupo($resultado_grupos);
    $carrera=$this->validacion->consultarCupo($resultado_grupos);
    if($sobrecupo!="ok" || (($disponibles-$seleccionados+$numeroEstudiantesCruce)<0 && $resultado_grupos['codProyecto']!=$carrera[0]['CARRERA']))
      {
        ?>No se puede cambiar por sobrecupo<?
      }
      elseif($seleccionados<=$numeroEstudiantesCruce)
      {
        ?>No se puede cambiar por cruce<?
      }
      else
        {
          ?><input type="image" name="adicion" width="30" height="30" src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/<? echo $icono ?>.png" ><?
        }

  }

  /**
   * Funcion que permite consultar los estudiantes seleccionados, los que presentan cruce y define el color de fondo del enlace
   * @param <array> $estudiantesCruce (codEstudiante=>codEspacio)
   * @param <int> $disponibles
   * @param <int> $numeroEstudiantesCruce
   */
  function consultarSeleccionadosCruce($seleccionados,$numeroEstudiantesCruce,$estudiantesCruce,$disponibles) {
    ?>onmouseover="return overlib('<?echo "Seleccionados: ".$seleccionados."<br>Estudiantes con cruce: ".$numeroEstudiantesCruce."<br>";
    if (is_array($estudiantesCruce))
      {
        foreach ($estudiantesCruce as $key => $value)
        {echo $key." -> ".$value."<br>";}
      }
      ?>',FGCOLOR,'#ffd038',WIDTH,190);" onmouseout="return nd();"<?
      if ($disponibles <= '0' || $numeroEstudiantesCruce>0 ||( $disponibles-$seleccionados+$numeroEstudiantesCruce)<0)
      {
        ?>bgColor='#F8E0E0'<?
      }
  }

  /**
   *
   * @param <type> $datosGrupo
   */
  function verificarEstudiantesSeleccionados($datosGrupo) {
   $seleccionados=$this->verificarCruceGrupo($datosGrupo);
   $i=0;
   foreach ($seleccionados as $key => $value) {
     $_REQUEST['codEstudiante'.$i]=$key;
     $i++;
  }
  return;
  }

  /**
   *
   * @param <array> $datosGrupo (grupo))
   * @return <array> $estudiante ('codigo del estudiante', 'valor cruce')
   */
  function verificarCruceGrupo($datosGrupo) {
$i = 0;
$estudiantes = array();
$cruzados = array();
foreach ($_REQUEST as $key => $value) {
  if (strstr($key, 'codEstudiante')) {
    $estudiantes[$i] = array('codEspacio' => $_REQUEST['codEspacio'],
                             'codProyecto' => $datosGrupo['codProyecto'],
                             'id_grupo' => $datosGrupo['id_grupo'],
                             'codEstudiante' => $value);
    $valor[$value] = $this->validacion->validarCruceHorario($estudiantes[$i]);
    $estudiante[$value] = $valor[$value];
    $i++;
  } else {
    
  }
}
return $estudiante;
}

  /**
   * Funcion que realiza el conteo de estudiantes con cruce
   * @param <array> $estudiantes ('codigo del estudiante', 'valor cruce')
   * @return <int> $i numero de estudiantes con cruce
   */
  function contarNumeroEstudiantesCruce($estudiantes) {
    $i=0;
    foreach ($estudiantes as $key => $value) {
      if ($value!="ok"){$i++;}
    }
    return $i;
  }

  /**
   * Funcion que extrae estudiantes con cruce de los seleccionados
   * @param <array> $estudiantes ('codigo del estudiante', 'valor cruce')
   * @return <array> $cruzados ('codigo del estudiante', 'codigo espacio')
   */
  function verificarEstudiantesCruce($estudiantes) {
    $i=0;
    foreach ($estudiantes as $key => $value) {
      if ($value!="ok")
        {
          $cruzados[$key]=$value['ESPACIOCRUCE'];
        }
    }
    return $cruzados;
  }

  /**
   * Funcion que extrae estudiantes con cruce de los seleccionados
   * @param <array> $estudiantes ('codigo del estudiante', 'valor cruce')
   * @return <array> $permitidos ('codigo del estudiante', ok)
   */
  function verificarEstudiantesSinCruce($estudiantes) {
    $i=0;
    foreach ($estudiantes as $key => $value) {
      if ($value=="ok")
        {
          $permitidos[$key]=$value;
        }
    }
    return $permitidos;
  }

//      foreach ($estudiantes as $key => $value) {
//        echo $key."=>".$value."<br>";
//      }exit;
//

  }
?>
