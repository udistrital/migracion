<?php
/**
 * Funcion admin_mensajeCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package admin_mensajeCoordinador
 * @subpackage Consulta
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 21/07/2011
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
class funcion_mensajeCoordinador extends funcionGeneral
{

      public $configuracion;
      public $ano;
      public $periodo;
      public $codProyecto;
      public $planEstudio;
      public $codEspacio;
      public $grupo;

        /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion) {

            $this->configuracion=$configuracion;
            $this->codProyecto=(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
            $this->planEstudio=(isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'');
            $this->codEspacio=(isset($_REQUEST['codEspacio'])?$_REQUEST['codEspacio']:'');
            $this->grupo=(isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'');
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

	    $this->sql=new sql_mensajeCoordinador($configuracion);
            
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


              //Buscar año y periodo activos
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}

        /**
         *
         */
        function armarVistaMensajesRecibidos() {

          $mensaje=$this->buscarMensajesRecibidos();

          if($mensaje!=false)
            {

          ?>
          <table class="sigma contenidotabla">
              <?
                $this->mostrarEncabezadoTablaRecibidos();
                foreach ($mensaje as $key => $value) {
                  $nombreEmisor=$this->buscarNombreEmisor($mensaje[$key]);
                  ?>
                  <tr class="cuadro_color">
                    <?$this->mostrarMensajesRecibidos($nombreEmisor,$mensaje[$key]);?>
                  </tr>
                  <?

                }


          ?>
            </table>
          <?

            }
          else
            {
                ?><br>
                  <div style="color:#13120d;background-color:#fff1a8;font-family:vardana;font-size:16px;text-align:center;width: 450px">
                    No existen mensajes recibidos para este per&iacute;odo!
                  </div>
                <?
            }
        }

        /**
         *
         */
        function armarVistaMensajesEnviados() {

          $mensaje=$this->buscarMensajesEnviados();

          if($mensaje!=false)
            {
          
          ?>
                    
            <table class="sigma contenidotabla">
                <?
                  $this->mostrarEncabezadoTablaEnviados();

                  foreach ($mensaje as $key => $value) {
                    $nombreReceptor=$this->buscarNombreReceptor($mensaje[$key]);
                    ?>
                    
                      <?$this->mostrarMensajesEnviados($nombreReceptor,$mensaje[$key]);?>

                    <?

                  }


            ?>
              </table>
            <?            
            
            }
          else
            {
                ?><br>
                  <div style="color:#13120d;background-color:#fff1a8;font-family:vardana;font-size:16px;text-align:center;width: 450px">
                    No existen mensajes enviados para este per&iacute;odo!
                  </div>
                <?
            }

        }

        /**
         *
         */
        function buscarMensajesRecibidos() {
          
              $variablesMensaje = array('codUsuario' => $this->usuario);

              $cadena_sql = $this->sql->cadena_sql("buscarMensajesRecibidos", $variablesMensaje);//echo $cadena_sql;exit;
              $arreglo_mensaje = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_mensaje;

        }
        
        /**
         *
         */
        function buscarMensajesEnviados() {

              $variablesMensaje = array('codUsuario' => $this->usuario);

              $cadena_sql = $this->sql->cadena_sql("buscarMensajesEnviados", $variablesMensaje);//echo $cadena_sql;exit;
              $arreglo_mensaje = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_mensaje;

        }

        /**
         *
         */
        function mostrarEncabezadoTablaRecibidos() {
        ?>
              <caption class="sigma">Mensajes Recibidos</caption>
              <tr>
                  <th class="sigma" colspan="2">
                    De
                  </th>
                  <th class="sigma" colspan="1">
                    Perfil
                  </th>
                  <th class="sigma" colspan="4">
                    Asunto
                  </th>
                  <th class="sigma" colspan="1">
                    Fecha
                  </th>
              </tr>
            <?
}

        /**
         *
         */
        function mostrarEncabezadoTablaEnviados() {
        ?>
              <caption class="sigma">Mensajes Enviados</caption>
              <tr>
                  <th class="sigma" colspan="2">
                    Para
                  </th>
                  <th class="sigma" colspan="1">
                    Perfil
                  </th>
                  <th class="sigma" colspan="4">
                    Asunto
                  </th>
                  <th class="sigma" colspan="1">
                    Fecha
                  </th>
              </tr>
            <?
}

        /**
         *
         */
        function buscarNombreEmisor($mensaje) {

          switch ($mensaje['TIPO_EMISOR']) {
              case 51:
                
                return $this->buscarNombreEstudiante($mensaje['CODIGO_EMISOR']);

                break;

              case 52:

                return $this->buscarNombreEstudiante($mensaje['CODIGO_EMISOR']);

                break;

              default:
                
                return $this->buscarNombreDocente($mensaje['CODIGO_EMISOR']);

                break;
    }


}
    
        /**
         *
         */
        function buscarNombreReceptor($mensaje) {

          switch ($mensaje['TIPO_RECEPTOR']) {
              case 51:

                return $this->buscarNombreEstudiante($mensaje['CODIGO_RECEPTOR']);

                break;

              case 52:

                return $this->buscarNombreEstudiante($mensaje['CODIGO_RECEPTOR']);

                break;

              default:

                return $this->buscarNombreDocente($mensaje['CODIGO_RECEPTOR']);

                break;
    }


}

        /**
         *
         * @param <type> $codigo del estudiante
         */
        function buscarNombreEstudiante($codigo) {


                  $variablesEstudiante = array('codigo' => $codigo);

                  $cadena_sql = $this->sql->cadena_sql("buscarNombreEstudiante", $variablesEstudiante);
                  $nombre_Estudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                  return $nombre_Estudiante[0]['NOMBRE'];


    }

        /**
         *
         * @param <type> $documento del docente 
         */
        function buscarNombreDocente($documento) {

              $variablesDocente = array('documento' => $documento);

              $cadena_sql = $this->sql->cadena_sql("buscarNombreDocente", $variablesDocente);
              $arreglo_Docente = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

              $nombreDocente=$arreglo_Docente[0]['APELLIDO'].' '.$arreglo_Docente[0]['NOMBRE'];

              return $nombreDocente;

        }

        /**
         *
         * @param <type> $nombreEmisor
         * @param <type> $datosMensaje
         */
        function mostrarMensajesRecibidos($nombreEmisor, $datosMensaje) {

            //Enlace contenido mensaje recibido
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeContenidoCoordinador";
            $variable.="&opcion=verContenidoMensajeRecibido";
            $variable.="&codProyecto=".$this->codProyecto;            
            $variable.="&codigoMensaje=".$datosMensaje['CODIGO'];            
            $variable.="&asunto=".$datosMensaje['ASUNTO'];            
            $variable.="&contenido=".$datosMensaje['CONTENIDO'];                  
            $variable.="&codigoEmisor=".$datosMensaje['CODIGO_EMISOR'];
            $variable.="&nombreEmisor=".$nombreEmisor;            
            $variable.="&tipoEmisor=".$datosMensaje['TIPO_EMISOR'];
            $variable.="&estadoMensaje=".$datosMensaje['ESTADO_MENSAJE'];
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
          
                ?>
              <tr class="cuadro_color" style="cursor:pointer <?if($datosMensaje['ESTADO_MENSAJE']==1){echo ';font-weight: bold';} ?>" onclick="location='<?echo $pagina.$variable?>'">
                       <td colspan="1"><?echo $datosMensaje['CODIGO_EMISOR']?></td>
                       <td colspan="1"><?echo $nombreEmisor?></td>
                       <td colspan="1">
                          <?
                       if($datosMensaje['TIPO_EMISOR']==51 OR $datosMensaje['TIPO_EMISOR']==52)
                         {echo 'Estudiante';}
                         else
                         {echo 'Docente';}
                         ?>
                       </td>
                       <td colspan="4"><?echo $datosMensaje['ASUNTO']?></td>
                       <td colspan="1"><?echo $datosMensaje['FECHA']?></td>
                       
              </tr>

       <?

        }

        /**
         *
         */
        function mostrarMensajesEnviados($nombreReceptor, $datosMensaje) {

            
            //Enlace contenido mensaje enviado   
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_mensajeContenidoCoordinador";
            $variable.="&opcion=verContenidoMensajeEnviado";
            $variable.="&codProyecto=".$this->codProyecto;            
            $variable.="&asunto=".$datosMensaje['ASUNTO'];            
            $variable.="&contenido=".$datosMensaje['CONTENIDO'];                  
            $variable.="&codigoReceptor=".$datosMensaje['CODIGO_RECEPTOR'];
            $variable.="&nombreReceptor=".$nombreReceptor;            
            $variable.="&tipoReceptor=".$datosMensaje['TIPO_RECEPTOR'];
            
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
                       
            <tr class="cuadro_color" style="cursor:pointer" onclick="location='<?echo $pagina.$variable?>'">

                       <td colspan="1"><?echo $datosMensaje['CODIGO_RECEPTOR']?></td>
                       <td colspan="1"><?echo 'Para: '.$nombreReceptor?></td>
                       <td colspan="1">
                          <?
                       if($datosMensaje['TIPO_RECEPTOR']==51)
                         {echo 'Estudiante Horas';}
                       elseif ($datosMensaje['TIPO_RECEPTOR']==52)
                         {echo 'Estudiante Cr&eacute;ditos';}
                       elseif ($datosMensaje['TIPO_RECEPTOR']==28)
                         {echo 'Coordinador';}
                       elseif ($datosMensaje['TIPO_RECEPTOR']==30)
                         {echo 'Docente';}
                         else
                         {echo 'N/D';}
                         ?>
                       </td>
                       <td colspan="4"><?echo $datosMensaje['ASUNTO']?></td>
                       <td colspan="1"><?echo $datosMensaje['FECHA']?></td>
       
                                 
                    </tr>                       
                       
                       
                       <?

        }

}
?>
