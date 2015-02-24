<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reglasConsejerias.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/phpmailer.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_admin_actualizarDatosEgresado extends funcionGeneral {

  public $configuracion;
  public $numero = 0; //cuenta las filas de la tabla de estudiantes asociados
  public $usuario;
  public $nivel;
  public $datosEstudiante;
  public $datosEgresado;
  public $datosGraduando;
  public $periodo;
  public $registroTipoDocumento;
  public $registroMunicipios;
  public $directorGrado;
  public $registroTitulos;
  public $secretarios;
  public $rectores;
  //public $datosDocente;
  //private $notasDefinitivas;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->sql = new sql_admin_actualizarDatosEgresado($configuracion);
    $this->log_us = new log();
    $this->reglasConsejerias = new reglasConsejerias();
    $this->validacion=new validarInscripcion();
    $this->formulario="admin_actualizarDatosEgresado";//nombre del bloque que procesa el formulario
    $this->bloque="egresados/admin_actualizarDatosEgresado";//nombre del bloque que procesa el formulario
    $this->formularioActualiza="admin_actualizarDatosEgresado";//nombre del bloque que procesa el formulario
    $this->bloqueActualiza="egresados/admin_actualizarDatosEgresado";//nombre del bloque que procesa el formulario

    //Conexion General
    $this->acceso_db = $this->conectarDB($this->configuracion, "");
    $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

    //Datos de sesion
    $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");

    //Conexion ORACLE
    
        $this->accesoOracle = $this->conectarDB($this->configuracion, "oraclesga");
        
    if($this->usuario=="")
    {
        echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
        EXIT;
    }

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($this->configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    
    $this->verificar="control_vacio(".$this->formulario.",'codigo','')";   
  }
    
    function consultarEgresado() {
        
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
        $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
        
        
        //echo $datoBusqueda;exit;
        $datoBusqueda=trim($datoBusqueda);
        $datoValido = $this->validarDatoBusqueda($datoBusqueda);
        if(strlen($datoBusqueda)<5)
        {
            echo "El dato para buscar debe contener al menos 5 caracteres";
            exit;
        }
        if($datoValido==true){
                    if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                        if($tipoBusqueda=='codigo'){
                            $codEstudiante = $datoBusqueda;
                        }  
                        if($tipoBusqueda=='identificacion'){
                            $codEstudiante = $this->consultarCodigoEgresadoPorIdentificacion($datoBusqueda);
                            if(is_array($codEstudiante )){
                                    $this->mostrarListadoProyectos($codEstudiante);
                                }
                        }

                    }else{
                        if($tipoBusqueda=='codigo'){
                            echo "C&oacute;digo de egresado no v&aacute;lido";
                        }
                        if($tipoBusqueda=='identificacion'){
                            echo "Identificac&oacute;n de egresado no v&aacute;lida";
                        }
                    }
                    
                if((isset($codEstudiante)?$codEstudiante:'') && !is_array($codEstudiante)){

                    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
                    $this->datosEgresado=$this->consultarDatosEgresado($codEstudiante,$this->datosEstudiante['CODIGO_CRA']);
                }

                if (is_array($this->datosEstudiante)) {
                        
                        $this->periodo=$this->buscarPeriodoActivo();
                        
                    ?>
                        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                  <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
                      <tr>
                        <td>
                    <?
                        $this->mostrarDatosEgresado();
                        $this->mostrarDatosContacto();
                      
                    ?>
                        </td>
                      </tr>
                      
                  </table>
                        </form>
                    <?
                } else {
            ?>
                  <table class="contenidotabla centrar">
                    <tr>
                      <td class="cuadro_brownOscuro centrar">
                        NO SE ENCONTRARON LOS DATOS DEL EGRESADO!
                      </td>
                    </tr>
                  </table>
            <?
                }
        }else{
                    echo "Valor no v&aacute;lido para la b&uacute;squeda";
            }
  }

  function mostrarDatosEgresado() {

    $this->buscarPeriodoActivo();
      ?>

      <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
          <tr>
            <td>
              <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                <caption class="sigma centrar"><? echo "DATOS DEL EGRESADO"; ?></caption>
                <tr>
                  <td>
                    <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                      <tr>
                        <td class='cuadro_plano' style="border:0px">Nombre: </td>
                        <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['NOMBRE'] ?></td>
                  </td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px" width="20%">C&oacute;digo: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['CODIGO'] ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Carrera: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['CODIGO_CRA'] . " - " . $this->datosEstudiante['NOMBRE_CRA'] ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Identificaci&oacute;n: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['IDENTIFICACION'])?$this->datosEstudiante['IDENTIFICACION']:'') ?></td>
                </tr>
               
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </tbody>
</table>

<?
                  
                }

  
    /**
     * 
     */
    function mostrarDatosContacto() {
    $tab=0;
    ?>
    <style type="text/css">.mayus {text-transform: uppercase;}</style>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
            <tr>
                <td>
                    <?
                    $html ="<center>";
                    $html .="<table class='contenidotabla sigma' width='100%' >";
                    $html .="<caption class='sigma centrar'>DATOS DE CONTACTO EGRESADO</caption>";   
                    $html .="<tr>";
                    $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Direcci&oacute;n:</b> ".(isset($this->datosEgresado['DIRECCION'])?$this->datosEgresado['DIRECCION']:'')."</td>";
                    $html .="</tr>";
                    $html .="<tr>";
                    $html .="   <td class='cuadro_plano' style='border:0px' width='50%'><b>Tel&eacute;fono Residencia:</b> ".(isset($this->datosEgresado['TELEFONO'])?$this->datosEgresado['TELEFONO']:'')."</td>";
                    $html .="   <td class='cuadro_plano' style='border:0px' width='50%'><b>Celular:</b> ".(isset($this->datosEgresado['CELULAR'])?$this->datosEgresado['CELULAR']:'')."</td>";
                    $html .="</tr>";
                    $html .="<tr>";
                    $html .="   <td class='cuadro_plano' style='border:0px'><b>E-Mail:</b> ".(isset($this->datosEgresado['EMAIL'])?$this->datosEgresado['EMAIL']:'')."</td>";
                    $html .="   <td class='cuadro_plano' style='border:0px' width='10%'><b>Empresa:</b> ".(isset($this->datosEgresado['EMPRESA'])?$this->datosEgresado['EMPRESA']:'')."</td>";
                    $html .="</tr>";
                    $html .="<tr>";
                    $html .="   <td class='cuadro_plano' style='border:0px'><b>Direcci&oacute;n Empresa:</b> ".(isset($this->datosEgresado['DIRECCION_EMPRESA'])?$this->datosEgresado['DIRECCION_EMPRESA']:'')."</td>";
                    $html .="   <td class='cuadro_plano' style='border:0px'><b>Tel&eacute;fono Empresa:</b> ".(isset($this->datosEgresado['TELEFONO_EMPRESA'])?$this->datosEgresado['TELEFONO_EMPRESA']:'')."</td>";
                    $html .="</tr>";
                    $html .="</table>";
                    $html .="</center><br><br>";
                    echo $html;
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                        <tr>
                            <td>
                                <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                                   
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;E-Mail:
                                        </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><? if(isset($this->datosEgresado['EMAIL'])) {?><input type='text' name='correoElectronico' id="correoElectronico" size="50" maxlength="150" value='<? echo $this->datosEgresado['EMAIL']; ?>'><?}else{?><input type="text" name='correoElectronico' id="correoElectronico" size="40" maxlength="50" ><?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10" align="center" ><br>
                                            <? $this->enlaceConsultar('Actualizar correo','actualizarDatosContacto');?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <?
    }

  
    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosEstudiante($codEstudiante) {
               
        $variables=array(
                            'codEstudiante'=>$codEstudiante
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEstudiante", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
    
    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosEgresado($codEstudiante,$codProyecto) {
               
        $variables=array(
                            'codEstudiante'=>$codEstudiante,
                            'proyecto'=>$codProyecto
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEgresado", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
    
   
    function buscarPeriodoActivo() {
               
        $variables=array(
                           
                        );   

        $cadena_sql = $this->sql->cadena_sql("periodoActivo", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado;
        
    }
    
    function consultarCodigoEgresadoPorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_egresado_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
    
    
    function mostrarListadoProyectos($codigos){
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        if(is_array($codigos)){
            echo "<br>C&oacute;digos relacionados a la busqueda:";
            echo "<br><br><table align='center' >";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=enviarCodigo";
                    $variable.="&action=".$this->bloque;
                    $variable.="&tipoBusqueda=codigo";
                    $variable.="&datoBusqueda=".$codigo['CODIGO'];
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['CODIGO'];?></a></td>
                        <? if (isset($codigo['NOMBRE'])?$codigo['NOMBRE']:''){?>
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['NOMBRE'];?></a></td>
                        <? }?>
                        <td><a href="<? echo $pagina.$variable;?>"><? echo " Proyecto: ".$codigo['COD_PROYECTO']." - ".$codigo['PROYECTO'];?></a></td>
                    </tr>
  <?          }
            echo "</table>";
            
}
    }
    
    
    /**
     * Función para mostrar enlace que envia los datos para consultar informacion de estudiante
     */
    function enlaceConsultar($nombre,$tipo)
        {
            ?><input type='hidden' name='action' value="<? echo $this->bloqueActualiza?>">
              <input type='hidden' name='opcion' value="actualizar">
              <input type='hidden' name='proyectoCurricular' value="<? echo $this->datosEstudiante['CODIGO_CRA'];?>">
              <input type='hidden' name='codEstudiante' value="<? echo $this->datosEstudiante['CODIGO'];?>">
              <input value="<?echo $nombre;?>" name="<?echo $tipo;?>" type="submit" >
            <?
        }
    
    /**
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ1234567890äÄëËïÏöÖüÜçÇ ";
        for ($i=0; $i<strlen($cadena); $i++)
        {
            if (strpos($permitidos, substr($cadena,$i,1))===false)
            {
                //no es válido;
                return false;
            }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }
    
    
}
?>
