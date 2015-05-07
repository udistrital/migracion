<?php


/**
 * Funcion registro_registrarDatosGraduando
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package 
 * @subpackage registro_registrarDatosGraduando
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
 * Clase funcion_registro_registrarDatosGraduando
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package registro_registrarDatosGraduando
 * @subpackage Admin
 */
class funcion_registro_registrarDatosGraduando extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;
  private $datosEgresado;
  private $cambiosTEgresado;
  private $cambiosTEstudiante;
  private $cambiosTOtros;
  private $datos;
  
  /**
     * Método constructor que crea el objeto sql de la clase funcion_registro_registrarDatosGraduando
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

        $this->formulario = "registro_registrarDatosGraduando";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "registro_registrarDatosGraduando";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_registro_registrarDatosGraduando($configuracion);
       
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
        if($this->nivel==83){
                            
            $this->accesoOracle = $this->conectarDB($configuracion, "secretarioacad");
        }elseif($this->nivel==116||$this->nivel==114){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }elseif($this->nivel==4 || $this->nivel==28){
                            
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        }elseif($this->nivel==110)
        {
            $this->accesoOracle = $this->conectarDB($this->configuracion, "asistente");
        }
        $this->procedimientos=new procedimientos();

    }

   
    
     /**
     * Función para consultar los datos de un egresado
     * @param type $codEstudiante
     * @return type 
     */
    function consultaDatosEgresado($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("datos_egresado", $codEstudiante);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado[0];
    }
    
    function verificarCambiosDatos(){
        if(is_array($this->datosEgresado)){
            $indiceEgresado=0;
            $indiceEstudiante=0;
            $indiceOtros=0;
                            if($this->datos['nombreEstudiante'].' '.$this->datos['apellidoEstudiante']!=(isset($this->datosEgresado['EGR_NOMBRE'])?$this->datosEgresado['EGR_NOMBRE']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_NOMBRE';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['nombreEstudiante'],'UTF-8').' '.mb_strtoupper($this->datos['apellidoEstudiante'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['apellidoEstudiante'].' '.$this->datos['nombreEstudiante']!=(isset($this->datosEgresado['EST_NOMBRE'])?$this->datosEgresado['EST_NOMBRE']:'')){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_NOMBRE';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".mb_strtoupper($this->datos['apellidoEstudiante'],'UTF-8').' '.mb_strtoupper($this->datos['nombreEstudiante'],'UTF-8')."'";
                                $indiceEstudiante++;
                            }

                            if($this->datos['identificacion']!=(isset($this->datosEgresado['EGR_NRO_IDEN'])?$this->datosEgresado['EGR_NRO_IDEN']:'') && is_numeric($this->datos['identificacion'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_NRO_IDEN';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['identificacion']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['identificacion']!=(isset($this->datosEgresado['EST_NRO_IDEN'])?$this->datosEgresado['EST_NRO_IDEN']:'') && is_numeric($this->datos['identificacion'])){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_NRO_IDEN';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['identificacion']."'";
                                $indiceEstudiante++;
                            }

                            if($this->datos['tipoIdentificacion']!=(isset($this->datosEgresado['EGR_TIP_IDEN'])?$this->datosEgresado['EGR_TIP_IDEN']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TIP_IDEN';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['tipoIdentificacion']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['tipoIdentificacion']!=(isset($this->datosEgresado['EST_TIPO_IDEN'])?$this->datosEgresado['EST_TIPO_IDEN']:'')){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_TIPO_IDEN';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['tipoIdentificacion']."'";
                                $indiceEstudiante++;
                            }

                            if($this->datos['lugarExpedicion']!=(isset($this->datosEgresado['EGR_LUG_EXP_IDEN'])?$this->datosEgresado['EGR_LUG_EXP_IDEN']:'') && is_numeric($this->datos['identificacion'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_LUG_EXP_IDEN';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['lugarExpedicion']."'";
                                $indiceEgresado++;
                            }

                            if((isset($this->datos['distritoMilitar'])?$this->datos['distritoMilitar']:'')!=(isset($this->datosEgresado['EST_NRO_DIS_MILITAR'])?$this->datosEgresado['EST_NRO_DIS_MILITAR']:'') && is_numeric($this->datos['distritoMilitar'])){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_NRO_DIS_MILITAR';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['distritoMilitar']."'";
                                $indiceEstudiante++;
                            }

                            if((isset($this->datos['libretaMilitar'])?$this->datos['libretaMilitar']:'')!=(isset($this->datosEgresado['EST_LIB_MILITAR'])?$this->datosEgresado['EST_LIB_MILITAR']:'')){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_LIB_MILITAR';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['libretaMilitar']."'";
                                $indiceEstudiante++;
                            }

                            if($this->datos['genero']!=(isset($this->datosEgresado['EGR_SEXO'])?$this->datosEgresado['EGR_SEXO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_SEXO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['genero']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['genero']!=(isset($this->datosEgresado['EST_SEXO'])?$this->datosEgresado['EST_SEXO']:'')){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_SEXO';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['genero']."'";
                                $indiceEstudiante++;
                            }
                            // no permite el cambio de estado si está en egresado, y solo se puede cambiar a estado T ó E, si es coordinador o secretario
                            if((isset($this->datos['estado'])?$this->datos['estado']:'')!=(isset($this->datosEgresado['EST_ESTADO_EST'])?$this->datosEgresado['EST_ESTADO_EST']:'') && (isset($this->datosEgresado['EST_ESTADO_EST'])?$this->datosEgresado['EST_ESTADO_EST']:'')!='E' && (isset($this->datos['estado'])?$this->datos['estado']:'')=='E'){
                                        $this->cambiosTEstudiante[$indiceEgresado]['campo']='EST_ESTADO_EST';
                                        $this->cambiosTEstudiante[$indiceEgresado]['valor']="'".$this->datos['estado']."'";
                                        $indiceEgresado++;
                                
                            }else{
                                if((isset($this->datos['estado'])?$this->datos['estado']:'')!=(isset($this->datosEgresado['EST_ESTADO_EST'])?$this->datosEgresado['EST_ESTADO_EST']:'') && (isset($this->datosEgresado['EST_ESTADO_EST'])?$this->datosEgresado['EST_ESTADO_EST']:'')==='E' ){
                                    //$mensaje .= "El estado de un egresado no puede ser cambiado. ";
                                }
                                
                            }

                            if($this->datos['direccion']!=(isset($this->datosEgresado['EGR_DIRECCION_CASA'])?$this->datosEgresado['EGR_DIRECCION_CASA']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECCION_CASA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['direccion'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['direccion']!=(isset($this->datosEgresado['EST_DIRECCION'])?$this->datosEgresado['EST_DIRECCION']:'')){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_DIRECCION';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".mb_strtoupper($this->datos['direccion'],'UTF-8')."'";
                                $indiceEstudiante++;
                            }
                            if($this->datos['ciudadResidencia']!=(isset($this->datosEgresado['EOT_COD_MUN_RES'])?$this->datosEgresado['EOT_COD_MUN_RES']:'') && is_numeric($this->datos['ciudadResidencia'])){
                                $this->cambiosTOtros[$indiceOtros]['campo']='EOT_COD_MUN_RES';
                                $this->cambiosTOtros[$indiceOtros]['valor']="'".$this->datos['ciudadResidencia']."'";
                                $indiceOtros++;
                            }

                            if($this->datos['telefonoFijo']!=(isset($this->datosEgresado['EST_TELEFONO'])?$this->datosEgresado['EST_TELEFONO']:'') && is_numeric($this->datos['telefonoFijo'])){
                                $this->cambiosTEstudiante[$indiceEstudiante]['campo']='EST_TELEFONO';
                                $this->cambiosTEstudiante[$indiceEstudiante]['valor']="'".$this->datos['telefonoFijo']."'";
                                $indiceEstudiante++;
                            }
                            
                            if($this->datos['telefonoFijo']!=(isset($this->datosEgresado['EGR_TELEFONO_CASA'])?$this->datosEgresado['EGR_TELEFONO_CASA']:'') && is_numeric($this->datos['telefonoFijo'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TELEFONO_CASA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoFijo']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['telefonoEmpresa']!=(isset($this->datosEgresado['EGR_TELEFONO_EMPRESA'])?$this->datosEgresado['EGR_TELEFONO_EMPRESA']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TELEFONO_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoEmpresa']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['telefonoCelular']!=(isset($this->datosEgresado['EGR_MOVIL'])?$this->datosEgresado['EGR_MOVIL']:'') && is_numeric($this->datos['telefonoCelular'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_MOVIL';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoCelular']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['telefonoCelular']!=(isset($this->datosEgresado['EOT_TEL_CEL'])?$this->datosEgresado['EOT_TEL_CEL']:'') && is_numeric($this->datos['telefonoCelular'])){
                                $this->cambiosTOtros[$indiceOtros]['campo']='EOT_TEL_CEL';
                                $this->cambiosTOtros[$indiceOtros]['valor']="'".$this->datos['telefonoCelular']."'";
                                $indiceOtros++;
                            }

                            if($this->datos['correoElectronico']!=(isset($this->datosEgresado['EGR_EMAIL'])?$this->datosEgresado['EGR_EMAIL']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_EMAIL';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['correoElectronico']."'";
                                $indiceEgresado++;
                            }
                            if($this->datos['correoElectronico']!=(isset($this->datosEgresado['EOT_EMAIL'])?$this->datosEgresado['EOT_EMAIL']:'')){
                                $this->cambiosTOtros[$indiceOtros]['campo']='EOT_EMAIL';
                                $this->cambiosTOtros[$indiceOtros]['valor']="'".$this->datos['correoElectronico']."'";
                                $indiceOtros++;
                            }

                            if($this->datos['empresa']!=(isset($this->datosEgresado['EGR_EMPRESA'])?$this->datosEgresado['EGR_EMPRESA']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['empresa'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            
                            if($this->datos['direccionEmpresa']!=(isset($this->datosEgresado['EGR_DIRECCION_EMPRESA'])?$this->datosEgresado['EGR_DIRECCION_EMPRESA']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECCION_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['direccionEmpresa'],'UTF-8')."'";
                                $indiceEgresado++;
                            }

                            if($this->datos['nombreTrabajoGrado']!=(isset($this->datosEgresado['EGR_TRABAJO_GRADO'])?$this->datosEgresado['EGR_TRABAJO_GRADO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TRABAJO_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['nombreTrabajoGrado'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            
                            if($this->datos['nombreDirector']!=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECTOR_TRABAJO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['nombreDirector'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            
                            if($this->datos['nombreDirector2']!=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO_2'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO_2']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECTOR_TRABAJO_2';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".mb_strtoupper($this->datos['nombreDirector2'],'UTF-8')."'";
                                $indiceEgresado++;
                            }
                            
                            if($this->datos['actaSustentacion']!=(isset($this->datosEgresado['EGR_ACTA_SUSTENTACION'])?$this->datosEgresado['EGR_ACTA_SUSTENTACION']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_ACTA_SUSTENTACION';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['actaSustentacion']."'";
                                $indiceEgresado++;
                            }
                            
                            if(($this->datos['nota']!=(isset($this->datosEgresado['EGR_NOTA'])?$this->datosEgresado['EGR_NOTA']:'')) && is_numeric($this->datos['nota']) && $this->datos['nota']>=0 && $this->datos['nota']<=50){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_NOTA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['nota']."'";
                                $indiceEgresado++;
                            }
                          
                            if(isset($this->datos['actaGrado'])!=(isset($this->datosEgresado['EGR_ACTA_GRADO'])?$this->datosEgresado['EGR_ACTA_GRADO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_ACTA_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['actaGrado']."'";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['mencion'])!=(isset($this->datosEgresado['EGR_CARACTER_NOTA'])?$this->datosEgresado['EGR_CARACTER_NOTA']:'')&& is_numeric($this->datos['mencion'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_CARACTER_NOTA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['mencion']."'";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['fechaGrado'])!=(isset($this->datosEgresado['EGR_FECHA_GRADO'])?$this->datosEgresado['EGR_FECHA_GRADO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_FECHA_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="TO_DATE('".$this->datos['fechaGrado']."','yyyy-mm-dd')";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['libro'])!=(isset($this->datosEgresado['EGR_LIBRO'])?$this->datosEgresado['EGR_LIBRO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_LIBRO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['libro']."'";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['folio'])!=(isset($this->datosEgresado['EGR_FOLIO'])?$this->datosEgresado['EGR_FOLIO']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_FOLIO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['folio']."'";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['registroDiploma'])!=(isset($this->datosEgresado['EGR_REG_DIPLOMA'])?$this->datosEgresado['EGR_REG_DIPLOMA']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_REG_DIPLOMA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['registroDiploma']."'";
                                $indiceEgresado++;
                            }
                            
                         if(!isset($this->datos['tituloObtenido']) || !isset($this->datos['rector']) || !isset($this->datos['secretarioAcademico'])){
                               
                            if($this->datos['tituloObtenido']!=(isset($this->datosEgresado['EGR_TITULO'])?$this->datosEgresado['EGR_TITULO']:'') && is_numeric($this->datos['tituloObtenido'])){
                            	$this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TITULO';
                            	$this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['tituloObtenido']."'";
                            	$indiceEgresado++;
                            }
                            
                            if(isset($this->datos['rector'])!=(isset($this->datosEgresado['EGR_RECTOR'])?$this->datosEgresado['EGR_RECTOR']:'') && is_numeric($this->datos['rector']) ){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_RECTOR';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['rector']."'";
                                $indiceEgresado++;
                            }
                            
                            if(isset($this->datos['secretarioAcademico'])!=(isset($this->datosEgresado['EGR_SECRETARIO'])?$this->datosEgresado['EGR_SECRETARIO']:'') && is_numeric($this->datos['secretarioAcademico'])){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_SECRETARIO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['secretarioAcademico']."'";
                                $indiceEgresado++;
                            }
                           }
        }
                        
    }
    
    
    function verificarActualizacionDatos(){
        $this->datos= $_REQUEST;
        $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
        if($codEstudiante ){
            $this->datosEgresado = $this->consultaDatosEgresado($codEstudiante);
            $this->verificarCambiosDatos();
            $listaCambiosEgresado='';
            $listaCambiosEstudiante='';
            $listaCambiosOtros='';
            if(is_array($this->cambiosTEgresado)){
                foreach ($this->cambiosTEgresado as $cambio) {

                    if(!$listaCambiosEgresado){
                        $listaCambiosEgresado = $cambio['campo']."=".$cambio['valor']."";
                    }else{
                        $listaCambiosEgresado.= ", ".$cambio['campo']."=".$cambio['valor']."";
                    }

                }
            }

            if(is_array($this->cambiosTEstudiante)){
                foreach ($this->cambiosTEstudiante as $cambio2) {

                    if(!$listaCambiosEstudiante){
                        $listaCambiosEstudiante = $cambio2['campo']."=".$cambio2['valor']."";
                    }else{
                        $listaCambiosEstudiante.= ", ".$cambio2['campo']."=".$cambio2['valor']."";
                    }

                }
            }
            
            if(is_array($this->cambiosTOtros)){
                foreach ($this->cambiosTOtros as $cambio3) {

                    if(!$listaCambiosOtros){
                        $listaCambiosOtros = $cambio3['campo']."=".$cambio3['valor']."";
                    }else{
                        $listaCambiosOtros.= ", ".$cambio3['campo']."=".$cambio3['valor']."";
                    }

                }
            }

            if($listaCambiosEgresado){
                $this->registroEgresado=$this->consultarDatosEgresado($codEstudiante,$this->datosEgresado['EST_CRA_COD']);
                if($this->registroEgresado>=1)
                {
                    $actualizadoEgresado = $this->actualizarRegistroEgresado($listaCambiosEgresado);

                    $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                    'evento'=>75,
                                                                                    'descripcion'=>'Actualización datos egresado: ',
                                                                                    'registro'=>$listaCambiosEgresado,
                                                                                    'afectado'=>$codEstudiante);
                    $this->procedimientos->registrarEvento($variablesRegistro);
                }else
                    {
                            $validacionDatos = $this->validarDatosRegistro();
                            if($validacionDatos=='ok'){
                                        $actualizadoEgresado = $this->registrarDatosEgresado();

                                        $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                                        'evento'=>76,
                                                                                                        'descripcion'=>'Registro datos egresado: ',
                                                                                                        'registro'=>$listaCambiosEgresado,
                                                                                                        'afectado'=>$codEstudiante);
                                        $this->procedimientos->registrarEvento($variablesRegistro);
                            }else{
                                        echo "<script>alert('".$validacionDatos."')</script>";
                                        $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                                        'evento'=>76,
                                                                                                        'descripcion'=>'Error Registro datos egresado ',
                                                                                                        'registro'=>$listaCambiosEgresado,
                                                                                                        'afectado'=>$codEstudiante);
                                        $this->procedimientos->registrarEvento($variablesRegistro);
                                        $pagina=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                        $variable='pagina=admin_inscripcionGraduando';
                                        $variable.='&opcion=verEstudiante';
                                        $variable.='&datoBusqueda='.$this->datos['codEstudiante'];
                                        $variable.='&tipoBusqueda=codigo';
                                        include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                            }
                    }
                                                    
            }
            if($listaCambiosEstudiante){
                $actualizadoEstudiante = $this->actualizarRegistroEstudiante($listaCambiosEstudiante);
                $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                'evento'=>75,
                                                                                'descripcion'=>'Actualización datos básicos de egresado: ',
                                                                                'registro'=>$listaCambiosEstudiante,
                                                                                'afectado'=>$codEstudiante);
                $this->procedimientos->registrarEvento($variablesRegistro);
                                                    
            }
            if($listaCambiosOtros){
                $actualizadoEstudiante = $this->actualizarRegistroEstudianteOtrosDatos($listaCambiosOtros);
                $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                'evento'=>75,
                                                                                'descripcion'=>'Actualización otros datos de egresado: ',
                                                                                'registro'=>$listaCambiosOtros,
                                                                                'afectado'=>$codEstudiante);
                $this->procedimientos->registrarEvento($variablesRegistro);
            }
            $pagina=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable='pagina=admin_inscripcionGraduando';
            $variable.='&opcion=verEstudiante';
            $variable.='&datoBusqueda='.$this->datos['codEstudiante'];
            $variable.='&tipoBusqueda=codigo';
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        
        
    }
    
    function registrarDatosEgresado(){
    	    	
    	foreach ($this->datos as $key => $value) {
    		if($value=='')
    		{
    			$this->datos[$key]=null;	    				    	
    		} 		
    	} 	
    	
    	if(!isset($this->datos['mencion'])){
    		$this->datos['mencion'] = 'null';    		    		
    	}
    	if(!isset($this->datos['nota'])){
    		$this->datos['nota'] = 'null';
    	}
    	if(!isset($this->datos['tituloObtenido'])){
    		$this->datos['tituloObtenido'] = 'null';    		    		
    	}
    	if(!isset($this->datos['rector'])){
    		$this->datos['rector'] = 'null';    		    		
    	}
    	if(!isset($this->datos['secretarioAcademico'])){
    		$this->datos['secretarioAcademico'] = 'null';    		    		
    	}        	
    	if(!isset($this->datos['fechaGrado'])){
    		$this->datos['fechaGrado'] = '';
    	}
    	if(!isset($this->datos['actaGrado'])){
    		$this->datos['actaGrado'] = '';
    	}
    	if(!isset($this->datos['libro'])){
    		$this->datos['libro'] = '';
    	}
    	if(!isset($this->datos['folio'])){
    		$this->datos['folio'] = '';
    	}	    
    	if(!isset($this->datos['registroDiploma'])){
    		$this->datos['registroDiploma'] = '';
    	}	    	
    	    	
        $cadena_sql = $this->sql->cadena_sql("registrarDatosEgresado", $this->datos);        
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    function actualizarRegistroEgresado($listaCambios){
        $datos=array('listaCambios'=>$listaCambios,
                    'codEstudiante'=>  $this->datos['codEstudiante']
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_egresado", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    
    function actualizarRegistroEstudiante($listaCambios){
        $datos=array('listaCambios'=>$listaCambios,
                    'codEstudiante'=>  $this->datos['codEstudiante']
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_estudiante", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    
    function actualizarRegistroEstudianteOtrosDatos($listaCambios){
        $datos=array('listaCambios'=>$listaCambios,
                    'codEstudiante'=>  $this->datos['codEstudiante']
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_estudianteOtros", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
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
        return $resultado[0][0];
        
    }
    
    
    function validarDatosRegistro(){
            $mensaje='';
            $band=0;
            if($this->datos['proyectoCurricular'] && $this->datos['nombreEstudiante'] && $this->datos['apellidoEstudiante']  && $this->datos['identificacion'] && $this->datos['genero'] && $this->datos['codEstudiante'] && $this->datos['tipoIdentificacion']){
                    if(is_numeric($this->datos['proyectoCurricular']) && is_numeric($this->datos['identificacion']) && is_numeric($this->datos['codEstudiante']) ){
                        $this->datos['nombreEstudiante']=trim($this->datos['nombreEstudiante']);
                        $this->datos['apellidoEstudiante']=trim($this->datos['apellidoEstudiante']);
                        if($this->datos['lugarExpedicion'] && !is_numeric($this->datos['lugarExpedicion']) ){
                            $band=1;
                            $mensaje="El valor del lugar de expedición no es válido. Seleccione uno de la lista";
                        }
                        
                        if(empty($this->datos['nombreEstudiante'])||$this->datos['nombreEstudiante']==''){
                            $band=1;
                            $mensaje="En los datos básicos debe haber al menos un Nombre";
                        }

                        if(empty($this->datos['apellidoEstudiante'])||$this->datos['apellidoEstudiante']==''){
                            $band=1;
                            $mensaje="En los datos básicos debe haber al menos un Apellido";
                        }
                        
                        if(isset($this->datos['nota']) && $this->datos['nota']!='' && ($this->datos['nota']<0 || $this->datos['nota']>50 || !is_numeric($this->datos['nota']))){
                            $band=1;
                            $mensaje="El valor de la nota no es válido. Debe ser un número entre cero y cincuenta";
                        }
                        
                        if($this->datos['tipoIdentificacion'] && is_numeric($this->datos['tipoIdentificacion']) ){
                            $band=1;
                            $mensaje="El valor del tipo de identificación no es válido. Seleccione uno de la lista";
                        }
                        
                        if (!isset($this->datos['tituloObtenido']) || !isset($this->datos['rector']) || !isset($this->datos['secretarioAcademico'])){

                        	if(isset($this->datos['tituloObtenido']) && !is_numeric($this->datos['tituloObtenido']) ){
                            $band=1;
                            $mensaje="El valor del título no es válido. Seleccione uno de la lista";
                       		} 
                        
                       		if(isset($this->datos['rector']) && !is_numeric($this->datos['rector']) ){
                            $band=1;
                            $mensaje="El valor del Rector no es válido. Seleccione uno de la lista";
                        	}
                        
                       		if(isset($this->datos['secretarioAcademico']) && !is_numeric($this->datos['secretarioAcademico']) ){
                            $band=1;
                            $mensaje="El valor del Secretario académico no es válido. Seleccione uno de la lista";
                        	}
                        }
                        if($band==0){
                            $mensaje='ok';
                        }
                    }else{
                        $mensaje= "Error al registrar el egresado, el valor del proyecto, identificación y/o código de estudiante no son válidos.";
                    }
            }else{
                $mensaje= "Error al registrar el egresado, faltan datos requeridos.";
            }
            
            return $mensaje;
    }
   
}
    ?>