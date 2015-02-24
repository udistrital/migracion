<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
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
class funcion_menuComunicacionesEstudiante extends funcionGeneral
{

      public $configuracion;
      public $ano;
      public $periodo;

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

	    $this->cripto=new encriptar();

	    $this->sql=new sql_menuComunicacionesEstudiante($configuracion);
            
            /**
             * Intancia para crear la conexion ORACLE
             */
            //$this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");
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


              //Buscar año y periodo activos
//            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
//            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
//            $this->ano=$resultado_peridoActivo[0]['ANO'];
//            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}

        /**
         *
         */
        function mostrarMenuNavegacion() {

            //Enlace consultar mensajes recibidos
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeEstudiante";
            $variable.="&opcion=verMensajesRecibidos";
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>                
            
                <div>
                  <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/mensaje.png" width="40" height="40" border="0">
                <a href="<?= $pagina.$variable?>">Recibidos | </a>                
            <?
                        
            //Enlace consultar mensajes enviados
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeEstudiante";
            $variable.="&opcion=verMensajesEnviados";          
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                <a href="<?= $pagina.$variable?>">Enviados | </a>
            <?                        

            //Enlace nuevo mensaje    
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=registro_mensajeEstudiante";
            $variable.="&opcion=nuevo";         
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                <a href="<?= $pagina.$variable?>">Nuevo</a>
                </div>
            <?
                        

        }



}
?>
