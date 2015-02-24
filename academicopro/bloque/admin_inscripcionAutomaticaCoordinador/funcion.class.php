<?php
/**
 * Funcion adminInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionAutomaticaCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 14/01/2013
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
 * Clase funcion_adminInscripcionAutomaticaCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionAutomaticaCoordinador
 * @subpackage Admin
 */
class funcion_adminInscripcionAutomaticaCoordinador extends funcionGeneral {

/**
 * Se crean atributos de la clase
 */
private $configuracion;
private $ano;
private $periodo;

/**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionAutomaticaCoordinador
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
        $this->sql=new sql_adminInscripcionAutomaticaCoordinador($configuracion);
        $this->formulario="admin_inscripcionAutomaticaCoordinador";

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

        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];

    }

    /**
     * Funcion que muestra el menu para ingresar a inscripciones por estudiante o por grupo
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param array $variable Contiene datos del proyecto curricular y planes de estudio
     * @param int $variable[0] Codigo del proyecto curricular
     * @param string $variable[1] Nombre del proyecto curricular
     * @param int $variable[2] Plan de estudio del proyecto curricular
     */
    function menuCoordinador($variable)
    {
        
        $inscripcion=$this->consultarInscripcionAutomatica($variable);
        $inscripciones=$this->consultarInscripcionesProyecto($variable);
       ?>
<script>
    
    function mostrarDiv() {
   document.getElementById('iniciarProceso').style.display = "block";
}

</script>
<div  style="display:none;font-size: 20px; color:#FF0000;text-align: center;border:1px solid #E9EFE6; vertical-align:middle" id="iniciarProceso"><?php 
echo "<img src=".$this->configuracion['site'].$this->configuracion['grafico']."/ajax-loader.gif>";
?><br>Ejecutando Proceso....</div><br>
<table class="sigma_borde centrar" width="100%">
    <caption class="sigma">INSCRIPCI&Oacute;N AUTOM&Aacute;TICA DEL PROYECTO CURRICULAR</caption>
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
            <?echo $variable[1]?>
        </td>
    </tr>
<?
        if(is_array($inscripcion)&&!empty($inscripcion))
        {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
        </td>
    </tr><?exit;
    }
        ?>    
        
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registro_inscripcionAutomaticaCoordinador";
                    $ruta.="&opcion=ejecutarInscripcion";
                    $ruta.="&codProyecto=".$variable[0];
                    $ruta.="&nombreProyecto=".$variable[1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <a href="<?= $pagina.$ruta ?>" onclick="mostrarDiv()">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pagina_inicial/preinscripcion_hab.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                <br>Ejecutar Inscripci&oacute;n autom&aacute;tica<br>(Este proceso tardará varios segundo en concluir.)
            </a>
        </td>

    </tr>
<?
        if(is_array($inscripciones)&&($inscripciones[0]['TOTAL'])>0)
        {
?>
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registro_inscripcionAutomaticaCoordinador";
                    $ruta.="&opcion=publicarInscripcion";
                    $ruta.="&codProyecto=".$variable[0];
                    $ruta.="&nombreProyecto=".$variable[1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <a href="<?= $pagina.$ruta ?>">
                 <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pagina_inicial/inscripcion_hab.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                <br>Publicar Inscripci&oacute;n autom&aacute;tica
            </a>
        </td>

    </tr><?            
    }else
        {
            ?>
            <tr class="centrar">
                <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pagina_inicial/inscripcion_des.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                        <br>Publicar Inscripci&oacute;n autom&aacute;tica
                </td>
            </tr>
        <?
        
        }
    ?>
        </table>
    <?        
    }

    /**
     * Funcion que se encarga de mostrar los proyectos curriculares y planes de estudio que tiene a cargo el coordinador
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function verProyectos()
    {
        /*
         * Consulta los proyectos curriculares con su respectivo plan de estudio, y se muestra en un <select>
         */
        $cadena_sql_proyectos=$this->sql->cadena_sql("proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1){

        ?>
<table class='sigma_borde centrar' align="center" width="80%">
    
    <caption class="sigma">SELECCIONE EL PROYECTO CURRICULAR</caption>
        <th class="sigma centrar">C&oacute;digo del Proyecto</th>
        <th class="sigma centrar">Nombre</th>
    

        <?
            for($i=0;$i<count($resultado_proyectos);$i++) {
                ?>
                    <tr>
                <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=admin_inscripcionAutomaticaCoordinador";
                    $variable.="&opcion=mostrarReporte";
                    $variable.="&codProyecto=".$resultado_proyectos[$i][0];
                    $variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                ?>
                    <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][0]?></a></td>
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
                $this->mostrarRegistro();
            }
            else
            {
              $this->noPlan();
              exit;
            }
        }
    }

        function noPlan() {
?>
                      <table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pequeno_universidad.png ">
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
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function mostrarRegistro() {
       
        if(isset($_REQUEST['codProyecto']))
            {
                $codProyecto=$_REQUEST['codProyecto'];
                $nombreProyecto=$_REQUEST['nombreProyecto'];
                $variable=array($codProyecto,$nombreProyecto);
            }else if(isset($_REQUEST['codProyecto']) && isset($_REQUEST['planEstudio']))
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto);
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql("datos_coordinador",$this->usuario);
                        $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                        $_REQUEST['nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];
                        
                        $variable=array($codProyecto,$nombreProyecto);
                    }

            $this->menuCoordinador($variable);
           
    }

   
    /**
     * Funcion que presenta reporte de inscripcion automatica
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function mostrarReporte() {
       
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
                        $cadena_sql=$this->sql->cadena_sql("datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                        $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $_REQUEST['planEstudio']=$planEstudio=$resultado_datosCoordinador[0][2];
                        $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                        $_REQUEST['nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];
                        
                        $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                    }

            $this->menuCoordinador($variable);
           
    }

    function consultarInscripcionAutomatica($variable) {
        $datos=array('codProyecto'=>$variable[0],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("consultarInscripcionAutomatica",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcion;
    }

    function consultarInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable[0],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("consultarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcionProyecto;
    }

}
?>
