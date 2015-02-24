
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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

//@ Esta clase se utiliza para el ingreso y validación de estudiante de posgrado para inscripcion por estudiante

class funcion_adminInscripcionEstudianteSoporte extends funcionGeneral {

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    $this->cripto = new encriptar();
    $this->tema = $tema;
    $this->sql = new sql_adminInscripcionEstudianteSoporte();
    $this->log_us = new log();
    $this->validacion = new validarInscripcion();
    $this->formulario = "admin_inscripcionEstudianteSoporte";

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    //Datos de sesion
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    $this->verificar="control_vacio(".$this->formulario.",'codEstudiante')";
  }

#Cierre de constructor

  /*
   * Funcion que permite ingresar el codigo de estudiante a consultar
   *
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, nombreProyecto)
   */

  function consultarEstudiante($configuracion) {
?>
    <table class='contenidotabla centrar'>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
        <tr align="center">
          <td class="centrar" colspan="4">
            <h4>Digite el c&oacute;digo del estudiante que desea consultar</h4>
          </td>
        </tr>
        <tr align="center">
          <td class="centrar" colspan="4">
            <input type="text" name="codEstudiante" size="11" maxlength="11">
            <input type="hidden" name="opcion" value="validar">
            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
            <input type="button" name="Consultar" value="Consultar" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
          </td>
        </tr>
        <tr>
          <td>
            <hr align="center">
          </td>
        </tr>
      </form>
    </table>

<?
  }

  /*
   * Función que valida si el estudiante pertenece al proyecto y plan de estudios seleccionado
   * pagina=>admin_inscripcionEstudianteSoporte
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, nombreProyecto, codEstudiante)
   */

  function validarEstudiante($configuracion) {
    $datosEstudiante=array('codEstudiante'=>$_REQUEST['codEstudiante']);
    $validacion = $this->validacion->datosEstudiante($datosEstudiante);
    if (is_array($validacion))
    {
        if(trim($validacion[0]['INDICA_CREDITOS'])=='S')
        {
          $variable = "pagina=admin_consultarInscripcionCreditosEstudianteSop";
        }
        elseif(trim($validacion[0]['INDICA_CREDITOS'])=='N')
        {
          $variable = "pagina=admin_consultarInscripcionEstudianteHorasSop";          
        }
        
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyectoEstudiante=" . $validacion[0]['PROYECTO_ESTUDIANTE'];
        $variable.="&planEstudioEstudiante=" . $validacion[0]['PLAN_ESTUDIANTE'];
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        exit;
    }
    else
      {
          echo "<script>alert('" . $validacion . "')</script>";
          $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
          $variable = "pagina=admin_inscripcionEstudianteSoporte";
          $variable.="&opcion=consultar";

          include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variable = $this->cripto->codificar_url($variable, $configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
      }
  }

}
?>
