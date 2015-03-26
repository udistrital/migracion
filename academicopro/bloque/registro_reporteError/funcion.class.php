<?php
/**
 * Funcion nombreFuncion
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 08/09/2011
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
class funcion_registroReporteError extends funcionGeneral
{

      public $configuracion;
      public $datosRemitente;

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
            
	    $this->cripto=new encriptar();
            
	    $this->sql=new sql_registroReporteError($configuracion);
            
            $this->formulario="registro_reporteError";//nombre del bloque que procesa el formulario
                           
            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");

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
 

            $this->verificar="control_vacio(".$this->formulario.",'asunto','')";              
            $this->verificar.="&control_vacio(".$this->formulario.",'contenido','')";              
            
	}

        
        
        /**
         *
         */
        function nuevoMensajeError() {
                       
            $this->mostrarFormulario();
        }        
        
       /**
         *
         */
        function mostrarFormulario() {                        

          ?>           
                      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
                            <fieldset>
                                <legend>Reportar error del Sistema<img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/bug.png" width="25" height="25" border="0"></legend>
                          <table>
                              <tr>                  
                                  <td>
                                      Asunto:
                                  </td>
                                  <td>
                                      <input type="text" name="asunto" size="50">
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="2">
                                      Por favor realice la descripci&oacute;n:
                                  </td>
                              </tr>
                              <tr id="fila" class="sigma" class="izquierda">
                                <td colspan="2">                                    
                                  <textarea rows="10" cols="90" name="contenido"></textarea>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                        <input type="hidden" name="opcion" value="enviarMensaje">                                                                    
                                        <input type="hidden" name="paginaRetorno" value="<?echo $_REQUEST['paginaRetorno']?>">
                                        <input type="hidden" name="opcionRetorno" value="<?echo $_REQUEST['opcionRetorno']?>">
                                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                        <input type="button" value="Enviar" name="aceptar" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/>
                                </td>                                        
                                <td>
                                        <input type="button" value="Cancelar" name="cancelar" onclick="<?$this->redireccionar('NoScript')?>">                                      
                                </td>
                              </tr>
                        </table>                     
                                                         
                            </fieldset>
                      </form>          
          <?                                    
        }
        

        function enviarMensaje() {

            $this->datosRemitente=$this->bucarDatosRemitente();            
            
            //se el registro de correo institucional no contiene el caracter @(arroba) se presenta mensaje y no envía correo 
            if(strstr($this->datosRemitente['MAIL_INSTITUCIONAL'], '@'))
                {
                $this->enviarCorreo();
                }
            else
                {
                    echo 'Su correo institucional no se encuentra registrado en el sistema, por favor comun&iacute;quese con su Proyecto Curricular!!';exit;
                }
            
            
            
        }
        
                
        function bucarDatosRemitente() {
            
              $variablesRemitente = array(  
                                            'codEstudiante' => $this->usuario
                                        );
                                            
              $cadena_sql = $this->sql->cadena_sql("buscarDatosRemitente", $variablesRemitente);
              $arreglo_emisor = $this->ejecutarSQL($this->configuracion,$this->accesoOracle, $cadena_sql, "busqueda");
                if(is_array($arreglo_emisor))
                    {
                  return $arreglo_emisor[0];
                    }
                else
                    {
                      echo 'No se rescataron los datos del Usuario';exit;
                  
                    }
                                          
                          
            
        }
 
        
        function enviarCorreo() {                              

            
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/mail/class.phpmailer.php");
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/mail/class.smtp.php");
            
            $mail = new PHPMailer();    
    
            //configuracion de cuenta de envio
            $mail->Host     = "mail.udistrital.edu.co";
            $mail->Mailer   = "smtp";
            $mail->SMTPAuth = true;
            $mail->Username = "condor@udistrital.edu.co";
            $mail->Password = "CondorOAS2012";
            $mail->Timeout  = 120;
            $mail->IsHTML(false);
            
            //remitente
            $mail->From     = $this->datosRemitente['MAIL_INSTITUCIONAL'];
            $mail->FromName = 'CONDOR- '.$this->datosRemitente['NOMBRE'];
            $contenido="Datos del estudiante:\n";
            $contenido.=' Usuario: '.$this->usuario. "\n";
            $contenido.=' Código: '.$this->datosRemitente['CODIGO']. "\n";
            $contenido.=' Nombre: '.$this->datosRemitente['NOMBRE']. "\n";
            $contenido.=' Proyecto: '.$this->datosRemitente['CODIGO_PROYECTO'].'-'.$this->datosRemitente['NOMBRE_PROYECTO']. "\n";
            $contenido.=' Mail institucional: '.$this->datosRemitente['MAIL_INSTITUCIONAL']. "\n";
            $contenido.=' Mail personal: '.$this->datosRemitente['MAIL_PERSONAL']. "\n\n";
            $contenido.= $_REQUEST["contenido"] . "\n\nEste mesaje ha sido enviado desde el módulo".$_REQUEST['paginaRetorno'];
            
            
            $mail->Body    = $contenido;
            $mail->Subject = $_REQUEST["asunto"];
            
            //destinatarios
            //$to_mail1 = ;       
            
            $mail->AddAddress('condor@udistrital.edu.co');
            
            if(!$mail->Send()) {

                    ?>
                  <script language='javascript'>
                      alert('Error! El mensaje no pudo ser enviado!');
                  </script>
                    <?
                    $this->redireccionar();
            } else {
                    ?>
                  <script language='javascript'>
                      alert('<?echo 'Mensaje enviado correctamente!'?>');
                  </script>                  
                    <?
                  $this->redireccionar();
            }            

        }
             
        /**
         *redirecciona a la pagina desde donde fue llamado el formulario de reporte de error 
         */
        function redireccionar($script="") {
                
                
                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                $variable="pagina=".$_REQUEST['paginaRetorno'];
                $variable.="&opcion=".$_REQUEST['opcionRetorno'];


                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                if($script=='NoScript')
                    {
                        echo "location.replace('".$pagina.$variable."')";  
                    }
                else
                    {
                        echo "<script>location.replace('".$pagina.$variable."')</script>";  
                    }
                
        }
        
        
}
?>
