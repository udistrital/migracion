<?php


/**
 * Funcion admin_consultarHistoricoRecibos
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package recibos
 * @subpackage admin_consultarHistoricoRecibos
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 25/06/2013
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");

/**
 * Clase funcion_adminConsultarHistoricoRecibos
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package admin_consultarHistoricoRecibos
 * @subpackage Admin
 */
class funcion_adminConsultarHistoricoRecibos extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminConsultarHistoricoRecibos
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {
        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        $this->configuracion=$configuracion;
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "admin_consultarHistoricoRecibos";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "recibos/admin_consultarHistoricoRecibos";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_adminConsultarHistoricoRecibos($configuracion);
        /**
         * Instancia para crear la conexion General
         */
        $this->acceso_db = $this->conectarDB($configuracion, "");
        /**
         * Instancia para crear la conexion de MySQL
         */
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        /**
         * Datos de sesion
         */
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->validacion=new validarInscripcion();
  
        /**
         * Intancia para crear la conexion ORACLE
         */
        if($this->nivel==80){//soporte
            $this->accesoOracle = $this->conectarDB($configuracion, 'soporteoas');
        }elseif($this->nivel==4||$this->nivel==28){//coordinador
            $this->accesoOracle = $this->conectarDB($configuracion, 'coordinador');
        }elseif($this->nivel==51||$this->nivel==52){//estudiante
            $this->accesoOracle = $this->conectarDB($configuracion, 'estudianteCred');
        }elseif($this->nivel==110){//asistente proyecto
            $this->accesoOracle = $this->conectarDB($configuracion, 'asistente');
        }elseif($this->nivel==117){//asistente CERI
            $this->accesoOracle = $this->conectarDB($configuracion, 'asistenteCeri');
        }elseif($this->nivel==68){//bienestar
            $this->accesoOracle = $this->conectarDB($configuracion, 'bienestar');
        }elseif($this->nivel==114||$this->nivel==116){//secretario proyecto y secretario de secretaria
            $this->accesoOracle = $this->conectarDB($configuracion, 'secretario');
        }elseif($this->nivel==109){//asistente contabilidad
            $this->accesoOracle = $this->conectarDB($configuracion, 'asistenteCont');
        }elseif($this->nivel==83){//secretario academico
            $this->accesoOracle = $this->conectarDB($configuracion, 'secretarioAcad');
        }elseif($this->nivel==16){//decano
            $this->accesoOracle = $this->conectarDB($configuracion, 'decano');
        }else{
            echo "NO TIENE PERMISOS PARA ESTE MODULO";
            exit;
        }
    }

    /**
     * Funcion para mostrar el formulario de consulta para ingresar el codigo del estudiante
     */
    function mostrarFormularioConsulta() {
        $datos_estudiante='';
        $codEstudiante='';
      ?>
    <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
    <? if($this->nivel!=51 && $this->nivel!=52 && $this->nivel!=''){ ?>  
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>

          <table id="tabla" class="sigma" width="100%">
          <caption class="sigma centrar">
            CONSULTAR HIST&Oacute;RICO RECIBOS DE PAGO
          </caption><br>
            <tr class="sigma derecha">
                <td width="50%"> Por C&oacute;digo<input type="radio" name="tipoBusqueda" value="codigo" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="codigo" || !(isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')){echo "checked";} ?>><br>
                                Por No. de Identificaci&oacute;n<input type="radio" name="tipoBusqueda" value="identificacion" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="identificacion"){echo "checked";} ?> ><br>
                                Por Nombre<input type="radio" name="tipoBusqueda" value="nombre" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="nombre"){echo "checked";} ?> >
                                 </td>
              <td width="1%">
                 
                <input type="text" name="datoBusqueda" size="20" onkeypress="return soloNumerosYLetras(event)" value="<? echo (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'')?>">
                <input type="hidden" name="opcion" value="consultar">
                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                
              </td>
              
            <td align="left">
              <input class="boton" type="button" value="Consultar" onclick="document.forms['<? echo $this->formulario?>'].submit()">
            </td>
          </tr>
        </table>
        
     </form>
<?
    }
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
        $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
        $datoValido = $this->validarDatoBusqueda($datoBusqueda);
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
                        echo "C&oacute;digo de estudiante no valido";
                    }
                    if($tipoBusqueda=='identificacion'){
                        echo "Identificaci&oacute;n de estudiante no valida";
                    }
                }
                if($tipoBusqueda=='nombre'){
                    $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
                    if(is_array($codEstudiante )){
                            $this->mostrarListadoProyectos($codEstudiante);
                        }
                }

                if(is_numeric($codEstudiante)){
                        $datos_estudiante = $this->consultaDatosEstudiante($codEstudiante);
                }

                if($this->nivel==4){
                        $datos['codEstudiante']=$codEstudiante;
                        $verificacion=$this->validacion->validarEstudiante($datos);        
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
                if($this->nivel==16){
                        $verificacion=$this->validacion->validarFacultadDecano($codEstudiante,$this->usuario);        
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
           
                if($this->nivel==112||$this->nivel==116||$this->nivel==111||$this->nivel==115){
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
                if($this->nivel==110||$this->nivel==114){
                    if(isset($codEstudiante)&&$codEstudiante!='')
                    {
                    $verificacion=$this->validacion->validarProyectoAsistente($codEstudiante,$this->usuario);
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
                    }else
                                                {
                            ?>
                                  <table >
                                    <tr>
                                      <td>
                                          Digite el c&oacute;digo de estudiante
                                      </td>
                                    </tr>
                                  </table>
                            <?
                            exit;
                        }

                }
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
                if(is_array($datos_estudiante)){
                    $recibos = $this->consultaRecibos($codEstudiante);
                    $this->mostrarDatosEstudiante($datos_estudiante); 
                    if(is_array($recibos)){
                        $this->mostrarHistoricoRecibos($recibos); 
                    }else{
                        echo "<br>No existen registros de recibos para ese c&oacute;digo";
                        exit;
                    }
                }elseif(is_array($codEstudiante)){
                        echo "<br>Seleccione un c&oacute;digo de estudiante";
                        exit;
                    }else{
                        if($tipoBusqueda=='identificacion'){
                            echo "<br>Identificaci&oacute;n de estudiante no v&aacute;lida";
                            exit;
                        }else{
                            echo "<br>C&oacute;digo de estudiante no v&aacute;lido";
                            exit;
                        }
                    }
        }else{
            echo "Valor no valido para la busqueda";
        }
    }


    /**
     * busca los datos de los recibos de un estudiante en la base de datos
     * @param type $codEstudiante
     * @return type 
     */
    function consultaRecibos($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("consultar_recibos_estudiante", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    
    /**
     * Función para consultar los datos de un estudiante
     * @param type $codEstudiante
     * @return type 
     */
    function consultaDatosEstudiante($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("datos_estudiante", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para mostrar los datos de un estudiante
     * @param type $datos_estudiante 
     */
    function mostrarDatosEstudiante($datos_estudiante){
        if ($datos_estudiante[0]['MODALIDAD']=='S'){
            $creditos = "CREDITOS";
        }elseif ($datos_estudiante[0]['MODALIDAD']=='N'){
            $creditos = "HORAS";
        }else{
            $creditos="";
        }
        ?>
    <br><br><table id="tabla" class="sigma" width="100%">
                    
                    <tr >
                        <td>CODIGO:</td>
                        <td><? echo $datos_estudiante[0]['CODIGO'];?></td>
                        <td>IDENTIFICACI&Oacute;N:</td>
                        <td><? echo $datos_estudiante[0]['IDENTIFICACION'];?></td> 
                    </tr>
                    <tr >
                        <td>NOMBRE:</td>
                        <td><? echo $datos_estudiante[0]['NOMBRE'];?></td>
                    </tr>
                    <tr >
                        <td>PROYECTO:</td>
                        <td><? echo $datos_estudiante[0]['PROYECTO'];?></td>
                        <td>ESTADO:</td>
                        <td><? echo $datos_estudiante[0]['ESTADO'];?></td>
                    </tr>
                     <tr >
                        <td>FACULTAD:</td>
                        <td><? echo $datos_estudiante[0]['FACULTAD'];?></td>
                        <td>MODALIDAD:</td>
                        <td><? echo $creditos;?></td>
                    </tr>
                    
                </table>
        <?
         
    }
    
    /**
     * Función para mostrar el historico de recibos de pago
     * @param type $recibos 
     */
    function mostrarHistoricoRecibos($recibos){
        ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tablaRecibos').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

    <?
        $html ="<h1> HIST&Oacute;RICO DE RECIBOS DE PAGO</h1>";
        $html .=" <table class='contenidotabla' id='tablaRecibos' width='100%'>";
        $html .=$this->armarEncabezadoTablaRecibos();
        $html .="<tbody>";

        $indice_weboffice=$this->configuracion["host"]."/weboffice/index.php?";	
	foreach ($recibos as $key => $recibo) {
            
                $variable="pagina=imprimirFactura";
                $variable.="&action=loginCondor";
                $variable.="&modulo=imprimirFactura";
                $variable.="&tipoUser=80";
                $variable.="&nivel=80";
                $variable.="&opcion=imprimir";
                $variable.="&no_pagina=true";
                $variable.="&factura=".$recibo['SECUENCIA'];
                $variable.="&anioRecibo=".$recibo['ANIO'];
                $variable.="&periodoRecibo=".$recibo['PERIODO'];
                $variable.="&usuario=".$this->usuario;
                
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $conceptos= $this->consultaConceptosRecibos($recibo['SECUENCIA'], $recibo['ANIO']);
                $valor_seguro = $this->buscarValorConcepto($conceptos,2);
                if(!$conceptos && $recibo['CUOTA']==1){
                    $valor_seguro = $this->consultaValorSeguro($recibo['ANIO'],$recibo['PERIODO']);
                }
                $valor_carnet = $this->buscarValorConcepto($conceptos,3);
                $valor_sistematizacion = $this->buscarValorConcepto($conceptos,4);
                
                $valor_total_ord = $this->obtenerValorTotalOrdinario($recibo['SECUENCIA'],$recibo['ANIO'],$recibo['PERIODO'],$recibo['VALOR_ORD'],$recibo['CUOTA'],$conceptos);                
                $valor_total_extra= $this->obtenerValorTotalExtraordinario($recibo['SECUENCIA'],$recibo['ANIO'],$recibo['PERIODO'], $recibo['VALOR_EXTRA'],$recibo['CUOTA'],$conceptos);
                
                $html .="<tr>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['ANIO']."-".$recibo['PERIODO'] ."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['SECUENCIA']."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($recibo['VALOR_ORD'])."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($valor_seguro)."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($valor_carnet)."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($valor_sistematizacion)."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($valor_total_ord)."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA_ORD'])?$recibo['FECHA_ORD']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($valor_total_extra)."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA_EXTRA'])?$recibo['FECHA_EXTRA']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['CUOTA']."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['REALIZO_PAGO']."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['VALOR_PAGADO'])?'$'.number_format($recibo['VALOR_PAGADO']):'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA_PAGO'])?$recibo['FECHA_PAGO']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['OBSERVACIONES'])?$recibo['OBSERVACIONES']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['ESTADO']."</td>";
                if($recibo['ESTADO']=='A' && $this->nivel!=51 && $this->nivel!=52 && $this->nivel!=''){
                    $html .="<td class='cuadro_plano centrar'><a target='_blank' href='".$indice_weboffice.$variable."'>Ver Recibo</a></td>";
                }else{
                    $html .="<td class='cuadro_plano centrar'>&nbsp;</td>";
                }
                $html .="</tr>";
             
         }
         $html .="</tbody>";
         $html .="</table>";
         echo $html;
    }
    
    /**
     * Función para armar el encabezado de la tabla de recibos de pago y retorna el html
     * @return string 
     */
    function armarEncabezadoTablaRecibos(){
        
        $html ="<br>";
        $html .="<thead>";
        $html .="<tr class='sigma '>";
        $html .="   <th>Per&iacute;odo</th>";
        $html .="   <th>Secuencia</th>";
        $html .="   <th>Matr&iacute;cula</th>";
        $html .="   <th>Seguro</th>";
        $html .="   <th>Carnet</th>";
        $html .="   <th>Sistematizaci&oacute;n</th>";
        $html .="   <th>Valor ordinario</th>";
        $html .="   <th>Fecha ordinaria</th>";
        $html .="   <th>Valor extraordinario</th>";
        $html .="   <th>Fecha extraordinaria</th>";
        $html .="   <th>Cuota</th>";
        $html .="   <th>Pago</th>";
        $html .="   <th>Valor pagado</th>";
        $html .="   <th>Fecha de pago</th>";
        $html .="   <th>Observaciones</th>";
        $html .="   <th>Estado del recibo</th>";
        $html .="   <th></th>";
        $html .="</tr>";
        $html .="</thead>";
       return $html;
    }
    
     /**
     * busca los datos de los conceptos de un recibo
     * @param int $codSecuencia,
     * @param int $anio
     * @return type 
     */
    function consultaConceptosRecibos($codSecuencia,$anio) {
            $datos= array(  'secuencia'=>$codSecuencia,
                            'anio'=>$anio);
          $cadena_sql = $this->sql->cadena_sql("consultar_conceptos_recibo", $datos);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para obtener el valor total del recibo por pago ordinario
     * @param type $codSecuencia
     * @param type $anio
     * @return type
     */
    function obtenerValorTotalOrdinario($codSecuencia, $anio,$periodo,$total_matricula,$cuota,$conceptos){
        $total=$total_matricula;
        if(is_array($conceptos)){
            foreach ($conceptos as $concepto) {
                if($concepto['AER_REFCOD']!=1){
                    $total=$total+$concepto['AER_VALOR'];
                }
            }
        }else{
            if($cuota==1){
                $valorSeguro = $this->consultaValorSeguro($anio,$periodo);
            }else{
                $valorSeguro = 0;
            }
            $total=$total+$valorSeguro;
        }
        return $total;
    }
    
    /**
     * Función para obtener el valor total del recibo por pago Extraordinario
     * @param type $codSecuencia
     * @param type $anio
     * @param type $valor_matr_extraordinario
     * @return type
     */
    function obtenerValorTotalExtraordinario($codSecuencia, $anio,$periodo,$valor_matr_extraordinario,$cuota,$conceptos){
        $total=$valor_matr_extraordinario;
        if(is_array($conceptos)){
            foreach ($conceptos as $concepto) {
                if($concepto['AER_REFCOD']!=1){
                    $total=$total+$concepto['AER_VALOR'];
                }
            }
        }else{
            if($cuota==1){
                $valorSeguro = $this->consultaValorSeguro($anio,$periodo);
            }else{
                $valorSeguro = 0;
            }
            $total=$total+$valorSeguro;
        }
        return $total;
    }
    
    /**
     * Función para consultar el valor del seguro de un respectivo periodo academico
     * @param int $anio
     * @param int $periodo
     * @return int
     */
    function consultaValorSeguro($anio,$periodo) {
          $datos = array('anio'=>$anio,
                        'periodo'=>$periodo);
          $cadena_sql = $this->sql->cadena_sql("consultar_valor_seguro", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado[0][0];
          
    }
    
    /**
     * Función para buscar el valor de un concepto
     * @param <array> $conceptos
     * @param int $codConcepto
     * @return int
     */
    function buscarValorConcepto($conceptos,$codConcepto){
        $valor=0;
        if($conceptos){
            foreach ($conceptos as $key => $concepto) {
                if($concepto['AER_REFCOD']==$codConcepto){
                         $valor= $concepto['AER_VALOR'];
                    }
            }
        }
        
        return $valor;
    }
    
    /**
     * Función para consultar los codigos de estudiantes relacionados a un número de identificación
     * @param int $identificacion
     * @return <array>
     */
    function consultarCodigoEstudiantePorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
   
    /**
     * Función para mostrar el listado de proyectos relacionados a un estudiante con el respectivo enlace
     * @param <array> $codigos
     */
    function mostrarListadoProyectos($codigos){
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        if(is_array($codigos)){
            echo "<br>C&oacute;digos relacionados a la busqueda:";
            echo "<br><br><table align='center' >";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=consultar";
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
     * Función para consultar los codigos de estudiante relacionados a un nombre
     * @param String $nombre
     * @return <array>
     */
    function consultarCodigoEstudiantePorNombre($nombre){
        $cadena_nombre='';
        $nombre = explode(" ", strtoupper($nombre));
        $palabras = count($nombre);
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
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ1234567890 ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
    
}
    ?>