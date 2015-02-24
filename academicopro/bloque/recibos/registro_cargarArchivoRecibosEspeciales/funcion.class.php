<?php


/**
 * Funcion registro_cargarArchivoRecibosEspeciales
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package 
 * @subpackage registro_cargarArchivoRecibosEspeciales
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 12/11/2014
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
 * Clase funcion_registroCargarArchivoRecibosEspeciales
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package registro_cargarArchivoRecibosEspeciales
 * @subpackage Admin
 */
class funcion_registroCargarArchivoRecibosEspeciales extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;
  private $datosRecibos;
  private $ano;
  private $periodo;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_registroCargarArchivoRecibosEspeciales
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

        $this->formulario = "registro_cargarArchivoRecibosEspeciales";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "recibos/registro_cargarArchivoRecibosEspeciales";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_registroCargarArchivoRecibosEspeciales($configuracion);
       
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
        if($this->nivel==80){
            $this->accesoOracle=$this->conectarDB($configuracion,"soporteoas");
        }else
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
            }
        $this->procedimientos=new procedimientos();
        $this->validacion=new validarInscripcion();
        
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        
    }

   /**
    * Función para cargar archivo excel
    */
    function cargarArchivoRecibos(){

            $maxSize=10*1024*1024;//tama�p en mb
            $upload_dir = $this->configuracion['raiz_documento']."/documentos/archivosCargaRecibosEspeciales/";
            if (isset($_FILES["archivo"])&&$_FILES["archivo"]['size']<=$maxSize
                    &&($_FILES["archivo"]['type']=="application/vnd.ms-excel"||$_FILES["archivo"]['type']=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) {
                    if ($_FILES["archivo"]["error"] > 0) {
                            echo "Error: " . $_FILES["file"]["error"] . "<br>";
                    } else {
                            $id= "_".uniqid();
                            if(move_uploaded_file($_FILES["archivo"]["tmp_name"], $upload_dir . $_FILES["archivo"]["name"].$id)){
                                    $ruta = $upload_dir ;
                                    $archivo =  $_FILES["archivo"]["name"].$id;
                                    $this->procesarExcelRecibos($ruta,$archivo);
                                    $this->procesarDatosRecibos();
                            }

                    }
            }else {echo "Archivo Invalido";}

                
   }
      
   /**
    * Función para procesar el archivo excel y posteriormente generar los recibos
    * @param type $ruta
    * @param type $archivo
    */
   function procesarExcelRecibos($ruta,$archivo){
       /** Include PHPExcel */
	require_once $this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/PHPExcel.php";
	/** Include PHPExcel_IOFactory */
	require_once $this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/PHPExcel/IOFactory.php";
	
	
	if (!file_exists($ruta . $archivo)) {
		exit("Por favor ingrese un archivo valido." . EOL);
	}
	
	$objPHPExcelReader = PHPExcel_IOFactory::load($ruta . $archivo);
	
        $lastRow = $objPHPExcelReader->getActiveSheet()->getHighestRow();
        $lastColumn = $objPHPExcelReader->getActiveSheet()->getHighestColumn();
        if($lastRow>1){
                $id=1;
                //leemos datos para los recibos del excel y lo pasamos a un array
                for ($i=3;$i<=$lastRow;$i++){
                    $codEstudiante = $objPHPExcelReader->getActiveSheet()->getCell("A".$i)->getValue();
                    if($codEstudiante){
                            $this->datosRecibos[$id]['codEstudiante'] = $objPHPExcelReader->getActiveSheet()->getCell("A".$i)->getValue();
                            $this->datosRecibos[$id]['anioRecibo'] = $objPHPExcelReader->getActiveSheet()->getCell("B".$i)->getValue();
                            $this->datosRecibos[$id]['perRecibo'] = $objPHPExcelReader->getActiveSheet()->getCell("C".$i)->getValue();
                            if($this->datosRecibos[$id]['perRecibo']==2){
                                $this->datosRecibos[$id]['perRecibo']=3;
                            }
                            $this->datosRecibos[$id]['cuota'] = $objPHPExcelReader->getActiveSheet()->getCell("D".$i)->getValue();
                            $this->datosRecibos[$id]['observaciones'] = $objPHPExcelReader->getActiveSheet()->getCell("E".$i)->getValue();
                            if($this->datosRecibos[$id]['anioRecibo'].$this->datosRecibos[$id]['perRecibo']<$this->ano.$this->periodo){
                                $this->datosRecibos[$id]['observaciones'] .= " PAGO PERIODO ".$this->datosRecibos[$id]['anioRecibo']."-".$this->datosRecibos[$id]['perRecibo'];
                            }
                            $this->datosRecibos[$id]['valorOrdinario'] = $objPHPExcelReader->getActiveSheet()->getCell("F".$i)->getValue();
                            $this->datosRecibos[$id]['fechaOrdinaria'] = $objPHPExcelReader->getActiveSheet()->getCell("G".$i)->getValue();
                            $this->datosRecibos[$id]['valorExtraordinario'] = $objPHPExcelReader->getActiveSheet()->getCell("H".$i)->getValue();
                            $this->datosRecibos[$id]['fechaExtraordinaria'] = $objPHPExcelReader->getActiveSheet()->getCell("I".$i)->getValue();
                            $id++;
                        }
                }
        }
        
   }
   
 
   
   /**
    * Función que procesa los datos tomados del archivo
    */
   function procesarDatosRecibos(){
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
                                            <span class="encabezado_normal">Generar recibos especiales</span>
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
        $numRegistros=count($this->datosRecibos);//menos la fila de encabezados
        $a=1;
        foreach ($this->datosRecibos as $key => $registro) {
            $verificacion='';
                 
                if($this->nivel== 80)
                    {
                        $registro['codProyecto'] = $this->consultarProyectoEstudiante($registro['codEstudiante']);
                        $this->datosRecibos[$key]['codProyecto']=$registro['codProyecto'];
                        $validacion =$this->validarDatos($registro);
                        if($validacion =='ok'){
                            $mensaje = $this->generarRecibo($registro);
                        }else{
                            $mensaje = $validacion;
                        }
                        echo "<br>No. ".$a." - Código: ".$registro['codEstudiante']." - Cod. Proyecto: ".$registro['codProyecto'];
                        echo $mensaje;

                    }else{
                            echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                            EXIT;
                        }
                $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
                echo "<script>callprogress(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                ob_flush();
                $a++;
           
       }
   }
   
    
   /**
     * Función para consultar los datos de un egresado
     * @param type $codEstudiante
     * @return type 
     */
    function consultarProyectoEstudiante($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("proyecto_estudiante", $codEstudiante);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado[0][0];
    }
    
    
    /**
     * Función para validar los datos del recibo
     * @param type $registro
     * @return string
     */
    function validarDatos($registro){
        $codEstudiante = $registro['codEstudiante'];
        $codProyecto = $registro['codProyecto'];
        $anio = (isset($registro['anioRecibo'])?$registro['anioRecibo']:'');
        $per = (isset($registro['perRecibo'])?$registro['perRecibo']:'');
        $cuota = (isset($registro['cuota'])?$registro['cuota']:'');
        $obs = (isset($registro['observaciones'])?$registro['observaciones']:'');
        $valor_ord = (isset($registro['valorOrdinario'])?$registro['valorOrdinario']:'');
        $fecha_ord = (isset($registro['fechaOrdinaria'])?$registro['fechaOrdinaria']:'');
        $valor_extra = (isset($registro['valorExtraordinario'])?$registro['valorExtraordinario']:'');
        $fecha_extra = (isset($registro['fechaExtraordinaria'])?$registro['fechaExtraordinaria']:'');
        $mensaje='';
        $validaFecha1='';
        $validaFecha2='';
        $anio_actual= date('Y');
        if(!is_numeric($anio) || $anio<1990 || $anio>$anio_actual){
            $mensaje .= " Año del recibo no valido";
        }
         
        if(!is_numeric($per) || ($per!=1 && $per!=3)){
            $mensaje .= " Período del recibo no valido";
        }
        if(!is_numeric($cuota) || ($cuota !=1 && $cuota !=2 && $cuota !=3)){
            $mensaje .= " Cuota no valida";
        }
        if (strlen($obs)>150){
            $mensaje .= " Observación no valida, máximo 150 caracteres.";
        }
        if(!is_numeric($valor_ord)){
            $mensaje .= " Valor ordinario no valido";
        }
        if($fecha_ord){
            $validaFecha1 =$this->validarFecha($fecha_ord);
        }
        if($validaFecha1!='ok'){
            $mensaje .= " Fecha pago ordinario no valida";
        }
        if(!is_numeric($valor_extra)){
            $mensaje .= " Valor extraordinario no valido";
        }
        if($fecha_extra){
            $validaFecha2 =$this->validarFecha($fecha_extra);
        }
        if($validaFecha2!='ok'){
            $mensaje .= " Fecha para pago extraordinario no valida";
        }
        if(!$mensaje){
            $mensaje='ok';
        }
        return $mensaje;
    }
    
    /**
     * Función para validar una fecha
     * @param type $fecha
     * @return string
     */
    function validarFecha($fecha){
        $mensaje='';
        $partes= explode("/", $fecha);
        if (checkdate ($partes[1],$partes[0],$partes[2])){
            $mensaje='ok';
        }
        else{
            $mensaje= "La fecha no es correcta";
        } 
        return $mensaje;
    }
    
    /**
     * Función para generar un recibo
     * @param type $datosRegistro
     * @return string
     */
    function generarRecibo($datosRegistro){
        $adicionadoRecibo= $this->adicionarReciboPago($datosRegistro);
        if($adicionadoRecibo){
            $adicionadoRefMatricula= $this->adicionarReferenciaMatricula($datosRegistro);
        }
        if($adicionadoRecibo && $adicionadoRefMatricula){
                    $mensaje= " - Recibo generado";
                    $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                        'evento'=>87,
                                                                                        'descripcion'=>'Recibo especial generado- Archivo excel',
                                                                                        'registro'=>'Proyecto: '.$datosRegistro['codProyecto'].', Año: '.(isset($datosRegistro['anioRecibo'])?$datosRegistro['anioRecibo']:'').', periodo: '.(isset($datosRegistro['perRecibo'])?$datosRegistro['perRecibo']:'').', Valor ordinario: '.(isset($datosRegistro['valorOrdinario'])?$datosRegistro['valorOrdinario']:'').', Fecha pago ordinaria: '.(isset($datosRegistro['fechaOrdinaria'])?$datosRegistro['fechaOrdinaria']:'').', Valor extraordinario: '.(isset($datosRegistro['valorExtraordinario'])?$datosRegistro['valorExtraordinario']:'').', Fecha pago extraordinaria: '.(isset($datosRegistro['fechaExtraordinaria'])?$datosRegistro['fechaExtraordinaria']:'').', Cuota: '.(isset($datosRegistro['cuota'])?$datosRegistro['cuota']:'').', observaciones: '.(isset($datosRegistro['observaciones'])?$datosRegistro['observaciones']:''),
                                                                                        'afectado'=>$datosRegistro['codEstudiante']);
                    $this->procedimientos->registrarEvento($variablesRegistro);
        }else{
            $mensaje= " - Error al generar recibo";
        }
    return $mensaje;
    }
    
    /**
     * Función para registrar un recibo de pago
     * @param array $datos
     * @return int
     */
    function adicionarReciboPago($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_recibo_pago",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para registrar una referencia de matricula de un recibo de pago
     * @param array $datos
     * @return int
     */
    function adicionarReferenciaMatricula($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_referencia_matricula",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
}
    ?>