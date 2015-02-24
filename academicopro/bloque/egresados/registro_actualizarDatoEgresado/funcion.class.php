<?php


/**
 * Funcion registro_actualizarDatosGraduando
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package 
 * @subpackage registro_actualizarDatosGraduando
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 01/11/2013
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
 * Clase funcion_registro_actualizarDatosEgresado
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package registro_actualizarDatosGraduando
 * @subpackage Admin
 */
class funcion_registro_actualizarDatosEgresado extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;
  private $datosEgresado;
  private $cambiosTEgresado;
  private $cambiosTEstudiante;
  private $cambiosTOtros;
  private $datos;
  
  /**
     * Método constructor que crea el objeto sql de la clase funcion_registro_actualizarDatosEgresado
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
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

        $this->formulario = "registro_actualizarDatosGraduando";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "registro_actualizarDatosGraduando";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_registro_actualizarDatosEgresado($configuracion);
       
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

        /**
         * Intancia para crear la conexion ORACLE
         */
        
        $this->accesoOracle = $this->conectarDB($configuracion, "oraclesga");
        $this->procedimientos=new procedimientos();

    }

   
    
     /**
     * Función para consultar los datos de un egresado
     * @param type $codEstudiante
     * @return type 
     */
    function consultaDatosEgresado($codEstudiante,$codProyecto) {
        $datos=array('codEstudiante'=>$codEstudiante,
                    'codProyecto'=>$codProyecto
            );
          $cadena_sql = $this->sql->cadena_sql("datos_egresado", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado[0];
    }
    
     /**
     * Función para consultar si tiene datos en la tabla de acegresado
     * @param type $codEstudiante,$codProyecto
     * @return type 
     */
    function existeDatosEgresado($codEstudiante,$codProyecto) {
        $datos=array('codEstudiante'=>$codEstudiante,
                    'codProyecto'=>$codProyecto
            );
          $cadena_sql = $this->sql->cadena_sql("existe_acegresado", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
    }
    
    function verificarCambiosDatos($tipo){
        if(is_array($this->datosEgresado)){
            $indiceEgresado=0;
            switch ($tipo) {
                
                    case 'contacto':
                            
                            if($this->datos['correoElectronico']!=(isset($this->datosEgresado['EGR_EMAIL'])?$this->datosEgresado['EGR_EMAIL']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_EMAIL';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['correoElectronico']."'";
                                $indiceEgresado++;
                            }
                    break;
                                       
                default:
                    break;
            }
            
            
        }
                        
    }
    
    
    function verificarActualizacionDatosE($tipo){
        $this->datos= $_REQUEST;
        $validacionDatos = $this->validarDatosRegistro();
        if($validacionDatos!='ok'){
                    echo "<script>alert('".$validacionDatos."')</script>";
                    $pagina=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                    $variable='pagina=admin_actualizarDatosEgresado';
                    $variable.='&opcion=verEgresado';
                    $variable.='&datoBusqueda='.$this->datos['codEstudiante'];
                    $variable.='&tipoBusqueda=codigo';
                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
        }
        $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
        $codProyecto = (isset($_REQUEST['proyectoCurricular'])?$_REQUEST['proyectoCurricular']:'');
        if($codEstudiante ){
            $this->datosEgresado = $this->consultaDatosEgresado($codEstudiante,$codProyecto);
            $existeDatosE = $this->existeDatosEgresado($codEstudiante,$codProyecto);
            if(is_array($existeDatosE)){
                    $this->verificarCambiosDatos($tipo);
                    $listaCambiosEgresado='';
                    if(is_array($this->cambiosTEgresado)){
                        foreach ($this->cambiosTEgresado as $cambio) {

                            if(!$listaCambiosEgresado){
                                $listaCambiosEgresado = $cambio['campo']."=".$cambio['valor']."";
                            }else{
                                $listaCambiosEgresado.= ", ".$cambio['campo']."=".$cambio['valor']."";
                            }

                        }
                    }


                    if($listaCambiosEgresado){
                        $actualizadoEgresado = $this->actualizarRegistroEgresado($listaCambiosEgresado);

                        $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                        'evento'=>81,
                                                                                        'descripcion'=>'Actualización correo egresado',
                                                                                        'registro'=>$this->datosEgresado['EGR_EMAIL'].'=>'.$this->datos['correoElectronico'],
                                                                                        'afectado'=>$codEstudiante);
                        $this->procedimientos->registrarEvento($variablesRegistro);

                    }
            }else{
                $this->datosEgresado['CORREO_NVO']=$this->datos['correoElectronico'];
                $actualizadoEgresado = $this->registrarDatosEgresado();
                if($actualizadoEgresado){
                    $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                        'evento'=>82,
                                                                                        'descripcion'=>'Registrar datos (correo) egresado',
                                                                                        'registro'=>"Proyecto->".$this->datosEgresado['EST_CRA_COD'].", Cod.-> ".$this->datosEgresado['EST_COD'].", Id.-> ".$this->datosEgresado['EST_NRO_IDEN'].", Tipo Id.-> ".$this->datosEgresado['EST_TIPO_IDEN'].", Nombre ".$this->datosEgresado['NOMBRE']." ".$this->datosEgresado['APELLIDO'].", Dir.-> ".$this->datosEgresado['EST_DIRECCION'].", Tel.-> ".$this->datosEgresado['EST_TELEFONO'].", Correo-> ".$this->datosEgresado['CORREO_NVO'],
                                                                                        'afectado'=>$codEstudiante);
                        $this->procedimientos->registrarEvento($variablesRegistro);
                }
            }
            $pagina=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable='pagina=admin_actualizarDatosEgresado';
            $variable.='&opcion=verEgresado';
            $variable.='&datoBusqueda='.$this->datos['codEstudiante'];
            $variable.='&tipoBusqueda=codigo';
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
    }
    
    function validarDatosRegistro(){
            $mensaje='';
            $band=0;
            if($this->datos['proyectoCurricular'] && $this->datos['codEstudiante'] ){
                    if(is_numeric($this->datos['proyectoCurricular']) && is_numeric($this->datos['codEstudiante']) ){
                       
                        if($band==0){
                            $mensaje='ok';
                        }
                    }else{
                        $mensaje= "Error al actualizar datos, el valor del proyecto, o código de estudiante no son válidos.";
                    }
            }else{
                $mensaje= "Error al actualizar, faltan datos requeridos.";
            }
            
            return $mensaje;
    }
    
    
    function actualizarRegistroEgresado($listaCambios){
        $datos=array('listaCambios'=>$listaCambios,
                    'codEstudiante'=>  $this->datosEgresado['EGR_EST_COD']
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_egresado", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
   
   function registrarDatosEgresado(){
        $cadena_sql = $this->sql->cadena_sql("registrarDatosEgresado", $this->datosEgresado);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
}
    ?>