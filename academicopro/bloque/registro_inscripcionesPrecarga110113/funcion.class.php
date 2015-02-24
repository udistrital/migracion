<?php
/**
 * Funcion nombreFuncion
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @actualizacion Monica Monroy
 * @actualizacion Milton Parra
 * @version 0.0.0.3
 * Fecha: 04/12/2012
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
 * Clase funcion_registroInscripcionesPrecarga
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package Soporte
 * @subpackage Registro
 */
class funcion_registroInscripcionesPrecarga extends funcionGeneral
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
            
	    $this->cripto=new encriptar();
            
	    $this->sql=new sql_registroInscripcionesPrecarga($configuracion);
            
            $this->formulario="registro_inscripcionesPrecarga";//nombre del bloque que procesa el formulario
                           
            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Instancia para crear la conexion a base de datos SGA de mysql
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
            

            $this->verificar="control_vacio(".$this->formulario.",'facultad','')";              
            
	}
        
        /**
         * Funcion que muestra el formulario de seleccion de facultad a cargar, carga de horarios y carga de preinscripcion por demanda de espacios reprobados.
         */
        function mostrarFormularioFacultad() {
            $facultades=array(array('codFacultad'=>23,'nombreFacultad'=>'Facultad de Medio Ambiente y Recursos Naturales'),
                                array('codFacultad'=>24,'nombreFacultad'=>'Facultad de Ciencias y Educaci&oacute;n'),
                                array('codFacultad'=>32,'nombreFacultad'=>'Facultad de Tecnolog&iacute;a - Polit&eacutecnica / Tecnol&oacute;gica'),
                                array('codFacultad'=>33,'nombreFacultad'=>'Facultad de Ingenier&iacute;a'),
                                array('codFacultad'=>101,'nombreFacultad'=>'Facultad de Artes-ASAB')
                            );
            $this->crearInicioSeccion(' Carga de datos para inscripciones de estudiantes ');
            foreach ($facultades as $facultad)
            {
                $boton1='';
                $boton2='disabled';
                $datos=$this->consultarDatosCargados($facultad['codFacultad']);
                if(is_array($datos))                
                {
                    $boton1='disabled';
                    $boton2='';
                }
                $this->crearInicioSeccion($facultad['codFacultad']." - ".$facultad['nombreFacultad']);
                $arregloBotones1=array('facultad'=>$facultad['codFacultad'],
                                        'boton1'=>$boton1,
                                        'boton2'=>$boton2,
                                        'action'=>'registro_cargarDatosEstudiantesInscripciones',
                                        'opcion'=>'cargarDatosFacultad',
                                        'nombreBoton'=>'Cargar datos');
                $this->mostrarBotonesCargaProyecto($arregloBotones1);
                $this->crearFinSeccion();
            }
            $this->crearFinSeccion();
            ?><br><br><?
            $this->crearInicioSeccion(' Actualizar Horarios ');
            $this->mostrarBotonHorario();
            $this->crearFinSeccion();
            ?><br><br><?
            $this->crearInicioSeccion(' Preinscripciones de espacios perdidos ');
            foreach ($facultades as $facultad)
            {
                $boton3='';
                $boton4='disabled';
                $datos=$this->consultarPreinscripcionesDemanda($facultad['codFacultad']);
                if(is_array($datos))                
                {
                    $boton1='disabled';
                    $boton2='';
                }
                $this->crearInicioSeccion($facultad['codFacultad']." - ".$facultad['nombreFacultad']);
                $arregloBotones2=array('facultad'=>$facultad['codFacultad'],
                                        'boton1'=>$boton3,
                                        'boton2'=>$boton4,
                                        'action'=>'registro_cargarReprobados',
                                        'opcion'=>'cargarPreinscripciones',
                                        'nombreBoton'=>'Registrar espacios perdidos');
                $this->mostrarBotonesCargaProyecto($arregloBotones2);
                $this->crearFinSeccion();
            }
            $this->crearFinSeccion();
        }
        
        /**
         * Funcion que permite crear el inicio de una seccion
         * @param type $mensaje
         */
        function crearInicioSeccion($mensaje) {
            ?><fieldset>
                <legend><?echo $mensaje;?></legend><?
        }

        /**
         * Funcion que permite crear el fin de una seccion
         */
        function crearFinSeccion() {
            ?></fieldset><?
        }
        
        /**
         *  Funcion que geenera botones para el proceso de carga de datos
         * @param type $arregloBotones
         */
        function mostrarBotonesCargaproyecto($arregloBotones) {
            ?>
            <table>
                <tr>                  
                    <td>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='registro_cargarDatosEstudiantesInscripciones' id="<? echo $this->formulario ?>">
                            <input type="hidden" name="action" value="<?echo $arregloBotones['action']?>">
                            <input type="hidden" name="opcion" value="<?echo $arregloBotones['opcion'];?>">
                            <input type="hidden" name="facultad" value="<?echo $arregloBotones['facultad'];?>">
                            <input type="submit" value="<?echo $arregloBotones['nombreBoton'];?>" <?echo $arregloBotones['boton1'];?>>
                        </form>       
                    </td>
                    <td>
                        <input type="submit" value="BorrarDatos" <?echo $arregloBotones['boton2'];?>>
                    </td>
                </tr>
            </table>
            <?
        }
        
        /**
         * Funcon que genera el boton de cargar horarios
         */
        function mostrarBotonHorario() {
            ?>
            <table>
                <tr>                  
                    <td>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                            <input type="hidden" name="opcion" value="cargarHorarios">
                            <input type="submit" value="Actualizar">                        
                        </form>       
                    </td>
                </tr>
            </table>
          <?
        }

        /**
         * Funcion que realiza la carga de horarios de produccion a la alterna
         */
        function cargarHorarios() {
            
            $periodo=$this->consultarPeriodo();
            $horariosOracle=$this->consultarHorarios($periodo);
            foreach ($horariosOracle as $horario) {
                $this->insertarHorario($horario);                
            }
            echo 'datos insertados';exit;
        }
        
        /**
         * Funcion que busca los proyectos de pregrado
         * @return type
         */
        function buscarProyectosPregrado() {
            $variables=array();            
            $cadena_sql = $this->sql->cadena_sql("buscarProyectosPregrado", $variables);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
        }

        /**
         * Funcionq que consulta los horarios registrados en la base de datos de produccion
         * @param type $periodo
         * @return type
         */
        function consultarHorarios($periodo) {
            $variables=$periodo;            
            $cadena_sql = $this->sql->cadena_sql("consultarHorarios", $variables);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
        }
        
        /**
         * Funcion que registra una franja de horario en la base de datos alterna
         * @param type $horario
         * @return type
         */
        function insertarHorario($horario) {
            $variables=$horario;            
            $cadena_sql = $this->sql->cadena_sql("insertarHorario", $variables);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "");
            return $resultado;            
        }

        /**
         * Funcion que verifica si existen datos de inscripciones cargados para la facultad
         * @param type $facultad
         * @return type
         */
        function consultarDatosCargados($facultad) {
            $cadena_sql = $this->sql->cadena_sql("consultarDatosCargados", $facultad);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
            return $resultado;
        }
        
        /**
         * Funcion que verifica si hay preinscripciones por demanda de espacios reprobados para la facultad
         * @param type $facultad
         */
        function consultarPreinscripcionesDemanda($facultad) {
            //$cadena_sql = $this->sql->cadena_sql("consultarPreinscripcionesDemanda", $facultad);
            //$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            //return $resultado;
        }
        
        /**
         * Funcionq ue permite consultar el año y periodo actual
         * @return type
         */
        function consultarPeriodo() {
            $cadena_sql = $this->sql->cadena_sql("consultarPeriodo","");
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado[0];            
        }
}
?>
