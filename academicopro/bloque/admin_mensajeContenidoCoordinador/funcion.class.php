<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package Comunicaciones
 * @subpackage Coordinador
 * @author Luis Fernando Torres R.
 * @version 0.0.0.1
 * Fecha: 21/07/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

/**
 * Clase funcion_adminConsultarInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_adminMensajeContenidoCoordinador extends funcionGeneral
{

      public $configuracion;

        /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion) {

            $this->configuracion=$configuracion;

	    /**
             * Incluye la clase encriptar.class.php
             *
             * Esta clase incluye funciones de encriptacion para las URL
             */
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            /**
             * Incluye la clase validar_fechas.class.php
             *
             * Esta clase incluye funciones que permiten validar si las fechas de adiciones y cancelaciones estan abiertas o no
             */
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");
            
	    $this->cripto=new encriptar();

	    $this->sql=new sql_adminMensajeContenidoCoordinador($configuracion);
            
            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");
            /**
             * Instancia para crear la conexion de MySQL
             */
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            /**
             * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
             */
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

            /**
             * Datos de sesion
             */
	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");


            /**
             * buscar los datos de ano y periodo actuales
             */
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}
                       
        /**
         *
         */
        function mostrarContenidoMensajeEnviado() {
            
          $nombreUsuario=$this->buscarNombreUsuario();
          
//          foreach ($_REQUEST as $key => $value) {
//            echo $key.'=>'.$value.'<br>';
//          }
          ?>
          <br>
       
           <fieldset>
          <legend>Mensaje enviado:</legend>
          <table id="tabla">  
            <tr>
              <td>
                  <b><?  echo $_REQUEST['asunto']?></b>
              </td>
            </tr>
            <tr>
              <td style="color:green">
                <?echo $nombreUsuario?> para <?echo $_REQUEST['nombreReceptor']?>
              </td>
            </tr>
            <tr>              
              <td colspan="2">
                <hr>
              </td>
            </tr>
            <tr>
              <td>
                <?echo nl2br($_REQUEST['contenido'])?>                  
              </td>
            </tr>
        </table>    
          </fieldset>
          <?

        }

        /**
         *
         */
        function mostrarContenidoMensajeRecibido() {
            
          $nombreUsuario=$this->buscarNombreUsuario();
          $resultadoActualizarEstadoMensaje=  $this->actualizarEstadoMensaje($_REQUEST['codigoMensaje']);
          
//          foreach ($_REQUEST as $key => $value) {
//            echo $key.'=>'.$value.'<br>';
//          }
          
          
          
          ?>
          <br>
       
           <fieldset>
          <legend>Mensaje recibido:</legend>
          <table id="tabla">  
            <tr>
              <td>
                  <b><?  echo $_REQUEST['asunto']?></b>
              </td>
            </tr>
            <tr>
              <td style="color:green">
                De <?echo $_REQUEST['nombreEmisor']?>
              </td>
            </tr>
            <tr>              
              <td colspan="2">
                <hr>
              </td>
            </tr>
            <tr>
              <td>
                <?echo nl2br($_REQUEST['contenido'])?>
              </td>
            </tr>
        </table>    
          </fieldset>
          <?

        }
        
         
        /**
         *
         */
        function nombreMetodoDefecto() {
            
          echo 'No esta bien definido el método';

        }

        function buscarNombreUsuario() {
          
          
              $variablesUsuario = array(  'codUsuario' => $this->usuario                                          
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscar_nombreUsuario", $variablesUsuario);//echo $cadena_sql;exit;
              $arreglo_usuario = $this->ejecutarSQL($this->configuracion,$this->accesoOracle, $cadena_sql, "busqueda");                  
              return $arreglo_usuario[0]['NOMBRE'].' '.$arreglo_usuario[0]['APELLIDO'];          
          
        }

        /**
         *
         * @param type $codMensaje
         * @return type 
         */
        function actualizarEstadoMensaje($codMensaje) {
            
              $variablesMensaje = array(  
                                            'codMensaje' => $codMensaje,
                                            'codReceptor' => $this->usuario
                                        );

              $cadena_sql = $this->sql->cadena_sql("actualizarEstadoMensaje", $variablesMensaje);//echo $cadena_sql;exit;
              $arreglo_mensaje = $this->ejecutarSQL($this->configuracion,$this->accesoOracle, $cadena_sql, "");              
              return $arreglo_mensaje;            
            
        }


}
?>
