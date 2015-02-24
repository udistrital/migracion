<?php
/**
 * Funcion adminInscripcionCoordinadorPosgrado
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 29/05/2013
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
 * Clase funcion_adminInscripcionCoordinadorCI
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_adminInscripcionCoordinadorCI extends funcionGeneral {

  private $configuracion;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionCoordinadorCI
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
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->sql=new sql_adminInscripcionCoordinadorCI($configuracion);
        $this->formulario="admin_inscripcionCoordinadorCI";

        
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
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        /**
         * Datos de sesion
         */
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        /**
         * Instancia para crear la conexion ORACLE
         */
         //Conexion ORACLE
        if($this->nivel==4){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        }elseif($this->nivel==110){
            $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }elseif($this->nivel==114){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }else{
            echo "EL PERFIL NO TIENE PERMISOS PARA ESTE MODULO";
        }
        $this->validacion=new validarInscripcion();

    }


    /**
     * Funcion que se encarga de mostrar los proyectos curriculares y planes de estudio que tiene a cargo el coordinador
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function verProyectos()
    {
        /*
         * Consulta los proyectos curriculares con su respectivo plan de estudio, y se muestra en un <select>
         */
        if($this->nivel==4){
            $cadena_sql_proyectos=$this->sql->cadena_sql("proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
            $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );  
        
        }elseif($this->nivel==110 || $this->nivel==114){
            $proyectos =$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel);
            
            if(is_array($proyectos)){
                    $cadena='';
                    foreach ($proyectos as $proyecto) {
                       if($cadena){
                           $cadena.= ", ".$proyecto['DEPENDENCIA'];

                       }else{
                           $cadena=$proyecto['DEPENDENCIA'];
                       }
                   }
                    $cadena_sql_proyectos=$this->sql->cadena_sql("proyectos_y_planes_asistentes",$cadena);//echo $cadena_sql_proyectos;exit;
                    $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );  

            }
        }
        
        
        if (is_array($resultado_proyectos))
        {
            if(count($resultado_proyectos)>1)
              {
              ?>
                <table class='sigma_borde centrar' align="center" width="80%" >
                    <caption class="sigma">SELECCIONE EL PLAN DE ESTUDIOS</caption>
                        <?
                            for($i=0;$i<count($resultado_proyectos);$i++)
                            {
                                if ((isset($resultado_proyectos[$i-1]['CODIGONIVEL'])?$resultado_proyectos[$i-1]['CODIGONIVEL']:'')!=$resultado_proyectos[$i]['CODIGONIVEL'])
                                {
                                  ?>
                                  <tr><th class="sigma_a centrar" colspan="4">NIVEL:<?echo $resultado_proyectos[$i]['NIVEL']?></th></tr>
                                      <th class="sigma centrar">Carrera</th>
                                      <th class="sigma centrar">Plan de Estudios</th>
                                      <th class="sigma centrar">Nombre</th>
                                      <th class="sigma centrar">Modalidad</th>
                                  <?
                                }?>
                                <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                                  <?
                                  $modulo=$this->definirModulo($resultado_proyectos[$i]);
                                  $enlace=$this->enlazarModulo($resultado_proyectos[$i],$modulo);
                                  if (trim($resultado_proyectos[$i]['CREDITOS'])=='S'){$modalidad='CR&Eacute;DITOS';}
                                  elseif (trim($resultado_proyectos[$i]['CREDITOS'])=='N'){$modalidad='HORAS';}
                                  ?>
                                  <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['PROYECTO']?></a></td>
                                  <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['PLAN']?></a></td>
                                  <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['NOMBRE']?></a></td>
                                  <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $modalidad?></a></td>
                                </tr>
                          <?
                          }
                        ?>
                </table>
                <?
              }else
                  {
                    $modulo=$this->definirModulo($resultado_proyectos[0]);
                    $enlace=$this->enlazarModulo($resultado_proyectos[0],$modulo);
                    //echo $enlace;exit;
                    echo "<script>location.replace('".$enlace."')</script>";
                    exit;

                  }
        }
        else
        {
        ?><table class='sigma_borde centrar' align="center" width="80%" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <caption class="sigma">No tiene proyectos registrados para realizar inscripci&oacute;n</caption>
          </table>
        <?
        }
    }

    /**
     * Funcion que permite definir el modulo que se utilizara para realizar inscripciones
     * @param <array> $proyecto
     * @return string
     */
    function definirModulo($proyecto) {

        $modulo="admin_inscripcionCoordinadorCI";
      
        return $modulo;
    }

    /**
     * Funcion que genera el enlace dependiendo del modulo
     * @param <array> $proyectos
     * @param <string> $modulo
     * @return <string> 
     */
    function enlazarModulo($proyectos,$modulo) {

      $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
      $variable="pagina=admin_inscripcionGrupoCoorCI";
      $variable.="&opcion=consultarPorNivel";
      $variable.="&codProyecto=".$proyectos['PROYECTO'];
      $variable.="&planEstudio=".$proyectos['PLAN'];
      $variable.="&nombreProyecto=".$proyectos['NOMBRE'];
      
      include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
      $this->cripto=new encriptar();
      $variable=$this->cripto->codificar_url($variable,$this->configuracion);
      return $pagina.$variable;
    }

    /**
     * Funcion que captura los valores del proyecto curricular y crea el menu de inscripcion por estudiante y grupo
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
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
                    $nombreProyecto=(isset($_REQUEST['nombreProyecto'])?$_REQUEST['nombreProyecto']:'');
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql;exit;
                        $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $planEstudio=$resultado_datosCoordinador[0]['PLAN'];
                        $codProyecto=$resultado_datosCoordinador[0]['PROYECTO'];
                        $nombreProyecto=$resultado_datosCoordinador[0]['NOMBRE'];

                        $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                    }

          
           
    }

}


?>
