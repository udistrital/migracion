
        <script language='javascript'>
            function seleccionarDestinatarios(enlace)
            {
                ventanaDestinatarios = window.open(enlace,'Codigos', 'width=700,height=700,scrollbars=yes');
            }

            function pasarNombreDestiantarios(cadena, miFormulario)
            {            

                formulario = opener.document.getElementById(miFormulario);
                formulario.para.value = cadena;
                close();
            }

            function pasarDatosDestinatarios(cadena, miFormulario)
            {

                formulario = opener.document.getElementById(miFormulario);
                formulario.receptor.value = cadena;
                close();
            }
            
            
            function ChequearTodos(chkbox, nombreFormulario)
            {
              
              //alert(nombreFormulario);                    
                for (var i=0;i < document.forms[nombreFormulario].elements.length;i++)
                {
                var elemento = document.forms[0].elements[i];
                    if (elemento.type == "checkbox")
                      {
                        elemento.checked = chkbox.checked
                      }
                }
            } 

            function verificarFormulario(formulario)
            {

                if( control_vacio(formulario,'receptor', 'Para:')&&control_vacio(formulario,'asunto', 'Asunto:') )
                  {                      
                      return true
                  }
                  else
                  {
                    return false;
                  }

            }


        </script>


<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package Comunicaciones
 * @subpackage Perfil Docente
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 25/07/2011
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
class funcion_registroMensajeDocente extends funcionGeneral
{

      public $configuracion;
      public $ano;
      public $periodo;
      public $codProyecto;
      public $planEstudio;
      public $codEspacio;
      public $grupo;
      public $nivelUsuario;

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

	    $this->sql=new sql_registroMensajeDocente($configuracion);
            
            $this->formulario="registro_mensajeDocente";//nombre del bloque que procesa el formulario

            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
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
            $this->nivelUsuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

              //Buscar año y periodo activos
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}
        
        /**
         *
         */
        function mostrarFormularioMensaje() {

          //direccion de ventana para seleccionar varios destinatarios
          $enlaceDestinatarios=$this->crearEnlaceSeleccionDestinatarios();

          ?>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
        <table id="tabla" class="sigma ">
<!--          <tr id="fila" class="sigma" class="izquierda">
            <td>
              De:
            </td>
          </tr>-->
          <tr id="fila" class="sigma" class="izquierda">
            <td>
                <a href='javascript:seleccionarDestinatarios("<?echo $enlaceDestinatarios?>")'>Para:</a>
            </td>
            <td>
              <textarea rows="1" cols="80" name="para" readonly="readonly" disabled="disabled">Por favor seleccione los destinatarios haciendo click en la palabra "Para:"...</textarea>
            </td>
          </tr>
          <tr id="fila" class="sigma" class="izquierda">
            <td>
              Asunto:
            </td>
            <td>
              <input type="text" name="asunto" size="70">
            </td>
          </tr>
          <tr id="fila" class="sigma" class="izquierda">
            <td colspan="2">
              <textarea rows="10" cols="90" name="contenido"></textarea>
            </td>
          </tr>
        </table>       
              <input type="hidden" name="codProyecto" value="<?echo $this->codProyecto?>">
              <input type="hidden" name="opcion" value="enviarMensaje">
              <input type="hidden" name="receptor">
              <input type="hidden" name="action" value="<? echo $this->formulario ?>">
              <input class="boton" type="button" value="enviar" name="enviar" onclick="if(verificarFormulario(<?echo $this->formulario?>)){document.forms['<? echo $this->formulario?>'].submit()}else{false}"><br>

      </form>

          
          <?

        }

        /**
         * registrarMensaje
         * 
         * Esta funcion da formato a los datos recibidos contiene los métodos para
         * el registro del mensaje en la base de datos.
         */
        function registrarMensaje() {

          $codigoMensaje=$this->reservarCodigoMensaje();
          $this->insertarMensaje($codigoMensaje);
          $datosReceptor=$this->explodeDatosReceptor();
          foreach ($datosReceptor as $key => $value) {
            $this->insertarMensajeReceptor($datosReceptor[$key],$codigoMensaje);
          }

          $this->redireccionarEnviado();
          

}

        /**
         *
         */
        function seleccionarDestinatarios() {
                        
          $estudianteDestinatario=  $this->buscarEstudianteDestinatario();
          $coordinadorDestinatario=  $this->buscarCoordinadorDestinatario();          
                    
          ?>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
        <fieldset>
          <legend>Por favor seleccione los destinatarios:</legend>      
          <input type="checkbox" name="checkbox11" value="checkbox" onClick="ChequearTodos(this,'<?echo $this->formulario?>');">&nbsp;Todos

        <table border='1' cellspacing='0' cellpadding='1'>
                        <tr>
                          <td>C&oacute;digo</td>
                          <td>Nombre</td>
                          <td>Tipo</td>
                        </tr>
            <?
                        
            
            foreach ($coordinadorDestinatario as $key => $value) {

              ?>
                        <tr>
                            <td>
                              <input type="checkbox" name="<?echo 'coordinador['.$key.']'?>" value="<?echo $value['CODIGO'].','.$value['APELLIDO'].' '.$value['NOMBRE'].','.$value['TIPO']?>">
                              <b>
                              <?echo $value['CODIGO']?>
                              </b>
                            </td>
                            <td>
                              <b>
                              <?echo $value['APELLIDO'].' '.$value['NOMBRE']?>
                              </b>  
                            </td>
                            <td>
                              <b>
                              <?//presenta el tipo de estudiante, HORAS o CREDITOS
                                if(trim($value['TIPO'])=='28')
                                  {
                                  echo 'COORDINADOR';
                                  }
                                  else{
                                    echo 'N/D';
                                  }
                              ?>
                              </b>
                            </td>
                        </tr>

                <?
            }            

            
            
            foreach ($estudianteDestinatario as $key => $value) {

              ?>
                        <tr>
                            <td>
                              <input type="checkbox" name="<?echo 'estudiante['.$key.']'?>" value="<?echo $value['CODIGO'].','.$value['NOMBRE_ESTUDIANTE'].','.$value['TIPO']?>">
                              <?echo $value['CODIGO']?>
                            </td>
                            <td>
                              <?echo $value['NOMBRE_ESTUDIANTE']?>
                            </td>
                            <td>
                              <?//presenta el tipo de estudiante, HORAS o CREDITOS
                                if(trim($value['TIPO'])=='S')
                                  {
                                  echo 'ESTUDIANTE CREDITOS';
                                  }
                                 elseif (trim($value['TIPO'])=='N') {
                                  echo 'ESTUDIANTE HORAS';
                                  }
                                  else{
                                    echo 'N/D';
                                  }
                              ?>
                            </td>
                        </tr>

                <?
            }
            ?>

        </table>
        </fieldset>
              <input type="hidden" name="opcion" value="pasarDestinatario">
              <input type="hidden" name="action" value="<? echo $this->formulario ?>">
              <button class="boton" type="submit" value="enviar" name="enviar">Listo</button>
        </form>
          <?
          
        }

        /**
         * 
         */
        function enviarDestinatarios() {

        $datosDestinatario=$this->explodeDestinatario();
        
        $cadenaNombres=$this->armarCadenaNombres($datosDestinatario);
        $cadenaDatosReceptor=$this->armarCadenaDatosReceptor($datosDestinatario);

        ?>
          <script language='javascript'>
            pasarNombreDestiantarios("<?echo $cadenaNombres?>","<? echo $this->formulario ?>")
            pasarDatosDestinatarios("<?echo $cadenaDatosReceptor  ?>","<? echo $this->formulario ?>")
          </script>
          <?

}

        /**
         *
         */
        function crearEnlaceSeleccionDestinatarios() {
          
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=registro_mensajeDocente";
            $variable.="&opcion=destinatarios";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable= $this->cripto->codificar_url($variable, $this->configuracion);
            
            return $pagina.$variable;
                


        }

        /**
         *
         */
        function buscarEstudianteDestinatario() {

              $variablesUsuario = array('codUsuario' => $this->usuario);

              $cadena_sql = $this->sql->cadena_sql("buscarEstudianteDestinatario", $variablesUsuario);
              $arreglo_destinatario = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_destinatario;

        }

        /**
         *
         */
        function buscarCoordinadorDestinatario() {
          
              $variablesDocente = array(  
                                            'codProyecto' => $_REQUEST['codProyecto']
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarCoordinadorDestinatario", $variablesDocente);//echo $cadena_sql;exit;
              $arreglo_coordinador = $this->ejecutarSQL($this->configuracion,$this->accesoOracle, $cadena_sql, "busqueda");              
              return $arreglo_coordinador;

        }

        /**
         *
         * @return <type> Array
         */
        function explodeDestinatario() {
          
          foreach ($_REQUEST['destinatario'] as $key => $value) {

          $desExplotado[$key]=explode(',', $_REQUEST['destinatario'][$key]);
          $destinatario[$key]['CODIGO']=$desExplotado[$key][0];
          $destinatario[$key]['NOMBRE']=$desExplotado[$key][1];
          $destinatario[$key]['TIPO']=$desExplotado[$key][2];
          }

          return $destinatario;
}

        /**
         *armarCadenaNombres
         *
         * Se esambla una cadena con los nombres para pasarla al formulario del mensaje en
         * el cammpo "Para:"
         *
         *
         * @param <type> $datosEstudiante
         * @return <type>
         */
        function armarCadenaNombres($datosEstudiante) {

          foreach ($datosEstudiante as $key => $value) {
            //echo $key.'=>'.$value['NOMBRE'].'<br>';
            $cadenaNombres.=$value['NOMBRE'].',';

          }

           //quita el ultimo caracter de la cadena, en este caso la ultima coma (,)
            $cadenaNombres = substr ($cadenaNombres, 0, strlen($cadenaNombres) - 1);

            return $cadenaNombres;
}

        /**
         *armarCadenaReceptor
         *
         * Ensambla una cadena con codigo,tipo,nombre para cada uno de los receptores,
         * cada receptor esta seperado por punto y coma (;) y cada dato del receptor se separa por coma (,)
         * esto para para facilitar la separación de los datos con la funcion explode.
         * @param <type> $datosReceptor
         * @return <type> 
         */
        function armarCadenaDatosReceptor($datosReceptor) {

          foreach ($datosReceptor as $key => $value) {
            //echo $key.'=>'.$value['NOMBRE'].'<br>';
            $cadenaDatos.=$value['CODIGO'].',';
            //define el tipo de estudiante horas=51, creditos=52
            if(trim($value['TIPO'])=='S')
              {
              $cadenaDatos.='52,';
              }
             elseif (trim($value['TIPO'])=='N') {
              $cadenaDatos.='51,';
              }
             elseif (trim($value['TIPO'])=='28') {
              $cadenaDatos.='28,';
              }              
              else{
                echo 'N/D';
              }

            $cadenaDatos.=$value['NOMBRE'].';';
            
          }

           //quita el ultimo caracter de la cadena, en este caso el último punto y coma (;)
            $cadenaDatos = substr ($cadenaDatos, 0, strlen($cadenaDatos) - 1);

            //echo $cadenaDatos;exit;

            return $cadenaDatos;
}

        /**
         *reservarCodigoMensaje
         *
         * se obtiene el valor del siguiente registro con la funcion nextval de Oracle
         * para luego registrar el mensaje  y los receptores con el codigo reservado
         * @return <type>
         */
        function reservarCodigoMensaje() {

              $cadena_sql = $this->sql->cadena_sql("buscarCodigoMensaje", "");
              $codigoMensaje = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

              if($codigoMensaje[0]['NEXTVAL'])
                {
                  return $codigoMensaje[0]['NEXTVAL'];
                }
              else
                {
                  echo 'No se puede obtener un c&oacute;digo del mensaje';
                  exit;
                }
              

}

        /**
         *
         * @return type 
         */
        function explodeDatosReceptor() {

          $receptor=explode(';', $_REQUEST['receptor']);
                    
          foreach ($receptor as $key => $value) {
            $receptor[$key]=explode(',', $receptor[$key]);
          $receptor[$key]['CODIGO']=$receptor[$key][0];
          $receptor[$key]['TIPO']=$receptor[$key][1];
          $receptor[$key]['NOMBRE']=$receptor[$key][2];          
          }
          return $receptor;          
}

        /**
         * redireccionarEnviado
         *
         * Luego de un envio de mensaje exitoso redirecciona la pagina
         */
        function redireccionarEnviado() {

              $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
              $variable="pagina=admin_mensajeDocente";
              $variable.="&opcion=verReporteEnvioMensajesRecibidos";
              $variable.="&codProyecto=".$this->codProyecto;
              include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
              $this->cripto=new encriptar();
              $variable=$this->cripto->codificar_url($variable,  $this->configuracion);


              echo "<script>location.replace('".$pagina.$variable."')</script>";

}

        /**
         *
         * @param <type> $param
         */
        function reporteEnvio() {
          
          ?>
            <div style="color:#13120d;background-color:#fff1a8;font-family:vardana;font-size:16px;text-align:center;width: 250px">
              Su mensaje ha sido enviado!
            </div>
          <?

}

        /**
         *inserta los datos del mensaje en la tabla acmensaje
         *
         * @param <type> $codigoMensaje codigo reservado para para la insercion del mensaje
         * @return <type>
         */
        function insertarMensaje($codigoMensaje) {

              $variablesMensaje = array(  'codigoMensaje' => $codigoMensaje,
                                          'asunto' => $_REQUEST['asunto'],
                                          'contenido' => $_REQUEST['contenido'],
                                          'tipoEmisor' => '30',
                                          'codigoEmisor' => $this->usuario,
                                        );

              //verficar que se realice la insercion
              $cadena_sql = $this->sql->cadena_sql("insertarMensaje", $variablesMensaje);//echo $cadena_sql;exit;
              $arreglo_destinatario = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
              if($arreglo_destinatario==TRUE)
                {
                  return $arreglo_destinatario;
                }
                else
                  {
                    echo 'No se puede registrar el Mensaje';
                    exit;
                  }

}

        /**
         *
         * @param <type> $datosReceptor
         * @param <type> $codigoMensaje
         * @return <type>
         */
        function insertarMensajeReceptor($datosReceptor,$codigoMensaje) {

              $variablesMensajeReceptor = array(  'codigoMensaje' => $codigoMensaje,
                                                  'tipoReceptor' => $datosReceptor['TIPO'],
                                                  'codigoReceptor' => $datosReceptor['CODIGO'],
                                                  'estadoMensajeReceptor' => 1
                                        );                                       
              
              //verficar que se realice la insercion
              $cadena_sql = $this->sql->cadena_sql("insertarMensajeReceptor", $variablesMensajeReceptor);//echo $cadena_sql;exit;
              $arreglo_MensajeReceptor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
              
              if($arreglo_MensajeReceptor==TRUE)
                {
                  return $arreglo_MensajeReceptor;
                }
                else
                  {
                    echo 'No se puede enviar el Mensaje al destinatario: '.$datosReceptor['CODIGO'].', '.$datosReceptor['NOMBRE'];
                    exit;
                  }

}

}
?>
