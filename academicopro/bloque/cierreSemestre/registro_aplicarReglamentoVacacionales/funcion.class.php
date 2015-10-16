<?php
//
/**
 * Funcion nombreFuncion
 *
 * Descripcion
 *
 * @package academicopro
 * @subpackage cierreSemestre
 * @author Karen Palacios
 * @update Milton Parra
 * @version 0.0.0.2
 * Fecha: 31/05/2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

/**
 * Clase funcion_registro
 *
 * descripcion
 * 
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_registroAplicarReglamentoVacacionales extends funcionGeneral
{

    public $configuracion;
    public $proyecto;
    public $anio;
    public $periodo;    
    public $periodoActivo;
    public $causal;
    public $porcentaje;
    public $numMatriculas;
    public $matriculas;
    public $espaciosPerdidos;
    public $codigoEstudiante;
    public $tipoProyecto;
    public $nivelProyecto;
    public $acuerdoEstudiante;
    public $estadoEstudiante;
    public $estudantesNoProcesados;
    public $renovaciones;
    public $renovaciones004;
    public $matriculas004;
	


    /**
     * 
     * @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
     */
    function __construct($configuracion){

          $this->configuracion=$configuracion;
          $this->cripto=new encriptar();
          $this->sql=new sql_registroAplicarReglamentoVacacionales($configuracion);
          $this->validacion=new validarInscripcion();
          $this->procedimientos=new procedimientos();
          $this->formulario="bloque_basicoRegistro";//nombre del bloque que procesa el formulario
		  $this->error=array();
          /**
           * Intancia para crear la conexion ORACLE
           */
		  $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
          /**
           * Instancia para crear la conexion General
           */
		  
            $this->acceso_db=$this->conectarDB($configuracion,"");
		  
          /**
           * Instancia para crear la conexion de MySQL
           */
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

          /**
           * ejemplo de validación de un formulario
           */
            $this->verificar="control_vacio(".$this->formulario.",'nombre','')";              
            $this->contador=1;
            $this->notasPerdidas=array();
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
            $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
          
            if($this->usuario=="")
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
      }

      }

    /**
       *
       */

      function proyectos($proyectoInicial,$anio,$periodo) {
          $proyectos=$this->consultarProyectosCierre($anio,$periodo);
          foreach ($proyectos as $key => $proyecto) {
              $this->aplicarReglamento($proyectos[$key]['PROYECTO'], $anio, $periodo);
          }
          echo "finalizo";exit;
          
      }
    /**
    * funcion inicializadora para aplicarReglamento
    */
    function aplicarReglamento($proyecto,$anio,$periodo){
        ?>            
        
            <head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogress(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = 'Aplicando Reglamento '+vItem+' de '+vTotal+' estudiantes.  '+vValor ;
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
            <!-- Ahora creo la barra de progreso con etiquetas DIV -->
             <div class="ProgressBar">
                  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% </div>
                  <div id="getProgressBarFill"></div>
                </div>
            </body><?
            
		$valida = $this->validaCalendario();
		
		if(!$valida){
			return false;
		}
		
		$this->proyecto=$proyecto;
                $datosProyecto=$this->consultarTipoProyecto();
                $this->tipoProyecto=$datosProyecto[0]['TIPO'];
                $this->nivelProyecto=$datosProyecto[0]['NIVEL'];
		$this->anio=$anio;
		$this->periodo=$periodo;



//echo "<br>activar registro de reglamento<br>";
		$this->insertarInicioEvento(94,$this->tipoProyecto);
                $estudiantes=$this->consultarEstudiantes();

                if(count($estudiantes)>0){
                    //$numeroEstudiante=0;
                    foreach ($estudiantes as $estudiante) {
                        if (isset($estudiante['PROMEDIO']))
                        {
                            $this->actualizarPromedioReglamento($estudiante);
                        }
                    }
                }
		//consulta los estudiantes que se encuentran registrados en la tabla de reglamento y que tienen inscripciones en vacacionales
        	$estudiantes=$this->consultarEstudiantesReglamento();
		//consulta todos los estudiantes en prueba para periodos anteriores
		$estudiantesPrueba=$this->consultarEstudiantesPrueba();
                //crea un arreglo de estudiantes que han estado en prueba indicando el motivo
		$this->estudiantesPrueba=$this->organizarEstudiantesPrueba($estudiantesPrueba);
                //consulta las notas perdidas de los estudiantes en la tabla de reglamento
                $this->notasPerdidas=$this->consultarNotasPerdidas();
		//Genera arreglo de notas perdidas organizado por estudiante, espacio, ano y periodo
                if (is_array($this->notasPerdidas)&&!empty($this->notasPerdidas))
                {
                    $this->organizarNotasPorEstudiante();
                }
		//ejecuta proceso por cada estudiante
                $a=0;
		if(!is_array($estudiantes))
                {
                    $this->error[]="No hay estudiantes para procesar";
                    //return false;			
		}else
                    {
                        $numRegistros=count($estudiantes);
			foreach($estudiantes as $clave=>$valor)
                        {

                            $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
                            echo "<script>callprogress(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                            flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                            ob_flush();
                            $a++;
                            usleep (300);                            
                            $this->procesarEstudiante($valor);
			}	
                    }
		//actualiza la fecha de fin del evento
		$this->insertarFinEvento(94);
                //vuelve al formulario de cierre
                $this->volverFormularioCierre();

		return true;
	}
	
	
	 /**
    * funcion que evalua uno a uno los estudiantes con los datos registrados en la tabla de reglamento($registroEstudiante)
    */	
	function procesarEstudiante($registroEstudiante){

		$this->codigoEstudiante=$registroEstudiante['COD_ESTUDIANTE'];
                $this->validarSetentaPorciento($registroEstudiante['COD_ESTUDIANTE']);
		$this->acuerdoEstudiante=$registroEstudiante['ACUERDO'];
//                echo "<br>".$this->codigoEstudiante." -- ".  $this->acuerdoEstudiante."<br>";
		$this->promedioEstudiante=($registroEstudiante['PROMEDIO']);
		$this->estadoEstudiante=($registroEstudiante['ESTADO']);
		$this->proyectoEstudiante=($registroEstudiante['COD_PROYECTO']);
                $this->causal=0;
                $this->porcentaje=0;
                $this->matriculas=0;
                $this->numMatriculas=0;
                $this->renovaciones='';
                $this->espaciosPerdidos=0;
                $this->matriculas004=0;
                $this->renovaciones004=0;                
		//almacenará solo los espacios que el estudiante perdio en el periodo actual
		$this->EspaciosPerdidosActuales=array();
		
		//almacenará todos los espacios que ha perdido el estudiante
		$this->EspaciosPerdidosTotales=array();	
		$reglamento='N';
		
		//aplica verificacion de motivo 1 
		$motivo1=$this->aplicarMotivoPromedio();
                //aplica verificacion de motivo 2
		$motivo2=$this->aplicarMotivoNumeroEspaciosPerdidos();
                //aplica verificacion de motivo 3
		$motivo3=$this->aplicarMotivoNumeroVecesEspacioPerdido($this->acuerdoEstudiante);
		//suma los motivos de prueba
		$motivo=$motivo1+$motivo2+$motivo3;
                //si hay algun motivo de prueba reglamento se coloca en S
		if($motivo<>0)
                {
                    $reglamento='S';
		}
                //verifica si hay espacios perdidos para el estudiante
                if(isset($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante]))
                {
                    if(is_array($this->EspaciosPerdidosActuales)&&count($this->EspaciosPerdidosActuales)>2)
                    {
                        $espacios=array_fill_keys($this->EspaciosPerdidosActuales, 1);
                        foreach ($espacios as $key => $value) {
                            foreach ($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante] as $key2 => $value) {
                                if ($key==$key2)
                                {$espacios[$key]=$value;}else{}
                            }
                        }
                    }else
                        {
                            $espacios=$this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante];
                        }
                    $this->espaciosPerdidos=json_encode($espacios);
                    $veces=max($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante]);
                }elseif($reglamento=='S')
                    {
                        if(is_array($this->EspaciosPerdidosActuales)&&count($this->EspaciosPerdidosActuales)>2)
                        {
                            $espacios=array_fill_keys($this->EspaciosPerdidosActuales, 1);
                            $this->espaciosPerdidos=json_encode($espacios);
                        }
                        $veces=1;
                    }else
                        {
                            $veces=0;
                        }
                //calcula el porcentaje del plan de estudios aprobado por el estudiante
                $this->porcentaje=$this->calcularPorcentajeTotalPlanEstudiante($this->anio+1);                        
                //actualiza el registro del estudiante en la tabla de reglamento
                if($reglamento=='N')
                {
                    switch ($this->estadoEstudiante)
                    {
                        case 'A':
                            $this->estadoEstudiante='A';
                            break;
                        case 'B':
                            $this->estadoEstudiante='A';
                            break;
                        case 'V':
                            $this->estadoEstudiante='V';
                            break;
                        case 'J':
                            $this->estadoEstudiante='V';
                            break;
                        case 'H':
                            $this->estadoEstudiante='H';
                            break;
                        case 'T':
                            $this->estadoEstudiante='T';
                            break;
                        default :
                            $this->estadoEstudiante='V';
                            break;
                    }
                    $this->actualizarEstadoEstudiante();
                }
                
		$this->actualizarReglamento($motivo,$reglamento,$veces);
                //si el estado de reglamento es S y el estado del estudiane es V, aplica reglamento
		if($reglamento=='S')
                {
                    //aplica reglamento con base en el acuerdo del estudiante
                    switch($this->acuerdoEstudiante)
                    {
                        case '2011004':
                                $this->aplicarAcuerdo2011004();
                        break;
                        case '2009007':
                                $this->aplicarAcuerdo2009007();
                        break;
                        case '1993027':
                                $this->aplicarAcuerdo1993027();
                        break;
                    }
                    $this->actualizarCausal();
                    //actualiza el estado del estudiante despues de aplicar reglamento
                    $this->actualizarEstadoEstudiante();
		}
		$this->contador++;
		return true;
	}
        
        /**
         * Funcion que permite actualizar el promedio de un estudiante que ha cursado vacacional
         * @param type $estudiante
         * @return type
         */
        function actualizarPromedioReglamento($estudiante) {
		$variables=array('codProyecto'=>$estudiante['COD_PROYECTO'],
                                 'codEstudiante'=>$estudiante['CODIGO'],
                                 'promedio'=>$estudiante['PROMEDIO'],
                                 'ano'=>$this->anio,
                                 'periodo'=>$this->periodo-1);
		$cadena_sql=$this->sql->cadena_sql('actualizarPromedioReglamento',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
		return $resultado;
            
        }
	/**
    * funcion que consulta todas las asignaturas perdidas de los estudiantes registrados en la tabla de reglamento para el actual anio y periodo
	* @return arreglo 
    */	
	function consultarNotasPerdidas(){
		$variables=array('proyecto'=>$this->proyecto,
						 'ano'=>$this->anio,
						 'periodo'=>$this->periodo);    
		$cadena_sql=$this->sql->cadena_sql('consultarNotas',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado;
	}

	/**
         * Funcion que crea un arreglo de notas de los estudiantes, en el cual la clave es el codigo del estudiante
         */
	function organizarNotasPorEstudiante(){
		foreach($this->notasPerdidas as $clave=>$valor){
			$this->registroNotasPerdidasPorEstudiante[$valor['COD_ESTUDIANTE']][$valor['COD_ASIGNATURA']][$valor['ANIOPERIODO']][]=$valor['NOTA'];
		}
	}

	/**
         * Funcion que crea un arreglo de notas de los estudiantes en prueba, en el cual la clave es el codigo del estudiante
         * @param type $estudiantesPrueba
         * @return type
         */
        function organizarEstudiantesPrueba($estudiantesPrueba){
		foreach($estudiantesPrueba as $clave=>$valor){
			$resultado[$valor['COD_ESTUDIANTE']][$valor['ANIOPERIODO']]['MOTIVO'][]=$valor['MOTIVO'];
			$resultado[$valor['COD_ESTUDIANTE']][$valor['ANIOPERIODO']]['PROMEDIO'][]=(isset($valor['PROMEDIO'])?$valor['PROMEDIO']:'');
			$resultado[$valor['COD_ESTUDIANTE']][$valor['ANIOPERIODO']]['REGLAMENTO'][]=$valor['REGLAMENTO'];
		}
		return $resultado;
	}
	
	
    /**
    * funcion que valida si es posible ejecutar este proceso segun el calendario academico
	* @return true si es valido, de otra manera false
    */	
	function validaCalendario(){
		$this->error[]="";
		return true;
	}

	
    /**
    * funcion que rescata los estudiantes de la tabla reglamento para un año y periodo especifico
    */	
	function consultarEstudiantesReglamento(){
		$variables=array('proyecto'=>$this->proyecto,
						 'ano'=>$this->anio,
						 'periodo'=>$this->periodo,
						 'periodoAnt'=>$this->periodo-1);
		$cadena_sql=$this->sql->cadena_sql('consultarEstudiantesReglamento',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado;
	}

        /**
         * Consulta los estudiantes con inscripciones en vacacionales 
         * @param type $codProyecto
         * @return type
         */
        function consultarEstudiantes() {
            $datos=array(
                'ano'=>  $this->anio,
                'periodo'=>$this->periodo,
                'proyecto'=>$this->proyecto
            );
          $cadena_sql = $this->sql->cadena_sql("consultarEstudiantes", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          return $resultado;
          
        }

    /**
    * funcion que rescata los estudiantes de la tabla reglamento que han quedado en prueba para los periodos diferentes al actual
    */	
	function consultarEstudiantesPrueba(){
		$variables=array('proyecto'=>$this->proyecto,
                                    'ano'=>  $this->anio,
                                    'periodo'=>$this->periodo
                        );    
		$cadena_sql=$this->sql->cadena_sql('consultarEstudiantesReglamentoenPrueba',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado;
	}

	 /**
    * Funcion Aplicar Prueba Academica por Promedio  
	* Circular 5 Vicerrectoria Viernes 17 de Febrero de 2012 Numeral 5 Aclaracion del promedios minimos
    */	
	function aplicarMotivoPromedio(){
		$motivo=0;
		switch($this->acuerdoEstudiante){
			case '2011004':
				if($this->promedioEstudiante<320){//cambiar por la consulta de nota aprobatoria
					$motivo=100;
				}
			break;
			default:
				//acuerdos 1993027 y 2009007
				if($this->promedioEstudiante<300){//cambiar por la consulta de nota aprobatoria
					$motivo=100;
				}			
			break;
		}
		return $motivo;

	}

	/**
         * Funcion para aplicar prueba academica por motivo 2: numero de espacios perdidos en el semestre
         * @return int
         */
        function aplicarMotivoNumeroEspaciosPerdidos(){
		$motivo=0;
		if(isset($this->registroNotasPerdidasPorEstudiante[$this->codigoEstudiante])){
			foreach($this->registroNotasPerdidasPorEstudiante[$this->codigoEstudiante] as $asignatura=>$valor){
				foreach($valor as $anioperiodo=>$nota){
					$anioper=$this->anio.$this->periodo;
                                        $anioperant=$this->anio.$this->periodo-1;
					$this->EspaciosPerdidosTotales[]=$asignatura."-".$anioperiodo;
					if(($anioper==$anioperiodo||$anioperant==$anioperiodo)){
						$this->EspaciosPerdidosActuales[]=$asignatura;
					}
				}
			}
		}else{
			return $motivo;
		}
                $aprobadas=$this->consultarAprobadasVacacionales($this->anio,$this->periodo,$this->codigoEstudiante);
                $this->EspaciosPerdidosActuales=array_unique($this->EspaciosPerdidosActuales);
                if(is_array($aprobadas))
                {
                    foreach ($aprobadas as $key => $value)
                    {
                        $this->EspaciosPerdidosActuales=array_diff($this->EspaciosPerdidosActuales,$value);
                    }
                }
                if(count($this->EspaciosPerdidosActuales)>2){
			$motivo=20;
		}
		return $motivo;
	}

	/**
        * funcion que recorre todos los espacios que perdidos para el periodo actual y los busca en el total de asignaturas perdidas para comprobar 
	* si la habia perdido anteriormente.
         * Aplica prueba academica por motivo 3
        */	
	function aplicarMotivoNumeroVecesEspacioPerdido($acuerdo){
		$motivo=0;
                $ano=$this->anio;
                $periodoAnt=$this->periodo-1;
		foreach($this->EspaciosPerdidosActuales as $valor){
                    $numeroVeces=0;
			//busca el total de veces q tiene espacio perdido en todos los periodos incluido el actual
                        foreach ($this->EspaciosPerdidosTotales as $key => $value) {
                            $reprobado=explode("-", $value);
                            if($reprobado[0]==$valor)
                                {
                                if($acuerdo==2011004)
                                {
                                    if ($reprobado[1]>20111)
                                    {
                                        $numeroVeces++;
                                    }
                                }else
                                    {
                                        $numeroVeces++;
                                    }
                                }
                        }
			//$numeroVeces=count(array_keys($this->EspaciosPerdidosTotales,$valor));
			if($numeroVeces>=2){
				$this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante][$valor]=$numeroVeces;
				$motivo=3;
			}
		}
		return $motivo;
	}
	
	/**
         * Funcion que registra el evento de inicio de aplicacion de reglamento
         * @param type $evento
         * @return boolean
         */
        function insertarInicioEvento($evento,$tipoProyecto){
		$variables=array('proyecto'=>$this->proyecto,
                                    'anio'=>$this->anio,
                                    'periodo'=>$this->periodo,
                                    'evento'=>$evento,
                                    'tipo_proyecto'=>$tipoProyecto);
						 
		$cadena_sql=$this->sql->cadena_sql('insertarInicioEvento',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");

		return true;
	}

	/**
         * Funcion que actualiza la fecha de fin del evento de aplicacion de reglamento
         * @param type $evento
         * @return boolean
         */
        function insertarFinEvento($evento){
		
		$variables=array('proyecto'=>$this->proyecto,
                                    'anio'=>$this->anio,
                                    'periodo'=>$this->periodo,
                                    'evento'=>$evento);
						 
		$cadena_sql=$this->sql->cadena_sql('insertarFinEvento',$variables); 
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
		
		return true;
	}

	
	/**
         * Función que actualiza el registro de reglamento de un estudiante con el numero de espacios perdidos, espacios que ha visto por tercera vez
         * y motivo de la prueba academica
         * @param type $motivo
         * @param type $reglamento
         * @return boolean
         */
        function actualizarReglamento($motivo,$reglamento,$veces){
            if ($this->espaciosPerdidos=='')$this->espaciosPerdidos='0';
                $variables=array('proyecto'=>$this->proyectoEstudiante,
                                    'anio'=>  $this->anio,
                                    'periodo'=>$this->periodo-1,
                                    'numVecesPerdidos'=>$veces,
                                    'espaciosTercerar'=>count($this->EspaciosPerdidosActuales),
                                    'motivo'=>$motivo,
                                    'reglamento'=>$reglamento,
                                    'estudiante'=>$this->codigoEstudiante,
                                    'causal'=>$this->causal,
                                    'porcentaje'=>$this->porcentaje,
                                    'espacios_perdidos'=>$this->espaciosPerdidos,
                                    'promedio'=>$this->promedioEstudiante,
                        );
                foreach ($variables as $key => $value) {
                    if($value=='')
                    {
                        $variables[$key]='null';
                    }
                }
		$cadena_sql=$this->sql->cadena_sql('actualizarReglamento',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
		
		return true;
	}
	
        /**
         * Funcion que actualiza la causal de exclusion para los estudinates que incurren en perdidad de calidad de estudiante
         * @return boolean
         */
        function actualizarCausal(){
		$variables=array('proyecto'=>$this->proyectoEstudiante,
                                    'anio'=>  $this->anio,
                                    'periodo'=>$this->periodo-1,
                                    'estudiante'=>$this->codigoEstudiante,
                                    'causal'=>$this->causal,
                                    'matriculas'=>$this->numMatriculas
                        );
						 
		$cadena_sql=$this->sql->cadena_sql('actualizarCausal',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
		
		return true;
	}
	
        /**
         * Funcion que permite aplicar reglamento a los estudiantes del acuerdo 004 de 2011
         * @return boolean
         */
	function aplicarAcuerdo2011004(){

		$vecesMotivo1=0;
		$vecesMotivo2=0;
                $tres=0;
                switch ($this->estadoEstudiante)
                {
                    case 'A':
                        $this->estadoEstudiante='B';
                        break;
                    case 'B':
                        $this->estadoEstudiante='B';
                        break;
                    case 'V':
                        $this->estadoEstudiante='J';
                        break;
                    case 'J':
                        $this->estadoEstudiante='J';
                        break;
                    default :
                        $this->estadoEstudiante='J';
                        break;
                }
                if(isset($this->estudiantesPrueba[$this->codigoEstudiante]))
                {
                    foreach($this->estudiantesPrueba[$this->codigoEstudiante] as $anioperiodo=>$motivo)
                    {
                        if($motivo['PROMEDIO'][0]<320&&$anioperiodo>20111)
                            {
                                    $vecesMotivo1++;
                            }
                            if(strpos($motivo['MOTIVO'][0],'2')!== false&&$anioperiodo>20111)
                            {
                                $vecesMotivo2++;
                            }
                    }
                }
		if(($vecesMotivo1>=4)||($vecesMotivo2>=4)){
			$this->estadoEstudiante='U';
                        if($vecesMotivo1>=4)
                        {$this->causal+=100;}
                        if($vecesMotivo2>=4)
                        {$this->causal+=20;}
		}
		if(isset($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante]))
                {
                    foreach($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante] as $clave=>$numeroVeces)
                    {
                        //se busca el total de veces  q tiene espacio perdido en todos los periodos incluido el actual
                        if($numeroVeces==3)
                        {
                            $setenta=$this->validarSetentaPorciento();
                            if(!$setenta)
                            {
                                $this->estadoEstudiante='U';
                                $tres++;
                            }
                        }elseif($numeroVeces>=4)
                            {
                                $this->estadoEstudiante='U';
                                $tres++;
                            }
                    }
                }
                if($tres>0){$this->causal+=3;}
		return true;
	}

	/**
         * Funcion que valida si un estudiante ha cursado más del 70% del plan de estudios
         * @return boolean
         */
        function validarSetentaPorciento(){
            if($this->porcentaje>=70)
            {
                return 'ok';
            }else
                {
		return false;
	}
	}

	/**
         * Funcion que valida si un estudiante ha cursado más del 70% del plan de estudios
         * @return boolean
         */
        function calcularPorcentajeTotalPlanEstudiante(){
            $setenta=$this->consultarPorcentajeTotalPlan($this->codigoEstudiante);
            if(is_array($setenta)&&isset($setenta[0]['CREDITOS_PLAN']))
            {
                switch (trim($setenta[0]['TIPO_ESTUDIANTE'])){
                    case 'S':
                        $porcentaje=($setenta[0]['CREDITOS_ESTUDIANTE']/$setenta[0]['CREDITOS_PLAN'])*100;
                        break;
                    case 'N':
                        $porcentaje=($setenta[0]['ESPACIOS_ESTUDIANTE']/$setenta[0]['CREDITOS_PLAN'])*100;
                        break;
                    default :
                        $porcentaje=0;
                        break;
                }
            }else
                {
                    $porcentaje=0;
                }
                return floor($porcentaje);
	}
	
	/**
         * Funcion que determina el porcentaje del plan de estudios cursado por el estudiante hasta el acuerdo 004
         * @return boolean
         */
        function calcularPorcentajePlanEstudiante(){
            $setenta=$this->consultarPorcentajePlan($this->codigoEstudiante);
            if(is_array($setenta)&&isset($setenta[0]['CREDITOS_PLAN']))
            {
                switch (trim($setenta[0]['TIPO_ESTUDIANTE'])){
                    case 'S':
                        $porcentaje=($setenta[0]['CREDITOS_ESTUDIANTE']/$setenta[0]['CREDITOS_PLAN'])*100;
                        break;
                    case 'N':
                        $porcentaje=($setenta[0]['ESPACIOS_ESTUDIANTE']/$setenta[0]['CREDITOS_PLAN'])*100;
                        break;
                    default :
                        $porcentaje=0;
                        break;
                }
                $porcentaje=floor($porcentaje);
            }elseif(!is_array($setenta))
                {
                    $porcentaje=0;
                }
            else
                {
                    if(strlen($this->codigoEstudiante)>10&&substr($this->codigoEstudiante, -11,5)>=20112)
                    {
                        $porcentaje=0;
                    }else{$porcentaje='';}
                }
                return $porcentaje;
	}        
	
	/**
	*Funcion aplicar Acuerdo 007 Diciembre 16 de 2009 Articulo 5 Paragrafo 1
	*/	
	function aplicarAcuerdo2009007(){
            $pruebas=$this->consultarPruebas007($this->codigoEstudiante);
		$si=0;
		
                if (is_array($pruebas)&&!empty($pruebas))
                {
                    //elimina los dos ultimos periodos articulo 5 acuerdo 007
                    array_pop($pruebas);
                    array_pop($pruebas);
                    foreach($pruebas as $clave=>$valor)
                    {
                        /*aplicar amnistias*/
                        if($pruebas[$clave]['ANIOPERIODO']=='20101'||$pruebas[$clave]['ANIOPERIODO']=='20111')
                        {
                            unset($pruebas[$clave]);
                        }
                    }
                    foreach ($pruebas as $key=>$value)
                    {
                        if ($pruebas[$key]['REGLAMENTO']=='S')
                        {
                            $si++;
                        }
                    }
                    if($si>=1)
                    {
                        $this->estadoEstudiante='Z';
                        $this->causal=4;
                    }else
                        {
                            switch ($this->estadoEstudiante)
                            {
                                case 'A':
                                    $this->estadoEstudiante='B';
                                    break;
                                case 'B':
                                    $this->estadoEstudiante='B';
                                    break;
                                case 'V':
                                    $this->estadoEstudiante='J';
                                    break;
                                case 'J':
                                    $this->estadoEstudiante='J';
                                    break;
                                default :
                                    $this->estadoEstudiante='J';
                                    break;
                            }
                        }
                }else
                    {
                        switch ($this->estadoEstudiante)
                        {
                            case 'A':
                                $this->estadoEstudiante='B';
                                break;
                            case 'B':
                                $this->estadoEstudiante='B';
                                break;
                            case 'V':
                                $this->estadoEstudiante='J';
                                break;
                            case 'J':
                                $this->estadoEstudiante='J';
                                break;
                            default :
                                $this->estadoEstudiante='J';
                                break;
                        }
                    }

		return true;
	}
	
	/**
         * Funcion para aplicar reglamento a estudiantes en acuerdo 027 de 1993
         * @return boolean
         */
	function aplicarAcuerdo1993027(){
                switch ($this->estadoEstudiante)
                {
                    case 'A':
                        $this->estadoEstudiante='B';
                        break;
                    case 'B':
                        $this->estadoEstudiante='B';
                        break;
                    case 'V':
                        $this->estadoEstudiante='J';
                        break;
                    case 'J':
                        $this->estadoEstudiante='J';
                        break;
                    default :
                        $this->estadoEstudiante='J';
                        break;
                }
                if(isset($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante]))
                {
                    foreach($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante] as $clave=>$numeroVeces)
                    {
                        //se busca el total de veces  q tiene espacio perdido en todos los periodos incluido el actual
                        if($numeroVeces==3)
                        {
                            $setenta=$this->validarSetentaPorciento();
                            if(!$setenta)
                            {
                                $this->estadoEstudiante='Z';
                                $this->causal=3;
                            }
                        }elseif($numeroVeces>=4)
                            {
                                $this->estadoEstudiante='Z';
                                $this->causal=3;
                            }
                    }
		}
		return true;
	}
	
	
	/**
         * Funcion que permite actualizar el estado del estudiante al que se le aplico reglamento
         * @return boolean
         */
	function actualizarEstadoEstudiante(){
		$variables=array('proyecto'=> $this->proyectoEstudiante,
						 'estado'=>$this->estadoEstudiante,
						 'estudiante'=>$this->codigoEstudiante);  
						 
		$cadena_sql=$this->sql->cadena_sql('actualizarEstadoEstudiante',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
		return true;
	}
	
      /**
       * Funcion que permite regresar al formulario de inicio de Cierre de semestre
       */
        function volverFormularioCierre() {
                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";          
		$variable="&pagina=admin_cierreVacacionales";
		$variable.="&opcion=consultarProyecto";
		$variable.="&codProyecto=".$this->proyecto;
		$variable= $this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
      }
      
      /**
       * Permite consultar los registros de reglamento que tiene un estudiante del acuerdo 007
       * @param type $codEstudiante
       * @return type
       */
      function consultarPruebas007($codEstudiante){
            $cadena_sql=$this->sql->cadena_sql('consultarPruebas007',$codEstudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
	}
        
        /**
         * Permite consultar los proyectos que tienen estudiantes para actualizar datos
         */
        function consultarProyectosCierre($anio,$periodo) {
            $variable=array('anio'=>$anio,
                                 'periodo'=>$periodo);
            $cadena_sql=$this->sql->cadena_sql('consultarProyectosCierre',$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }
        /**
         * Permite consultar el total de espacios o creditos aprobados por el estudiante y el total de su plan de estudios hasta 2011-3
         */
        function consultarPorcentajePlan($codEstudiante) {
            $variable=array('codEstudiante'=>$codEstudiante);
            $cadena_sql=$this->sql->cadena_sql('consultarPorcentajePlan',$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
      
        }

        /**
         * Permite consultar el total de espacios o creditos aprobados por el estudiante y el total de su plan de estudios
         */
        function consultarPorcentajeTotalPlan($codEstudiante) {
            $variable=array('codEstudiante'=>$codEstudiante);
            $cadena_sql=$this->sql->cadena_sql('consultarPorcentajeTotalPlan',$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }
        
        /**
         * Permite consultar los proyectos que tienen estudiantes para actualizar datos
         */
        function consultarAprobadasVacacionales($anio,$periodo,$codEstudiante) {
            $variable=array('anio'=>$anio,
                            'periodo'=>$periodo,
                            'codEstudiante'=>$codEstudiante);
            $cadena_sql=$this->sql->cadena_sql('consultarAprobadasVacacionales',$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }
        
        /**
         * Permite consultar el tipo de proyecto que hace el cierre
         */
        function consultarTipoProyecto() {
            $cadena_sql=$this->sql->cadena_sql('consultarTipoProyecto',$this->proyecto);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
        
        }
      

}
?>

