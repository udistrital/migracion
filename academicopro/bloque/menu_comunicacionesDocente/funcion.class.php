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
class funcion_menuComunicacionesDocente extends funcionGeneral
{

      public $configuracion;
      public $ano;
      public $periodo;
      public $codProyecto;

        /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion) {

            $this->configuracion=$configuracion;
            $this->codProyecto=$_REQUEST['codProyecto'];
 
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

            $this->fechas=new validar_fechas();
            
	    $this->cripto=new encriptar();

	    $this->sql=new sql_menuComunicacionesDocente($configuracion);            

	}

        /**
         *
         */
        function mostrarMenuNavegacion() {

            //Enlace consultar mensajes recibidos
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeDocente";
            $variable.="&opcion=verMensajesRecibidos";
            $variable.="&codProyecto=".$this->codProyecto;
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>                
            
                <div>
                  <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/mensaje.png" width="40" height="40" border="0">
                <a href="<?= $pagina.$variable?>">Recibidos | </a>                
            <?
                        
            //Enlace consultar mensajes enviados
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeDocente";
            $variable.="&opcion=verMensajesEnviados";
            $variable.="&codProyecto=".$this->codProyecto;            
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                <a href="<?= $pagina.$variable?>">Enviados | </a>
            <?                        

            //Enlace nuevo mensaje    
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=registro_mensajeDocente";
            $variable.="&opcion=nuevo";
            $variable.="&codProyecto=".$this->codProyecto;            
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                <a href="<?= $pagina.$variable?>">Nuevo | </a>                
            <?
                        
            //Enlace consejerias
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_consejeriasDocente";
            $variable.="&opcion=verEstudiantes";
            $variable.="&codProyecto=".$this->codProyecto;            
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                <a href="<?= $pagina.$variable?>">
                    <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/atras.png" width="15" height="15" border="0">
                    Consejer&iacute;a </a>
                </div>
            <?
                        

        }



}
?>
