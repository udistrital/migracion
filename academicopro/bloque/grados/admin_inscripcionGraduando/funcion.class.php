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
class funcion_admin_inscripcionGraduando extends funcionGeneral {

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
    $this->sql = new sql_admin_inscripcionGraduando($configuracion);
    $this->log_us = new log();
    $this->reglasConsejerias = new reglasConsejerias();
    $this->validacion=new validarInscripcion();
    $this->formulario="admin_inscripcionGraduando";//nombre del bloque que procesa el formulario
    $this->bloque="grados/admin_inscripcionGraduando";//nombre del bloque que procesa el formulario
    $this->formularioActualiza="registro_actualizarDatosGraduando";//nombre del bloque que procesa el formulario
    $this->bloqueActualiza="grados/admin_inscripcionGraduando";//nombre del bloque que procesa el formulario
    $this->formularioRegistro="registro_registrarDatosGraduando";//nombre del bloque que procesa el formulario
    $this->bloqueRegistro="grados/registro_registrarDatosGraduando";//nombre del bloque que procesa el formulario

    //Conexion General
    $this->acceso_db = $this->conectarDB($this->configuracion, "");
    $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

    //Datos de sesion
    $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");
//    var_dump($this->usuario);
//    var_dump($this->nivel);
//    var_dump($this->identificacion);

    //Conexion ORACLE
    if ($this->nivel==83)
    {
        $this->accesoOracle = $this->conectarDB($this->configuracion, "secretarioacad");
    }elseif($this->nivel==116 ||$this->nivel==114){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }elseif($this->nivel==4)
        {
            $this->accesoOracle = $this->conectarDB($this->configuracion, "coordinador");
        }elseif($this->nivel==110)
        {
            $this->accesoOracle = $this->conectarDB($this->configuracion, "asistente");
        }else
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
            }
        
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
    
    function consultarEstudiante() {
        
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
                            $codEstudiante = $this->consultarCodigoEstudiantePorIdentificacion($datoBusqueda);
                            if(is_array($codEstudiante )){
                                    $this->mostrarListadoProyectos($codEstudiante);
                                }
                        }

                    }else{
                        if($tipoBusqueda=='codigo'){
                            echo "C&oacute;digo de estudiante no v&aacute;lido";
                        }
                        if($tipoBusqueda=='identificacion'){
                            echo "Identificac&oacute;n de estudiante no v&aacute;lida";
                        }
                    }
                    if($tipoBusqueda=='nombre'){
                        $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
                        if(is_array($codEstudiante )){
                                $this->mostrarListadoProyectos($codEstudiante);
                            }
                    }
                if((isset($codEstudiante)?$codEstudiante:'') && !is_array($codEstudiante)){

                    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
                    if ( is_array($this->datosEstudiante))
                    {
                    $this->datosEgresado=$this->consultarDatosEgresado($codEstudiante,$this->datosEstudiante['CODIGO_CRA']);
                    $this->datosGraduando=$this->consultarDatosGraduando($codEstudiante,$this->datosEstudiante['CODIGO_CRA']);
                    }
                }

                if (is_array($this->datosEstudiante)) {
                        if($this->nivel==83){
                                    $verificacion=$this->validacion->validarFacultadSecAcademico($codEstudiante,$this->usuario);        
                                    if($verificacion!='ok')
                                        {
                                            ?>
                                                  <table class="contenidotabla centrar">
                                                    <tr>
                                                      <td class="cuadro_brownOscuro centrar">
                                                          <?echo $verificacion;?>
                                                      </td>
                                                    </tr>
                                                  </table>
                                            <?
                                            exit;
                                        }
                        }  
                        if($this->nivel==116){
                                            $verificacion=$this->validacion->validarFacultadAsistente($codEstudiante,$this->usuario);        
                                            if($verificacion!='ok')
                                                {
                                                    ?>
                                                          <table class="contenidotabla centrar">
                                                            <tr>
                                                              <td class="cuadro_brownOscuro centrar">
                                                                  <?echo $verificacion;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                    <?
                                                    exit;
                                                }
                                            }
                    if($this->nivel==4){
                        $datosVerificacion=array('planEstudio'=>  $this->datosEstudiante['PENSUM'],
                                                'codEstudiante'=>  $this->datosEstudiante['CODIGO']
                                                );
                        $verificacion=$this->validacion->validarEstudiante($datosVerificacion);
                        if(!is_array($verificacion))
                            {
                                ?>
                                      <table class="contenidotabla centrar">
                                        <tr>
                                          <td class="cuadro_brownOscuro centrar">
                                              <?echo $verificacion;?>
                                          </td>
                                        </tr>
                                      </table>
                                <?
                                exit;
                            }
                        }
                    if($this->nivel==114 ||$this->nivel==110){
                        $verificacion=$this->validacion->validarProyectoAsistente($this->datosEstudiante['CODIGO'],$this->usuario);
                        if(!is_array($verificacion)&&$verificacion!='ok')
                            {
                                ?>
                                      <table class="contenidotabla centrar">
                                        <tr>
                                          <td class="cuadro_brownOscuro centrar">
                                              <?echo $verificacion;?>
                                          </td>
                                        </tr>
                                      </table>
                                <?
                                exit;
                            }
                        }
                        $this->periodo=$this->buscarPeriodoActivo();
                        $this->registroTipoDocumento=$this->consultarTiposDocumentos();
                        $this->registroMunicipios=$this->consultarMunicipios();
                        $this->directorGrado=$this->consultarDirectorGrado();
                        $this->registroTitulos=$this->consultarTitulosGrado();
                        $this->secretarios=$this->consultarSecretarios();
                        $this->rectores=$this->consultarRectores();
                    ?>
                        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                  <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
                      <tr>
                        <td>
                    <?
                      $this->mostrarDatosEstudiante();
                      $this->mostrarDatosContacto();
                      $this->mostrarDatosTrabajoGrado();
                      if ($this->nivel==83 || $this->nivel==116)
                        {
                            $this->mostrarFormDatosGrado();
                        }elseif($this->nivel==4)
                            {
                                $this->mostrarDatosGrado();
                            }
                    ?>
                        </td>
                      </tr>
                      <?if(!is_array($this->datosEgresado))
                      {
                      ?>
                    <tr>
                        <td colspan="10" align="center" ><hr>
                            <? $this->enlaceConsultar('Registrar datos Egresado','registrarDatos');?>
                        </td>
                    </tr>
                      <?}?>
                  </table>
                        </form>
                    <?
                } else {
            ?>
                  <table class="contenidotabla centrar">
                    <tr>
                      <td class="cuadro_brownOscuro centrar">
                        NO SE ENCONTRARON LOS DATOS DEL ESTUDIANTE!
                      </td>
                    </tr>
                  </table>
            <?
                }
        }else{
                    echo "Valor no v&aacute;lido para la b&uacute;squeda";
            }
  }


    /**
     * 
     */
    function mostrarDatosEstudiante() {
    $tab=0;
    if(isset($this->datosEgresado['APELLIDO']))
        {$this->datosEstudiante['APELLIDO']=$this->datosEgresado['APELLIDO'];}
    if(isset($this->datosEgresado['NOMBRE']))
        {$this->datosEstudiante['NOMBRE']=$this->datosEgresado['NOMBRE'];}
        elseif(isset($this->datosEgresado['APELLIDO']))
        {$nombre=  explode(' ', $this->datosEgresado['APELLIDO']);
        $this->datosEstudiante['APELLIDO']=$nombre[1];
        $this->datosEstudiante['NOMBRE']=$nombre[0];
        }
    if(isset($this->datosEstudiante['NOMBRE'])&&!isset($this->datosEstudiante['APELLIDO']))
    {
        $nombre=  explode(' ', $this->datosEstudiante['NOMBRE']);
        $this->datosEstudiante['APELLIDO']=$nombre[0];
        $this->datosEstudiante['NOMBRE']=$nombre[1];
    }
    if(isset($this->datosEgresado['IDENTIFICACION']))
        {$this->datosEstudiante['IDENTIFICACION']=$this->datosEgresado['IDENTIFICACION'];}
    if(isset($this->datosEgresado['TIPO_IDENTIFICACION']))
        {$this->datosEstudiante['TIPO_IDENTIFICACION']=$this->datosEgresado['TIPO_IDENTIFICACION'];}
    if(isset($this->datosEgresado['SEXO']))
        {$this->datosEstudiante['SEXO']=$this->datosEgresado['SEXO'];}
    
      ?><link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
        <style type="text/css">.mayus {text-transform: uppercase;}</style>
                    <form enctype='multipart/form-data' method='POST' action='index.php' name='form1'>
                    </form>
        
        <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
            <tbody>
                <tr>
                  <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                      <caption class="sigma centrar"><? echo "DATOS DEL ESTUDIANTE"; ?></caption>
                      <tr><td class='cuadro_plano centrar' style="border:0px">Todos los campos marcados con <font color="red">*</font> son obligatorios.</td></tr>
                      <tr>
                        <td>
                          <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                            <tr>
                              <td style="border:0px" rowspan="12" >


                                <?
                                //ruta de la foto para validar si existe
                                $fotoLocal=$this->configuracion['raiz_appserv'].'/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg';
                                //ruta de la foto para presentarla
                                $fotoWeb=$this->configuracion['host'].'/appserv/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg'; 

                                $archivo=  @fopen($fotoLocal,"r");
                                if(!$archivo)
                                      { 
                                        $fotoLocal=$this->configuracion['raiz_appserv'].'/est_fotos/'.$this->datosEstudiante['CODIGO'].'.JPG';
                                        $fotoWeb=$this->configuracion['host'].'/appserv/est_fotos/'.$this->datosEstudiante['CODIGO'].'.JPG';	
                                        $archivo=  @fopen($fotoLocal,"r");		
                                      }
                                //si la foto existe la muestra si no presenta una imagen por defecto
                                if($archivo){

                                  echo '<img src="'.$fotoWeb.'" height="180" border="0">';
                                  }else
                                    {
                                    echo '<img src="'.$this->configuracion['site'] . $this->configuracion['grafico'].'/personal.png" width="80" height="80" border="0">';
                                    }
                                ?>                         
                              </td>
                              </tr>
                              <tr>
                              <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Nombres: </td>
                              <td class='cuadro_plano' colspan="3" style="border:0px"><input type="text" name='nombreEstudiante' id="nombreEstudiante" size="40" class="mayus" <? if($this->datosEstudiante['NOMBRE']) echo "value='".$this->datosEstudiante['NOMBRE']."'";?> ></td>
                      </tr>
                      <tr>
                          <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Apellidos: </td>
                          <td class='cuadro_plano' colspan="3" style="border:0px"><input type="text" name='apellidoEstudiante' id="apellidoEstudiante" size="40" class="mayus" <? if($this->datosEstudiante['APELLIDO']) echo "value='".$this->datosEstudiante['APELLIDO']."'";?> ></td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px" >&nbsp;&nbsp;C&oacute;digo: </td>
                        <td class='cuadro_plano' colspan="3" style="border:0px"><? echo $this->datosEstudiante['CODIGO']; ?><input type='hidden' name='codEstudiante' value="<? echo $this->datosEstudiante['CODIGO']; ?>"></td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px">&nbsp;&nbsp;Carrera: </td>
                        <td class='cuadro_plano' colspan="3" style="border:0px"><? echo $this->datosEstudiante['CODIGO_CRA'] . " - " . $this->datosEstudiante['NOMBRE_CRA'] ?></td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px">&nbsp;&nbsp;Plan de Estudios: </td>
                        <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['PENSUM'])?$this->datosEstudiante['PENSUM']:'No registra dato'); ?></td>
                        <td class='cuadro_plano' style="border:0px">Modalidad: </td>
                        <td class='cuadro_plano' style="border:0px"><? if(trim($this->datosEstudiante['MODALIDAD'])=='S')
                        {echo "CR&Eacute;DITOS";}
                        elseif(trim($this->datosEstudiante['MODALIDAD'])=='N')
                        {echo "HORAS";}
                        else {"SIN DATO";}?></td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Genero: </td>
                          <td colspan="3" >
                              <?
                              $html_genero="<select id='genero' tabindex='".$tab++."' size='1' name='genero'>";
                              $html_genero.="<option value=''>Seleccione uno</option>  ";
                              $generos=array('F'=>'FEMENINO',
                                              'M'=>'MASCULINO');
                              foreach ($generos as $key => $genero) {
                                      $html_genero.="<option value='".$key."' ";
                                                              if((isset($this->datosEstudiante['SEXO'])?$this->datosEstudiante['SEXO']:'')==$key){
                                                                   $html_genero.=" selected ";
                                                              }
                                       $html_genero.=" >".$genero."</option>  ";
                              }
                              $html_genero.="</select>";
                              echo $html_genero;
                              ?>

                          </td>
                      </tr>
                      <tr>
                          <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Tipo Identificaci&oacute;n: </td>
                          <td colspan="3">
                              <?
                              $html_tipoDoc="<select id='tipoIdentificacion' tabindex='".$tab++."' size='1' name='tipoIdentificacion'>";
                              $html_tipoDoc.="<option value=''>Seleccione uno</option>  ";          
                              foreach ($this->registroTipoDocumento as $key => $tipoDoc) {
                                      $html_tipoDoc.="<option value='".$tipoDoc[0]."' ";
                                      if($this->datosEstudiante['TIPO_IDENTIFICACION']==$tipoDoc[0]){
                                           $html_tipoDoc.=" selected ";
                                      }
                                       $html_tipoDoc.=" >".$tipoDoc[1]."</option>  ";          
                              }
                              $html_tipoDoc.="</select>";
                              echo $html_tipoDoc;

                              ?>
                          </td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Identificaci&oacute;n: </td>
                        <td class='cuadro_plano' colspan="3" style="border:0px"><? if(isset($this->datosEstudiante['IDENTIFICACION'])) {?><input type='text' name='identificacion' id="identificacion" size="20" maxlength="14" value='<? echo $this->datosEstudiante['IDENTIFICACION']; ?>' onKeyPress="return solo_numero_sin_slash(event)"><?}else{?><input type="text" name='identificacion' id="identificacion" size="20" maxlength="14" onKeyPress="return solo_numero_sin_slash(event)">  Digite el valor sin comas ni puntos.<?}?>
                        </td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Lugar de Expedici&oacute;n: </td>
                          <td colspan="3" >
                              <?
                              $html_municipios="<select id='lugarExpedicion' tabindex='".$tab++."' size='1' name='lugarExpedicion'>";
                              $html_municipios.="<option value='0'>Seleccione uno</option>  ";          
                              foreach ($this->registroMunicipios as $key => $municipio) {
                                      $html_municipios.="<option value='".$municipio[0]."' ";
                                                              if((isset($this->datosEgresado['LUGAR_DOCUMENTO'])?$this->datosEgresado['LUGAR_DOCUMENTO']:'')==$municipio[0]){
                                                                   $html_municipios.=" selected ";
                                                              }
                                       $html_municipios.=" >".$municipio[1]."</option>  ";          
                              }
                              $html_municipios.="</select>";
                              echo $html_municipios;
                              ?>

                          </td>
                      </tr>
                      <tr>
                          <td class='cuadro_plano' style="border:0px">&nbsp;&nbsp;Libreta Militar:</td>
                          <td width="200px"><input type="text" name='libretaMilitar' id="libretaMilitar" size="18"  <? if(isset($this->datosEstudiante['LIBRETA'])) echo "value='".$this->datosEstudiante['LIBRETA']."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                          </td>
                          <td class='cuadro_plano' style="border:0px">Distrito Militar:</td>
                          <td ><input type="text" name='distritoMilitar' id="distritoMilitar" size="2" maxlength="2" <? if(isset($this->datosEstudiante['DISTRITO_MILITAR'])) echo "value='".$this->datosEstudiante['DISTRITO_MILITAR']."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                          </td>
                      </tr>
                      <tr>
                        <td class='cuadro_plano' style="border:0px"><font color="red">*</font>&nbsp;Estado Actual: </td>
                        <?
                        if (trim($this->datosEstudiante['ESTADO'])!='E' && ($this->nivel==83 ||$this->nivel==116))
                        {
                        ?>
                          <td colspan="3">
                              <?
                              $html_estado="<select id='estado' tabindex='".$tab++."' size='1' name='estado'>";
                              $html_estado.="<option value='".$this->datosEstudiante['ESTADO']."'>".$this->datosEstudiante['DESC_ESTADO']."</option>  ";          
                              $html_estado.="<option value='E' ";
                              $html_estado.=" >ESTUDIANTE GRADUADO</option>  ";
                              $html_estado.="</select>";
                              if (trim($this->datosEstudiante['FALLECIDO'])=='S'){$html_estado= " - <strong>FALLECIDO</strong>";}
                              echo $html_estado;

                              ?>
                          </td><?}else{?>
                        <td class='cuadro_plano' colspan="3" style="border:0px"><? echo $this->datosEstudiante['DESC_ESTADO']; if (trim((isset($this->datosEstudiante['FALLECIDO'])?$this->datosEstudiante['FALLECIDO']:''))=='S'){echo " - <strong>FALLECIDO</strong>";}?></td>
                          <?}?>
                      </tr>
                      <?if(is_array($this->datosEgresado))
                      {
                      ?>
                      <tr>
                          <td colspan="5" align="center" ><br>
                              <? $this->enlaceConsultar('Actualizar datos básicos','actualizarDatosBasicos');?>
                          </td>
                      </tr>
                      <?}?>
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
    if(isset($this->datosEgresado['TELEFONO']))
        {$this->datosEstudiante['TELEFONO']=$this->datosEgresado['TELEFONO'];}
    if(isset($this->datosEgresado['CELULAR']))
        {$this->datosEstudiante['CELULAR']=$this->datosEgresado['CELULAR'];}
    if(isset($this->datosEgresado['EMAIL']))
        {$this->datosEstudiante['EMAIL']=$this->datosEgresado['EMAIL'];}
    ?>
    <style type="text/css">.mayus {text-transform: uppercase;}</style>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
            <tr>
                <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                        <caption class="sigma centrar"><? echo "DATOS DE CONTACTO"; ?></caption>
                        <tr>
                            <td>
                                <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Direcci&oacute;n: </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><input type="text" name='direccion' id="direccion" size="40" class="mayus" <? if((isset($this->datosEgresado['DIRECCION']))){ echo "value='".$this->datosEgresado['DIRECCION']."'";}else {echo "value='".(isset($this->datosEstudiante['DIRECCION'])?$this->datosEstudiante['DIRECCION']:'')."'";}?> ></td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Ciudad de Residencia: </td>
                                        <td colspan="4" >
                                            <?
                                            $html_municipios="<select id='ciudadResidencia' tabindex='".$tab++."' size='1' name='ciudadResidencia'>";
                                            $html_municipios.="<option value='0'>Seleccione uno</option>  ";          
                                            foreach ($this->registroMunicipios as $key => $municipio) {
                                                    $html_municipios.="<option value='".$municipio[0]."' ";
                                                                            if($this->datosEstudiante['POBLACION']==$municipio[0]){
                                                                                 $html_municipios.=" selected ";
                                                                            }
                                                     $html_municipios.=" >".$municipio[1]."</option>  ";          
                                            }
                                            $html_municipios.="</select>";
                                            echo $html_municipios;
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Tel&eacute;fono Residencia:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;<? if(isset($this->datosEstudiante['TELEFONO'])) {?><input type='text' name='telefonoFijo' id="telefonoFijo" size="10" maxlength="10" value='<? echo $this->datosEstudiante['TELEFONO']; ?>' onKeyPress="return solo_numero_sin_slash(event)"><?}else{?><input type="text" name='telefonoFijo' id="telefonoFijo" size="10" maxlength="10" onKeyPress="return solo_numero_sin_slash(event)">  Digite el valor sin comas ni puntos.<?}?>
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Celular:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;<? if(isset($this->datosEstudiante['CELULAR'])) {?><input type='text' name='telefonoCelular' id="telefonoCelular" size="10" maxlength="10" value='<? echo $this->datosEstudiante['CELULAR']; ?>' onKeyPress="return solo_numero_sin_slash(event)"><?}else{?><input type="text" name='telefonoCelular' id="telefonoCelular" size="10" maxlength="10" onKeyPress="return solo_numero_sin_slash(event)">  Digite el valor sin comas ni puntos.<?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;E-Mail:
                                        </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><? if(isset($this->datosEstudiante['EMAIL'])) {?><input type='text' name='correoElectronico' id="correoElectronico" size="50" maxlength="150" value='<? echo $this->datosEstudiante['EMAIL']; ?>'><?}else{?><input type="text" name='correoElectronico' id="correoElectronico" size="40" maxlength="50" ><?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Empresa:
                                        </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><? if(isset($this->datosEgresado['EMPRESA'])) {?><input type='text' name='empresa' id="empresa" size="50" maxlength="150" class="mayus" value='<? echo $this->datosEgresado['EMPRESA']; ?>'><?}else{?><input type="text" name='empresa' id="empresa" size="50" maxlength="50" class="mayus"><?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Direcci&oacute;n Empresa:
                                        </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><? if(isset($this->datosEgresado['DIRECCION_EMPRESA'])) {?><input type='text' name='direccionEmpresa' id="direccionEmpresa" size="50" maxlength="150" class="mayus" value='<? echo $this->datosEgresado['DIRECCION_EMPRESA']; ?>'><?}else{?><input type="text" name='direccionEmpresa' id="direccionEmpresa" size="50" maxlength="50" class="mayus"><?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Tel&eacute;fono Empresa:
                                        </td>
                                        <td class='cuadro_plano' colspan="4" style="border:0px"><? if(isset($this->datosEgresado['TELEFONO_EMPRESA'])) {?><input type='text' name='telefonoEmpresa' id="telefonoEmpresa" size="25" maxlength="25" value='<? echo $this->datosEgresado['TELEFONO_EMPRESA']; ?>'><?}else{?><input type="text" name='telefonoEmpresa' id="telefonoEmpresa" size="25" maxlength="25"><?}?>
                                        </td>
                                    </tr>
                                    <?if(is_array($this->datosEgresado))
                                    {
                                    ?>
                                    <tr>
                                        <td colspan="10" align="center" ><br>
                                            <? $this->enlaceConsultar('Actualizar datos de contacto','actualizarDatosContacto');?>
                                        </td>
                                    </tr>
                                    <?}?>
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
    function mostrarDatosTrabajoGrado() {
        
    if(isset($this->datosEgresado['NOMBRE_TRABAJO']))
        {$this->datosGraduando['NOMBRE_TRABAJO']=$this->datosEgresado['NOMBRE_TRABAJO'];}
    if(isset($this->datosEgresado['DIRECTOR_TRABAJO']))
        {$this->datosGraduando['DOC_DIRECTOR']=$this->datosEgresado['DIRECTOR_TRABAJO'];}
    if(isset($this->datosEgresado['DIRECTOR_TRABAJO_2']))
        {$this->datosGraduando['DOC_DIRECTOR_2']=$this->datosEgresado['DIRECTOR_TRABAJO_2'];}
    if(isset($this->datosEgresado['ACTA_SUST']))
        {$this->datosGraduando['ACTA_SUST']=$this->datosEgresado['ACTA_SUST'];}
        
    $tab=0;
    ?>
    <style type="text/css">.mayus {text-transform: uppercase;}</style>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
            <tr>
                <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                        <caption class="sigma centrar"><? echo "INFORMACI&Oacute;N TRABAJO DE GRADO / PASANT&Iacute;A"; ?></caption>
                        <tr>
                            <td>
                                <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Trabajo de grado: </td>
                                        <td class='cuadro_plano' colspan="5" style="border:0px"><textarea rows="2" cols="80" name='nombreTrabajoGrado' id="nombreTrabajoGrado" maxlength="2000" class="mayus" ><? if($this->datosGraduando['NOMBRE_TRABAJO']) echo $this->datosGraduando['NOMBRE_TRABAJO'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Director 1: </td>
                                        <td colspan="5" >
                                            <?
                                            $html_director="<select id='nombreDirector' tabindex='".$tab++."' size='1' name='nombreDirector'>";
                                            $html_director.="<option value=''>Seleccione uno</option>  ";          
                                            if(isset($this->datosEgresado['DIRECTOR_TRABAJO'])){
                                                 $html_director.="<option value='".$this->datosEgresado['DIRECTOR_TRABAJO']."' ";
                                                 $html_director.=" selected ";
                                                 $html_director.=" >".$this->datosEgresado['DIRECTOR_TRABAJO']."</option>  ";
                                            }
                                            foreach ($this->directorGrado as $key => $director) {
                                                    $html_director.="<option value='".$director[1]."' ";
                                                        if((isset($this->datosGraduando['DOC_DIRECTOR'])?$this->datosGraduando['DOC_DIRECTOR']:'')==$director[0]){
                                                             $html_director.=" selected ";
                                                        }
                                                     $html_director.=" >".$director[1]."</option>  ";
                                            }
                                            $html_director.="</select>";
                                            echo $html_director;
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Director 2: </td>
                                        <td colspan="5" >
                                            <?
                                            $html_director="<select id='nombreDirector2' tabindex='".$tab++."' size='1' name='nombreDirector2'>";
                                            $html_director.="<option value=''>Seleccione uno</option>  ";          
                                            if(isset($this->datosEgresado['DIRECTOR_TRABAJO_2'])){
                                                 $html_director.="<option value='".$this->datosEgresado['DIRECTOR_TRABAJO_2']."' ";
                                                 $html_director.=" selected ";
                                                 $html_director.=" >".$this->datosEgresado['DIRECTOR_TRABAJO_2']."</option>  ";
                                            }
                                            foreach ($this->directorGrado as $key => $director) {
                                                    $html_director.="<option value='".$director[1]."' ";
                                                        if((isset($this->datosGraduando['DOC_DIRECTOR_2'])?$this->datosGraduando['DOC_DIRECTOR_2']:'')==$director[0]){
                                                             $html_director.=" selected ";
                                                        }
                                                     $html_director.=" >".$director[1]."</option>  ";
                                            }
                                            $html_director.="</select>";
                                            echo $html_director;
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <?if(isset($this->datosGraduando['TIPO_TRABAJO']))
                                        {?>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Tipo de trabajo:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><?echo $this->datosGraduando['TIPO_TRABAJO'];?>
                                        </td>
                                        <?}else{?>
                                        <td class='cuadro_plano' style="border:0px"></td><td></td>
                                        <?}
                                            /*
                                            $html_trabajo="<select id='tipoTrabajoGrado' tabindex='".$tab++."' size='1' name='tipoTrabajoGrado'>";
                                            $html_trabajo.="<option value=''>Seleccione uno</option>  ";
                                            $trabajoGrado=array('Grado'=>'Grado',
                                                                'Pasantia'=>'Pasant&iacute;a');
                                            foreach ($trabajoGrado as $key => $trabajo) {
                                                    $html_trabajo.="<option value='".$key."' ";
                                                                            if((isset($this->datosGraduando['TIPO_TRABAJO'])?$this->datosGraduando['TIPO_TRABAJO']:'')==$key){
                                                                                 $html_trabajo.=" selected ";
                                                                            }
                                                     $html_trabajo.=" >".$trabajo."</option>  ";
                                            }
                                            $html_trabajo.="</select>";
                                            echo $html_trabajo;*/
                                            ?>


                                        <td class='cuadro_plano' style="border:0px">Nro. Acta de sustentaci&oacute;n:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosGraduando['ACTA_SUST'])) {?><input type='text' name='actaSustentacion' id="actaSustentacion" size="10" maxlength="10" value='<? echo $this->datosGraduando['ACTA_SUST']; ?>'><?}else{?><input type="text" name='actaSustentacion' id="actaSustentacion" size="10" maxlength="10" ><?}?>
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">Nota:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEgresado['NOTA'])) {?><input type='text' name='nota' id="nota" size="2" maxlength="2" value='<? echo $this->datosEgresado['NOTA']; ?>'><?}else{?><input type="text" name='nota' id="nota" size="2" maxlength="2" onKeyPress="return solo_numero_sin_slash(event)" ><?}?>
                                        </td>
                                    </tr>
                                    <?if(is_array($this->datosEgresado))
                                    {
                                    ?>
                                    <tr>
                                        <td colspan="6" align="center" ><br>
                                            <? $this->enlaceConsultar('Actualizar datos trabajo de grado','actualizarDatosTrabajoGrado');?>
                                        </td>
                                    </tr>
                                    <?}?>
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
     * función para mostrar los datos de grado - solo consulta
     */
    function mostrarDatosGrado() {
    $tab=0;
    ?>
    <style type="text/css">.mayus {text-transform: uppercase;}</style>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
            <tr>
                <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                        <caption class="sigma centrar"><? echo "INFORMACI&Oacute;N GRADO"; ?></caption>
                        <tr>
                            <td>
                                <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Acta de grado: </td>
                                        <td class='cuadro_plano' style="border:0px" width="10%"><? echo (isset($this->datosEgresado['ACTA_GRADO'])?$this->datosEgresado['ACTA_GRADO']:'');?> </td>
                                        <td class='cuadro_plano' style="border:0px" width="20%">Menci&oacute;n: </td>
                                        <td class='cuadro_plano' style="border:0px" width="10%"><? 
                                        $menciones=array('1'=>'Aprobado',
                                                            '2'=>'Meritorio',
                                                            '3'=>'Laureado',);
                                        if(isset($this->datosEgresado['MENCION'])?$this->datosEgresado['MENCION']:''){    
                                            echo (isset($menciones[$this->datosEgresado['MENCION']])?$menciones[$this->datosEgresado['MENCION']]:'');
                                        }
                                        ?></td>
                                        <td class='cuadro_plano' style="border:0px" width="20%">Fecha de grado:</td>
                                        <td class='cuadro_plano' style="border:0px" width="10%"><? echo (isset($this->datosEgresado['FECHA_GRADO'])?$this->datosEgresado['FECHA_GRADO']:'');?> </td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Libro:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEgresado['LIBRO'])?$this->datosEgresado['LIBRO']:''); ?></td>
                                        <td class='cuadro_plano' style="border:0px">Folio:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEgresado['FOLIO'])?$this->datosEgresado['FOLIO']:''); ?></td>
                                        <td class='cuadro_plano' style="border:0px">Registro diploma:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEgresado['DIPLOMA'])?$this->datosEgresado['DIPLOMA']:''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Titulo obtenido: </td>
                                        <td class='cuadro_plano' style="border:0px" colspan="6"><?echo (isset($this->datosEgresado['TITULO'])?$this->datosEgresado['TITULO']:'');?></td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Rector: </td>
                                        <td class='cuadro_plano' style="border:0px" colspan="6" ><? echo $this->buscarRector();?></td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Secretario Acad&eacute;mico: </td>
                                        <td class='cuadro_plano' style="border:0px" colspan="6" ><? echo $this->buscarSecretario();?></td>
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
     * función para mostrar los datos de grado consulta registro actualizar
     */
    function mostrarFormDatosGrado() {
    $tab=0;
    ?>
    <style type="text/css">.mayus {text-transform: uppercase;}</style>
    <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
            <tr>
                <td>
                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                        <caption class="sigma centrar"><? echo "INFORMACI&Oacute;N GRADO"; ?></caption>
                        <tr>
                            <td>
                                <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Acta de grado: </td>
                                        <td class='cuadro_plano' style="border:0px"><input type="text" name='actaGrado' id="actaGrado" size="6" maxlength="6" class="mayus" <? if((isset($this->datosEgresado['ACTA_GRADO']))) echo "value='".$this->datosEgresado['ACTA_GRADO']."'";?> ></td>
                                        <td class='cuadro_plano' style="border:0px" width="10%">Menci&oacute;n: </td>
                                        <td >
                                            <?
                                            $menciones=array('1'=>'Aprobado',
                                                            '2'=>'Meritorio',
                                                            '3'=>'Laureado',);
                                            
                                            $html_mencion="<select id='mencion' tabindex='".$tab++."' size='1' name='mencion'>";
                                            $html_mencion.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($menciones as $key => $mencion) {
                                                    $html_mencion.="<option value='".$key."' ";
                                                                            if((isset($this->datosEgresado['MENCION'])?$this->datosEgresado['MENCION']:'')==$key){
                                                                                 $html_mencion.=" selected ";
                                                                            }
                                                     $html_mencion.=" >".$mencion."</option>  ";          
                                            }
                                            $html_mencion.="</select>";
                                            echo $html_mencion;
                                            ?>

                                        </td>
                                        <td class='cuadro_plano' style="border:0px">Fecha de grado:
                                        </td>
                                            <td >
                                                <input name="fechaGrado" type="text" id="fechaGrado" size="10" <? if(isset($this->datosEgresado['FECHA_GRADO']))echo "value=".$this->datosEgresado['FECHA_GRADO'];?> readonly="readonly">
                                                <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="botonFecha" id="botonFecha" tabindex='<? echo $tab++;?>'>

                                                <script type="text/javascript">  
                                                    Calendar.setup({
                                                        inputField    : "fechaGrado",
                                                        button        : "botonFecha",
                                                        align         : "Tr"
                                                    });
                                                </script>
                                            </td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Libro:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEgresado['LIBRO'])) {?><input type='text' name='libro' id="libro" size="3" maxlength="3" value='<? echo $this->datosEgresado['LIBRO']; ?>'><?}else{?><input type="text" name='libro' id="libro" size="3" maxlength="3" <?}?>
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">Folio:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEgresado['FOLIO'])) {?><input type='text' name='folio' id="folio" size="3" maxlength="3" value='<? echo $this->datosEgresado['FOLIO']; ?>'><?}else{?><input type="text" name='folio' id="folio" size="3" maxlength="3" ><?}?>
                                        </td>
                                        <td class='cuadro_plano' style="border:0px">Registro diploma:
                                        </td>
                                        <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEgresado['DIPLOMA'])) {?><input type='text' name='registroDiploma' id="registroDiploma" size="10" maxlength="10" value='<? echo $this->datosEgresado['DIPLOMA']; ?>'><?}else{?><input type="text" name='registroDiploma' id="registroDiploma" size="10" maxlength="10" ><?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px">&nbsp;Titulo obtenido:
                                        </td>
                                        <td colspan="6">
                                            <?
                                            $html_titulos="<select id='tituloObtenido' tabindex='".$tab++."' size='1' name='tituloObtenido'>";
                                            $html_titulos.="<option value=''>Seleccione uno</option>  ";  
                                          
                                         	if (isset($this->registroTitulos))
                                            {       
                                            foreach ($this->registroTitulos as $key => $titulo) {
                                                    $html_titulos.="<option value='".$titulo[0]."' ";
                                                        if($this->datosEstudiante['SEXO']==$titulo[2]&&(isset($this->datosEgresado['TITULO'])?$this->datosEgresado['TITULO']:'')==$titulo[0]){
                                                             $html_titulos.=" selected ";
                                                        }
                                                     $html_titulos.=" >".$titulo[1]."</option>  ";          
                                            }
                                            }
                                            $html_titulos.="</select>";
                                            echo $html_titulos;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Rector: </td>
                                        <td colspan="6" >
                                            <?
                                            $fecha_grado=ereg_replace("[/]", "", $this->datosEgresado['FECHA_GRADO']);
                                            $html_rector="<select id='rector' tabindex='".$tab++."' size='1' name='rector'>";
                                            $html_rector.="<option value=''>Seleccione uno</option>  ";
									
                                            foreach ($this->rectores as $key => $rector) {
                                                    $html_rector.="<option value='".$rector[0]."' ";
                                                    if((isset($this->datosEgresado['RECTOR'])?$this->datosEgresado['RECTOR']:'')==$rector[0] && $fecha_grado>=$rector['RECTOR_DESDE'] && $fecha_grado<=$rector['RECTOR_HASTA']){
                                                         $html_rector.=" selected ";
                                                    }
                                                     $html_rector.=" >".$rector[1]."</option>  ";          
                                            }
                                            
                                            $html_rector.="</select>";                                            
                                            echo $html_rector;
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='cuadro_plano' style="border:0px" width="20%">&nbsp;Secretario Acad&eacute;mico: </td>
                                        <td colspan="6" >
                                            <?
                                            $html_secretarios="<select id='secretarioAcademico' tabindex='".$tab++."' size='1' name='secretarioAcademico'>";
                                            $html_secretarios.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($this->secretarios as $key => $secretario) {
                                                    $html_secretarios.="<option value='".$secretario[0]."' ";
                                                    if((isset($this->datosEgresado['SECRETARIO'])?$this->datosEgresado['SECRETARIO']:'')==$secretario[0] && $fecha_grado>=$secretario['SEC_DESDE'] && $fecha_grado<=$secretario['SEC_HASTA']){
                                                         $html_secretarios.=" selected ";
                                                    }
                                                     $html_secretarios.=" >".$secretario[1]."</option>  ";          
                                            }
                                            $html_secretarios.="</select>";
                                            echo $html_secretarios;
                                            ?>

                                        </td>
                                    </tr>
                                    <?if(is_array($this->datosEgresado))
                                    {
                                    ?>
                                    <tr>
                                        <td colspan="10" align="center" ><br>
                                            <? $this->enlaceConsultar('Actualizar datos de grado','actualizarDatosGrado');?>
                                        </td>
                                    </tr>
                                    <?}?>
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
     * Permite obtener el listado de los tipos de documentos de identidad registrados
     * @return type
     */
    function consultarTiposDocumentos() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarTiposDocumentos", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de municipios registrados
     * @return type
     */
    function consultarMunicipios() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarMunicipios", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de directores de grado
     * @return type
     */
    function consultarDirectorGrado() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarDirectorGrado", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de tituluos de grado para el proyecto
     * @return type
     */
    function consultarTitulosGrado() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarTitulosGrado", $this->datosEstudiante['CODIGO_CRA']);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de secretarios de la facultad
     * @return type
     */
    function consultarSecretarios() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarSecretarios", $this->datosEstudiante['FACULTAD']);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de secretarios de la facultad
     * @return type
     */
    function consultarRectores() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarRectores", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
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

        //var_dump($variables);exit;

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEgresado", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
    
    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosGraduando($codEstudiante,$codProyecto) {
               
        $variables=array(
                            'codEstudiante'=>$codEstudiante,
                            'proyecto'=>$codProyecto
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosGraduando", $variables);
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
    
    function consultarCodigoEstudiantePorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_id", $identificacion);
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
    
    function consultarCodigoEstudiantePorNombre($nombre){
        $cadena_nombre='';
        $nombre = explode(" ", mb_strtoupper($nombre,'UTF-8'));
//        $min=array('á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ','ç');
//        $may=array('Á','É','Í','Ó','Ú','Ä','Ë','Ï','Ö','Ü','Ñ','ç');
//        $nombre=str_replace($min,$may,$nombre);
        $i=1;
            
        foreach ($nombre as $parte) {
            if($i==1){
                $cadena_nombre="'%".$parte."%'";
            }else{
                
                $cadena_nombre.=" AND est_nombre like '%".$parte."%'";
            }
            $i++;
        }
        
        $nombre=str_replace(" ", "%", $nombre);
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_nombre", $cadena_nombre);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }

    
    /**
     * Función para mostrar enlace que envia los datos para consultar informacion de estudiante
     */
    function enlaceConsultar($nombre,$tipo)
        {
            ?><input type='hidden' name='action' value="<? echo $this->bloqueActualiza?>">
              <input type='hidden' name='opcion' value="actualizar">
              <input type='hidden' name='tipoActualizacion' value="actualizarDatos">
              <input type='hidden' name='proyectoCurricular' value="<? echo $this->datosEstudiante['CODIGO_CRA'];?>">
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
    
    function buscarSecretario(){
        $nombre_secretario='';
        if(isset($this->datosEgresado['SECRETARIO'])?$this->datosEgresado['SECRETARIO']:''){
            foreach ($this->secretarios as $key => $secretario) {
                    if((isset($this->datosEgresado['SECRETARIO'])?$this->datosEgresado['SECRETARIO']:'')==$secretario[0]){
                         $nombre_secretario = $secretario[1];
                    }
            }
        }
        return $nombre_secretario;
    }
    
    
    function buscarRector(){
        $nombre_rector='';
        if(isset($this->datosEgresado['RECTOR'])?$this->datosEgresado['RECTOR']:''){
            foreach ($this->rectores as $key => $rector) {
                    if((isset($this->datosEgresado['RECTOR'])?$this->datosEgresado['RECTOR']:'')==$rector[0]){
                         $nombre_rector = $rector[1];
                    }
            }
        }
        return $nombre_rector;
        
    }
}
?>
