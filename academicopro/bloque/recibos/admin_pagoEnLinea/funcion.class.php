<?php


/**
 * Funcion admin_pagoEnLinea
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package recibos
 * @subpackage admin_pagoEnLinea
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 25/06/2013
 * Fecha Actualización: 24/12/2013
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

/**
 * Clase funcion_adminPagoEnLinea
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package admin_pagoEnLinea
 * @subpackage Admin
 */
class funcion_adminPagoEnLinea extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminPagoEnLinea
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
        $this->formulario = "admin_pagoEnLinea";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "recibos/admin_pagoEnLinea";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_adminPagoEnLinea($configuracion);
       /**
         * Intancia para crear la conexion ORACLE
         */
        $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");
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

    }

    /**
     * Función para verificar si el pago del recibo es ordinario o extraordinario
     */
    function verificarTipoPago(){
        $factura = (isset($_REQUEST['factura'])?$_REQUEST['factura']:0);
        $cod_estudiante = (isset($_REQUEST['usuario'])?$_REQUEST['usuario']:0);
        
        if($factura>0){
            $recibo = $this->consultarReciboPago($factura,$cod_estudiante);
            if($recibo[0]['REALIZO_PAGO']=='SI'){
                echo "El recibo ya se encuentra cancelado";
            }else{
                $tipo_pago = $this->obtenerTipoPago($recibo);
                $datosPago = $this->obtenerDatosPago($cod_estudiante,$recibo, $tipo_pago);
                if(is_array($datosPago) && $datosPago['VALOR_RECIBO']>0){
                    $this->mostrarDatosPago($datosPago);
                }else{
                    echo "Recibo de pago vencido";
                }
            }
        }else{
            echo "No existe codigo de recibo para pago";
        }
    }
    
    /**
     * Función para consultar los datos del recibo de pago que desea pagar en linea
     * @param int $factura
     * @param int $cod_estudiante
     * @return array
     */
    function consultarReciboPago($factura,$cod_estudiante){
        $datos=array('factura'=>$factura,
                     'cod_estudiante' =>$cod_estudiante);
        $cadena_sql = $this->sql->cadena_sql("consultar_recibo_estudiante", $datos);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función que devuelve el tipo de pago recibe las fechas formato dd/mm/aaaa
     * @param array $recibo
     * @return string
     */
    function obtenerTipoPago($recibo){
        $fecha_hoy = date("Ymd"); 
 
        $tmp_fecha_ord = explode("/", $recibo[0]['FECHA_ORD']);
        $fecha_ord = $tmp_fecha_ord[2].$tmp_fecha_ord[1].$tmp_fecha_ord[0];
        
        $tmp_fecha_extra = explode("/", $recibo[0]['FECHA_EXTRA']);
        $fecha_extra = $tmp_fecha_extra[2].$tmp_fecha_extra[1].$tmp_fecha_extra[0];
        
        if($fecha_hoy <= $fecha_ord){
            $tipo_pago = 'ORDINARIO';
        }  elseif($fecha_hoy <= $fecha_extra) {
            $tipo_pago = 'EXTRAORDINARIO';
        }else{
            $tipo_pago = 'NINGUNO';
        }
        return $tipo_pago;
    }
    
    /**
     * Función que devuelve los datos dependiendo el tipo de pago
     * @param int $cod_estudiante
     * @param array $recibo
     * @param String $tipo_pago
     * @return array
     */
    function obtenerDatosPago($cod_estudiante,$recibo,$tipo_pago) {
        $datos='';
        $valorPago=  $this->obtenerValorPago($recibo,$tipo_pago);
        $datos['VALOR_RECIBO']=(isset($valorPago)?$valorPago:0);
        if($datos['VALOR_RECIBO']>0){
            $datos_estudiante = $this->consultaDatosEstudiante($cod_estudiante);
            $datos['REFERENCIA']=$recibo[0]['SECUENCIA'];
            $datos['TIPO_DOC_IDEN']=$datos_estudiante[0]['TIPO_IDENTIFICACION'];
            $datos['NUM_DOC_IDEN']=$datos_estudiante[0]['IDENTIFICACION'];
            $datos['NOMBRE_ESTUDIANTE']=$datos_estudiante[0]['NOMBRE'];
            $datos['CONCEPTO']="1 - MATRICULA ";
            $datos['VALOR_IVA']=0;
            
        }
        return $datos;
    }
    
    /**
     * Función para consultar los datos de un estudiante
     * @param int $codEstudiante
     * @return type 
     */
    function consultaDatosEstudiante($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("datos_estudiante", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para mostrar los datos del pago
     * @param type $datosPago
     */
    function mostrarDatosPago($datosPago){
        //$enlace_pagina_pago = "http://190.144.140.19/udistrital/index.php";
        $enlace_pagina_pago = "https://www.abcpagos.com/udistrital/index.php";
            
        ?>

        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='<? echo $enlace_pagina_pago;?>' >
            <center>
            <table id="tabla" class="sigma" width="70%">
            <caption class="sigma centrar">
                PAGO EN L&Iacute;NEA
            </caption><br>
              <tr >
                    <td class="sigma derecha" width="50%">Referencia : </td>
                    <td ><? echo $datosPago['REFERENCIA'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Tipo documento de Identidad : </td>
                    <td ><? echo $datosPago['TIPO_DOC_IDEN'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Número documento de Identidad : </td>
                    <td ><? echo $datosPago['NUM_DOC_IDEN'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Nombre : </td>
                    <td ><? echo $datosPago['NOMBRE_ESTUDIANTE'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Valor a pagar : </td>
                    <td ><? echo $datosPago['VALOR_RECIBO'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Concepto : </td>
                    <td ><? echo $datosPago['CONCEPTO'];?></td>
              </tr>
              <tr >
                    <td class="sigma derecha" width="50%">Valor de Iva : </td>
                    <td ><? echo $datosPago['VALOR_IVA'];?></td>
              </tr>
              
              <tr>
                <td align="center" colspan='2'>
                  <input type="hidden" name="cod_empresa" value="0106">
                  <input type="hidden" name="codigo_servicio" value="1001">
                  <input type="hidden" name="referencia" value="<? echo $datosPago['REFERENCIA']; ?>">
                  <input type="hidden" name="tipo_documento" value="<? echo $datosPago['TIPO_DOC_IDEN'];?>">
                  <input type="hidden" name="documento" value="<? echo $datosPago['NUM_DOC_IDEN'];?>">
                  <input type="hidden" name="nombre" value="<? echo $datosPago['NOMBRE_ESTUDIANTE'];?>">
                  <input type="hidden" name="valor" value="<? echo $datosPago['VALOR_RECIBO'];?>">
                  <input type="hidden" name="concepto" value="<? echo $datosPago['CONCEPTO'];?>">
                  <input type="hidden" name="iva" value="<? echo $datosPago['VALOR_IVA'];?>">
                  
                  <input class="boton" type="submit" value="Pagar" >
                </td>
            </tr>
          </table>
        </center>
        </form>

<?
    }

    /**
     * Función para consultar el valor del seguro en un año y periodo respectivo
     * @param int $anio
     * @param int $periodo
     * @return <array>
     */
    function consultarValorSeguro($anio, $periodo){
        $datos= array('anio'=>$anio,
                        'periodo'=>$periodo);
        $cadena_sql = $this->sql->cadena_sql("valorSeguro", $datos);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para obtener el valor de pago
     * @param <array> $recibo
     * @param String $tipo_pago
     * @return int
     */
    function obtenerValorPago($recibo,$tipo_pago){
        //Si la secuencia esta registrada en ACREFEST entonces calculamos el pago basados en dicha informacion
	$valorMatricula=$recibo[0]['VALOR_ORD'];	
	$valorMatriculaExtra=$recibo[0]['VALOR_EXTRA'];
	
	$registroConceptos = $this->consultarConceptosRecibo($recibo[0]["SECUENCIA"],$recibo[0]["ANIO"]);
        if(is_array($registroConceptos))
	{	
			
		$otrosConceptos=0;
		$conceptos=0;
		
		//Matricula
		$j=0;
		while(isset($registroConceptos[$j][0]))
		{
			
			if($registroConceptos[$j][3]==2)
			{
				$valorSeguro=$registroConceptos[$j][4];
				$otrosConceptos+=$registroConceptos[$j][4];
			}
			elseif($registroConceptos[$j][3]>2)
			{
				$otrosConceptos+=$registroConceptos[$j][4];
			}
			
			$j++;
		}
		$valorPagar=$valorMatricula+$otrosConceptos;
		$valorPagarExtra=$valorMatriculaExtra+$otrosConceptos;
		
	}
	else
	{
		//Si es la primera cuota
		if($recibo[0]['CUOTA']==1)
		{
			$registroSeguro =$this->consultarValorSeguro($recibo[0]['ANIO'],$recibo[0]['PERIODO']);
                        $valorSeguro=$registroSeguro[0][0];
			
			$valorPagar=$valorMatricula+$valorSeguro;
		}
		else
		{
			$valorSeguro=0;
			$valorPagar=$valorMatricula;
		
		}
		
		//Calcular matricula extraordinario
		
		if($recibo[0]['CUOTA']==1)
		{
			$valorPagarExtra=$valorMatriculaExtra+$valorSeguro;
		}
		else
		{
			$valorPagarExtra=$valorMatriculaExtra;
		}
	}
        if($tipo_pago=='ORDINARIO'){
            return $valorPagar;
        }elseif($tipo_pago=='EXTRAORDINARIO'){
            return $valorPagarExtra;
        }else{
            return 0;
        }
        
	
    }
    
    
    /**
     * Función para consultar los conceptos relacionados a un recibo de acuerdo a la secuencia y año del recibo
     * @param int $secuencia
     * @param int $anio
     * @return <array>
     */
    function consultarConceptosRecibo($secuencia,$anio){
        $datos = array( 'SECUENCIA'=>$secuencia,
                        'ANIO'=>$anio);
        $cadena_sql = $this->sql->cadena_sql("conceptosRecibo", $datos);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    }
    ?>