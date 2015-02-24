<?php


/**
 * Funcion registro_cargarDatosEgresado
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package 
 * @subpackage registro_cargarDatosEgresado
 * @author Maritza Callejas
 * @version 0.0.0.2
 * Fecha: 03/12/2014
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
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");

/**
 * Clase funcion_registroCargarDatosEgresado
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package registro_cargarDatosEgresado
 * @subpackage Admin
 */
class funcion_registroCargarDatosEgresado extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;
  private $datosEgresado;
  private $datosEstudiante;
  private $cambiosTEgresado;
  private $datos;
  private $datosCargados;
  
  /**
     * Método constructor que crea el objeto sql de la clase funcion_registroCargarDatosEgresado
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

        $this->formulario = "registro_cargarDatosEgresado";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "registro_cargarDatosEgresado";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_registroCargarDatosEgresado($configuracion);
       
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
        }elseif($this->nivel==116){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }elseif($this->nivel==80){
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
        }
        $this->procedimientos=new procedimientos();
        $this->validacion=new validarInscripcion();
        
    }

   /**
    * Función para cargar archivo excel
    */
    function cargarArchivo(){

            $maxSize=10*1024*1024;//tama�p en mb
            $upload_dir = $this->configuracion['raiz_documento']."/documentos/archivosCargaEgresados/";
            if (isset($_FILES["archivo"])&&$_FILES["archivo"]['size']<=$maxSize
                    &&($_FILES["archivo"]['type']=="application/vnd.ms-excel"||$_FILES["archivo"]['type']=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) {
                    if ($_FILES["archivo"]["error"] > 0) {
                            echo "Error: " . $_FILES["file"]["error"] . "<br>";
                    } else {
                            $id= "_".uniqid();
                            if(move_uploaded_file($_FILES["archivo"]["tmp_name"], $upload_dir . $_FILES["archivo"]["name"].$id)){
                                    $ruta = $upload_dir ;
                                    $archivo =  $_FILES["archivo"]["name"].$id;
                                    //$this->iniciarEncabezados();
                                    $this->procesarExcel($ruta,$archivo);
                                    $this->cargarDatos();
                            }

                    }
            }else {echo "Archivo Invalido";}
                
   }
   
   
   /**
    * Función para procesar el archivo excel y posteriormente cargar los datos
    * @param type $ruta
    * @param type $archivo
    */
   function procesarExcel($ruta,$archivo){
       /** Include PHPExcel */
	require_once $this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/PHPExcel.php";
	/** Include PHPExcel_IOFactory */
	require_once $this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/PHPExcel/IOFactory.php";
	
	
	if (!file_exists($ruta . $archivo)) {
		exit("Plantilla no valida." . EOL);
	}
	
	$objPHPExcelReader = PHPExcel_IOFactory::load($ruta . $archivo);
	
        $lastRow = $objPHPExcelReader->getActiveSheet()->getHighestRow();
        if($lastRow>2){
                //leemos datos de egresados de la plantilla de excel y lo pasamos a un array
                for ($i=3;$i<=$lastRow;$i++){
                    $codEstudiante = $objPHPExcelReader->getActiveSheet()->getCell("A".$i)->getValue();
                    $documento = $objPHPExcelReader->getActiveSheet()->getCell("B".$i)->getValue();

                    if ($codEstudiante && $documento) {
                        $this->datosCargados[$i]['codEstudiante'] = $objPHPExcelReader->getActiveSheet()->getCell("A".$i)->getValue();
                        $this->datosCargados[$i]['documento'] = $objPHPExcelReader->getActiveSheet()->getCell("B".$i)->getValue();
                        $this->datosCargados[$i]['nombreTrabajoGrado'] = $objPHPExcelReader->getActiveSheet()->getCell("C".$i)->getValue();
                        $this->datosCargados[$i]['nombreDirector'] = $objPHPExcelReader->getActiveSheet()->getCell("D".$i)->getValue();
                        $this->datosCargados[$i]['actaSustentacion'] = $objPHPExcelReader->getActiveSheet()->getCell("E".$i)->getValue();
                        $this->datosCargados[$i]['dia'] = $objPHPExcelReader->getActiveSheet()->getCell("F".$i)->getValue();
                        $this->datosCargados[$i]['mes'] = $objPHPExcelReader->getActiveSheet()->getCell("G".$i)->getValue();
                        $this->datosCargados[$i]['anio'] = $objPHPExcelReader->getActiveSheet()->getCell("H".$i)->getValue();
                        $this->datosCargados[$i]['actaGrado'] = $objPHPExcelReader->getActiveSheet()->getCell("I".$i)->getValue();
                        $this->datosCargados[$i]['nota'] = $objPHPExcelReader->getActiveSheet()->getCell("J".$i)->getValue();
                        $this->datosCargados[$i]['libro'] = $objPHPExcelReader->getActiveSheet()->getCell("K".$i)->getValue();
                        $this->datosCargados[$i]['folio'] = $objPHPExcelReader->getActiveSheet()->getCell("L".$i)->getValue();
                        $this->datosCargados[$i]['registroDiploma'] = $objPHPExcelReader->getActiveSheet()->getCell("M".$i)->getValue();
                        $this->datosCargados[$i]['direccion'] = $objPHPExcelReader->getActiveSheet()->getCell("N".$i)->getValue();
                        $this->datosCargados[$i]['telefonoFijo'] = $objPHPExcelReader->getActiveSheet()->getCell("O".$i)->getValue();
                        $this->datosCargados[$i]['correoElectronico'] = $objPHPExcelReader->getActiveSheet()->getCell("P".$i)->getValue();
                        $this->datosCargados[$i]['empresa'] = $objPHPExcelReader->getActiveSheet()->getCell("Q".$i)->getValue();
                        $this->datosCargados[$i]['direccionEmpresa'] = $objPHPExcelReader->getActiveSheet()->getCell("R".$i)->getValue();
                        $this->datosCargados[$i]['telefonoEmpresa'] = $objPHPExcelReader->getActiveSheet()->getCell("S".$i)->getValue();
                        $this->datosCargados[$i]['telefonoCelular'] = $objPHPExcelReader->getActiveSheet()->getCell("T".$i)->getValue();
                        $this->datosCargados[$i]['mencion'] = $objPHPExcelReader->getActiveSheet()->getCell("U".$i)->getValue();
                        $this->datosCargados[$i]['nombreDirector2'] = $objPHPExcelReader->getActiveSheet()->getCell("V".$i)->getValue();
                        $this->datosCargados[$i]['estado']='E';
                    }
                    
                }
        }
        
   }
   
   
   /**
    * Función que procesa los datos tomados del archivo
    */
   function cargarDatos(){
       ?>
            <head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogress(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = 'Procesando datos: '+vItem+' de '+vTotal+' registros.  '+vValor ;
                 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                </script>
                <style type="text/css">
                /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
                  .ProgressBar     { width: 70%; border: 1px solid black; background: #eef; height: 1.25em; display: block; margin-left: auto;margin-right: auto }
                  .ProgressBarText { position: absolute; font-size: 1em; width: 35em; text-align: center; font-weight: normal; }
                  .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
                </style>
            </head>
            <body>
                <table style="width: 100%; text-align: left;" border="0" cellpadding="6" cellspacing="0">
                            <tr class="bloquecentralcuerpo">
                                    <td colspan="2" rowspan="1">
                                            <span class="encabezado_normal">Carga Datos de egresados</span>
                                            <hr class="hr_division">
                                    </td>		
                            </tr>	
                </table>
            <!-- Ahora creo la barra de progreso con etiquetas DIV -->
             <div class="ProgressBar">
                  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% </div>
                  <div id="getProgressBarFill"></div>
                </div>
            </body>
    <?
        $numRegistros=count($this->datosCargados);
        $a=1;
        foreach ($this->datosCargados as $key => $registro) {
            $verificacion='';
            $mensaje='';
            if($key>2){
                echo "<br>No. ".$a." - Código: ".$registro['codEstudiante']." - Documento: ".$registro['documento'];
                if($registro['codEstudiante'] && $registro['documento']){
                    if($this->nivel==83){
                           $verificacion=$this->validacion->validarFacultadSecAcademico($registro['codEstudiante'],$this->usuario); 
                    }

                    if($verificacion=='ok' || $this->nivel== 80)
                        {
                            //$this->verificarActualizacionDatos($registro,'basicos');
                            $this->datosEgresado='';
                            $this->datosEstudiante='';
                            $this->datosEstudiante = $this->consultarEstudiante($registro['codEstudiante'],$registro['documento']);
                            if(is_array($this->datosEstudiante)){
                                $this->datosEgresado = $this->consultaDatosEgresado($registro['codEstudiante'],$this->datosEstudiante['COD_PROYECTO'],$registro['documento']);
                                $datosRegistro=  $this->procesarRegistro($registro);
                                if(is_array($this->datosEgresado)){
                                        $estadoActualizado = $this->procesarEstado($datosRegistro);
                                        if($this->datosEstudiante['ESTADO']==='E' || $estadoActualizado>0 ){
                                                //actualizar datos grado Egresado
                                                $mensaje = $this->verificarActualizacionDatos($datosRegistro);
                                        }elseif($this->datosEstudiante['ESTADO']!='E' && $this->nivel!=83){
                                                    $mensaje="No puede cargar los datos, el estudiante no se encuentra en estado Egresado.";
                                                }
                                }else{
                                
                                    if($datosRegistro['fechaGrado'] && $datosRegistro['actaGrado'] && $datosRegistro['folio'] && $datosRegistro['codEstudiante'] && $datosRegistro['codProyecto']&& $datosRegistro['identificacion']){
                                                $estadoActualizado = $this->procesarEstado($datosRegistro);
                                                if($this->datosEstudiante['ESTADO']==='E' || $estadoActualizado>0 ){
                                                    $titulo = $this->consultarTitulosGrado($this->datosEstudiante['COD_PROYECTO'], $this->datosEstudiante['SEXO']);
                                                    if(is_array($titulo)){
                                                        $datosRegistro['tituloObtenido']=$titulo[0][0];
                                                    }
                                                    //registrarEgresado
                                                    $datosRegistro['rector'] =  $this->obtenerRector($datosRegistro['fechaGrado']);
                                                    if($this->datosEstudiante['COD_FACULTAD']){
                                                        $datosRegistro['secretarioAcademico'] =  $this->obtenerSecretarioAcademico($datosRegistro['fechaGrado'],$this->datosEstudiante['COD_FACULTAD']);
                                                    }
                                                    $registrado =$this->registrarDatosEgresado($datosRegistro);
                                                    if($registrado){
                                                        $mensaje = " - Registro de datos de grado existoso";
                                                        $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                    'evento'=>75,
                                                                                    'descripcion'=>'Registrar datos egresado - Archivo excel',
                                                                                    'registro'=>'Proyecto: '.$datosRegistro['codProyecto'].', Nombre: '.$datosRegistro['nombreEstudiante'].', Fecha grado: '.(isset($datosRegistro['fechaGrado'])?$datosRegistro['fechaGrado']:''),
                                                                                    'afectado'=>$datosRegistro['codEstudiante']);
                                                        $this->procedimientos->registrarEvento($variablesRegistro);
                                                    }else{
                                                        $mensaje = " - Error al registrar";
                                                    }
                                                }elseif($this->datosEstudiante['ESTADO']!='E' && $this->nivel!=83){
                                                    $mensaje="No puede cargar los datos, el estudiante no se encuentra en estado Egresado.";
                                                }
                                        }else{
                                            if(!(isset($datosRegistro['identificacion'])?$datosRegistro['identificacion']:'')){ $mensaje= " Dato documento no valido.";}
                                            if(!(isset($datosRegistro['codEstudiante'])?$datosRegistro['codEstudiante']:'')){ $mensaje= " Dato codEstudiante no valido.";}
                                            if(!(isset($datosRegistro['codProyecto'])?$datosRegistro['codProyecto']:'')){ $mensaje= " Dato codProyecto no valido.";}
                                            if(!(isset($datosRegistro['fechaGrado'])?$datosRegistro['fechaGrado']:'')){ $mensaje= " Dato fecha de grado (día, mes y año) no valido.";}
                                            if(!(isset($datosRegistro['actaGrado'])?$datosRegistro['actaGrado']:'')){ $mensaje= " Dato acta no valido.";}
                                            if(!(isset($datosRegistro['folio'])?$datosRegistro['folio']:'')){ $mensaje= " Dato folio no valido.";}
                                            
                                        }
                                    
                                }
                                
                            }
                            
                            echo $mensaje;

                        }else{
                            echo " - ".$verificacion;
                        }
                    $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
                    echo "<script>callprogress(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                    flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                    ob_flush();
                    $a++;
                }else{
                    echo " - El código y documento del estudiante son requeridos.";
                }
        }
       }
   }
   
   /**
     * Función para consultar los datos de un egresado
     * @param type $codEstudiante,$codProyecto,$documento
     * @return type 
     */
    function consultaDatosEgresado($codEstudiante,$codProyecto,$documento) {
          $datos = array('codEstudiante'=>$codEstudiante,
                        'codProyecto'=>$codProyecto,
                        'documento'=>$documento);
          
          $cadena_sql = $this->sql->cadena_sql("datos_egresado", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado[0];
    }
    
    /**
     * Función para verificar datos cargados
     * @param type $registro
     * @return type
     */
    function procesarRegistro($registro){
        $nombre=$this->datosEstudiante['NOMBRE']." ".$this->datosEstudiante['APELLIDO'];
        $tipoIdentificacion=$this->datosEstudiante['TIPO_IDENTIFICACION'];
        $lugarExpedicion=0;
        $genero=  $this->datosEstudiante['SEXO'];
        
        $registro['nombreTrabajoGrado']=mb_strtoupper($registro['nombreTrabajoGrado'],'UTF-8');
        $nombreTrabajoGrado=(isset($registro['nombreTrabajoGrado'])?$registro['nombreTrabajoGrado']:'');
        $registro['nombreDirector']=mb_strtoupper($registro['nombreDirector'],'UTF-8');
        $nombreDirector = (isset($registro['nombreDirector'])?$registro['nombreDirector']:'');
        $registro['nombreDirector2']=mb_strtoupper($registro['nombreDirector2'],'UTF-8');
        $nombreDirector2= (isset($registro['nombreDirector2'])?$registro['nombreDirector2']:'');
        $actaSustentacion= (isset($registro['actaSustentacion'])?$registro['actaSustentacion']:'');
        $dia=(isset($registro['dia'])?$registro['dia']:'');
        $mes=(isset($registro['mes'])?$registro['mes']:'');
        if(!is_numeric($mes) && $mes){
            $mes=$this->numeroMes($registro['mes']);
            if(!is_numeric($mes) && $mes){
                echo " Dato mes de grado no valido.";
            }
        }
        $anio=(isset($registro['anio'])?$registro['anio']:'');
        if($anio<1948 || $anio>  date('Y')){$anio='';}
        if($dia && $mes && $anio){
            $fechaGrado =$anio."/".  str_pad($mes, 2,0,STR_PAD_LEFT)."/".str_pad($dia, 2,0,STR_PAD_LEFT);
        }else{
            $fechaGrado='';
        }
        
        $actaGrado= (isset($registro['actaGrado'])?$registro['actaGrado']:'');
        if(is_numeric($registro['nota']) && $registro['nota']>0 && $registro['nota']<=50){
            $registro['nota'] = str_replace('.', '', $registro['nota']);
            if($registro['nota']<10){
                $registro['nota']=$registro['nota']*10;
            }
            $nota=$registro['nota'];
        }else{
            $nota='';
        }
        $libro=(isset($registro['libro'])?$registro['libro']:'');
        $folio=(isset($registro['folio'])?$registro['folio']:'');
        $registroDiploma=(isset($registro['registroDiploma'])?$registro['registroDiploma']:'');
        $tituloObtenido='';
        $rector =  '';
        $secretarioAcademico='';
        if(!$registro['direccion']){
            if((isset($this->datosEstudiante['DIRECCION'])?$this->datosEstudiante['DIRECCION']:'')){
                $direccion=$this->datosEstudiante['DIRECCION'];
            }else{
                $direccion='';
            }
        }else{
            $direccion=mb_strtoupper($registro['direccion'],'UTF-8');
        }
        if(!(isset($registro['telefono'])?$registro['telefono']:'')){
            if((isset($this->datosEstudiante['TELEFONO'])?$this->datosEstudiante['TELEFONO']:'')){
                $telefono=$this->datosEstudiante['TELEFONO'];
            }else{
                $telefono='';
            }
        }else{
            $telefono=$registro['telefono'];
        }
        if(!(isset($registro['correo'])?$registro['correo']:'')){
            if($this->datosEstudiante['CORREO']){
                $correo=$this->datosEstudiante['CORREO'];
            }else{
                $correo='';
            }
        }else{
            $correo=$registro['correo'];
        }
        
        $registro['empresa']=mb_strtoupper($registro['empresa'],'UTF-8');
        $empresa=(isset($registro['empresa'])?$registro['empresa']:'');
        $registro['direccionEmpresa']=mb_strtoupper($registro['direccionEmpresa'],'UTF-8');
        $direccionEmpresa=(isset($registro['direccionEmpresa'])?$registro['direccionEmpresa']:'');
        $telefonoEmpresa=(isset($registro['telefonoEmpresa'])?$registro['telefonoEmpresa']:'');
        $celular=(isset($registro['telefonoCelular'])?$registro['telefonoCelular']:'');
        if((isset($registro['mencion'])?$registro['mencion']:'')){
                    switch (mb_strtoupper($registro['mencion'],'UTF-8')){
                        case "APROBADO":
                            $mencion = 1;
                            break;
                        case "MERITORIO":
                            $mencion = 2;
                            break;
                        case "LAUREADO":
                            $mencion = 3;
                            break;
                        default :
                            $mencion = '';
                            break;
                    }
        }else{
            $mencion='';
        }
        
        $datosRegistro=array('codProyecto'=> $this->datosEstudiante['COD_PROYECTO'],
                        'nombreEstudiante'=> $nombre,
                        'identificacion'=> $this->datosEstudiante['IDENTIFICACION'],
                        'tipoIdentificacion'=> $tipoIdentificacion,
                        'lugarExpedicion'=> $lugarExpedicion,
                        'genero'=> $genero,
                        'nombreTrabajoGrado'=> $nombreTrabajoGrado,
                        'nombreDirector'=> $nombreDirector,
                        'nombreDirector2'=> $nombreDirector2,
                        'actaSustentacion'=> $actaSustentacion,
                        'fechaGrado'=> $fechaGrado,
                        'actaGrado'=> $actaGrado,
                        'nota'=> $nota,
                        'libro'=> $libro,
                        'folio'=> $folio,
                        'registroDiploma'=> $registroDiploma,
                        'tituloObtenido'=>$tituloObtenido,
                        'rector'=> $rector,
                        'secretarioAcademico'=> $secretarioAcademico,
                        'codEstudiante'=>  $this->datosEstudiante['CODIGO'],
                        'direccion'=> $direccion,
                        'telefonoFijo'=> $telefono,
                        'correoElectronico'=> $correo,
                        'empresa'=> $empresa,
                        'direccionEmpresa'=> $direccionEmpresa,
                        'telefonoEmpresa'=> $telefonoEmpresa,
                        'telefonoCelular'=> $celular,
                        'mencion'=> $mencion            
            );
            return $datosRegistro;
    }
    
    
       
    /**
     * Función que verifica que campos cambiaron
     * @param type $tipo
     * @param type $registro
     */
    function verificarCambiosDatos( $registro){
            $this->datos=$registro;
            $indiceEgresado=0;
            $fechaGrado='';
                            if((isset($this->datos['direccion'])?$this->datos['direccion']:'')!=(isset($this->datosEgresado['EGR_DIRECCION_CASA'])?$this->datosEgresado['EGR_DIRECCION_CASA']:'') && (isset($this->datos['direccion'])?$this->datos['direccion']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECCION_CASA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['direccion']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_DIRECCION_CASA'])?$this->datosEgresado['EGR_DIRECCION_CASA']:'');
                                $indiceEgresado++;
                            }

                            if((isset($this->datos['telefonoFijo'])?$this->datos['telefonoFijo']:'')!=(isset($this->datosEgresado['EGR_TELEFONO_CASA'])?$this->datosEgresado['EGR_TELEFONO_CASA']:'') && is_numeric((isset($this->datos['telefonoFijo'])?$this->datos['telefonoFijo']:'')) && (isset($this->datos['telefonoFijo'])?$this->datos['telefonoFijo']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TELEFONO_CASA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoFijo']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_TELEFONO_CASA'])?$this->datosEgresado['EGR_TELEFONO_CASA']:'');
                                $indiceEgresado++;
                            }
                            if((isset($this->datos['telefonoEmpresa'])?$this->datos['telefonoEmpresa']:'')!=(isset($this->datosEgresado['EGR_TELEFONO_EMPRESA'])?$this->datosEgresado['EGR_TELEFONO_EMPRESA']:'') && (isset($this->datos['telefonoEmpresa'])?$this->datos['telefonoEmpresa']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TELEFONO_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoEmpresa']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_TELEFONO_EMPRESA'])?$this->datosEgresado['EGR_TELEFONO_EMPRESA']:'');
                                $indiceEgresado++;
                            }
                            if((isset($this->datos['telefonoCelular'])?$this->datos['telefonoCelular']:'')!=(isset($this->datosEgresado['EGR_MOVIL'])?$this->datosEgresado['EGR_MOVIL']:'') && is_numeric((isset($this->datos['telefonoCelular'])?$this->datos['telefonoCelular']:'')) && (isset($this->datos['telefonoCelular'])?$this->datos['telefonoCelular']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_MOVIL';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['telefonoCelular']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_MOVIL'])?$this->datosEgresado['EGR_MOVIL']:'');
                                $indiceEgresado++;
                            }

                            if((isset($this->datos['correoElectronico'])?$this->datos['correoElectronico']:'')!=(isset($this->datosEgresado['EGR_EMAIL'])?$this->datosEgresado['EGR_EMAIL']:'') && (isset($this->datos['correoElectronico'])?$this->datos['correoElectronico']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_EMAIL';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['correoElectronico']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_EMAIL'])?$this->datosEgresado['EGR_EMAIL']:'');
                                $indiceEgresado++;
                            }

                            if((isset($this->datos['empresa'])?$this->datos['empresa']:'')!=(isset($this->datosEgresado['EGR_EMPRESA'])?$this->datosEgresado['EGR_EMPRESA']:'') && (isset($this->datos['empresa'])?$this->datos['empresa']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['empresa']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_EMPRESA'])?$this->datosEgresado['EGR_EMPRESA']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['direccionEmpresa'])?$this->datos['direccionEmpresa']:'')!=(isset($this->datosEgresado['EGR_DIRECCION_EMPRESA'])?$this->datosEgresado['EGR_DIRECCION_EMPRESA']:'') && (isset($this->datos['direccionEmpresa'])?$this->datos['direccionEmpresa']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECCION_EMPRESA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['direccionEmpresa']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_DIRECCION_EMPRESA'])?$this->datosEgresado['EGR_DIRECCION_EMPRESA']:'');
                                $indiceEgresado++;
                            }

                            if((isset($this->datos['nombreTrabajoGrado'])?$this->datos['nombreTrabajoGrado']:'')!=(isset($this->datosEgresado['EGR_TRABAJO_GRADO'])?$this->datosEgresado['EGR_TRABAJO_GRADO']:'') && (isset($this->datos['nombreTrabajoGrado'])?$this->datos['nombreTrabajoGrado']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_TRABAJO_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['nombreTrabajoGrado']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_TRABAJO_GRADO'])?$this->datosEgresado['EGR_TRABAJO_GRADO']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['nombreDirector'])?$this->datos['nombreDirector']:'')!=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO']:'') && (isset($this->datos['nombreDirector'])?$this->datos['nombreDirector']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECTOR_TRABAJO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['nombreDirector']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['nombreDirector2'])?$this->datos['nombreDirector2']:'')!=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO_2'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO_2']:'') && (isset($this->datos['nombreDirector2'])?$this->datos['nombreDirector2']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_DIRECTOR_TRABAJO_2';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['nombreDirector2']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_DIRECTOR_TRABAJO_2'])?$this->datosEgresado['EGR_DIRECTOR_TRABAJO_2']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['actaSustentacion'])?$this->datos['actaSustentacion']:'')!=(isset($this->datosEgresado['EGR_ACTA_SUSTENTACION'])?$this->datosEgresado['EGR_ACTA_SUSTENTACION']:'') && (isset($this->datos['actaSustentacion'])?$this->datos['actaSustentacion']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_ACTA_SUSTENTACION';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['actaSustentacion']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_ACTA_SUSTENTACION'])?$this->datosEgresado['EGR_ACTA_SUSTENTACION']:'');
                                $indiceEgresado++;
                            }
                            
                            if(((isset($this->datos['nota'])?$this->datos['nota']:'')!=(isset($this->datosEgresado['EGR_NOTA'])?$this->datosEgresado['EGR_NOTA']:'')) && is_numeric((isset($this->datos['nota'])?$this->datos['nota']:'')) && (isset($this->datos['nota'])?$this->datos['nota']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_NOTA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['nota']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_NOTA'])?$this->datosEgresado['EGR_NOTA']:'');
                                $indiceEgresado++;
                            }
                          
                            if((isset($this->datos['actaGrado'])?$this->datos['actaGrado']:'')!=(isset($this->datosEgresado['EGR_ACTA_GRADO'])?$this->datosEgresado['EGR_ACTA_GRADO']:'') && (isset($this->datos['actaGrado'])?$this->datos['actaGrado']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_ACTA_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['actaGrado']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_ACTA_GRADO'])?$this->datosEgresado['EGR_ACTA_GRADO']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['mencion'])?$this->datos['mencion']:'')!=(isset($this->datosEgresado['EGR_CARACTER_NOTA'])?$this->datosEgresado['EGR_CARACTER_NOTA']:'')&& is_numeric((isset($this->datos['mencion'])?$this->datos['mencion']:'')) && (isset($this->datos['mencion'])?$this->datos['mencion']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_CARACTER_NOTA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['mencion']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_CARACTER_NOTA'])?$this->datosEgresado['EGR_CARACTER_NOTA']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['fechaGrado'])?$this->datos['fechaGrado']:'')!=(isset($this->datosEgresado['EGR_FECHA_GRADO'])?$this->datosEgresado['EGR_FECHA_GRADO']:'') && (isset($this->datos['fechaGrado'])?$this->datos['fechaGrado']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_FECHA_GRADO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="TO_DATE('".$this->datos['fechaGrado']."','yyyy-mm-dd')";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_FECHA_GRADO'])?$this->datosEgresado['EGR_FECHA_GRADO']:'');
                                $indiceEgresado++;
                                $fechaGrado=$this->datos['fechaGrado'];
                            }
                            
                            if(!(isset($this->datosEgresado['EGR_RECTOR'])?$this->datosEgresado['EGR_RECTOR']:'') && ((isset($this->datos['fechaGrado'])?$this->datos['fechaGrado']:'') || (isset($this->datosEgresado['EGR_FECHA_GRADO'])?$this->datosEgresado['EGR_FECHA_GRADO']:''))){
                                if(!(isset($fechaGrado)?$fechaGrado:'')){
                                    $fechaGrado=$this->datosEgresado['EGR_FECHA_GRADO'];
                                }
                                $fecha = str_replace('/', '', $fechaGrado);
                                $fecha = str_replace('-', '', $fechaGrado);
                                
                                $rector =  $this->obtenerRector($fecha);
                                if($rector>0){
                                    $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_RECTOR';
                                    $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$rector."'";
                                    $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_RECTOR'])?$this->datosEgresado['EGR_RECTOR']:'');
                                    $indiceEgresado++;
                                }
                                
                            }                           
    
                            
                            if(!(isset($this->datosEgresado['EGR_SECRETARIO'])?$this->datosEgresado['EGR_SECRETARIO']:'') && ((isset($this->datos['fechaGrado'])?$this->datos['fechaGrado']:'') || (isset($this->datosEgresado['EGR_FECHA_GRADO'])?$this->datosEgresado['EGR_FECHA_GRADO']:''))){
                                if(!$fechaGrado){
                                    $fechaGrado=$this->datosEgresado['EGR_FECHA_GRADO'];
                                }
                                $fecha = str_replace('/', '', $fechaGrado);
                                $fecha = str_replace('-', '', $fechaGrado);
                              
                                if($this->datosEstudiante['COD_FACULTAD']){
                                    
                                    $secretario =  $this->obtenerSecretarioAcademico($fecha,$this->datosEstudiante['COD_FACULTAD']);
                                    if($secretario>0){
                                        $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_SECRETARIO';
                                        $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$secretario."'";
                                        $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_SECRETARIO'])?$this->datosEgresado['EGR_SECRETARIO']:'');
                                        $indiceEgresado++;
                                    }
                                }
                            }                           
    
                            
                            if((isset($this->datos['libro'])?$this->datos['libro']:'')!=(isset($this->datosEgresado['EGR_LIBRO'])?$this->datosEgresado['EGR_LIBRO']:'') && (isset($this->datos['libro'])?$this->datos['libro']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_LIBRO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['libro']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_LIBRO'])?$this->datosEgresado['EGR_LIBRO']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['folio'])?$this->datos['folio']:'')!=(isset($this->datosEgresado['EGR_FOLIO'])?$this->datosEgresado['EGR_FOLIO']:'') && (isset($this->datos['folio'])?$this->datos['folio']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_FOLIO';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['folio']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_FOLIO'])?$this->datosEgresado['EGR_FOLIO']:'');
                                $indiceEgresado++;
                            }
                            
                            if((isset($this->datos['registroDiploma'])?$this->datos['registroDiploma']:'')!=(isset($this->datosEgresado['EGR_REG_DIPLOMA'])?$this->datosEgresado['EGR_REG_DIPLOMA']:'') && (isset($this->datos['registroDiploma'])?$this->datos['registroDiploma']:'')){
                                $this->cambiosTEgresado[$indiceEgresado]['campo']='EGR_REG_DIPLOMA';
                                $this->cambiosTEgresado[$indiceEgresado]['valor']="'".$this->datos['registroDiploma']."'";
                                $this->cambiosTEgresado[$indiceEgresado]['anterior']=(isset($this->datosEgresado['EGR_REG_DIPLOMA'])?$this->datosEgresado['EGR_REG_DIPLOMA']:'');
                                $indiceEgresado++;
                            }
                            
                            
    }                            
    
    /**
     * Función que verifica si ya existe registro de egresado para actualizarlo, sino para registrarlo
     * @param type $registro
     * @param type $tipo
     */
    function verificarActualizacionDatos($registro){
            $mensaje=''            ;
            $codEstudiante=$registro['codEstudiante'];
                    $this->cambiosTEgresado = '';
                    $this->verificarCambiosDatos($registro);
                    $listaCambiosEgresado='';
                    $cambiosLogEgresado='';
                    if(is_array($this->cambiosTEgresado)){
                        foreach ($this->cambiosTEgresado as $cambio) {

                            if(!$listaCambiosEgresado){
                                $listaCambiosEgresado = $cambio['campo']."=".$cambio['valor']."";
                                $cambiosLogEgresado = $cambio['campo'].":".$cambio['anterior']."=>".$cambio['valor']."";
                            }else{
                                $listaCambiosEgresado.= ", ".$cambio['campo']."=".$cambio['valor']."";
                                $cambiosLogEgresado .= ", ".$cambio['campo'].":".$cambio['anterior']."=>".$cambio['valor']."";

                            }

                        }
                    }

                    $actualizadoEgresado=0;

                    if($listaCambiosEgresado){
                        $actualizadoEgresado = $this->actualizarRegistroEgresado($listaCambiosEgresado);

                        $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                        'evento'=>75,
                                                                                        'descripcion'=>'Actualización datos egresado - Archivo excel',
                                                                                        'registro'=>str_replace( "'", " ",$cambiosLogEgresado),
                                                                                        'afectado'=>$codEstudiante);
                        $this->procedimientos->registrarEvento($variablesRegistro);

                    }else{
                        $mensaje=' - No hay datos para actualizar';
                    }
                    
                    if($actualizadoEgresado>0 ){
                       $mensaje= " - Registro actualizado";
                    }
                            
        return $mensaje;
    }
    
    /**
     * Función para verificar y actualizar el estado del estudiante
     * @param type $registro
     * @return type
     */
    function procesarEstado($registro){
            // no permite el cambio de estado si está en egresado, y solo se puede cambiar a estado E, 
            // solo si es secretario academico
            $actualizado='';
            if((isset($this->datosEstudiante['ESTADO'])?$this->datosEstudiante['ESTADO']:'')!='E' && $this->nivel==83 ){
                    $actualizado=$this->actualizarEstado($registro);
                    if(!$actualizado){
                        echo " El estado del estudiante no se pudo actualizar";
                    }

            }elseif((isset($this->datosEstudiante['ESTADO'])?$this->datosEstudiante['ESTADO']:'')!='E' && $this->nivel!=83 ){
                    echo " El perfil no puede cambiar el estado del estudiante a Egresado.";
                
            }elseif((isset($this->datosEstudiante['ESTADO'])?$this->datosEstudiante['ESTADO']:'')=='E'){
                    echo " El estudiante ya se encuentra en estado Egresado.";
                
            }
            return $actualizado;

    }
    /**
     * Función para actualizar un registro de egresado
     * @param type $listaCambios
     * @return type
     */    
    function actualizarRegistroEgresado($listaCambios){
        $datos=array('listaCambios'=>$listaCambios,
                    'codEstudiante'=>  $this->datosEgresado['EGR_EST_COD']
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_egresado", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    /**
     * Función para actualizar el estado de un estudiante
     * @param type $registro
     * @return type
     */
    function actualizarEstado($registro){
        $datos=array('codEstudiante'=>  $registro['codEstudiante'],
                    'codProyecto'=>  $registro['codProyecto'],
                    'estado'=>  'E'
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_estado", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    
    /**
     * Función para actualizar registros de egresado
     * @param type $registro
     * @return type
     */
    function registrarDatosEgresado($registro){
        
        $cadena_sql = $this->sql->cadena_sql("registrarDatosEgresado", $registro);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    /**
     * Función que retorna número de mes a partir del nombre
     * @param type $mes
     * @return int
     */
    function numeroMes($mes){
        $numeroMes = 0;
        $mes=mb_strtoupper(trim($mes),'UTF-8');
        switch ($mes) {
            case 'ENERO':
                $numeroMes='01';
                break;
            case 'FEBRERO':
                $numeroMes='02';
                break;
            case 'MARZO':
                $numeroMes='03';
                break;
            case 'ABRIL':
                $numeroMes='04';
                break;
            case 'MAYO':
                $numeroMes='05';
                break;
            case 'JUNIO':
                $numeroMes='06';
                break;
            case 'JULIO':
                $numeroMes='07';
                break;
            case 'AGOSTO':
                $numeroMes='08';
                break;
            case 'SEPTIEMBRE':
                $numeroMes='09';
                break;
            case 'OCTUBRE':
                $numeroMes='10';
                break;
            case 'NOVIEMBRE':
                $numeroMes='11';
                break;
            case 'DICIEMBRE':
                $numeroMes='12';
                break;

            default:
                break;
        }
        return $numeroMes;
    }
   
    /**
     * Funcion para consultar datos de estudiante
     * @param type $codEstudiante
     * @param type $documento
     * @return type
     */
    function consultarEstudiante($codEstudiante,$documento){
        $datos=array('documento'=>$documento,
                    'codEstudiante'=>  $codEstudiante
            );
        $cadena_sql = $this->sql->cadena_sql("consultar_estudiante", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0];
    }
    
    /**
     * Función para consultar el rector a partir de fecha de grado
     * @return type
     */
    function obtenerRector($fecha) {
        $codRector=0;
        $rectores = $this->consultarRectores();
        $fecha = str_replace('/', '', $fecha);
        $fecha = str_replace('-', '', $fecha);
            
        if(is_array($rectores)){
            foreach ($rectores as $rector) {
                if($fecha>=$rector['FECHA_DESDE'] && $fecha<=$rector['FECHA_HASTA'] ){
                    $codRector=$rector['REC_COD'];
                }
            }
        }
        return $codRector;
        
    }
    /**
     * Función para consultar los rectores
     * @return type
     */
    function consultarRectores() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarRectores", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }

    /**
     * Función para consultar el secretario academico a partir de fecha de grado
     * @return type
     */
    function obtenerSecretarioAcademico($fecha,$codFacultad) {
        $codSecretario=0;
        $fecha = str_replace('/', '', $fecha);
        $fecha = str_replace('-', '', $fecha);
        $secretarios = $this->consultarSecretariosAcademicos($codFacultad);
        if(is_array($secretarios)){
            foreach ($secretarios as $secretario) {
                if($fecha>=$secretario['FECHA_DESDE'] && $fecha<=$secretario['FECHA_HASTA'] ){
                    $codSecretario=$secretario['SEC_COD'];
                }
            }
        }
        return $codSecretario;
        
    }
    /**
     * Función para consultar los rectores
     * @return type
     */
    function consultarSecretariosAcademicos($codFacultad) {
       
        $cadena_sql = $this->sql->cadena_sql("consultarSecretarios", $codFacultad);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
   
    function consultarTitulosGrado($codProyecto, $sexo) {
            $datos=array('codProyecto'=>$codProyecto,
                        'sexo'=>$sexo);
        $cadena_sql = $this->sql->cadena_sql("consultarTituloGrado", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }
}
    ?>