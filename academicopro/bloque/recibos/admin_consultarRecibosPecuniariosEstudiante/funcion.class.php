<?php


/**
 * Funcion admin_consultarRecibosPecuniariosEstudiante
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package recibos
 * @subpackage admin_consultarRecibosPecuniariosEstudiante
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 26/11/2014
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
 * Clase funcion_adminConsultarRecibosPecuniariosEstudiante
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package admin_consultarRecibosPecuniariosEstudiante
 * @subpackage Admin
 */
class funcion_adminConsultarRecibosPecuniariosEstudiante extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminConsultarRecibosPecuniariosEstudiante
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
        $this->formulario = "admin_consultarRecibosPecuniariosEstudiante";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "recibos/admin_consultarRecibosPecuniariosEstudiante";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_adminConsultarRecibosPecuniariosEstudiante($configuracion);
       
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
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
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
        if($this->nivel==51 || $this->nivel==52 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
        }elseif($this->nivel==121 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "egresado");
        }else{
                echo "NO TIENE PERMISOS PARA ESTE MODULO";
                exit;
            }
    }

    /**
     * Funcion para mostrar el formulario de consulta para ingresar el codigo del estudiante
     */
    function mostrarFormularioConsultaRecPec() {
        $datos_estudiante='';
        $codEstudiante='';
      ?>
    <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
    <? 
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:$this->usuario);
        $tipoBusqueda=(isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'codigo');
        $datoValido = $this->validarDatoBusqueda($datoBusqueda);
        if($datoValido==true){
                if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                      
                    if($tipoBusqueda=='identificacion'){//esta opcion es para egresado que ingresa por identificación
                        $codEstudiante = $this->consultarCodigoEgresadoPorIdentificacion($datoBusqueda);
                        if(is_array($codEstudiante )){
                            $this->mostrarListadoProyectos($codEstudiante);
                        }else{
                            $datoBusqueda=$codEstudiante;
                            $tipoBusqueda='codigo';
                        }
                    }
                    if($tipoBusqueda=='codigo'){
                        $codEstudiante = $datoBusqueda;
                        $datos_estudiante = $this->consultaDatosEstudiante($codEstudiante);
                    }
                    if(is_array($datos_estudiante)){
                        $recibos = $this->consultaRecibosDerechosPecuniarios($codEstudiante);
                        $this->mostrarDatosEstudiante($datos_estudiante); 
                        if(is_array($recibos)){
                            $this->mostrarRecibosDerechosPecuniarios($recibos); 
                        }else{
                            echo "<br>No existen registros de recibos de derechos pecuniarios para ese c&oacute;digo";
                            exit;
                        }
                    }elseif(is_array($codEstudiante)){
                            echo "<br>Seleccione un c&oacute;digo de estudiante";
                            exit;
                        }elseif(!$codEstudiante && $tipoBusqueda!='identificacion'){
                            echo "<br>C&oacute;digo de estudiante no v&aacute;lido";
                            exit;

                        }
                }else{
                    if($tipoBusqueda=='codigo'){
                        echo "C&oacute;digo de estudiante no valido";
                    }
                    if($tipoBusqueda=='identificacion'){
                        echo "Identificaci&oacute;n de estudiante no valida";
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
    function consultaRecibosDerechosPecuniarios($codEstudiante) {
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
                    
                </table>
        <?
         
    }
    
    /**
     * Función para mostrar los recibos de pago de derechos pecuniarios
     * @param type $recibos 
     */
    function mostrarRecibosDerechosPecuniarios($recibos){
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
        $html ="<h1> RECIBOS GENERADOS DE DERECHOS PECUNIARIOS</h1>";
        $html .=" <table class='contenidotabla' id='tablaRecibos' width='100%'>";
        $html .=$this->armarEncabezadoTablaRecibosDerechos();
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
                
                $html .="<tr>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['ANIO']."-".$recibo['PERIODO'] ."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['SECUENCIA']."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA'])?$recibo['FECHA']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>$".number_format($recibo['VALOR'])."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['CONCEPTO'])?$recibo['CONCEPTO']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA_ORD'])?$recibo['FECHA_ORD']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['REALIZO_PAGO']."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['VALOR_PAGADO'])?'$'.number_format($recibo['VALOR_PAGADO']):'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['FECHA_PAGO'])?$recibo['FECHA_PAGO']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".(isset($recibo['OBSERVACIONES'])?$recibo['OBSERVACIONES']:'--')."</td>";
                $html .="<td class='cuadro_plano centrar'>".$recibo['ESTADO']."</td>";
                if($recibo['ESTADO']=='A' && $this->nivel!=''){
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
    function armarEncabezadoTablaRecibosDerechos(){
        
        $html ="<br>";
        $html .="<thead>";
        $html .="<tr class='sigma '>";
        $html .="   <th>Per&iacute;odo</th>";
        $html .="   <th>Secuencia</th>";
        $html .="   <th>Fecha de generaci&oacute;n</th>";
        $html .="   <th>Valor </th>";
        $html .="   <th>Concepto</th>";
        $html .="   <th>Fecha ordinaria</th>";
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
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "1234567890 ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
    
    /**
     * Función para consultar los codigos de estudiantes relacionados a un número de identificación
     * @param int $identificacion
     * @return <array>
     */
    function consultarCodigoEgresadoPorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_egresado_por_id", $identificacion);
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
           echo "<br>Seleccione el c&oacute;digo para seguir el proceso.";
             
        }
    }
    
}
    ?>