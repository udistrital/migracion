<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 23/06/2011
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
class funcion_adminConsultarPreinscritosDemandaPorProyecto extends funcionGeneral
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
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");
            
	    $this->cripto=new encriptar();

	    $this->sql=new sql_adminConsultarPreinscritosDemandaPorProyecto($configuracion);
            
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
	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");


            /**
             * buscar los datos de ano y periodo actuales
             */
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}

    /**
     * Funcion de desambiguacion para que el coordinador seleccione el proyecto curricular que desea consultar
     */    
    function verProyectos() {
        
        //proyectos asociados al coordinador
        $proyectosAsociados=$this->buscarProyectosAsociados($this->usuario);

        if(count($proyectosAsociados)>1){

        $nombreUsuario=  $this->buscarNombreUsuario($this->usuario);
            
        ?>
<table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>PROYECTOS CURRICULARES ASOCIADOS A <br><?echo $nombreUsuario[0]['NOMBRES']." ".$nombreUsuario[0]['APELLIDOS']?></h4>
            <hr noshade class="hr">
        </td>
    </tr><br>
    <tr class="centrar">
        <td class="cuadro_color centrar" colspan="2">
          SELECCIONE EL PROYECTO CURRICULAR
        </td>
    </tr>
    <tr>
      <td class="cuadro_color centrar" width="20%"><b>C&oacute;digo</b></td>
      <td class="cuadro_color centrar" width="80%"><b>Nombre</b></td>
    </tr>


        <?
            for($i=0;$i<count($proyectosAsociados);$i++) {
                ?>
                    <tr>

                <?
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=admin_consultarPreinscritosDemandaPorProyecto";
                $variable.="&opcion=reporte";
                $variable.="&modalidad=S";
                $variable.="&codProyecto=".$proyectosAsociados[$i]['PROYECTO'];
                $variable.="&nombreProyecto=".$proyectosAsociados[$i]['NOMBRE'];

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                ?>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $proyectosAsociados[$i]['PROYECTO']?></a></td>
            <td class="cuadro_plano"><a href="<?echo $pagina.$variable?>"><?echo $proyectosAsociados[$i]['NOMBRE']?></a></td>
                </tr>
    <?

            }
        ?>

    
</table>

        <?
    }else
        {
            
        $modalidad=$_REQUEST['modalidad'];
            $_REQUEST['codProyecto']=$proyectosAsociados[0]['PROYECTO'];
            $_REQUEST['nombreProyecto']=$proyectosAsociados[0]['NOMBRE'];            
            $this->mostrarReporte($modalidad);
        }


    }        

    /**
     *
     * @param type $usuario numero de identificacion de usuario
     * @return type 
     */
    function buscarProyectosAsociados($usuario) {
                            
              $variables = array('usuario' => $usuario,
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarProyectosAsociados", $variables);
              $arreglo_proyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_proyectos;
        
    }    
    
    /**
     *
     * @param type $usuario
     * @return type 
     */
    function buscarNombreUsuario($usuario) {
                            
              $variables = array('usuario' => $usuario,
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarNombreUsuario", $variables);
              $arreglo_nombre = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_nombre;
        
    }      
    
    /**
         *
         */
    function mostrarReporte() {
   
        //modalidad creditos=S Horas=N
        $modalidad=$_REQUEST['modalidad'];
        $this->mostrarDatosProyecto();
        $nivelesPlan=  $this->buscarNivelesPlan($_REQUEST['codProyecto'], $modalidad);      
        if(!is_array($nivelesPlan)){
            echo 'NO EXISTEN ESPACIOS ACAD&Eacute;MICOS EN ';
                if($modalidad=='S'){
                    echo 'CR&Eacute;DITOS';
                }
                else{
                    echo 'HORAS';                    
                    }
               echo ' PARA EL PLAN DE ESTUDIOS';
            exit;
        }
               ?><table class="Cuadricula" style="font-size:12px"><?
                    
                        foreach ($nivelesPlan as $key => $value) {
                            
                            ?><tr><td align="center"  colspan="4"><b><?echo 'PER&Iacute;ODO '.$value['NIVEL'];?></b></td></tr><?
                            
                            
                            $espaciosNivel=$this->buscarEspaciosNivel($_REQUEST['codProyecto'], $value['NIVEL'],$modalidad);                            
                            $this->mostrarEspaciosNivel($espaciosNivel);
                            ?><tr><td align="center"  colspan="4"><hr></b></td></tr><?
                        }
                        
                ?></table>

                    <br>
                    <?        

        }

     /**
     *
     */
    function mostrarDatosProyecto() {
      
        ?>                
        <div class="h1">
            <table align="center">
                <tr class="encabezado_registro centrar">
                    <td>
                        <b><?echo $_REQUEST['codProyecto']?></b>
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        <b><?echo $_REQUEST['nombreProyecto']?></b>
                    </td>              
                </tr>
                <tr class="encabezado_registro centrar">
                    <td>
                    </td>                    
                    <td>
                    </td>                    
                    <td>
                        <b><?  $this->enlaceReporteCreditos()?> |</b> 
                        <b><?  $this->enlaceReporteHoras()?></b>
                    </td>                    
                </tr>
            </table>            
        </div>             
                
        <?
                 

    }                                
        
    /**
     *
     * @param type $usuario
     * @return type 
     */
    function buscarNivelesPlan($proyecto,$modalidad) {
        
              $variables = array('codProyecto' => $proyecto,
                                 'modalidad' => $modalidad
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarNivelesPlan", $variables);
              $arreglo_niveles = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_niveles;
        
    }      
    
    /**
     *
     * @param type $usuario
     * @return type 
     */
    function buscarEspaciosNivel($proyecto, $nivel, $modalidad) {
                            
              $variables = array('codProyecto' => $proyecto,
                                 'nivel' => $nivel,
                                 'modalidad' => $modalidad
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarEspaciosNivel", $variables);
              $arreglo_espacios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_espacios;
        
    }      
    
    /**
     *
     * @param type $espacios 
     */
    function mostrarEspaciosNivel($espacios) {
                        ?>
                    <tr align="center">
                        <td>C&oacute;digo</td>
                        <td>Espacio Acad&eacute;mico</td>
                        <td>Preinscritos<br>Proyecto</td>
                        <td>Preinscritos<br>Facultad</td>
                    </tr>
                <?
        
        foreach ($espacios as $key => $espacio) {
          $enlace=$this->crearEnlaceProyecto($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$espacio['CODIGO']);
          $totalPreinscritosEspacioProyecto=$this->contarPreinscritosEspacioProyecto($_REQUEST['codProyecto'],$espacio['CODIGO']);
          $codigoFacultad=  $this->buscarCodigoFacultad($_REQUEST['codProyecto']);
          $totalPreinscritosEspacioFacultad=$this->contarPreinscritosEspacioFacultad($codigoFacultad,$espacio['CODIGO'])
                ?>
                    <tr class='bloquecentralcuerpo' onclick="location.href='<?=$enlace?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td class="contenidotabla" border="1px"><?echo $espacio['CODIGO']?></td>
                        <td align="left"><?echo $espacio['NOMBRE']?></td>
                        <td align="center"><?echo $totalPreinscritosEspacioProyecto?></td>
                        <td align="center"><?echo $totalPreinscritosEspacioFacultad?></td>
                    </tr>
                <?       
        }
                
    }

    /**
     *
     * @param type $proyecto
     * @param type $espacio
     * @return type 
     */
    function contarPreinscritosEspacioProyecto($proyecto,$espacio) {
              
    $variables = array('codProyecto' => $proyecto,
                       'codEspacio' => $espacio,
                       'ano'=>$this->ano,
                       'periodo'=>  $this->periodo,
                       );      
                                
      $cadena_sql = $this->sql->cadena_sql("contarPreinscritosEspacioProyecto", $variables);
      $numeroEstudiantesPreinscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      
      return $numeroEstudiantesPreinscritos[0][0];

    }    
   

    function buscarCodigoFacultad($proyecto) {
              
        $variables = array('codProyecto' => $proyecto,
                       );      
                                
      $cadena_sql = $this->sql->cadena_sql("buscarCodigoFacultad", $variables);
      $codigoFacultad = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      
      return $codigoFacultad[0][0];                
        
    }


    function contarPreinscritosEspacioFacultad($facultad, $espacio) {
        
        $variables = array('codFacultad' => $facultad,
                           'codEspacio' => $espacio,
                           'ano'=>$this->ano,
                           'periodo'=>  $this->periodo
                       );      
                                
      $cadena_sql = $this->sql->cadena_sql("contarPreinscritosEspacioFacultad", $variables);
      $preinscritosEspacioFacultad = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      
      return $preinscritosEspacioFacultad[0][0];             
        
        
        
    }

    function crearEnlaceProyecto($codProyecto,$nombreProyecto,$codEspacio) {
           
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=admin_consultarPreinscritosDemandaPorProyecto";
            $ruta.= "&opcion=consultarListadoEstudiantes";
            $ruta.= "&codEspacio=" . $codEspacio;
            $ruta.= "&codProyecto=" . $codProyecto;
            $ruta.= "&nombreProyecto=" . $nombreProyecto;
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            return $enlace=$indice.$ruta;

}


    function mostrarListadoEstudiantesProyecto() {                      

      $datosEspacio=$this->buscarDatosEspacio($_REQUEST['codEspacio']);
      $this->mostrarDatosEspacio($datosEspacio);

      $this->mostrarEnlaceRetorno($datosEspacio, $_REQUEST['codProyecto'],$_REQUEST['nombreProyecto']);
      
        $numeroPreinscritos=$this->buscarNumeroEstudiantesPreinscritosProyecto($_REQUEST['codProyecto'],$_REQUEST['codEspacio']);      
        $preinscritos=  $this->buscarEstudiantesPreinscritosProyecto($_REQUEST['codProyecto'],$_REQUEST['codEspacio']);   
      if (is_array($preinscritos)&& $preinscritos[0][0]>0)
      {        
                
        $this->PresentarDatosProyecto($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$numeroPreinscritos);
        $this->presentarEstudiantesInscritos($preinscritos);
      }else
      {
        $this->presentarDatosProyecto($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$numeroPreinscritos);
        $this->presentarNoEstudiantesInscritos();
      }
    }

    /**
         *
         */
    function buscarDatosEspacio($codigo) {
                       
              $variablesEspacios = array('codEspacio' => $codigo,
                                         'ano'=>$this->ano,
                                         'periodo'=>  $this->periodo,
                                         'opcion' => codigo);

              $cadena_sql = $this->sql->cadena_sql("buscarDatosEspacio", $variablesEspacios);
              $arreglo_datos_espacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_datos_espacio[0];
    }     
    

    function mostrarDatosEspacio($datosEspacio) {
      
        ?>                
        <div class="h1">
            <table align="center">
                <tr class="encabezado_registro centrar">
                    <td>
                        <b><?echo $datosEspacio['CODIGO']?></b>
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        <b><?echo $datosEspacio['NOMBRE']?></b>
                    </td>
                </tr>
            </table>            
        </div>             
                
        <?
                 

    }    
    
    
function buscarNumeroEstudiantesPreinscritosProyecto($proyecto,$espacio) {
              
    $variables = array('codProyecto' => $proyecto,
                       'codEspacio' => $espacio,
                       'ano'=>$this->ano,
                       'periodo'=>  $this->periodo,
                       );      
      
      $cadena_sql = $this->sql->cadena_sql("buscarNumeroEstudiantesPreinscritos", $variables);
      $numeroEstudiantesPreinscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      
      return $numeroEstudiantesPreinscritos[0][0];

}

    function buscarEstudiantesPreinscritosProyecto($proyecto, $espacio) {
        
    $variables = array('codProyecto' => $proyecto,
                       'codEspacio' => $espacio,
                       'ano'=>$this->ano,
                       'periodo'=>  $this->periodo,
                       );         
        
      $cadena_sql = $this->sql->cadena_sql("buscarEstudiantesPreinscritosProyecto", $variables);//echo $cadena_sql;exit;
      return $datosEstudiantesInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

}    
   
    function presentarDatosProyecto($codigo,$nombre,$preinscritos) {
      ?>
        <table class='contenidotabla'>
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar' colspan="1"><?echo $codigo;?></td>
                  <td class='cuadro_plano centrar' colspan="2"><?echo $nombre;?></td>
                  <td class='cuadro_plano centrar'>Preinscritos: <?echo $preinscritos;?></td>
              </tr>
              <?

    }
 
    function presentarNoEstudiantesInscritos() {
      ?>

              <tr class="cuadro_color">
                <td class='cuadro_plano centrar' colspan="5">NO HAY ESTUDIANTES PREINSCRITOS</td>
              </tr>
          </table>
      <?
    }    
 
    
   function presentarEstudiantesInscritos($datosInscritos) {
      ?>
        
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar'>Nro</td>
                  <td class='cuadro_plano centrar'>Cod. Estudiante</td>
                  <td class='cuadro_plano centrar' width="250">Nombre</td>
                  <td class='cuadro_plano centrar'>Estado</td>
              </tr>
              <?  for ($a = 0; $a < count($datosInscritos); $a++) {
                
              ?>
              <tr>
                  <td class='cuadro_plano centrar'><?echo $a+1;?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a]['CODIGO'];?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a]['NOMBRE'];?></td>
                  <?
                  switch($datosInscritos[$a]['ESTADO'])
                  {
                  case "A":
                      $estado="Activo";
                      break;

                  case "B":
                      $estado="Prueba y Activo";
                      break;

                  case "J":
                      $estado="Prueba y Vacaciones";
                      break;

                  case "V":
                      $estado="Vacaciones";
                      break;

                  default :
                      $estado="Inactivo";
                    break;

                  }
                  ?>
                  <td class='cuadro_plano centrar'><?echo $estado;?>
              </tr>
              <?}?>
          </table>
      <?
    }
  
    function mostrarEnlaceRetorno($espacio,$codProyecto, $nombreProyecto) {
        
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=admin_consultarPreinscritosDemandaPorProyecto";
            $ruta.= "&opcion=reporte";
            $ruta.= "&codEspacio=" . $espacio['CODIGO'];
            $ruta.= "&modalidad=" . $espacio['MODALIDAD'];
            $ruta.= "&codProyecto=" . $codProyecto;
            $ruta.= "&nombreProyecto=" . $nombreProyecto;

            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            $enlace=$indice.$ruta;            
            ?>
            <a href="<?echo $enlace?>">Regresar</a>
            <?
            
    }    
    
    
    function enlaceReporteCreditos() {
       
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=admin_consultarPreinscritosDemandaPorProyecto";
            $ruta.= "&opcion=reporte";
            $ruta.= "&modalidad=S";
            $ruta.= "&codProyecto=".$_REQUEST['codProyecto'];
            $ruta.= "&nombreProyecto=".$_REQUEST['nombreProyecto'];
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            $enlace=$indice.$ruta;            
            ?>
            <a href="<?echo $enlace?>">Cr&eacute;ditos</a>
            <?                
            
    }    
    
    function enlaceReporteHoras() {
       
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=admin_consultarPreinscritosDemandaPorProyecto";
            $ruta.= "&opcion=reporte";
            $ruta.= "&modalidad=N";
            $ruta.= "&codProyecto=".$_REQUEST['codProyecto'];
            $ruta.= "&nombreProyecto=".$_REQUEST['nombreProyecto'];
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            $enlace=$indice.$ruta;            
            ?>
            <a href="<?echo $enlace?>">Horas</a>
            <?                
            
    }    
    
}
?>
