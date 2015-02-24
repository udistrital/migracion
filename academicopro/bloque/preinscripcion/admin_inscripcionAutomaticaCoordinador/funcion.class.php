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
private $codProyecto;
private $inscripciones;

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
        if(!$this->usuario)
        {
            echo "Sesi&oacute;n cerrada. Por favor ingrese nuevamente.";
            exit;
        }
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
        ?>
<script  language="javascript">
    var navegador = navigator.userAgent;
    function navega()
    {
                    if (navigator.userAgent.indexOf('MSIE') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        document.execCommand('Stop');
                    } else if (navigator.userAgent.indexOf('Firefox') !=-1)
                    {
                    } else if (navigator.userAgent.indexOf('Chrome') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                    } else if (navigator.userAgent.indexOf('Opera') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                        document.execCommand('Stop');
                    } else
                    {  
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                        document.execCommand('Stop');
                    }
    }</script><?        
        $this->codProyecto=$variable[0];
        $preinscritos=$inscritos=0;
        $eventoInscripcion=$this->consultarEventoInscripcionAutomatica($variable);
        $inscripcion=$this->consultarInscripcionAutomatica($variable);
        $ejecucion=$this->consultarEjecucionInscripcion();
        $inscripciones=$this->consultarInscripcionesProyecto($variable);
        $this->inscripciones=$inscripciones;
       ?>

<table class="sigma_borde centrar" width="100%">
    <caption class="sigma">INSCRIPCI&Oacute;N AUTOM&Aacute;TICA DEL PROYECTO CURRICULAR</caption>
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
            <?echo $variable[1]?>
        </td>
    </tr>
    
<?
        if(!is_array($eventoInscripcion))
        {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>En este momento se encuentran cerradas las fechas para realizar la Inscripci&oacute;n autom&aacute;tica.<br>";?>
        </td>
    </tr><?exit;
        }elseif(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
        </td>
    </tr><?$inscritos=1;
    }elseif(is_array($ejecucion))
    {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>En este momento se est&aacute; ejecutando la Inscripci&oacute;n autom&aacute;tica para el Proyecto. Por favor espere.<br>";?>
        </td>
    </tr><?exit;
    }elseif (is_array($inscripcion)) {
        $preinscritos=1;
        ?>    
        
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                <div class="navdiv">
                    <script type="text/javascript">
                        navega();
                    </script>
                </div>            
            
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registro_inscripcionAutomaticaCoordinador";
                    $ruta.="&opcion=borrarInscripcion";
                    $ruta.="&codProyecto=".$variable[0];
                    $ruta.="&nombreProyecto=".$variable[1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <a href="<?= $pagina.$ruta ?>" onclick="mostrarDiv()" style="color:#FF0000;">Ya existen datos de Inscripci&oacute;n autom&aacute;tica<br>¿Desea borrarlos y ejecutarla nuevamente?<br>
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/borrar.png" width="50" height="50" border="0" alt="Inscripcion por Grupo"><br>Borrar Datos
            </a>
        </td>

    </tr>
<?
            
        }else{if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
        </td>
    </tr><?$inscritos=1;
    }else{
            
        ?>    
        
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                <div class="navdiv">
                    <script type="text/javascript">
                        navega();
                    </script>
                </div>            
            
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

            <a href="<?= $pagina.$ruta ?>" onclick="mostrarDiv()">Ejecutar Inscripci&oacute;n autom&aacute;tica<br>(Este proceso tardará varios segundos en concluir)<br>
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/preinscripcion_hab.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
            </a>
        </td>

    </tr>
    <?}
    
    }
        if ($inscritos==0)
        {
        if(is_array($inscripciones)&&($inscripciones[0]['TOTAL'])>0)
        {
?>
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                <div class="navdiv">
                    <script type="text/javascript">
                        navega();
                    </script>
                </div>            
            
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

            <br><a href="<?= $pagina.$ruta ?>">Publicar Inscripci&oacute;n autom&aacute;tica<br>
                 <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/inscripcion_hab.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                
            </a>
        </td>

    </tr><?            
    }else
        {
            ?>
            <tr class="centrar">
                <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/inscripcion_des.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                        <br>Publicar Inscripci&oacute;n autom&aacute;tica
                </td>
            </tr>
        <?
        
        }
    }
                if(is_array($inscripciones)&&($inscripciones[0]['TOTAL'])>0)
        {
                    
                }else{
                    if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
                    {
                    }else{if (is_array($inscripcion)){}else{

        
?>
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registro_inscripcionAutomaticaCoordinador";
                    $ruta.="&opcion=noEjecutarInscripcion";
                    $ruta.="&codProyecto=".$variable[0];
                    $ruta.="&nombreProyecto=".$variable[1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <br><br><a href="<?= $pagina.$ruta ?>"><font color="red">No ejecutar Inscripci&oacute;n autom&aacute;tica</font><br>(Solo se registrar&aacute;n los permisos para la inscripción de Espacios Académicos)<br>
                 <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/Modificar.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                
            </a>
        </td>

    </tr><?                    
                    }
                    
                    }
                    
                    }
    ?>
        </table>
    <?
                if($preinscritos==1)
                {
                    $this->mostrarListadoEstudiantes('1');
                }
                if($inscritos==1)
                {
                    $this->mostrarListadoEstudiantes('2');
                }
    
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
                        $cadena_sql=$this->sql->cadena_sql("datos_coordinador",$this->usuario);
                        $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $_REQUEST['planEstudio']=$planEstudio=$resultado_datosCoordinador[0][2];
                        $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                        $_REQUEST['nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];
                        
                        $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                    }

            $this->menuCoordinador($variable);
           
    }
    
    /**
     * Funcion que presenta el listado de estudiantes procesados con numero de preinscritas por demanda, inscritas por auto y porcentaje de inscripcion
     * @param type $estudiantes
     */
    function mostrarListadoEstudiantes($tipo){
        $e=1;
        switch ($tipo) {
            case '1':
                $tabla='acinspre';
                $resultados='parciales';
                $final=' (a&uacute;n no han sido publicados)';
                break;

            case '2':
                $tabla='acinspre';
                $resultados='finales';
                $final='';
                break;

            default:
                break;
        }
        $estudiantes=$this->consultarEstudiantesClasificados();
        $totalClasificados=count($estudiantes);
        $inscripciones=$this->consultarPreinscripciones();
        $totalPreinscripciones=count($inscripciones);
        $totalInscritos=$this->inscripciones[0][0];
        $totalPrenscritos=$this->consultarPreinscripcionesDemandaProyecto();
        $totalNoInscritos=$totalPrenscritos[0][0]-$totalInscritos;
        echo "<br>...:: <b>Resultados ".$resultados." del proceso de Inscripci&oacute;n Autom&aacute;tica".$final."</b> ::...";
        echo "<br>Total estudiantes con Preinscripciones por demanda: ".$totalPreinscripciones;
        echo "<br>Total espacios Preinscritos por demanda en el proyecto: ".$totalPrenscritos[0][0];
        echo "<br>Total estudiantes procesados: ".$totalClasificados;
        echo "<br>Total espacios inscritos: ".$totalInscritos;
        echo "<br>Total espacios no inscritos: ".$totalNoInscritos." (incluye espacios de estudiantes en vacaciones).<br><br>";
        if($totalClasificados>=1)
        {
            echo "<table class='contenidotabla' width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
            echo "<thead class='sigma'>
                <th class='niveles centrar' width='10'>No.</th>
                <th class='niveles centrar' width='15'>C&oacute;digo Estudiante</th>
                <th class='niveles centrar' width='15'>Preinscritas por demanda</th>
                <th class='niveles centrar' width='15'>Inscritas</th>
                <th class='niveles centrar' width='15'>% Preinscritas</th>
                <th class='niveles centrar' width='20'>Observaci&oacute;n</th>
                </thead>";

            foreach ($estudiantes as $key => $datos)
            {
                $datos['totalPreinscritos']=0;
                foreach ($inscripciones as $key2 => $total)
                {
                    if($datos['codEstudiante']==$total['CODIGO'])
                    {
                        $datos['totalPreinscritos']=$total['TOTAL'];
                    }
                }
                $datos['totalInscritosAuto']=0;
                if($datos['totalPreinscritos']>0){
                    $datos['totalInscritosAuto']=$this->consultarRegistrosProceso($tabla, $datos['codEstudiante']);
                    $porcentaje = number_format((($datos['totalInscritosAuto']/$datos['totalPreinscritos'])*100), 0)." %";
                    $observacion=" ";
                }else{
                    $porcentaje ="-";
                    $observacion="No realiz&oacute; preinscripci&oacute;n por demanda";
                }
                echo "<tr>";
                echo "<td class='cuadro_plano centrar' >".$e."</td>";$e++;
                echo "<td class='cuadro_plano centrar' >".$datos['codEstudiante']."</td>";
                echo "<td class='cuadro_plano centrar' >".$datos['totalPreinscritos']."</td>";
                echo "<td class='cuadro_plano centrar' >".$datos['totalInscritosAuto']."</td>";
                echo "<td class='cuadro_plano centrar' >".$porcentaje."</td>";
                echo "<td class='cuadro_plano' >".$observacion;
                echo "</tr>";
            }
        }
         echo "</table>";
    }    

    /**
     * Funcion que consulta los estudiantes con la clasificación de prioridad
     * @param <array> $datos
     * @return <int>
     */
    function consultarEstudiantesClasificados() {
        $cadena_sql=$this->sql->cadena_sql("consultarEstudiantesClasificados",$this->codProyecto);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
        return $resultado;
    }
    
    /**
     * Funcion que permite consultar los eventos de inscripcion automatica registrados en la tabla
     * @param type $variable
     * @return type
     */
    function consultarInscripcionAutomatica($variable) {
        $datos=array('codProyecto'=>$variable[0],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("consultarInscripcionAutomatica",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcion[0];
    }

    /**
     * Funcion que permite consultar el registro del permiso para realizar la inscripcion automatica
     * @param type $variable
     * @return type
     */
    function consultarEventoInscripcionAutomatica($variable) {
        $datos=array('codProyecto'=>$variable[0],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("eventoPreinscripcionAutomatica",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcion;
    }

    /*
     * Consulta el total de las inscripciones del proyecto en la tabla de acinspre
     */
    function consultarInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable[0],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("consultarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcionProyecto;
    }

    /**
     * Consulta el total de preinscripciones por demanda de cada estudiante del proyecto
     * @return type
     */
    function consultarPreinscripciones() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarPreinscripciones",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto;
    }

    /**
     * Consulta el total de inscripciones realizadas a traves de la inscripcion automatica para cada estudiante
     * @param type $tabla
     * @param type $codEstudiante
     * @return type
     */
    function consultarRegistrosProceso($tabla,$codEstudiante) {
        $datos=array('tabla'=>$tabla,
                    'codEstudiante'=>$codEstudiante,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarRegistrosProceso",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto[0][0];
    }

    /**
     * Funcion que permite consultar las preincripciones por demanda de los estudiantes del proyecto
     * @return type
     */
    function consultarPreinscripcionesDemandaProyecto() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarPreinscripcionesDemandaProyecto",$datos);
        $resultadoPreinscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoPreinscripcionProyecto;
    }
    
    /**
     * Funcion que consulta la ejecucion del proceso
     */
    function consultarEjecucionInscripcion() {
        $datos=array('codProyecto'=>  $this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("consultarEjecucionInscripcion",$datos);
        return $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }
    
    
}
?>
