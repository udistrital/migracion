<?php
/**
 * Funcion adminInscripcionCoordinadorHoras
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 31/05/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"])) {
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
 * Clase funcion_adminInscripcionCoordinadorHoras
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_adminInscripcionCoordinadorHoras extends funcionGeneral {
  private $configuracion;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionCoordinadorHoras
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {

        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->sql=new sql_adminInscripcionCoordinadorHoras($configuracion);
        $this->formulario="admin_inscripcionCoordinadorHoras";

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
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    /**
     * Funcion que muestra el menu para ingresar a inscripciones por estudiante o por grupo
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param array $variable Contiene datos del proyecto curricular y planes de estudio
     * @param int $variable[0] Codigo del proyecto curricular
     * @param string $variable[1] Nombre del proyecto curricular
     * @param int $variable[2] Plan de estudio del proyecto curricular
     */
    function menuCoordinador($variable)
    {
       ?>
<table class="sigma_borde centrar" width="100%">
    <caption class="sigma">* INSCRIPCIONES *</caption>
    <tr class="centrar">
        <td colspan="2" class="sigma centrar"  width="50%">
            <?
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $ruta="pagina=admin_inscripcionEstudianteCoorPosgrado";
            $ruta.="&opcion=consultar";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];

            $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/solo.png" width="50" height="50" border="0" alt="Inscripcion por Estudiante">
                <br>Inscripci&oacute;n<br> por Estudiante
            </a>
        </td>
        <td colspan="2" class="sigma centrar" width="50%">
            <?
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $ruta="pagina=admin_inscripcionGrupoCoorHoras";
            $ruta.="&opcion=consultarPorNivel";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];
           
            $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
            //echo $ruta;
        ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/personas.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                <br>Inscripci&oacute;n<br> por Grupo
            </a>
        </td>

    </tr>
</table>
<?
    }


    /**
     * Funcion que captura los valores del proyecto curricular y crea el menu de inscripcion por estudiante y grupo
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function mostrarRegistro($infoProyecto="") {
        if($infoProyecto!="")
            {
                $planEstudio=$infoProyecto[0]['PLAN'];
                $codProyecto=$infoProyecto[0]['PROYECTO'];
                $nombreProyecto=$infoProyecto[0]['NOMBRE'];

                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }else if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=isset($_REQUEST['nombreProyecto']);
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql("proyectos_curriculares",$this->usuario);//echo $cadena_sql;exit;
                        $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $planEstudio=$resultado_datosCoordinador[0]['PLAN'];
                        $codProyecto=$resultado_datosCoordinador[0]['PROYECTO'];
                        $nombreProyecto=$resultado_datosCoordinador[0]['NOMBRE'];

                        $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                    }

            $this->menuCoordinador($variable);
           
    }

}


?>
