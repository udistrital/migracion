
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

class funcion_adminConsultarSolicitudesUsuario extends funcionGeneral {

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
    $this->sql = new sql_adminConsultarSolicitudesUsuario($configuracion);
    $this->log_us = new log();
    $this->parametros=array();
    $this->formulario = "admin_consultarSolicitudesUsuario";
    $this->bloque = "usuarios/admin_consultarSolicitudesUsuario";

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "administrador");
    
    //conexion distribuida 1 conecta a MySUDD de lo contrario conecta a ORACLE
    if ($configuracion['dbdistribuida']==1)
        {
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }
        else
            {
                $this->accesoMyOracle = $this->accesoOracle;
            }
        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];
        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

  }

  
  /**
   * Función para mostrar el formulario de consulta por listado o por detalle
   */
  function mostrarFormulario() {
        $idSolicitud=(isset($_REQUEST['idSolicitud'])?$_REQUEST['idSolicitud']:'');
        
        if(!$idSolicitud){
            $this->mostrarEnlaceSolicitarUsuario();
            $this->solicitudes = $this->consultarSolicitudesCuentasUsuarios();

            //muestra solicitudes
            $this->mostrarDatosSolicitudes();
        }else{
            $this->solicitudes = $this->consultarDetalleSolicitud($idSolicitud);
            $this->mostrarDetalleSolicitud();
            $proyectos=$this->buscarProyectosAsociados();
            $this->mostrarProyectosAsociados($proyectos);
            $this->mostrarEnlaceRetorno();
        }
  }

  
  /**
   * Función para consultar las solicitudes de cuentas de usuario
   * @return <array>
   */
  function consultarSolicitudesCuentasUsuarios() {
      $cadena_sql = $this->sql->cadena_sql("solicitudes_cuentas", '');
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  /**
   * Función para mostrar el formulario con los datos de las solicitudes
   */
  function mostrarDatosSolicitudes() {
         ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tablaSolicitudes').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

        <?
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";	
	        
        echo "<table class='tablaMarco' width='100%'>";
        echo "      <tr class=texto_elegante >";
        echo "          <td>";
        echo "          <b>::::..</b>  Solicitudes de cuentas";
        echo "          <hr class=hr_subtitulo>";
        echo "          </td>";
        echo "      </tr>	";
        echo "</table>";
        echo "<table class='contenidotabla' id='tablaSolicitudes' >";
        echo "<thead>";
        echo "<tr>";
        echo "<th >No. solicitud</th>";
        echo "<th >Fecha registro</th>";
        echo "<th >Solicitante</th>";
        echo "<th >Usuario</th>";
        echo "<th >Estado</th>";
        echo "<th >Opciones</th>";
        echo "<th >&nbsp;</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if(is_array($this->solicitudes)){
                foreach ($this->solicitudes as $solicitud) {
                    $variable = "pagina=admin_consultarSolicitudesUsuario";
                    $variable.= "&action=".$this->bloque;
                    $variable.="&opcion=consultarSolicitud";
                    $variable.="&idSolicitud=".$solicitud['SOL_ID'];
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                    $enlace_consultar=$indice.$variable;
                    $solicitud['SOLICITANTE']=(isset($solicitud['SOLICITANTE'])?$solicitud['SOLICITANTE']:'');
                    echo "<tr>";
                    echo "<td align='center' ><a href='".$enlace_consultar."'>".$solicitud['SOL_ID']."</a></td>";
                    echo "<td >".$solicitud['SOL_FECHA']."</td>";
                    echo "<td >".$solicitud['SOL_NRO_IDEN_SOLICITANTE']." - ".$solicitud['SOLICITANTE']."</td>";
                    echo "<td >".$solicitud['SOL_CODIGO']." - ".$solicitud['NOMBRE_USUARIO']."</td>";
                    echo "<td >".$solicitud['ESTADO_SOLICITUD']."</td>";
                    if($solicitud['SOL_ESTADO_SOLICITUD']==1){
                        $variable2="pagina=registro_aprobarSolicitudUsuario";
                        $variable2.="&action=usuarios/registro_aprobarSolicitudUsuario";
                        $variable2.="&opcion=aprobar";
                        $variable2.="&idSolicitud=".$solicitud['SOL_ID'];

                        $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
                        $enlace_aprobar=$indice.$variable2;
                        echo "<td ><a href='".$enlace_aprobar."'>Aprobar</a>";
                        
                        $variable3="pagina=registro_adicionarSolicitudUsuario";
                        $variable3.="&action=usuarios/registro_adicionarSolicitudUsuario";
                        $variable3.="&opcion=inactivarSolicitud";
                        $variable3.="&idSolicitud=".$solicitud['SOL_ID'];
                        $variable3=$this->cripto->codificar_url($variable3,$this->configuracion);
                        $enlace_inactivar=$indice.$variable3;
                         
                        echo "<br><br><a href='".$enlace_inactivar."'>Inactivar</a></td>";
                        
                    }else{
                        echo "<td>&nbsp;</td>";
                    }
                    echo "<td>&nbsp;</td>";
                    //echo "<td ><a href='".$indice.$variable."'>Adjuntar Soporte</a></td>";
                    echo "</tr>";
                }
        }
        echo "</tbody>";
        
  }
  

  /**
   * Función para mostrar el detalle de una solicitud
   */
  function mostrarDetalleSolicitud(){
        echo "<table class='tablaMarco' width='90%' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "      <tr class=texto_elegante >";
        echo "          <td>";
        echo "          <b>::::..</b>  Solicitud de cuenta No.".$this->solicitudes[0]['SOL_ID'];
        echo "          <hr class=hr_subtitulo>";
        echo "          </td>";
        echo "      </tr>	";
        echo "</table>";  

        echo "<table class='tablaMarco' width='90%' border='1' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "<caption>DATOS SOLICITUD</caption>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>FECHA SOLICITUD:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['SOL_FECHA']."</td>";    
        echo "<td class='sigma_c'>ESTADO:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['ESTADO_SOLICITUD']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>SOLICITANTE:</td>";    
        echo "<td colspan='3' class='sigma'>".(isset($this->solicitudes[0]['SOLICITANTE'])?$this->solicitudes[0]['SOLICITANTE']:'')."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>IDENTIFICACI&Oacute;N SOLICITANTE:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['SOL_NRO_IDEN_SOLICITANTE']."</td>";    
        echo "<td class='sigma_c'>CARGO:</td>";    
        echo "<td class='sigma'>".(isset($this->solicitudes[0]['CARGO'])?$this->solicitudes[0]['CARGO']:'')."</td>";    
        echo "</tr>";  
        echo "</table>";  
        
        echo "<BR>";  
        
        echo "<table class='tablaMarco' width='90%' border='1' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "<caption>DATOS DEL TITULAR DE LA CUENTA</caption>";    
        echo "<tr>";    
        echo "<td class='sigma_c'> IDENTIFICACI&Oacute;N USUARIO:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['SOL_CODIGO']."</td>";    
        echo "<td class='sigma_c'>TIPO IDEN.:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['TIPO_IDENTIFICACION']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>NOMBRE:</td>";    
        echo "<td colspan='3' class='sigma'>".$this->solicitudes[0]['NOMBRE_USUARIO']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>TELEFONO:</td>";    
        echo "<td class='sigma'>".(isset($this->solicitudes[0]['TELEFONO'])?$this->solicitudes[0]['TELEFONO']:'')."</td>";    
        echo "<td class='sigma_c'>CELULAR:</td>";    
        echo "<td class='sigma'>".(isset($this->solicitudes[0]['CELULAR'])?$this->solicitudes[0]['CELULAR']:'')."</td>";    
        echo "</tr>";   
        echo "<tr>";    
        echo "<td class='sigma_c'>CORREO:</td>";    
        echo "<td colspan='3' class='sigma'>".$this->solicitudes[0]['CORREO_ELECTRONICO']."</td>";    
        echo "</tr>";   
        echo "<tr>";    
        echo "<td class='sigma_c'>DIRECCI&Oacute;N:</td>";    
        echo "<td colspan='3' class='sigma'>".(isset($this->solicitudes[0]['DIRECCION'])?$this->solicitudes[0]['DIRECCION']:'')."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>FECHA INICIO CONTRATO:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['SOL_FECHA_INICIO']."</td>";    
        echo "<td class='sigma_c'>FECHA FIN CONTRATO (Si es OPS):</td>";    
        echo "<td class='sigma'>".(isset($this->solicitudes[0]['SOL_FECHA_FIN'])?$this->solicitudes[0]['SOL_FECHA_FIN']:'')."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td class='sigma_c'>TIPO USUARIO:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['TIPO_USUARIO']."</td>";    
        echo "<td class='sigma_c'>TIPO VINCULACION:</td>";    
        echo "<td class='sigma'>".$this->solicitudes[0]['TIPO_VINCULACION']."</td>";    
        echo "</tr>";    
        echo "</table>";    

  }

  
  /**
   * Función para consultar el detalle de una solicitud
   * @param int $idSolicitud
   * @return type
   */
  function consultarDetalleSolicitud($idSolicitud) {
      $cadena_sql = $this->sql->cadena_sql("detalle_solicitud_cuenta", $idSolicitud);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  /**
   * Función para consultar los datos de un proyecto
   * @param type $codigo
   * @return type
   */
  function consultarDatosProyectoCurricular($codigo) {
      $cadena_sql = $this->sql->cadena_sql("datos_proyecto_curricular", $codigo);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  /**
   * Función para consultar los datos de una facultad
   * @param type $codigo
   * @return type
   */
  function consultarDatosFacultad($codigo) {
      $cadena_sql = $this->sql->cadena_sql("datos_facultad", $codigo);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  function buscarProyectosAsociados(){
      $proyectos = $this->consultarProyectosUsuario($this->solicitudes[0]['SOL_CODIGO']);
      return $proyectos;
  }
  
  function consultarProyectosUsuario($codigo){
      $cadena_sql = $this->sql->cadena_sql("proyectos_relacionados_usuario", $codigo);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  function mostrarProyectosAsociados($proyectos) {
        ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#proyectos').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

        <?       
        echo "<BR><table class='tablaMarco' width='100%'>";
        echo "      <caption>PROYECTOS O DEPENDENCIAS ASOCIADOS AL USUARIO</caption>";
        echo "</table>";
        echo "<table class='contenidotabla' id='proyectos' >";
        echo "<thead>";
        echo "<tr>";
        echo "<th >Usuario</th>";
        echo "<th >Tipo usuario</th>";
        echo "<th >Proyecto o Dependencia</th>";
        echo "<th >Estado</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if(is_array($proyectos)){
                foreach ($proyectos as $proyecto) {
                    echo "<tr>";
                    echo "<td >".$proyecto['USUWEB_CODIGO']."</td>";
                    echo "<td >".$proyecto['USUWEB_TIPO']." - ".$proyecto['USUTIPO_TIPO']."</td>";
                    echo "<td >".$proyecto['COD_PROYECTO']." - ".$proyecto['PROYECTO']."</td>";
                    echo "<td >".$proyecto['USUWEB_ESTADO']."</td>";
                    echo "</tr>";
                }
        }
        echo "</tbody>";
        echo "</table>";
        
  }
  
  function mostrarEnlaceSolicitarUsuario(){
      $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
      $variable="pagina=admin_consultarSolicitudesUsuario";
      $variable.="&opcion=nuevo";
      $variable=$this->cripto->codificar_url($variable,$this->configuracion);
      $enlace_nuevo=$indice.$variable;
	
      echo "<table width='100%' ><tr><td align='right'><a href='".$enlace_nuevo."'><span>:: Solicitar Usuario </span></a><td><tr></table>";
  }
  
  function mostrarFormularioNuevo(){  
        $tab=1;
        //consultamos los tipos de documento
        $tipos_documento=  $this->consultarTiposDocumento();
        foreach ($tipos_documento as $key => $tipo_documento) {
                unset($tipos_documento[$key]['TDO_CODIGO']);
                unset($tipos_documento[$key]['TDO_NOMBRE']);

        }

        //consultamos los tipos de Cuentas
        $tipos_cuenta=  $this->consultarTiposCuenta();
        foreach ($tipos_cuenta as $key => $tipo_cuenta) {
                unset($tipos_cuenta[$key]['USUTIPO_COD']);
                unset($tipos_cuenta[$key]['USUTIPO_TIPO']);

        }
        
        $this->verificar="control_vacio(".$this->formulario.",'solicitante')";
	$this->verificar.="&&control_vacio(".$this->formulario.",'nombre')";
	$this->verificar.="&&control_vacio(".$this->formulario.",'apellido')";
	$this->verificar.="&&control_vacio(".$this->formulario.",'identificacion')";
	$this->verificar.="&&control_vacio(".$this->formulario.",'correo')";
	$this->verificar.="&&verificar_correo(".$this->formulario.",'correo')";
	$this->verificar.="&&control_vacio(".$this->formulario.",'fecha_inicio')";
	$this->verificar.="&&seleccion_valida(".$this->formulario.",'tipo_documento')";
	$this->verificar.="&&seleccion_valida(".$this->formulario.",'tipo_cuenta')";

         ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>
        <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
            	
       <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
        <div id="normal" >
                
		<table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                    <tr class=texto_elegante >
                        <td colspan='2'>
                                    <b>::::..</b>  Solicitar Cuenta
                                <hr class=hr_subtitulo>
                        </td>
                    </tr>		
                    <tr>    
                        <td width="30%"><span style='color:red;' > * </span>Identificación de la persona que aprueba (Coordinador, Decano o Jefe)</td>
                            <td >
                                    <input type="text" name='solicitante' id="solicitante" size="15" <? if(isset($_REQUEST['solicitante'])?$_REQUEST['solicitante']:'')echo "value='".$_REQUEST['solicitante']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return solo_numero_sin_slash(event)" maxlength="11">
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Nombres del usuario</td>
                            <td >
                                    <input type="text" name='nombre' id="nombre" size="30" <? if(isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'')echo "value='".$_REQUEST['nombre']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return soloNumerosYLetras(event)" maxlength="100">
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Apellidos del usuario</td>
                            <td >
                                    <input type="text" name='apellido' id="apellido" size="30" <? if(isset($_REQUEST['apellido'])?$_REQUEST['apellido']:'')echo "value='".$_REQUEST['apellido']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return soloNumerosYLetras(event)" maxlength="100">
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Número de identificación </td>
                            <td >
                                    <input type="text" name='identificacion' id="identificacion" size="30" <? if(isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'')echo "value='".$_REQUEST['identificacion']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return solo_numero_sin_slash(event)" maxlength="11">
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Tipo de identificación</td>
                            <td >
                                    <? 
                                            $lista_tipos_documento= $this->html->cuadro_lista($tipos_documento,'tipo_documento',$this->configuracion,(isset($_REQUEST['tipo_documento'])?$_REQUEST['tipo_documento']:0),0,FALSE,$tab++,'tipo_documento','');
                                            echo $lista_tipos_documento;
                                    ?>
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Correo electrónico</td>
                            <td >
                                    <input type="text" name='correo' id="correo" size="30" <? if(isset($_REQUEST['correo'])?$_REQUEST['correo']:'')echo "value='".$_REQUEST['correo']."'";?> tabindex='<? echo $tab++;?>' maxlength="250">
                            </td>
                    </tr>
                    <tr>    
                            <td >Teléfono</td>
                            <td >
                                    <input type="text" name='telefono' id="telefono" size="30" <? if(isset($_REQUEST['telefono'])?$_REQUEST['telefono']:'')echo "value='".$_REQUEST['telefono']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return solo_numero_sin_slash(event)" maxlength="15">
                            </td>
                    </tr>
                    <tr>    
                            <td >Celular</td>
                            <td >
                                    <input type="text" name='celular' id="celular" size="30" <? if(isset($_REQUEST['celular'])?$_REQUEST['celular']:'')echo "value='".$_REQUEST['celular']."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return solo_numero_sin_slash(event)" maxlength="15">
                            </td>
                    </tr>
                    <tr>    
                            <td >Dirección</td>
                            <td >
                                    <input type="text" name='direccion' id="direccion" size="30" <? if(isset($_REQUEST['direccion'])?$_REQUEST['direccion']:'')echo "value='".$_REQUEST['direccion']."'";?> tabindex='<? echo $tab++;?>' maxlength="250">
                            </td>
                    </tr>
                    <tr>    
                            <td >Tipo de contrato</td>
                            <td >
                                    <input type="radio" name="tipo_contrato" value="9" onclick="$('#img_fec_fin').show();" <? if(!(isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:'') || (isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:'')=='9'){echo 'checked';}?> tabindex='<? echo $tab++;?>'> OPS&nbsp;&nbsp;
                                    <input type="radio" name="tipo_contrato" value="10" onclick="$('#img_fec_fin').hide();$('#fecha_fin').val('');" <? if( (isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:'')=='10'){echo 'checked';}?>> Planta<br>
                            </td>
                    </tr>
				
                    <tr>    
                            <td ><span style='color:red;' > * </span>Fecha de inicio de contrato</td>
                            <td>
                            <table>
                                <tr>
                                    <td >
                                        <input name="fecha_inicio" type="text" id="fecha_inicio" size="15" <? if(isset($_REQUEST['fecha_inicio'])?$_REQUEST['fecha_inicio']:'')echo "value='".$_REQUEST['fecha_inicio']."'";?>   readonly="readonly"/></td>
                                    <td >
                                        <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="btnFecha" id="btnFecha" tabindex='<? echo $tab++;?>'>

                                        <script type="text/javascript">  
                                            Calendar.setup({
                                                inputField    : "fecha_inicio",
                                                button        : "btnFecha",
                                                align         : "Tr"
                                            });
                                        </script>
                                    </td>
                                </tr>
                            </table>
                            </td>
                    </tr>
                    <tr>    
                            <td >Fecha de fin de contrato(Si es OPS)</td>
                            <td>
                                <table><tr>
                                    <td >
                                        <input name="fecha_fin" type="text" id="fecha_fin" size="15" <? if(isset($_REQUEST['fecha_fin'])?$_REQUEST['fecha_fin']:'')echo "value='".$_REQUEST['fecha_fin']."'";?>  readonly="readonly"/>
                                    </td>
                                    <td >
                                        <div  id="img_fec_fin"  <?if(isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:''<>'10' && isset($_REQUEST['tipo_contrato'])?$_REQUEST['tipo_contrato']:''<>'') echo "style='display: none'"?>>
                                        <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="btnFechaFin" id="btnFechaFin" tabindex='<? echo $tab++;?>'>
                                        <script type="text/javascript">  
                                            Calendar.setup({
                                                inputField    : "fecha_fin",
                                                button        : "btnFechaFin",
                                                align         : "Tr"
                                            });
                                        </script>
                                        </div>
                                    </td>
                                </tr></table>
                                </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>Tipo Cuenta</td>
                            <td >
                                    <? 
                                        $lista_tipos_cuenta= $this->html->cuadro_lista($tipos_cuenta,'tipo_cuenta',$this->configuracion,(isset($_REQUEST['tipo_cuenta'])?$_REQUEST['tipo_cuenta']:0),0,FALSE,$tab++,'tipo_cuenta','');
                                        echo $lista_tipos_cuenta;
                                    ?>

                            </td>
                    </tr>
				
                    <tr>    
                            
                            <td colspan='2' align='center'><? $this->enlaceRegistrar();?></td>
                    </tr>
				
		</table>
            <div id="div_mensaje1" align="center" class="ab_name">
                Espacios requeridos (*)            
            </div>
           
	    </div>
               
           </form>
        <?
    }

  function consultarTiposDocumento() {
      $cadena_sql = $this->sql->cadena_sql("consultarTipoDocumento", '');
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  function consultarTiposCuenta() {
      $cadena_sql = $this->sql->cadena_sql("consultarTipoCuenta", '');
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
       //$pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->bloque?>">
          <input type='hidden' name='opcion' value="registrar">
          <input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">                              
            
            
         <?
    }
    
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_consultarSolicitudesusuario";
            $variable2.="&opcion=consultar";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
}
?>
