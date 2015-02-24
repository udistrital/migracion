<?php
/**
 * Funcion nombreFuncion
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 08/09/2011
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
class funcion_registroBuscadorGraduando extends funcionGeneral
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
            
	    $this->cripto=new encriptar();
            
	    $this->sql=new sql_registroBuscadorGraduando($configuracion);
            
            $this->formulario="registro_buscadorGraduando";//nombre del bloque que procesa el formulario
            $this->bloque="grados/registro_buscadorGraduando";//nombre del bloque que procesa el formulario
                           
            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");
            /**
             * Instancia para crear la conexion de MySQL
             */
            //$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

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
            //$cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            //$resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            //$this->ano=$resultado_peridoActivo[0]['ANO'];
            //$this->periodo=$resultado_peridoActivo[0]['PERIODO'];

            $this->verificar="control_vacio(".$this->formulario.",'datoBusqueda','')";              
            
	}
                       
  function MostrarBuscadorEstudiante() {

          ?>           
                      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->bloque ?>' id="<? echo $this->formulario ?>">
                            <fieldset>
                                <legend>Buscar estudiante</legend>
                          <table  width="100%">
                              <tr class="sigma">
                                <td width="30%" class="sigma derecha"> Por C&oacute;digo<input type="radio" name="tipoBusqueda" value="codigo" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="codigo" || !(isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')){echo "checked";} ?>><br>
                                Por No. de Identificaci&oacute;n<input type="radio" name="tipoBusqueda" value="identificacion" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="identificacion"){echo "checked";} ?> ><br>
                                Por Nombre<input type="radio" name="tipoBusqueda" value="nombre" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="nombre"){echo "checked";} ?> >
                                 </td>
                                <td width="15%">
                                        <input type="text" name="datoBusqueda" size="20" value="<? echo (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'')?>">
                                </td>
                                <td  width="55%">
                                        <input type="hidden" name="opcion" value="enviarCodigo">                                                                    
                                        <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                        <input type="button" value="Enviar" name="aceptar" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/>
                                </td>                                        
                              </tr>
                        </table>                     
                                                         
                            </fieldset>
                      </form>          
          <?       
      
  }
 
        
        /**
         *
         */
        function nombreMetodoDefecto() {
            
          echo 'este un bloque b&aacute;sico metodo por defecto';

        }





}
?>
