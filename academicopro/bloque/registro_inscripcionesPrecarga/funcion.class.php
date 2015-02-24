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
            $this->formulario2="registro_cargarDatosEstudiantesInscripciones";//nombre del bloque que procesa el formulario
                           
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
            $this->verificar2="control_vacio(".$this->formulario2.",'codEstudiante','')";              
            
	}
        
        /**
         * Funcion que presenta el formulario de opciones para carga de datos (Por facultad, proyecto o estudiante)
         */
        function mostrarFormularioOpcionesCarga() {
            $this->crearInicioSeccion('Seleccione la opci&oacute;n para cargar datos');
            $arregloBotones=array(array('boton'=>'',
                                    'action'=>'registro_inscripcionesPrecarga',
                                    'opcion'=>'cargarDatosFacultad',
                                    'nombreBoton'=>'Facultad'),
                                    array('boton'=>'',
                                    'action'=>'registro_inscripcionesPrecarga',
                                    'opcion'=>'cargarDatosProyecto',
                                    'nombreBoton'=>'Proyecto'),
                                    array('boton'=>'',
                                    'action'=>'registro_inscripcionesPrecarga',
                                    'opcion'=>'cargarDatosEstudiante',
                                    'nombreBoton'=>'Estudiante'));
            $this->mostrarBotonesCargaProyecto($arregloBotones);
            $this->crearFinSeccion();
            
            
        }
        
        /**
         * Funcion que muestra el formulario de seleccion de facultad a cargar, carga de horarios y carga de preinscripcion por demanda de espacios reprobados.
         */
        function mostrarFormularioCargaFacultad() {
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
                $arregloBotones1=array(array('boton'=>$boton1,
                                        'action'=>'registro_cargarDatosEstudiantesInscripciones',
                                        'opcion'=>'cargarDatosFacultad',
                                        'nombreBoton'=>'Cargar datos',
                                        'variables'=>array('facultad'=>$facultad['codFacultad'])),
                                        array('boton'=>$boton2,
                                        'action'=>'registro_cargarDatosEstudiantesInscripciones',
                                        'opcion'=>'borrarDatosFacultad',
                                        'nombreBoton'=>'Borrar datos',
                                        'variables'=>array('facultad'=>$facultad['codFacultad'])));
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
                $arregloBotones2=array(array('boton'=>$boton3,
                                        'action'=>'registro_cargarReprobados',
                                        'opcion'=>'cargarPreinscripciones',
                                        'nombreBoton'=>'Registrar espacios perdidos',
                                        'variables'=>array('facultad'=>$facultad['codFacultad'])));
                $this->mostrarBotonesCargaProyecto($arregloBotones2);
                $this->crearFinSeccion();
            }
            $this->crearFinSeccion();
        }
        
        /**
         * Funcion que presenta el formulario para seleccionar el proyecto a cargar
         */
        function mostrarFormularioCargaProyecto() {
            $this->crearInicioSeccion(' Seleccione el Proyecto Curricular ');
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/html.class.php");
            $html = new html();
            $this->verificar = "seleccion_valida(".$this->formulario2.",'codProyecto')";
                
            $tmp_proyectos = $this->consultarCarreras();
            for($i=0;$i<count($tmp_proyectos);$i++) {
                $proyectos[$i][0]=$tmp_proyectos[$i]['CARRERA'];
                $proyectos[$i][1]=$tmp_proyectos[$i]['CARRERA']."-".$tmp_proyectos[$i]['NOMBRE_CARRERA'];
            }
           
        ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <table id="tabla"  class="contenidotabla" width="100%">
                    <tr >
                    <td width="20%" class='cuadro_plano centrar'>
                        <?
                            $mi_cuadro = $html->cuadro_lista($proyectos, "codProyecto", $this->configuracion,'', 0, FALSE, 1, "",400);
                            echo $mi_cuadro ;
                        ?>
                        <input type="hidden" name="opcion" value="cargarDatosProyecto">
                        <input type="hidden" name="pagina" value="<? echo $this->formulario2 ?>">
                        
                    </td>
                    
                    </tr>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
                    <input type="button" name="Cargar Datos" value="Cargar Datos" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario2?>'].submit()}else{false}">                    </td>
                </tr>
                </table>
            </form>
        <?
        $this->crearFinSeccion();
        }
        
        /**
         * Funcion que presenta el formulario para ingresar el codigo de estudiante para cargar datos
         */
        function mostrarFormularioCargaEstudiante() {
            $this->crearInicioSeccion(' Digite el c&oacute;digo del estudiante ');
            ?>
            <table class='contenidotabla centrar'>
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <tr align="center">
                  <td class="centrar" colspan="4">
                    <input type="text" name="codEstudiante" size="11" maxlength="11">
                    <input type="hidden" name="opcion" value="cargarDatosEstudiante">
                    <input type="hidden" name="action" value="<? echo $this->formulario2 ?>">
                  </td>
                </tr>
                <tr align="center">
                  <td class="centrar" colspan="4">
                    <input type="button" name="Cargar Datos" value="Cargar Datos" onclick="if(<? echo $this->verificar2; ?>){document.forms['<? echo $this->formulario2?>'].submit()}else{false}">
                  </td>
                </tr>
              </form>
            </table><?
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
        function mostrarBotonesCargaProyecto($arregloBotones) {
            ?>
            <table>
                <tr>
                    <?
                    foreach ($arregloBotones as $botones) {
                    ?>
                        <td>
                            <?
                            $this->crearFormularioBoton($botones);
                            ?>
                        </td>
                    <?
                    }
                    ?>
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
         * Funcion que crea un formaulario po cada boton con las variables definidas en el arreglo
         * @param type $arregloBotones
         */
        function crearFormularioBoton($arregloBotones) {
            ?>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='registro_cargarDatosEstudiantesInscripciones' id="<? echo $this->formulario ?>">
                            <input type="hidden" name="action" value="<?echo $arregloBotones['action']?>">
                            <input type="hidden" name="opcion" value="<?echo $arregloBotones['opcion'];?>">
                            <?
                            if(isset($arregloBotones['variables'])&&is_array($arregloBotones['variables']))
                            {
                                foreach ($arregloBotones['variables'] as $clave=>$variable) {
                                ?>
                                    <input type="hidden" name="<?echo $clave;?>" value="<?echo $variable;?>">
                                <?
                                }
                            }
                            ?>
                            <input type="submit" name="<?echo $arregloBotones['nombreBoton'];?>" value="<?echo $arregloBotones['nombreBoton'];?>" <?echo $arregloBotones['boton'];?>>
                        </form>       
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
        
        /**
         * Funcionq ue permite consultar los proyectos de pregrado
         * @return type
         */
        function consultarCarreras() {
            $cadena_sql = $this->sql->cadena_sql("consultarDatosCarreras","");
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;            
        }
}
?>
