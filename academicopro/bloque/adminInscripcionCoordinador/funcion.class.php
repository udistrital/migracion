<?php
/**
 * Funcion adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
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
 * Clase funcion_adminInscripcionCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_adminInscripcionCoordinador extends funcionGeneral {


    /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionCoordinador
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

        $this->cripto=new encriptar();
        $this->sql=new sql_adminInscripcionCoordinador();
        $this->formulario="adminInscripcionCoordinador";

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
    function menuCoordinador($configuracion, $variable)
    {
       ?>
<table class="sigma_borde centrar" width="100%">
    <caption class="sigma">INSCRIPCIONES</caption>
    <tr class="centrar">
        <td colspan="2" class="sigma centrar"  width="50%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminInscripcionEstudianteCoordinador";
            $ruta.="&opcion=consultar";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/solo.png" width="50" height="50" border="0" alt="Inscripcion por Estudiante">
                <br>Inscripci&oacute;n<br> por Estudiante
            </a>
        </td>
        <td colspan="2" class="sigma centrar" width="50%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminInscripcionGrupoCoordinador";
            $ruta.="&opcion=consultar";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];
           
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/personas.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                <br>Inscripci&oacute;n<br> por Grupo
            </a>
        </td>

    </tr>
</table>
<?
    }

    /**
     * Funcion que se encarga de mostrar los proyectos curriculares y planes de estudio que tiene a cargo el coordinador
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function verProyectos($configuracion)
    {
        /*
         * Consulta los proyectos curriculares con su respectivo plan de estudio, y se muestra en un <select>
         */
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1){

        ?>
<table class='sigma_borde centrar' align="center" width="80%" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    
    <caption class="sigma">SELECCIONE EL PLAN DE ESTUDIOS</caption>
        <th class="sigma centrar">Carrera</th>
        <th class="sigma centrar">Plan de Estudios</th>
        <th class="sigma centrar">Nombre</th>
    

        <?
            for($i=0;$i<count($resultado_proyectos);$i++) {
                ?>
                    <tr>
                <?
                    if ($resultado_proyectos[$i][0]==97 || $resultado_proyectos[$i][0]==98)
                    {
                        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"planEstudio",$resultado_proyectos[$i][2]);//echo $cadena_sql_plan;exit;
                        $nombreProyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_plan,"busqueda" );
                        $resultado_proyectos[$i][1]=$nombreProyecto[0][0];
                    }
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionCoordinador";
                    $variable.="&opcion=mostrar";
                    $variable.="&codProyecto=".$resultado_proyectos[$i][0];
                    $variable.="&planEstudio=".$resultado_proyectos[$i][2];
                    $variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                ?>
                    <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][0]?></a></td>
                    <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][2]?></a></td>
                    <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][1]?></a></td>
                </tr>
        <?
            }
        ?>
</table>
        <?
    }else
        {
            if(is_array($resultado_proyectos))
            {
                $this->mostrarRegistro($configuracion);
            }
            else
            {
              $this->noPlan($configuracion);
              exit;
            }
        }
    }

        function noPlan($configuracion) {
?>
                      <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                              </td>
                          </tr>
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>NO EXISTEN PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
                                  <hr noshade class="hr">

                              </td>
                          </tr>
                      </table>
                  <?


}

    /**
     * Funcion que captura los valores del proyecto curricular y crea el menu de inscripcion por estudiante y grupo
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function mostrarRegistro($configuracion) {
       
        if($_REQUEST['proyecto'])
            {
                $arreglo=explode("-",$_REQUEST['proyecto']);
                $planEstudio=$arreglo[0];
                $codProyecto=$arreglo[1];
                $nombreProyecto=$arreglo[2];
                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }else if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                        $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $_REQUEST['planEstudio']=$planEstudio=$resultado_datosCoordinador[0][2];
                        $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                        $_REQUEST['nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];
                        
                        $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                    }

            $this->menuCoordinador($configuracion,$variable);
           
    }

   



}
?>
