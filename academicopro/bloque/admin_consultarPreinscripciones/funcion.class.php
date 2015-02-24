
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

class funcion_admin_consultarPreinscripcionDemandaEstudiante extends funcionGeneral {

  private $configuracion;
  private $nivelUsuario;
  private $codEstudiante;
  private $arregloTabla;
  private $arregloPreinscritos;
  private $usuario;
  private $ano;
  private $periodo;
  private $cierre;

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
    $this->sql = new sql_admin_consultarPreinscripcionDemandaEstudiante();
    $this->log_us = new log();
    $this->formulario = "admin_consultarPreinscripcionDemandaEstudiante";
    $this->validacion = new validarInscripcion();
    $this->verificar="control_vacio(".$this->formulario.",'codEspacioAgil')";
    $obj_sesion = new sesiones($configuracion);


    //Conexion General
    $this->acceso_db=$this->conectarDB($configuracion,"");

    //Conexion sga
    $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
    $this->nivelUsuario=$obj_sesion->rescatar_valor_sesion($configuracion, "nivelUsuario");
    //Conexion Oracle dependiendo del usuario
    $this->accesoOracle=$this->conectarDB($configuracion,"preinscripcion");
    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
    $this->id_accesoSesion=$this->resultadoSesion[0][0];

    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
    $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db,"id_usuario");
    $cadena_sql=$this->sql->cadena_sql("periodoPreinscripciones",'');
    $resultado_periodo=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
    $this->ano=$resultado_periodo[0]['ANO'];
    $this->periodo=$resultado_periodo[0]['PERIODO'];
    $this->arregloTabla=array();
    $this->arregloPreinscritos=array();
    $this->cierre=array();
//Funcion JavaScript que no permite utilizar el botón derecho del raton


  }

  /**
   * Esta funcion presenta el horario del estudiante
   * Utiliza los metodos consultarDatosEstudiante,presentarDatosEstudiante,consultarEspaciosPreinscritos,validar_fechasPreinscripcionProyecto, validarEstadoEstudiantePreinscripciones,
   *  presentarEstudianteCreditos, presentarEstudianteHoras, finTabla, finalizarTablaFinal
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (codEstudiante)
   */
  function consultar() {
      $preinscritos=$this->consultarEstudiantesPreinscritos();
      $preinscripciones=  $this->consultarPreinscripciones();
      ?>
    <table class="contenidotabla centrar" width="100%">
        <?/**
         * Mensaje para realizar piloto
         */?>
        <tr>
            <td class="centrar">
                ESTUDIANTES CON PREINSCRIPCIONES: <?echo $preinscritos[0][0];?>
            </td>
        </tr>
        <tr>
            <td class="centrar">
                REGISTROS DE PREINSCRIPCIONES: <?echo $preinscripciones[0][0]?>
            </td>
        </tr>
    </table>
      <?

  }


  /**
   * Funcion que consulta fechas activas de perinscripcion para el proyecto
   * @param type $datosFechas
   * @return type 
   */ 
  function consultarFechas($datosFechas) {
      $cadena_sql=$this->sql->cadena_sql('fechas_activas_coordinador', $datosFechas);
      return $fechas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
  }

  /**
   * Funcion que sirve para valor default del bloque
   */

  function nuevoRegistro() {

  }

  /**
   * Funcion que permite consultar datos del estudiante
   * @param <int> $codEstudiante
   * @return <array> 
   */
  function consultarEstudiantesPreinscritos() {
    $cadena_sql = $this->sql->cadena_sql("consultarEstudiantesPreinscritos");
    return $registroDatos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que consulta los parametros para preinscripcion de plan de estudios de horas
   * @param type $datosEstudiante
   * @return type 
   */
  function consultarPreinscripciones($datosEstudiante) {
    $cadena_sql=$this->sql->cadena_sql("consultarPreinscripciones",$datosEstudiante);//echo $cadena_sql;exit;
    return $registroMaximo=$this->ejecutarSQL($this->configuracion,$this->accesoOracle,$cadena_sql,"busqueda" );

  }
}
?>
