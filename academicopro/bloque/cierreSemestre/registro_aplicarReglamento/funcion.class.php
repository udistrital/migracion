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
class funcion_registroAplicarReglamento extends funcionGeneral
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
    public $porcentaje004;
    public $renovaciones004;
    public $matriculas004;



    /**
     * 
     * @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
     */
    function __construct($configuracion){

          $this->configuracion=$configuracion;
          $this->cripto=new encriptar();
          $this->sql=new sql_registro($configuracion);
          $this->validacion=new validarInscripcion();
          $this->procedimientos=new procedimientos();
          $this->formulario="bloque_basicoRegistro";//nombre del bloque que procesa el formulario
          $this->formulario2="registro_aplicarReglamento";//nombre del bloque que procesa el formulario
          $this->verificar2="control_vacio(".$this->formulario2.",'codEstudiante','')";              
          
		  $this->error=array();
          /**
           * Instancia para crear la conexion General
           */
		  
          $this->acceso_db=$this->conectarDB($configuracion,"");
		  
          /**
           * Instancia para crear la conexion de MySQL
           */
          $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

          $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
          $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
   
          //Conexion ORACLE
            if($this->nivel==4){
                $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
            }elseif($this->nivel==110){
                $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
            }elseif($this->nivel==114){
                $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
            }elseif($this->nivel==80){
                $this->accesoOracle=$this->conectarDB($configuracion,"soporteoas");
            }else{
                echo "NO TIENE PERMISOS PARA ESTE MODULO";
                exit;
            }
          /**
           * ejemplo de validación de un formulario
           */
          $this->verificar="control_vacio(".$this->formulario.",'nombre','')";              
          $this->contador=1;
          $this->notasPerdidas=array();
          
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
                //Si el proyecto es un posgrado no aplica reglamento
                if (trim($this->nivelProyecto)!='PREGRADO')
                {
                    //inserta el evento de inicio
                    $this->insertarInicioEvento(73,$this->tipoProyecto);
                    //actualiza la fecha de fin del evento
                    $this->insertarFinEvento(73);
                    //vuelve al formulario de cierre
                    $this->volverFormularioCierre();
                    exit;
                }
		$this->insertarInicioEvento(73,$this->tipoProyecto);
		//consulta los estudiantes que se encuentran registrados en la tabla de reglamento del proyecto
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
		$this->insertarFinEvento(73);
                //vuelve al formulario de cierre
                $this->volverFormularioCierre();

		return true;
	}
	
	
	 /**
    * funcion que evalua uno a uno los estudiantes con los datos registrados en la tabla de reglamento($registroEstudiante)
    * Tambien permite recalcular el estado de un estudainte --Milton Parra 30/01/2014
    */	
	function procesarEstudiante($registroEstudiante){

		$this->codigoEstudiante=isset($registroEstudiante['COD_ESTUDIANTE'])?$registroEstudiante['COD_ESTUDIANTE']:'';
        $this->validarSetentaPorciento($registroEstudiante['COD_ESTUDIANTE']);
		$this->acuerdoEstudiante=isset($registroEstudiante['ACUERDO'])?$registroEstudiante['ACUERDO']:'';
//                echo "<br>".$this->codigoEstudiante." -- ".  $this->acuerdoEstudiante."<br>";
		$this->promedioEstudiante=isset($registroEstudiante['PROMEDIO'])?$registroEstudiante['PROMEDIO']:'';
		$this->estadoEstudiante=isset($registroEstudiante['ESTADO'])?$registroEstudiante['ESTADO']:'';
                $this->causal=0;
                $this->porcentaje=0;
                $this->matriculas=0;
                $this->numMatriculas=0;
                $this->renovaciones='';
                $this->espaciosPerdidos=0;
                $this->porcentaje004=0;
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
                //calcula el numero de matriculas del estudiante
                $this->numMatriculas=$this->calcularMatriculasEstudiante();
                if ($this->acuerdoEstudiante==2011004)
                {
                    $this->renovaciones=$this->contarRenovaciones004();
                    if($this->renovaciones!='ok')
                    {
                        $reglamento='S';
                    }
                }
                //actualiza el registro del estudiante en la tabla de reglamento
                    $this->actualizarReglamento($motivo,$reglamento,$veces);
                //si el estado de reglamento es S y el estado del estudiane es V, aplica reglamento
		if($reglamento=='S'&&$this->estadoEstudiante=='V')
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
		}//si se realiza el recalculo del estado del estudiante
                elseif($reglamento=='S'&&isset($registroEstudiante['recalcular'])&&$registroEstudiante['recalcular']==1)
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
                        $registroEstudiante['ESTADO']=isset($registroEstudiante['ESTADO'])?$registroEstudiante['ESTADO']:'';
                        //registra el evento de recálculo de reglamento
                        $datosRegistro=array('usuario'=>  $this->usuario,
                                                'evento'=>48,
                                                'descripcion'=>'Recalcula estado de estudiante',
                                                'registro'=>$this->anio.",".$this->periodo.", anterior:".$registroEstudiante['ESTADO'].", actual:".$this->estadoEstudiante,
                                                'afectado'=>$this->codigoEstudiante);
                        $this->procedimientos->registrarEvento($datosRegistro);
                    }elseif($reglamento=='N'&&isset($registroEstudiante['recalcular'])&&$registroEstudiante['recalcular']==1)
                    {
                        $this->actualizarCausal();
                        //actualiza el estado del estudiante despues de aplicar reglamento
                        if ($this->estadoEstudiante=='B'||$this->estadoEstudiante=='A')
                        {
                            $this->estadoEstudiante='A';
                        }else
                            {
                                $this->estadoEstudiante='V';
                            }
                        $this->actualizarEstadoEstudiante();
                        $datosRegistro=array('usuario'=>  $this->usuario,
                                                'evento'=>48,
                                                'descripcion'=>'Recalcula estado de estudiante',
                                                'registro'=>$this->anio.",".$this->periodo.", anterior:".$registroEstudiante['ESTADO'].", actual:".$this->estadoEstudiante,
                                                'afectado'=>$this->codigoEstudiante);
                        $this->procedimientos->registrarEvento($datosRegistro);
                    }elseif($this->renovaciones!='ok'&&$this->acuerdoEstudiante==2011004)
                        {
                            $this->causal+=5000;
                            $this->estadoEstudiante='U';
                            $this->actualizarCausal();
                            //actualiza el estado del estudiante despues de aplicar reglamento
                            $this->actualizarEstadoEstudiante();
                            $datosRegistro=array('usuario'=>  $this->usuario,
                                                    'evento'=>48,
                                                    'descripcion'=>'Recalcula estado de estudiante',
                                                    'registro'=>$this->anio.",".$this->periodo.", anterior:".$registroEstudiante['ESTADO'].", actual:".$this->estadoEstudiante,
                                                    'afectado'=>$this->codigoEstudiante);
                            $this->procedimientos->registrarEvento($datosRegistro);                          
                        }
		$this->contador++;
		return true;
	}


	/**
    * funcion que consulta todas las asignaturas perdidas de los estudiantes registrados en la tabla de reglamento para el actual anio y periodo
	* @return arreglo 
    */	
	function consultarNotasPerdidas(){
		$variables=array('proyecto'=>$this->proyecto,
						 'anio'=>$this->anio,
						 'periodo'=>$this->periodo);    
		$cadena_sql=$this->sql->cadena_sql('consultarNotas',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado;
	}

        /**
         * Funcio que permite consultar las notas perdidas de un estudiante
         * @return type
         */
        function consultarNotasPerdidasEstudiante($codEstudiante){
		$variables=array('proyecto'=>$this->proyecto,
                                'anio'=>$this->anio,
                                'periodo'=>$this->periodo,
                                'codEstudiante'=>$codEstudiante);
		$cadena_sql=$this->sql->cadena_sql('consultarNotasEstudiante',$variables);
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
			$resultado[$valor['COD_ESTUDIANTE']][$valor['ANIOPERIODO']]['PROMEDIO'][]=$valor['PROMEDIO'];
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
						 'anio'=>$this->anio,
						 'periodo'=>$this->periodo);    
		$cadena_sql=$this->sql->cadena_sql('consultarEstudiantesReglamento',$variables); 
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado;
	}

    /**
    * funcion que rescata los estudiantes de la tabla reglamento para un año y periodo especifico
    */	
	function consultarEstudianteRecalcularReglamento($codEstudiante){
		$variables=array('proyecto'=>$this->proyecto,
						 'anio'=>$this->periodoActivo[0]['ANO'],
						 'periodo'=>$this->periodoActivo[0]['PERIODO'],
                                                 'codEstudiante'=>$codEstudiante[0]['CODIGO'],
                                                 'proyecto'=>$codEstudiante[0]['CARRERA']);
		$cadena_sql=$this->sql->cadena_sql('consultarEstudianteRecalcularReglamento',$variables);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado[0];
	}

    /**
    * funcion que rescata los estudiantes de la tabla reglamento que han quedado en prueba para los periodos diferentes al actual
    */	
	function consultarEstudiantesPrueba(){
		$variables=array('proyecto'=>$this->proyecto);    
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
					$this->EspaciosPerdidosTotales[]=$asignatura."-".$anioperiodo;
					if(($anioper==$anioperiodo)){ 	
						$this->EspaciosPerdidosActuales[]=$asignatura;
					}
				}
			}
		}else{
			return $motivo;
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
		$variables=array('proyecto'=>$this->proyecto,
                                    'anio'=>  $this->anio,
                                    'periodo'=>$this->periodo,
                                    'numVecesPerdidos'=>$veces,
                                    'espaciosTercerar'=>count($this->EspaciosPerdidosActuales),
                                    'motivo'=>$motivo,
                                    'reglamento'=>$reglamento,
                                    'estudiante'=>$this->codigoEstudiante,
                                    'causal'=>$this->causal,
                                    'porcentaje'=>$this->porcentaje,
                                    'matriculas'=>$this->numMatriculas,
                                    'espacios_perdidos'=>$this->espaciosPerdidos,
                                    'promedio'=>$this->promedioEstudiante,
                                    'porcentaje004'=>$this->porcentaje004,
                                    'matriculas004'=>$this->matriculas004,
                                    'renovaciones004'=>$this->renovaciones004
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
		$variables=array('proyecto'=>$this->proyecto,
                                    'anio'=>  $this->anio,
                                    'periodo'=>$this->periodo,
                                    'estudiante'=>$this->codigoEstudiante,
                                    'causal'=>$this->causal,
                                    'porcentaje'=>$this->porcentaje,
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
                if ($this->estadoEstudiante=='A'||$this->estadoEstudiante=='B')
                {
                    $this->estadoEstudiante='B';
                }else
                    {
                        $this->estadoEstudiante='J';
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
                            $setenta=$this->validarSetentaPorciento($this->codigoEstudiante);
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
                if($this->renovaciones=='ok')
                {
                    
                }else
                    {
                        $this->causal+=5000;
                        $this->estadoEstudiante='U';
                    }
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
         * Función uqe permite realizar el conteo de renovaciones pendientes para un estudiante del 004 y determinar si puede continuar
         * @return string|boolean
         */
        function contarRenovaciones004() {
            if(is_array($this->matriculas))
            {
                foreach ($this->matriculas as $key => $matricula) {
                    if($this->matriculas[$key]['NOT_ANO']>=2012)
                    {
                        $matriculas[]=$this->matriculas[$key];
                    }
                }
            }
            $porcentaje=$this->calcularPorcentajePlanEstudiante();
            $this->porcentaje004=$porcentaje;
            if (is_numeric($porcentaje)&&$porcentaje>99)$porcentaje=99;
            if(isset($matriculas)&&is_array($matriculas))
            {
                $numMatriculas=count($matriculas);
            }else
                {
                    $numMatriculas=0;
                }
            if ($this->tipoProyecto==3)
            {
                $tipoProyecto=$this->tipoProyecto;
            }else
                {
                    $tipoProyecto=1;
                }
                $codEstudiante=substr($this->codigoEstudiante, -11, 4);
                if(strlen($this->codigoEstudiante)>=11&&$codEstudiante>=2012&&$numMatriculas>0)
                {
                    $numMatriculas=$numMatriculas-1;
                }
            if (is_numeric($porcentaje))
            {
                $numRenovaciones=$this->consultarTablaPermanencia($porcentaje,$tipoProyecto,$this->acuerdoEstudiante);
            }else
                {
                    $numRenovaciones='';
                }
                unset ($porcentaje);
            $this->matriculas004=$numMatriculas;
            $this->renovaciones004=$numRenovaciones;
            if(is_numeric($numRenovaciones)&&$numMatriculas<$numRenovaciones)
            {
                return "ok";
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
                            if ($this->estadoEstudiante=='A'||$this->estadoEstudiante=='B')
                            {
                                $this->estadoEstudiante='B';
                            }else
                                {
                                    $this->estadoEstudiante='J';
                                }
                        }
                }else
                    {
                        if ($this->estadoEstudiante=='A'||$this->estadoEstudiante=='B')
                        {
                            $this->estadoEstudiante='B';
                        }else
                            {
                                $this->estadoEstudiante='J';
                            }
                    }

		return true;
	}
	
	/**
         * Funcion para aplicar reglamento a estudiantes en acuerdo 027 de 1993
         * @return boolean
         */
	function aplicarAcuerdo1993027(){
            if ($this->estadoEstudiante=='A'||$this->estadoEstudiante=='B')
            {
                $this->estadoEstudiante='B';
            }else
                {
                    $this->estadoEstudiante='J';
                }
		if(isset($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante]))
                {
                    foreach($this->EspacioPerdidoMotivoPrueba[$this->codigoEstudiante] as $clave=>$numeroVeces)
                    {
                        //se busca el total de veces  q tiene espacio perdido en todos los periodos incluido el actual
                        if($numeroVeces==3)
                        {
                            $setenta=$this->validarSetentaPorciento($this->codigoEstudiante);
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
         * Funcion que permite calcular el numero de matriculas que lleva un estudiante
         * @return type
         */
        function calcularMatriculasEstudiante() {
            $this->matriculas=$this->consultarMatriculasEstudiante($this->codigoEstudiante);
            return count($this->matriculas);
        }        
	
	/**
         * Funcion que permite actualizar el estado del estudiante al que se le aplico reglamento
         * @return boolean
         */
	function actualizarEstadoEstudiante(){
		$variables=array('proyecto'=> $this->proyecto,
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
		$variable="&pagina=adminCierreSemestre";
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
         * Funcion que presenta el formulario de opciones para actualizar los datos (Por proyecto o estudiante)
         * Miltn Parra 27/01/2014
         */
        function seleccionarOpcion() {
            $this->crearInicioSeccion('Seleccione la opci&oacute;n para actualizar datos');
            $arregloBotones=array(array('boton'=>'',
                                    'action'=>'cierreSemestre/admin_recalcularEstadoEstudiante',
                                    'opcion'=>'mostrarFormulario',
                                    'nombreBoton'=>'Listado'),
                                    array('boton'=>'',
                                    'action'=>'cierreSemestre/registro_aplicarReglamento',
                                    'opcion'=>'opcionEstudiante',
                                    'nombreBoton'=>'Estudiante'));
            $this->mostrarBotonesCargaProyecto($arregloBotones);
            $this->crearFinSeccion();
        }
        
        /**
         * Funcion que permite crear el inicio de una seccion
         * Miltn Parra 27/01/2014
         * @param type $mensaje
         */
        function crearInicioSeccion($mensaje) {
            ?><fieldset>
                <legend><?echo $mensaje;?></legend><?
        }
        
        /**
         * Funcion que permite crear el fin de una seccion
         * Miltn Parra 27/01/2014
         */
        function crearFinSeccion() {
            ?></fieldset><?
        }
        
        /**
         *  Funcion que geenera botones para el proceso de carga de datos
         * Miltn Parra 27/01/2014
         * @param type $arregloBotones
         */
        function mostrarBotonesCargaProyecto($arregloBotones) {
            ?>
            <table>
                <tr>
                    <?
                    foreach ($arregloBotones as $botones) {
                    ?>
                        <td>
                            <?
                            $this->crearFormularioBoton($botones);
                            ?>
                        </td>
                    <?
                    }
                    ?>
                </tr>
            </table>
            <?
        }

        /**
         * Funcion que crea un formaulario po cada boton con las variables definidas en el arreglo
         * Miltn Parra 27/01/2014
         * @param type $arregloBotones
         */
        function crearFormularioBoton($arregloBotones) {
            ?>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='registro_cargarDatosEstudiantesInscripciones' id="<? echo $this->formulario ?>">
                            <input type="hidden" name="action" value="<?echo $arregloBotones['action']?>">
                            <input type="hidden" name="opcion" value="<?echo $arregloBotones['opcion'];?>">
                            <?
                            if(isset($arregloBotones['variables'])&&is_array($arregloBotones['variables']))
                            {
                                foreach ($arregloBotones['variables'] as $clave=>$variable) {
                                ?>
                                    <input type="hidden" name="<?echo $clave;?>" value="<?echo $variable;?>">
                                <?
                                }
                            }
                            ?>
                            <input type="submit" name="<?echo $arregloBotones['nombreBoton'];?>" value="<?echo $arregloBotones['nombreBoton'];?>" <?echo $arregloBotones['boton'];?>>
                        </form>       
            <?
        }        
        
        /**
         * Funcion que presenta el formulario para ingresar el codigo de estudiante para recalcular datos
         * Miltn Parra 27/01/2014
         */
        function mostrarFormularioCargaEstudiante() {
            $this->crearInicioSeccion(' Ingrese el c&oacute;digo del estudiante ');
            ?>
            <table class='contenidotabla centrar'>
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <tr align="center">
                  <td class="centrar" colspan="4">
                    <input type="text" name="codEstudiante" size="11" maxlength="11">
                    <input type="hidden" name="opcion" value="recalcularEstudiante">
                    <input type="hidden" name="action" value="cierreSemestre/<? echo $this->formulario2 ?>">
                  </td>
                </tr>
                <tr align="center">
                  <td class="centrar" colspan="4">
                    <input type="button" name="Cargar Datos" value="Recalcular Datos" onclick="if(<? echo $this->verificar2; ?>){document.forms['<? echo $this->formulario2?>'].submit()}else{false}">
                  </td>
                </tr>
              </form>
            </table><?
            $this->crearFinSeccion();
        }
        
        /**
         * Funcion que permite recalcular el estado para un solo estudiante y presenta el reporte del proceso
         * Miltn Parra 28/01/2014
         */
        function recalcularUnEstudiante()
        {
            $codEstudiante=$_REQUEST['codEstudiante'];
            $this->recalcularEstudiante($codEstudiante);
            $estudiantes=array($codEstudiante);
            $this->generarReporte($estudiantes);
        }


        /**
         * Funcion que recalcula el estado académico de un estudiante para el período anterior.
         * Miltn Parra 28/01/2014
         */
        function recalcularEstudiante($codEstudiante) {
            $this->periodoActivo();
            $datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
            $datosEstudiante['codEstudiante']=$codEstudiante;
            //$datosEstudiante[0]['codEstudiante']=$datosEstudiante[0]['CODIGO'];
            //$datosEstudiante[0]['PROYECTO_ESTUDIANTE']=$datosEstudiante[0]['CARRERA'];
            //verifica si el estudiante pertenece al proyecto curricular del coordinador
            if ($this->nivel==28||$this->nivel==4 ||$this->nivel==110)
            {
                $validar=$this->validacion->validarEstudiante($datosEstudiante);
                if (!is_array($validar))
                {
                    $this->estudantesNoProcesados[]=$codEstudiante;
                    return;
                }
            }
            $estudiante=$this->consultarEstudianteRecalcularReglamento($datosEstudiante);
            $estudiante['recalcular']=1;
            $this->proyecto=$datosEstudiante[0]['CARRERA'];
            $this->notasPerdidas=$this->consultarNotasPerdidasEstudiante($codEstudiante);
            if (is_array($this->notasPerdidas)&&!empty($this->notasPerdidas))
            {
                $this->organizarNotasPorEstudiante();
            }
            $recalcular=$this->procesarEstudiante($estudiante);
        }
        
        /**
         * Funcion que recalcula el estado de los estudiantes de un listado
         * Miltn Parra 28/01/2014
         */
        function recalcularListado() {
            foreach ($_REQUEST['codEstudiante'] as $key => $value)
            {
                $this->recalcularEstudiante($value);
            }
            $this->generarReporte($_REQUEST['codEstudiante']);
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
         * Permite consultar el total de matriculas de un estudiante a partir de las notas obtenidas
         */
        function consultarMatriculasEstudiante($codEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('consultarMatriculasEstudiante',$codEstudiante);
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
      
        /**
         * Permite consultar la tabla de permanencia con base en el porcentaje, tipo de proyecto y acuerdo del estudiante
         */
        function consultarTablaPermanencia($porcentaje,$tipoProyecto,$acuerdo) {
            $variable=array('porcentaje'=>$porcentaje,
                            'tipo_proyecto'=>$tipoProyecto,
                            'acuerdo'=>$acuerdo
                    );
            $cadena_sql=$this->sql->cadena_sql('consultarTablaPermanencia',$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado[0]['RENOVACIONES'];
            
        }
        /**
         * Consulta el período para ejecutar el proceso
         */
        function periodoActivo() {
            $cadena_sql=$this->sql->cadena_sql('periodo_activo');
            $this->periodoActivo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
            $this->anio=$this->periodoActivo[0]['ANO'];
            $this->periodo=$this->periodoActivo[0]['PERIODO'];

        }        
      
        /**
         * consulta los datos basicos de un estudiante
         * @param type $codEstudiante
         * @return type
         */
        function consultarDatosEstudiante($codEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('consultarDatosEstudiante',$codEstudiante);
            $estudiante=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $estudiante;
        }        
      

        function generarReporte($arreglo_estudiantes){
            if (is_array($this->estudantesNoProcesados))
            {
                foreach ($arreglo_estudiantes as $key => $value) {
                    foreach ($this->estudantesNoProcesados as $key2 => $value2) {
                        if ($value==$value2)
                        {
                            unset ($arreglo_estudiantes[$key]);
                            
                        }
                    }
                }
            }
            $cadena_estudiantes='';
            foreach ($arreglo_estudiantes as $key => $estudiante) {
                if(!$cadena_estudiantes){
                    $cadena_estudiantes=$estudiante;
                }else{
                    $cadena_estudiantes.=",".$estudiante;
}
            }
            $resultadoProceso=$this->consultarDatosReglamentoEstudiantes($cadena_estudiantes);
            
            $clase='cuadro_plano';
            echo '<table>
                <tr>
                <td><br>
                </td>
                </tr>
                </table>';
            if (is_array($arreglo_estudiantes)&&!empty($arreglo_estudiantes))
            {
                $clase="cuadro_color";
?>
	  <table class="contenidotabla" width='80%' border='1' align='center' cellpadding='4 px' cellspacing='0px'>
              <tr class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="50%" colspan="2" >
                      <strong><br>MOTIVOS DE PRUEBA/BAJO RENDIMIENTO<br></strong> 
                  </td>
                  <td class="<?echo $clase;?> centrar" width="50%" colspan="2">
                      <strong><br>CAUSALES EXCLUSIÓN<br></strong> 
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>1</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Bajo Promedio
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>1</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Promedio inferior a 3.2 para acuerdo 004 ó 3.0 para acuerdo 007 y 027.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>2</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado 3 o m&aacute;s espacios acad&eacute;micos.
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>2</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado 3 o m&aacute;s espacios acad&eacute;micos por cuatro per&iacute;odos.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>3</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Cursar un espacio acad&eacute;mico 3 veces o m&aacute;s.
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>3</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado un espacio acad&eacute;mico 3 veces o m&aacute;s.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong></strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>4</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Acumulaci&oacute;n de pruebas acad&eacute;micas.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="10%">
                      <strong></strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>5</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber cumplido el plazo m&aacute;ximo para la terminaci&oacute;n del plan de estudios. 
                  </td>
              </tr>
          </table><p></p>

          
<?
            if(is_array($resultadoProceso))
            {
                $this->mostrarReporteResultadoProceso($resultadoProceso);
            }
            }else
                {
                ?>
                    <table class="tablaBase centrar" width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >
                        <tr>
                            <td class="<?echo $clase;?>" width="100%" colspan="2">
                                <strong><br>NO SE PROCESARON ESTUDIANTES<br></strong> 
                            </td>
                        </tr>
                    </table>
                <?
                }
		           
        }
        
        /**
         * Funcion que permite consultar los datos del cierre de semestre en la tabla de reglamento
         * @param <array> $cadena_estudiantes
         * @return type
         */
        function consultarDatosReglamentoEstudiantes($cadena_estudiantes) {
            $variables=array('estudiantes'=>$cadena_estudiantes,
                            'periodo'=>$this->periodo,
                            'anio'=>$this->anio,
                            );
            $cadena_sql=$this->sql->cadena_sql('consultarDatosReglamento',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }

        
         /**
     * Funcion que presenta el encabezado de la tabla
     * @param type $resultadoProceso
     */    
    function mostrarReporteResultadoProceso($resultadoProceso){
        $total=count($resultadoProceso);
        $clase='cuadro_plano';
        ?>
	  <table class="contenidotabla" width='80%' border='1' align='center' cellpadding='4 px' cellspacing='0px'>
              <tr class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="100%" colspan="13">
        <?
        
        if($total>0){
            $this->mostrarDetalleProceso($resultadoProceso);
        }
        ?>
                  </td>
              </tr>
          </table>                      
            <?
    }        
    
     /**
      * Función para mostrar el detalle del proceso de aplicación de reglamento 
      * @param type $resultadoProceso
      */
    function mostrarDetalleProceso($resultadoProceso){
        ?>
            <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() { 
                        $('#tabla').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
                
    <?  
        echo "<h1>Detalles del Proceso</h1>";
        $html = "<table id='tabla'>";
        $html .= "<thead>";
        $html .= "<tr>"; 
        $html .= "<td style='font-size: 9pt;'>No.</td>"; 
        $html .= "<td style='font-size: 9pt;'>Código Estudiante</td>"; 
        $html .= "<td style='font-size: 9pt;'>Nombre Estudiante</td>";
        $html .= "<td style='font-size: 9pt;'>Estado Anterior</td>";
        $html .= "<td style='font-size: 9pt;'>Estado Actual</td>";
        $html .= "<td style='font-size: 9pt;'>Acuerdo</td>";
        $html .= "<td style='font-size: 9pt;'>Motivo Prueba</td>";
        $html .= "<td style='font-size: 9pt;'>Num. Esp. Acad. Reprob.</td>";
        $html .= "<td style='font-size: 9pt;'>Máx. veces esp. reproba.</td>";
        $html .= "<td style='font-size: 9pt;'>Promedio</td>";
        $html .= "<td style='font-size: 9pt;'>Causal Exclusi&oacute;n</td>";
        $html .= "<td style='font-size: 9pt;'>% Plan Aprobado</td>";
        $html .= "<td style='font-size: 9pt;'>Esp. Acad. Reprob. y veces (Prueba)</td>";        
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach ($resultadoProceso as $key => $estudiante) {
                $id=$key+1;
            $html .= "<tr>";
            $html .= "<td style='font-size: 10pt;'>".$id."</td>";
            foreach ($estudiante as $key2=>$obs_estudiante) {
                if (!is_numeric($key2))
                {
                    if($key2=='ESPACIOS_REPROBADOS'&&!is_numeric($obs_estudiante))
                    {
                        $html.="<td style='font-size: 10pt;'>";
                        $espacios=json_decode($obs_estudiante);
                        foreach ($espacios as $key => $value) {
                            $html.=$key.":".$value."<br>";
                        }
                        $html.="</td>";
                    }
                    else{
                            $html.="<td style='font-size: 10pt;'>".$obs_estudiante."</td>";
                        }
                }
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        
        $html .= "</table>";
        echo $html;
        if(is_array($this->estudantesNoProcesados))
        {
            echo "<h1>Estudiantes no Procesados</h1>";
            $html = "<table id='tabla'>";
            $html .= "<thead>";
            $html .= "<tr>"; 
            $html .= "<td style='font-size: 9pt;'>No.</td>"; 
            $html .= "<td style='font-size: 9pt;'>Código Estudiante</td>"; 
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            foreach ($this->estudantesNoProcesados as $key => $estudiante) {
                    $id=$key+1;
                $html .= "<tr>";
                $html .= "<td style='font-size: 10pt;'>".$id."</td>";
                $html .= "<td style='font-size: 10pt;'>".$estudiante."</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";

            $html .= "</table>";
            echo $html;
        }
    }
}
?>

