
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminConsultarCalendarioEventos extends funcionGeneral {

  private $configuracion;
  private $parametros;


  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->procedimientos = new procedimientos();
    $this->html = new html();

    //$this->tema = $tema;
    $this->sql = new sql_adminConsultarCalendarioEventos($configuracion);
    $this->log_us = new log();
    $this->parametros=array();
    $this->formulario = "admin_consultarCalendarioEventos";
    $this->bloque = "fechasEventos/admin_consultarCalendarioEventos";

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];
    $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->tipoUser=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    
    //Conexion Oracle
    if($this->tipoUser==20){
            $this->accesoOracle = $this->conectarDB($configuracion, "administrador");
    }elseif($this->tipoUser==16){
            $this->accesoOracle = $this->conectarDB($configuracion, "decano");
    }if($this->tipoUser==32){
            $this->accesoOracle = $this->conectarDB($configuracion, "vicerrector");
    }if($this->tipoUser==4 || $this->tipoUser==28){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
    }else{
            $this->accesoOracle = $this->conectarDB($configuracion, "administrador");

    }
    
  }

  
  /**
   * Función para mostrar el formulario de consulta por listado o por detalle
   */
  function mostrarFormulario() {
            $datos='';
            if($this->tipoUser==16 || $this->tipoUser==32 || $this->tipoUser==20 ){
                $this->mostrarEnlaceRegistrarFechas();
            }
            
            if($this->tipoUser==4 || $this->tipoUser==28){
                $datos['coordinador']=$this->usuario;
            }else{
                $datos['coordinador']='';
            }
            
            if($this->tipoUser==16){
                $datos['decano']=$this->usuario;
            }else{
                $datos['decano']='';
            }
            $this->calendario = $this->consultarCalendarioEventos($datos);
            //muestra solicitudes
            $this->mostrarCalendarioEventos();
        
  }

  
  /**
   * Función para consultar las fechas de eventos
   * @return <array>
   */
  function consultarCalendarioEventos($datos) {
      $cadena_sql = $this->sql->cadena_sql("calendario_eventos", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  
  
  /**
   * Función para mostrar el formulario con los datos de las solicitudes
   */
  function mostrarCalendarioEventos() {
         ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tablaCalendario').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

        <?
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";	
	        
        echo "<table class='tablaMarco' width='100%'>";
        echo "      <tr class=texto_elegante >";
        echo "          <td>";
        echo "          <b>::::..</b>  Calendario de eventos para el per&iacute;odo ".$this->calendario[0][0]."-".$this->calendario[0][1];
        echo "          <hr class=hr_subtitulo>";
        echo "          </td>";
        echo "      </tr>	";
        echo "</table>";
        echo "<table class='contenidotabla' id='tablaCalendario' >";
        echo "<thead>";
        echo "<tr>";
        echo "<th >Cod. Proyecto</th>";
        echo "<th >Proyecto curricular</th>";
        echo "<th >Cod. evento</th>";
        echo "<th >Descripción evento</th>";
        echo "<th >Fecha y hora inicial</th>";
        echo "<th >Fecha y hora final</th>";
        echo "<th >Estado</th>";
        echo "<th >";
        if ($this->tipoUser==16 || $this->tipoUser==32 || $this->tipoUser==20 ){
            echo "Opciones";
        }else{
            echo "&nbsp;";
        }
        echo "</th>";
        echo "<th >&nbsp;</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if(is_array($this->calendario)){
                foreach ($this->calendario as $calendario) {
                    $variable = "pagina=admin_consultarCalendarioEventos";
                    $variable.= "&action=".$this->bloque;
                    $variable.="&opcion=consultarSolicitud";
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                    $enlace_consultar=$indice.$variable;
                    echo "<tr>";
                    echo "<td align='center' >".$calendario['CRA_COD']."</td>";
                    echo "<td >".$calendario['CRA_NOMBRE']."</td>";
                    echo "<td >".$calendario['ACD_COD_EVENTO']."</td>";
                    echo "<td >".$calendario['ACD_DESCRIPCION']."</td>";
                    echo "<td >".$calendario['ACE_FEC_INI']."</td>";
                    echo "<td >".(isset($calendario['ACE_FEC_FIN'])?$calendario['ACE_FEC_FIN']:'')."</td>";
                    echo "<td >".$calendario['ACE_ESTADO']."</td>";
                    if($calendario['ACE_ESTADO']=='A' && ($this->tipoUser==16 || $this->tipoUser==32 || $this->tipoUser==20 )){
                        $variable2="pagina=".$this->formulario;
                        $variable2.="&opcion=actualizar";
                        $variable2.="&anio=".$calendario['ACE_ANIO'];
                        $variable2.="&periodo=".$calendario['ACE_PERIODO'];
                        $variable2.="&codEvento=".$calendario['ACD_COD_EVENTO'];
                        $variable2.="&codProyecto=".$calendario['CRA_COD'];
                        $variable2.="&fechahora_inicio=".$calendario['ACE_FEC_INI'];
                        $variable2.="&fechahora_fin=".(isset($calendario['ACE_FEC_FIN'])?$calendario['ACE_FEC_FIN']:'');
                        
                        $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
                        $enlace_aprobar=$indice.$variable2;
                        echo "<td ><a href='".$enlace_aprobar."'>Actualizar</a></td>";

                        $variable3="pagina=registro_adicionarFechasCalendario";
                        $variable3.="&opcion=solicitarConfirmacion";
                        $variable3.="&anio=".$calendario['ACE_ANIO'];
                        $variable3.="&periodo=".$calendario['ACE_PERIODO'];
                        $variable3.="&codEvento=".$calendario['ACD_COD_EVENTO'];
                        $variable3.="&codProyecto=".$calendario['CRA_COD'];
                        $variable3.="&fechaInicial=".$calendario['ACE_FEC_INI'];
                        $variable3.="&fechaFinal=".(isset($calendario['ACE_FEC_FIN'])?$calendario['ACE_FEC_FIN']:'');
                        $variable3=$this->cripto->codificar_url($variable3,$this->configuracion);
                        $enlace_inactivar=$indice.$variable3;
                         
                        echo "<td><a href='".$enlace_inactivar."'>Inactivar</a></td>";
                        
                    }else{
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                    }
                    
                    //echo "<td ><a href='".$indice.$variable."'>Adjuntar Soporte</a></td>";
                    echo "</tr>";
                }
        }
        echo "</tbody>";
        
  }
  
  /**
   * Función para mostrar el enlace de registrar nuevas fechas de calendario
   */
  function mostrarEnlaceRegistrarFechas(){
      $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
      $variable="pagina=admin_consultarCalendarioEventos";
      $variable.="&opcion=nuevo";
      $variable.="&tipoUser=".$this->tipoUser;
      $variable=$this->cripto->codificar_url($variable,$this->configuracion);
      $enlace_nuevo=$indice.$variable;
	
      echo "<table width='100%' ><tr><td align='right'><a href='".$enlace_nuevo."'><span>:: Registrar fechas</span></a><td><tr></table>";
  }
  
  /**
   * Funvión para mostrar el formulario de un nuevo registro de fechas
   */
  function mostrarFormularioNuevo(){  
        $tab=1;
        $datos='';
       
            if($this->tipoUser==16){
                $datos['decano']=$this->usuario;
            }else{
                $datos['decano']='';
            }
            
        if($this->tipoUser==16 || $this->tipoUser==32 || $this->tipoUser==20 ){
                $facultades=  $this->consultarFacultades($datos);
                if($facultades){
                    $facultadFormulario[0][0]='';
                    $facultadFormulario[0][1]='--';
                    $facultadFormulario[1][0]='999';
                    $facultadFormulario[1][1]='Todas las facultades';
                    foreach ($facultades as $key => $facultad) {
                        $facultadFormulario[$key+2][0]=$facultad['DEP_COD'];
                        $facultadFormulario[$key+2][1]=$facultad['FACULTAD'];
                    }
                }else{
                    $facultadFormulario='';
                    $lista_facultades='';
                }
                $proyectos=  $this->consultarProyectos($datos);
                if($proyectos){
                    $proyectoCurricular[0][0]='';
                    $proyectoCurricular[0][1]='--';
                    $proyectoCurricular[1][0]='999';
                    $proyectoCurricular[1][1]='Todos los proyectos';
                    foreach ($proyectos as $key => $proyecto) {
                        $proyectoCurricular[$key+2][0]=$proyecto['CRA_COD'];
                        $proyectoCurricular[$key+2][1]=$proyecto['PROYECTO'];
                    }
                }else{
                    $proyectoCurricular='';
                    $lista_proyectos='';
                }
                $eventos=  $this->consultarEventos($datos);
                if($eventos){
                    $descripcionEventos[0][0]='';
                    $descripcionEventos[0][1]='--';
                    foreach ($eventos as $key => $evento) {
                        $descripcionEventos[$key+1][0]=$evento['COD_EVENTO'];
                        $descripcionEventos[$key+1][1]=$evento['EVENTO'];
                    }
                }else{
                    $descripcionEventos='';
                    $lista_eventos='';
                }
                $this->verificar="seleccion_valida(".$this->formulario.",'proyecto')";
                $this->verificar.="&&seleccion_valida(".$this->formulario.",'codFacultad')";
                $this->verificar.="&&seleccion_valida(".$this->formulario.",'evento')";
                $this->verificar.="&&control_vacio(".$this->formulario.",'fechahora_inicio')";
                $this->verificar.="&&control_vacio(".$this->formulario.",'fechahora_fin')";

                 ?>


           <script type="text/javascript" src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.min.js";?>"></script>
                <!--Load Script and Stylesheet -->
                <script type="text/javascript" src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.simple-dtpicker.js";?>"></script>
                <link type="text/css" href="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.simple-dtpicker.css";?>" rel="stylesheet" />
                <!---->

               <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div id="normal" >

                        <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                            <tr class=texto_elegante >
                                <td colspan='2'>
                                            <b>::::..</b>  Registrar fechas
                                        <hr class=hr_subtitulo>
                                </td>
                            </tr>		
                            <tr>    
                                <td width="30%"><span style='color:red;' > * </span>Facultad</td>
                                    <td >
                                            <? 
                                                    if($facultadFormulario){
                                                        $this->configuracion["ajax_function"]="xajax_proyectosFacultad";
                                                        $this->configuracion["ajax_control"]="codFacultad";
                                                                                                         
                                                        $lista_facultades= $this->html->cuadro_lista($facultadFormulario,'codFacultad',$this->configuracion,(isset($_REQUEST['codFacultad'])?$_REQUEST['codFacultad']:0),2,FALSE,$tab++,'codFacultad','');
                                                    }
                                                    echo $lista_facultades;
                                            ?>
                                    </td>
                            </tr>
                            <tr>    
                                <td width="30%"><span style='color:red;' > * </span>Proyecto curricular</td>
                                <td > <div id="DIV_PROYECTO">
                                            <? 
                                                    if($proyectoCurricular){
                                                        $lista_proyectos= $this->html->cuadro_lista($proyectoCurricular,'codProyecto',$this->configuracion,(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:0),0,FALSE,$tab++,'codProyecto','');
                                                    }
                                                    echo $lista_proyectos;
                                            ?>
                                        </div>
                                    </td>
                            </tr>
                            <tr>    
                                <td width="30%"><span style='color:red;' > * </span>Nivel Proyectos </td>
                                <td >   <table> 
                                            <tr><td><input type="checkbox" name="pregrado" value="1" checked> PREGRADO<br>
                                                <input type="checkbox" name="posgrado" value="2"> POSGRADO<br>
                                                <input type="checkbox" name="maestria" value="3"> MAESTRIA<br></td>
                                                <td><input type="checkbox" name="doctorado" value="4"> DOCTORADO<br>
                                                <input type="checkbox" name="extension" value="5"> EXTENSION<br></td>
                                            </tr>
                                        </table>
                                        
                                </td>
                            </tr>
                            <tr>    
                                    <td ><span style='color:red;' > * </span>Evento</td>
                                    <td >
                                            <? 
                                                    if($descripcionEventos){
                                                        $lista_eventos= $this->html->cuadro_lista($descripcionEventos,'codEvento',$this->configuracion,(isset($_REQUEST['codEvento'])?$_REQUEST['codEvento']:0),0,FALSE,$tab++,'codEvento','');
                                                    }
                                                    echo $lista_eventos;
                                            ?>
                                    </td>
                            </tr>
                            <tr>    
                                    <td ><span style='color:red;' > * </span>Fecha y hora de inicio
                                        <br>        
                                        <input type="text" name="fechahora_inicio" <? if(isset($_REQUEST['fechahora_fin'])){ echo "value='".$_REQUEST['fechahora_fin']."'";}?> >
                                            <script type="text/javascript">
                                                    $(function(){
                                                            $('*[name=fechahora_inicio]').appendDtpicker({"inline": true});
                                                    });
                                            </script>
                                    </td>
                                    
                                    <td ><span style='color:red;' > * </span>Fecha y hora de finalización 
                                        <br>
                                            <input type="text" name="fechahora_fin" <? if(isset($_REQUEST['fechahora_fin'])){ echo "value='".$_REQUEST['fechahora_fin']."'";}?>>
                                            <script type="text/javascript">
                                                    $(function(){
                                                            $('*[name=fechahora_fin]').appendDtpicker({"inline": true});
                                                    });
                                            </script>
                                    </td>
                            </tr>

                            <tr>    

                                    <td colspan='2' align='center'><? $this->enlaceRegistrar();?></td>
                            </tr>

                        </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        Espacios requeridos (*)     
                        <input type='hidden' name='xajax' value='<? echo $_REQUEST['xajax'];?>'>
                        <input type='hidden' name='xajax_file' value='<? echo $_REQUEST['xajax_file'];?>'>
								
                    </div>

                    </div>

                   </form>
                <?
        }else{
            ?>
                            <table class="contenidotabla centrar">
                              <tr>
                                <td class="cuadro_brownOscuro centrar">
                                    <?echo "El perfil no tiene permisos para este modulo";?>
                                </td>
                              </tr>
                            </table>
                        <?                                    
                        $this->mostrarEnlaceRetorno();
                        exit;
        }
    }
  
    /**
     * Función para mostrar el formulario de actualizar fechas
     */
    function mostrarFormularioActualizar(){  
        
        $tab=1;
        
         if($this->tipoUser==16){
                $datos['decano']=$this->usuario;
            }else{
                $datos['decano']='';
            }
        if($this->tipoUser==16 || $this->tipoUser==32 || $this->tipoUser==20 ){
            $codProyecto = (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
            $codEvento = (isset($_REQUEST['codEvento'])?$_REQUEST['codEvento']:'');
            $proyectos=  $this->consultarProyectos($datos);
            if($proyectos){
                $proyectoCurricular='';
                foreach ($proyectos as $key => $proyecto) {
                    if($codProyecto==$proyecto['CRA_COD']){
                        $proyectoCurricular=$proyecto;
                    }
                    
                }
            }
            $eventos=  $this->consultarEventos();
            if($eventos){
                $descripcionEventos='';
                foreach ($eventos as $key => $evento) {
                    if($codEvento==$evento['COD_EVENTO']){
                        $descripcionEventos=$evento;
                    }
                }
            }
            if($proyectoCurricular && $descripcionEventos){
            $this->verificar="control_vacio(".$this->formulario.",'fechahora_inicio')";
            $this->verificar.="&&control_vacio(".$this->formulario.",'fechahora_fin')";

             ?>


           <script type="text/javascript" src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.min.js";?>"></script>
            <!--Load Script and Stylesheet -->
            <script type="text/javascript" src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.simple-dtpicker.js";?>"></script>
            <link type="text/css" href="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']."/jquery/calendarioDateTime/jquery.simple-dtpicker.css";?>" rel="stylesheet" />
            <!---->

           <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
            <div id="normal" >

                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='2'>
                                        <b>::::..</b>  Actualizar fechas
                                    <hr class=hr_subtitulo>
                            </td>
                        </tr>		
                        <tr>    
                            <td width="30%">Proyecto curricular</td>
                                <td >
                                        <? 
                                                echo $proyectoCurricular['PROYECTO'];
                                        ?>
                                </td>
                        </tr>
                        <tr>    
                                <td >Evento</td>
                                <td >
                                        <? 
                                                echo $descripcionEventos['EVENTO'];
                                        ?>
                                </td>
                        </tr>
                        <tr>    
                                <td ><span style='color:red;' > * </span>Fecha y hora de inicio
                                    <br>
                                    <input type="text" name="fechahora_inicio" <? if($_REQUEST['fechahora_inicio']){ echo "value='".$_REQUEST['fechahora_inicio']."'";}?>>
                                        <script type="text/javascript">
                                                $(function(){
                                                        $('*[name=fechahora_inicio]').appendDtpicker({"inline": true});
                                                });
                                        </script>
                                </td>
                                <td ><span style='color:red;' > * </span>Fecha y hora de finalización 
                                    <br>
                                        <input type="text" name="fechahora_fin" <? if($_REQUEST['fechahora_fin']){ echo "value='".$_REQUEST['fechahora_fin']."'";}?>>
                                        <script type="text/javascript">
                                                $(function(){
                                                        $('*[name=fechahora_fin]').appendDtpicker({"inline": true});
                                                });
                                        </script>
                                </td>
                        </tr>

                        <tr>    

                                <td colspan='2' align='center'><? $this->enlaceActualizar($codProyecto,$codEvento);?></td>
                        </tr>

                    </table>
                <div id="div_mensaje1" align="center" class="ab_name">
                    Espacios requeridos (*)            
                </div>

                </div>

               </form>
            <?
            }else{
                        ?>
                            <table class="contenidotabla centrar">
                              <tr>
                                <td class="cuadro_brownOscuro centrar">
                                    <?echo "Proyecto o evento no valido";?>
                                </td>
                              </tr>
                            </table>
                        <?                                    
                        $this->mostrarEnlaceRetorno();
                        exit;
            
                 }
        }else{
                        ?>
                            <table class="contenidotabla centrar">
                              <tr>
                                <td class="cuadro_brownOscuro centrar">
                                    <?echo "El perfil no tiene permisos para este modulo";?>
                                </td>
                              </tr>
                            </table>
                        <?                                    
                        $this->mostrarEnlaceRetorno();
                        exit;
            
                 }
    }

  
  /**
   * Función para consultar todas las facultades
   * @param type $datos
   * @return type
   */  
  function consultarFacultades($datos) {
      $cadena_sql = $this->sql->cadena_sql("consultar_facultades", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
   * Función para consultar todos los proyectos 
   * @param type $datos
   * @return type
   */  
  function consultarProyectos($datos) {
      $cadena_sql = $this->sql->cadena_sql("consultar_proyectos", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
   * Función para consultar todos los eventos
   * @return type
   */
  function consultarEventos() {
      $cadena_sql = $this->sql->cadena_sql("consultar_eventos", '');
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
     * Funcion que muestra el enlace para registrar en la tabla de solicitud
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function enlaceRegistrar() {
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->bloque?>">
          <input type='hidden' name='opcion' value="registrar">
          <?/*<input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">                              */?>
          <input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            
            
         <?
    }
    
  /**
   * Función para mostrar el boton de actualizar unas fechas
   * @param type $codProyecto
   * @param type $codEvento
   */
    function enlaceActualizar($codProyecto,$codEvento) {
       //$pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->bloque?>">
          <input type='hidden' name='opcion' value="actualizarFechas">
          <input type='hidden' name='codProyecto' value="<? echo $codProyecto;?>">
          <input type='hidden' name='codEvento' value="<? echo $codEvento;?>">
          <?/*<input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">                              */?>
          <input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            
            
         <?
    }
    
    /**
     * Función para mostrar enlace de retorno
     */
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_consultarCalendarioEventos";
            $variable2.="&opcion=consultar";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
}
?>
