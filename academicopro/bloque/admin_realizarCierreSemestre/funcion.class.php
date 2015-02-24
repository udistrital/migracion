<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 23/06/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/ejecutarHomologaciones.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
/**
 * Clase funcion_adminConsultarInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_adminRealizarCierreSemestre extends funcionGeneral
{

      public $configuracion;
        
	function __construct($configuracion) {

            $this->configuracion=$configuracion;
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");
            
	    $this->cripto=new encriptar();
	    $this->sql=new sql_adminRealizarCierreSemestre($configuracion);
            $this->homologaciones=new homologaciones();
            $this->procedimientos=new procedimientos();
            
            //Iniciar instnacias de bases de datos
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
            $this->acceso_db=$this->conectarDB($configuracion,"");
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links             
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

            // Datos de sesion
	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");



              //buscar los datos de ano y periodo actuales
              $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
              $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              $this->ano=$resultado_peridoActivo[0]['ANO'];
              $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}
         
        
        /**
         * Esta es la funcion de desambiguacion
         */
        function inciarCarguedeNotas() {
            
            $codProyecto=$_REQUEST['codProyecto'];
            echo '<h1><style="font-family: Arial"><Tiempo aproximado de ejecución 5 minutos...<h1>';
            ?><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/reloj.gif" width="80" height="80" border="0"><br><?
            sleep(2);
     
            $this->calcularNotasDefinitivas($codProyecto);
             $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";                
                $variable="pagina=admin_realizarCierreSemestre";
                $variable.="&opcion=redireccionCargueNotas";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&nombreProyecto=".$proyectoConsejeriaDocente[$i]['NOMBRE_PROYECTO'];
                $variable= $this->cripto->codificar_url($variable, $this->configuracion);
                
                echo "<script>location.replace('".$pagina.$variable."')</script>";

            
        }
        /**
         * Esta es la funcion de desambiguacion
         */
        function redireccionCargueNotas() {
            
            echo '<H1>Tiempo aproximado de ejecución 5 minutos...<H1>';
        ?><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/reloj.gif" width="80" height="80" border="0"><br><?
            sleep(1);
            $codProyecto=$_REQUEST['codProyecto'];
                       
            $this->cargarNotas($codProyecto);
            
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";                
            	$variable.="&pagina=adminCierreSemestre";
		$variable.="&opcion=consultarProyecto";
		$variable.="&proyecto=".$_REQUEST['codProyecto'];
		$variable= $this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$pagina.$variable."')</script>";
            
            exit;
        }
        
        /**
         * 
         * @param type $codProyecto
         */
        function calcularNotasDefinitivas($codProyecto) {
            

            $notasParciales=$this->consultarNotasParciales($codProyecto);
            $numRegistros=  count($notasParciales);
            
            foreach ($notasParciales as $registroNota) {
                
                $acumulado=$this->calcularNotas($registroNota);
                $resultadoActualizacion=$this->actualizarNota($registroNota,$acumulado);
                if($resultadoActualizacion==1){
                    
                }
                else{
                    echo "No se actualiz&oacute; el registro del estudiante con c&oacute;digo ".$registroNota['COD_ESTUDIANTE'];
                }
                
            }
         
            echo "Se calcularon ".$numRegistros." notas definitivas";
            
        }
        
        /**
         * Toma los valores de las notas parciales y los porcentajes de cada nota y realiza el calculo de la nota definitivas
         * Estos valores se toman de las tablas de inscripciones(nota) y de cursos(porcentaje)
         * Si el estudiante habilita la habilitacion vale el 70% y la nota obtenida durante el semestre el 30%
         * las notas se aproxima a un decimal ejemplo 43.5 = 44; 45.8=46
         * las notas definitivas se almacenan en la base de datos utilizando numeros enteros de 0 a 50
         * 
         * @param type $registroNota 
         * @return type
         */
        function calcularNotas($registroNota) {
            
            
                $acumulado=0;
                $acumuladoNota1=$acumuladoNota2=0;
                $acumuladoNota3=$acumuladoNota4=0;
                $acumuladoNota5=$acumuladoNota6=0;
                $acumuladoLab=$acumuladoEx=0;
                
                if(isset($registroNota['PARCIAL1']) && isset($registroNota['PORCENTAJE1'])){
                    $acumuladoNota1=$registroNota['PARCIAL1']*$registroNota['PORCENTAJE1']/100;                   
                }
                    if(isset($registroNota['PARCIAL2']) && isset($registroNota['PORCENTAJE2'])){
                    $acumuladoNota2=$registroNota['PARCIAL2']*$registroNota['PORCENTAJE2']/100;
                }
                if(isset($registroNota['PARCIAL3']) && isset($registroNota['PORCENTAJE3'])){
                    $acumuladoNota3=$registroNota['PARCIAL3']*$registroNota['PORCENTAJE3']/100;
                }
                if(isset($registroNota['PARCIAL4']) && isset($registroNota['PORCENTAJE4'])){
                    $acumuladoNota4=$registroNota['PARCIAL4']*$registroNota['PORCENTAJE4']/100;
                }
                if(isset($registroNota['PARCIAL5']) && isset($registroNota['PORCENTAJE5'])){
                    $acumuladoNota5=$registroNota['PARCIAL5']*$registroNota['PORCENTAJE5']/100;
                }
                if(isset($registroNota['PARCIAL6']) && isset($registroNota['PORCENTAJE6'])){
                    $acumuladoNota6=$registroNota['PARCIAL6']*$registroNota['PORCENTAJE6']/100;
                }
                if(isset($registroNota['PARCIAL_LAB']) && isset($registroNota['PORCENTAJE_LAB'])){
                    $acumuladoLab=$registroNota['PARCIAL_LAB']*$registroNota['PORCENTAJE_LAB']/100;
                }
                if(isset($registroNota['PARCIAL_EX']) && isset($registroNota['PORCENTAJE_EX'])){
                    $acumuladoEx=$registroNota['PARCIAL_EX']*$registroNota['PORCENTAJE_EX']/100;
                }
                
//                echo 'acumuladoNota1= '.$acumuladoNota1.'<br>';
//                echo 'acumuladoNota2= '.$acumuladoNota2.'<br>';
//                echo 'acumuladoNota3= '.$acumuladoNota3.'<br>';
//                echo 'acumuladoNota4= '.$acumuladoNota4.'<br>';
//                echo 'acumuladoNota5= '.$acumuladoNota5.'<br>';
//                echo 'acumuladoNota6= '.$acumuladoNota6.'<br>';
//                echo 'acumuladoLab= '.$acumuladoLab.'<br>';
//                echo 'acumuladoEx= '.$acumuladoEx.'<br><br>';
                
                $acumulado= ($acumuladoNota1+$acumuladoNota2+
                            $acumuladoNota3+$acumuladoNota4+
                            $acumuladoNota5+$acumuladoNota6+
                            $acumuladoLab+$acumuladoEx);

                
                if(isset($registroNota['PARCIAL_HAB']) && isset($registroNota['PORCENTAJE_HAB'])){
                                        
                    $acumuladoHab=$registroNota['PARCIAL_HAB']*$registroNota['PORCENTAJE_HAB']/100;
                    //En caso de que el estudainte halilite, la habilitacion se toma como el 70% y lo obtenido anteriormente como el 30%
                    $acumulado=$acumuladoHab+$acumulado*.3;
                }                                                             
                
                $acumulado=round($acumulado, 0, PHP_ROUND_HALF_UP);
                
                return $acumulado;
        }
        
        /**
         * Actualiza el valor calculado de la definitiva en el tabla de inscripciones
         * 
         * @param type $registroNota
         * @param type $acumulado
         * @return type
         */
        function actualizarNota($registroNota,$acumulado) {
                        
             $datos=array(
                        'ano'=>$this->ano,
                        'periodo'=>$this->periodo,
                        'codEstudainte'=>$registroNota['COD_ESTUDIANTE'],
                        'codProyecto'=>$registroNota['COD_PROYECTO'],
                        'codEspacio'=>$registroNota['COD_ESPACIO'],
                        'acumulado'=>$acumulado
                        );
             
          $cadena_sql = $this->sql->cadena_sql("actualizarNotasAcins", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
          
          return $resultado;
        }
        
        /**
         * funcion que carga las notas desde la tabla de inscripciones hacia la tabla de notas
         * tieme en cuenta los diferentes tipos de espacios:
         * 1. Pertenecen al plan de estudios del estudiante
         * 2. Esta relacionados por una regla de homologacion
         * 3. Ninguno de los anteriores
         * 
         * @param type $codProyecto
         */
        function cargarNotas($codProyecto) {
            
            $estudiantes=  $this->consultarEstudiantes($codProyecto);            
            $tabla_homologaciones = $this->consultarTablaHomologaciones($codProyecto);            
            //$notaAprobatoria=$this->consultarNotaAprobatoria($codProyecto);
            $validacionTablaHomologaciones = $this->verificarTablaHomologaciones($tabla_homologaciones);            
            //$totalHuerfanos=0;

                $espaciosProyecto = $this->consultarEspaciosProyecto($codProyecto); 
                if(count($estudiantes)>0){                            
                    
                    //$numeroEstudiante=0;
                    foreach ($estudiantes as $estudiante) {
                                                
                        //echo $numeroEstudainte=$numeroEstudiante+1;
                        //echo '=>'.$estudiante['CODIGO'];
                        //echo '<br>';
                        //consulta notas del estudiante en la tabla inscripciones y espacios del plan de estudios
                        $notasInscripciones = $this->consultarNotasInscripcionesEstudiante($estudiante['CODIGO'],$estudiante['COD_PROYECTO']);

                        if(is_array($notasInscripciones) && isset($notasInscripciones[0]['COD_ESPACIO'])){
                            
                            $codigosInscripciones=  $this->buscarCodigosInscripciones($notasInscripciones);
                            $espaciosPlanEstudio = $this->buscarEspaciosPlan($estudiante['COD_PLAN'],$espaciosProyecto);
                            
                            //IMPLICITAS
                            $resultadoHomologaciones=$this->homologaciones->ejecutarHomologacion($estudiante,$notasInscripciones,'',$espaciosPlanEstudio, $tabla_homologaciones,'implicitas',0);                                    
                            $resultadoHomologacionesImplicitas=$resultadoHomologaciones['IMPLICITAS'];                            
                            
                            if(is_array($resultadoHomologacionesImplicitas) && isset($resultadoHomologacionesImplicitas[0]['COD_ESPACIO'])){
                                //echo 'existen notas de homologaciones implicitas<br>';
                                $this->ejecutarImplicitas($estudiante,$resultadoHomologacionesImplicitas);
                                $codigosImplicitos=  $this->buscarCodigosEspaciosImplicitos($resultadoHomologacionesImplicitas);                                
                                //$numeroImplicitos=  count($codigosImplicitos);
                                //echo 'Implicitos='.$numeroImplicitos.'<br>';
                                //var_dump($codigosImplicitos);
                                $codigosInscripcionesSinImplicitas=  array_diff($codigosInscripciones, $codigosImplicitos);
                                
                                if (is_array($codigosInscripcionesSinImplicitas)){         
                                    
                                    $notasInscripcionesSinImplicitas=  $this->buscarDatosInscripciones($codigosInscripcionesSinImplicitas,$notasInscripciones);
                                                                        
                                }
                                else{
                                    //echo 'No existen codigos de inscripciones despues de restar implicitas<br>';
                                }
                                
                                
                            }
                            else{
                                $notasInscripcionesSinImplicitas=$notasInscripciones;
                                $codigosInscripcionesSinImplicitas=$codigosInscripciones;//LINEA AGREGADA
                                //echo 'Implicitas=0<br>';                                
                                }
                              
                            
                            //UNOAUNO
                            $resultadoHomologaciones=$this->homologaciones->ejecutarHomologacion($estudiante,$notasInscripcionesSinImplicitas,'',$espaciosPlanEstudio, $tabla_homologaciones,'unoauno',0);
                            $resultadoHomologacionesUnoauno=$resultadoHomologaciones['UNOAUNO'];
                            
                            if (is_array($resultadoHomologacionesUnoauno) && isset($resultadoHomologacionesUnoauno[0]['ESPACIO_HOM'])){
                                //echo 'existen notas de homologaciones uno a uno<br>';
                                $this->ejecutarUnoAUno($estudiante,$resultadoHomologacionesUnoauno);
                                $codigosUnoauno=  $this->buscarCodigosEspaciosUnoauno($resultadoHomologacionesUnoauno);
                                //$numeroUnoauno=  count($codigosUnoauno);
                                //echo 'Uno a uno='.$numeroUnoauno.'<br>';
                                //var_dump($codigosUnoauno);
                                $codigosInscripcionesSinUnoauno=  array_diff($codigosInscripcionesSinImplicitas, $codigosUnoauno);
                                if(is_array($codigosInscripcionesSinUnoauno)){
                                    //estos son lo archivos sobran y se consideran como notas Huerfanas
                                    $notasInscripcionesSinUnoauno=  $this->buscarDatosInscripciones($codigosInscripcionesSinUnoauno,$notasInscripcionesSinImplicitas);
                                }
                                else{

                                }
                            }
                            else {
                                $notasInscripcionesSinUnoauno=$notasInscripcionesSinImplicitas;
                                $codigosInscripcionesSinUnoauno=$codigosInscripcionesSinImplicitas;
                                //echo 'Unoa uno=0<br>';
                            }                            
      
                            
//                            echo "Notas";
//                            var_dump($notasInscripciones);
//                            echo "Sin implicitas";
//                            var_dump($codigosInscripcionesSinImplicitas);
                            $notasHuerfanas=$notasInscripcionesSinUnoauno; 
                            //$numeroHuerfanos=count($notasHuerfanas);                            
                            //echo 'Huerfanos='.$numeroHuerfanos;
                            //var_dump($notasHuerfanas);
                            $codigosHuerfanos=$codigosInscripciones;
                            
                            //echo '<br><b>Arreglo huerfanos:</b> ';
                            //var_dump($notasHuerfanas);
                            //$totalHuerfanos=$totalHuerfanos+$numeroHuerfanos;
                            //echo '<br><b>$totalHuerfanos='.$totalHuerfanos.'</b>';                            
                            
                            $this->ejecutarHuerfanos($estudiante,$notasHuerfanas);
//                            if(is_array($notasHuerfanas) && isset($notasHuerfanas['0']['ESPACIO_HOM'])){
//                                
//                            }
//                            else{
//                                
//                            }
                            
                        
                        }
                        else{
                            //echo 'No exiten inscripciones para el estudiante '.$estudiante['CODIGO'];                           
                        }
                          unset($notasInscripciones, $notasInscripcionesSinImplicitas, $notasInscripcionesSinUnoauno, $notasHuerfanas);
                        //echo '<hr><br>';
                        //exit;
                    }//fin foreach
                 
                }
                else{
                        $mensaje = "No ingreso estudiante para homologar";
                        echo $mensaje;exit;

                }


                $datosProyecto=$this->consultasDatosProyecto($codProyecto);                
                $this->insertarAccaleventos($datosProyecto);

            
        }        
    
        /**
         * 
         * @param type $cod_plan
         * @param type $espacios_proyecto
         * @return type
         */
        function buscarEspaciosPlan($cod_plan,$espacios_proyecto){
        $espacios_plan="";
        if(is_array($espacios_proyecto)) {
            $k=0;
       
            for($i=0;$i<count($espacios_proyecto); $i++) {
                if($cod_plan == $espacios_proyecto[$i]['PENSUM']){
                    $espacios_plan[$k]['COD_CRA'] = $espacios_proyecto[$i]['COD_CRA']; 
                    $espacios_plan[$k]['COD_ASI'] = $espacios_proyecto[$i]['COD_ASI']; 
                    $espacios_plan[$k]['SEMESTRE'] = $espacios_proyecto[$i]['SEMESTRE']; 
                    $espacios_plan[$k]['INDICA_ELECTIVA'] = $espacios_proyecto[$i]['INDICA_ELECTIVA']; 
                    $espacios_plan[$k]['H_TEORICAS'] = $espacios_proyecto[$i]['H_TEORICAS']; 
                    $espacios_plan[$k]['H_PRACTICAS'] = $espacios_proyecto[$i]['H_PRACTICAS']; 
                    $espacios_plan[$k]['ESTADO'] = $espacios_proyecto[$i]['ESTADO']; 
                    $espacios_plan[$k]['CREDITOS'] = $espacios_proyecto[$i]['CREDITOS']; 
                    $espacios_plan[$k]['PENSUM'] = $espacios_proyecto[$i]['PENSUM']; 
                    $espacios_plan[$k]['H_AUTONOMO'] = $espacios_proyecto[$i]['H_AUTONOMO']; 
                    $espacios_plan[$k]['INDICA_PROMEDIO'] = $espacios_proyecto[$i]['INDICA_PROMEDIO']; 
                    $espacios_plan[$k]['CLASIFICACION'] = $espacios_proyecto[$i]['CLASIFICACION']; 
                    $k++;
                    
                }
            }
           
       }
        
      return $espacios_plan;
       
    }               
        
        function buscarDatosInscripciones($codigosInscripciones,$datosInscripciones) {
            
            foreach ($codigosInscripciones as $codigo) {
                   foreach ($datosInscripciones as $datos) {

                       if($codigo==$datos['COD_ESPACIO']){
                           $resultado[]=$datos;
                       }
                   }
               }

               return $resultado;                     
            
        }
        
        /**
         * 
         * @param type $huerfanos
         * @param type $espaciosIncripciones
         * @return type
         */
        function buscarDatosEspaciosHuerfanos($huerfanos, $espaciosIncripciones) {
        
            
            foreach ($espaciosIncripciones as $espacio) {
                foreach ($huerfanos as $huerfano) {
                    
                    if($espacio['COD_ESPACIO']==$huerfano){
                        $resultado[]=$espacio;
                    }
                }
            }
            return $resultado;        
        }
    
        /**
     * Funcion que registra homologaciones implicitas
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
        function ejecutarImplicitas($datos_estudiante,$homologaciones){        
                    
            
        $notas=array();
        foreach ($homologaciones as $homologacion) {
            $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['COD_PROYECTO'],
                                            'NOT_EST_COD'=>$datos_estudiante['CODIGO'],
                                            'NOT_ASI_COD'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ANO'=>$this->ano,
                                            'NOT_PER'=>$this->periodo,
                                            'NOT_SEM'=>$homologacion['NIVEL_NOTA'],
                                            'NOT_NOTA'=>$homologacion['NOTA'],
                                            'NOT_GR'=>$homologacion['GRUPO'],
                                            'NOT_OBS'=>$homologacion['OBSERVACION'],
                                            'NOT_FECHA'=> date('d/m/y'),
                                            'NOT_EST_REG'=>'A',
                                            'NOT_CRED'=>$homologacion['CREDITOS'],
                                            'NOT_NRO_HT'=>$homologacion['HTD'],
                                            'NOT_NRO_HP'=>$homologacion['HTC'],
                                            'NOT_NRO_AUT'=>$homologacion['HTA'],
                                            'NOT_CEA_COD'=>$homologacion['CLASIFICACION'],
                                            'NOT_ASI_COD_INS'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ASI_HOMOLOGA'=>$homologacion['COD_ESPACIO'],
                                            'NOT_EST_HOMOLOGA'=>'N'  );
                                   
                                        
            $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
            if($ingresado>0)
            {
                $notas[]=array('HOMOLOGADO'=>'S',
                                'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                'COD_PPAL'=>$homologacion['COD_ESPACIO']);
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'54',
                                            'descripcion'=>'Registra Equivalencia Implicita',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                            'afectado'=>$datos_estudiante['CODIGO']);
            }else
                {
                    $notas[]=array('HOMOLOGADO'=>'N',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['COD_ESPACIO']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                 'afectado'=>$datos_estudiante['CODIGO']);
                }
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        return $notas;
               
       }                
       
        /**
     * Funcion que registra homologaciones implicitas
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
        function ejecutarHuerfanos($datos_estudiante,$homologaciones){        
        
        $notas=array();
        foreach ($homologaciones as $homologacion) {
            $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['COD_PROYECTO'],
                                            'NOT_EST_COD'=>$datos_estudiante['CODIGO'],
                                            'NOT_ASI_COD'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ANO'=>$this->ano,
                                            'NOT_PER'=>$this->periodo,
                                            'NOT_SEM'=>0,
                                            'NOT_NOTA'=>$homologacion['NOTA'],
                                            'NOT_GR'=>$homologacion['GRUPO'],
                                            'NOT_OBS'=>$homologacion['OBSERVACION'],
                                            'NOT_FECHA'=> date('d/m/y'),
                                            'NOT_EST_REG'=>'A',
                                            'NOT_CRED'=>$homologacion['CREDITOS'],
                                            'NOT_NRO_HT'=>$homologacion['HTD'],
                                            'NOT_NRO_HP'=>$homologacion['HTC'],
                                            'NOT_NRO_AUT'=>$homologacion['HTA'],
                                            'NOT_CEA_COD'=>$homologacion['CLASIFICACION'],
                                            'NOT_ASI_COD_INS'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ASI_HOMOLOGA'=>0,
                                            'NOT_EST_HOMOLOGA'=>'N'  );
                                       
            $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
            if($ingresado>0)
            {
                $notas[]=array('HOMOLOGADO'=>'S',
                                'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                'COD_PPAL'=>$homologacion['COD_ESPACIO']);
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'54',
                                            'descripcion'=>'Registra Equivalencia huerfanos',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                            'afectado'=>$datos_estudiante['CODIGO']);
            }else
                {
                    $notas[]=array('HOMOLOGADO'=>'N',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['COD_ESPACIO']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                 'afectado'=>$datos_estudiante['CODIGO']);
                }
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        return $notas;
               
       }                       
       
        /**
     * Funcion que registra homologaciones uno a uno
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
        function ejecutarUnoAUno($datos_estudiante,$homologaciones){

            foreach ($homologaciones as $homologacion)
            {
                $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['COD_PROYECTO'],
                                                'NOT_EST_COD'=>$datos_estudiante['CODIGO'],
                                                'NOT_ASI_COD'=>$homologacion['ESPACIO_PPAL'],
                                                'NOT_ANO'=>$this->ano,
                                                'NOT_PER'=>$this->periodo,
                                                'NOT_SEM'=>$homologacion['NOTA']['NIVEL_NOTA'],
                                                'NOT_NOTA'=>$homologacion['NOTA']['NOTA'],
                                                'NOT_GR'=>$homologacion['NOTA']['GRUPO'],
                                                'NOT_OBS'=>$homologacion['NOTA']['OBSERVACION'],
                                                'NOT_FECHA'=> date('d/m/y'),
                                                'NOT_EST_REG'=>'A',
                                                'NOT_CRED'=>$homologacion['NOTA']['CREDITOS'],
                                                'NOT_NRO_HT'=>$homologacion['NOTA']['HTD'],
                                                'NOT_NRO_HP'=>$homologacion['NOTA']['HTC'],
                                                'NOT_NRO_AUT'=>$homologacion['NOTA']['HTA'],
                                                'NOT_CEA_COD'=>$homologacion['NOTA']['CLASIFICACION'],
                                                'NOT_ASI_COD_INS'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_ASI_HOMOLOGA'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_EST_HOMOLOGA'=>'N'  );

                $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
                if($ingresado>0)
                {
                    $notas[]=array('HOMOLOGADO'=>'S',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['ESPACIO_PPAL']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'54',
                                                'descripcion'=>'Registra Equivalencia UnoAUno',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                'afectado'=>$datos_estudiante['CODIGO']);
                }else
                    {
                        $notas[]=array('HOMOLOGADO'=>'N',
                                        'COD_ESTUDIANTE'=>$datos_estudiante['CODIGO'],
                                        'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                        'COD_PPAL'=>$homologacion['ESPACIO_PPAL']);
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                    'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                     'afectado'=>$datos_estudiante['CODIGO']);
                    }
                $this->procedimientos->registrarEvento($variablesRegistro);
            }
            
            return $notas;
    }   

        /**
         * Consulta las notas parciales de todos los estudiantes de un proyecto,
         * estas notas se encuantran en la tabla de inscripciones, tambien se consulta el porcentaje de cada nota
         * de la tabla de cursos 
         * 
         * @param type $codProyecto
         * @return type
         */
        function consultarNotasParciales($codProyecto) {
            
           $datos=array('codProyecto'=>$codProyecto,
                        'ano'=>$this->ano,
                        'periodo'=>$this->periodo
                        );
        
          $cadena_sql = $this->sql->cadena_sql("consultarNotasParciales", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          
          return $resultado;            
            
            
        }
        
        /**
         * 
         * @param type $espacios
         * @return type
         */
        function buscarCodigosEspaciosImplicitos($espacios) {            
            
            foreach ($espacios as $espacio) {
                
                $resultado[]=$espacio['COD_ESPACIO'];
            }
            return $resultado;
        }
        
        /**
         * 
         * @param type $espacios
         * @return type
         */
        function buscarCodigosEspaciosUnoauno($espacios) {            
                        
                foreach ($espacios as $espacio) {

                    $resultado[]=$espacio['ESPACIO_HOM'];
                }
 
                return $resultado;
        }
        
        /**
         * 
         * @param type $espacios
         * @return type
         */
        function buscarCodigosInscripciones($espacios) {            
            
            foreach ($espacios as $espacio) {
                
                $resultado[]=$espacio['COD_ESPACIO'];
            }
            
            return $resultado;
        }
        
        /**
         * Consulta los estudiantes activos del proyecto (A, B)
         * @param type $codProyecto
         * @return type
         */
        function consultarEstudiantes($codProyecto) {
            
        $datos=array('codProyecto'=>$codProyecto,
                );
        
          $cadena_sql = $this->sql->cadena_sql("consultarEstudiantes", $datos);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          return $resultado;
          
        }

        /**
     * Funcion que consulta los registro que existen en la base de datos de la tabla de homologaciones
     * y retorna el resultado
     * @param <int> $cod_proyecto
     */
        function consultarTablaHomologaciones($cod_proyecto) {
            
            $cadena_sql = $this->sql->cadena_sql("consultarTablaHomologaciones",$cod_proyecto);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            return $resultado;
 
    }

        /**
          * Funcion que valida si existen registros en la tabla de homologaciones
          * @param <array> $tabla_homologaciones
          */
        function verificarTablaHomologaciones($tabla_homologaciones){
        if(is_array($tabla_homologaciones)){
            if(count($tabla_homologaciones)>0){
                return 'ok';
            }else{
                return 'No existen registros en la tabla de homologaciones para el Proyecto Curricular.';
                exit;
            }
        }else{
                return 'No existen registros en la tabla de homologaciones para el Proyecto Curricular.';
                exit;
            }
    }
        
        /**
   * Funcion que consulta la nota aprobatoria para el proyecto
   * @param type $cod_proyecto
   * @return type
   */
        function consultarNotaAprobatoria($cod_proyecto){
      $datos= array('cod_proyecto'=>$cod_proyecto);
      $cadena_sql = $this->sql->cadena_sql("consultarNotaAprobatoria",$datos);      
      $resultado= $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
      return $resultado[0][0];
    }    
        
        /**
     * Funcion que consulta los datos de un espacio academico
     * @param <int> $cod_espacio
     * @param <int> $cod_plan
     */
        function consultarEspaciosProyecto($cod_proyecto){
        
       $cadena_sql = $this->sql->cadena_sql("consultarEspaciosProyecto",$cod_proyecto);
       $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
       
       return $resultado;
       
    }
    
        /**
        * Funcion que consulta las notas de un estudiante que existen en la base de datos en la inscripciones
        * @param <int> $cod_estudiante
        */
        function consultarNotasInscripcionesEstudiante($cod_estudiante,$cod_proyecto){
          $datos= array('cod_estudiante'=>$cod_estudiante,
                        'cod_proyecto'=>$cod_proyecto);
         $cadena_sql = $this->sql->cadena_sql("consultarNotasInscripcionesEstudiante",$datos);
         $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

         return $resultado;

       }    
 
       /**
        * 
        * @param type $codProyecto
        */ 
       function consultasDatosProyecto($codProyecto) {
          
         $datos= array(
                        'codProyecto'=>$codProyecto
                        );
         $cadena_sql = $this->sql->cadena_sql("consultasDatosProyecto",$datos);
         $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

         return $resultado[0];
           
        }
       
        /**
     * Funcion que adiciona un registro de homologacion en Oracle
     * @param <array> $datos(NOT_CRA_COD, NOT_EST_COD, NOT_ASI_COD, NOT_ANO, NOT_PER, NOT_SEM, NOT_NOTA,NOT_GR, NOT_OBS, NOT_FECHA, NOT_EST_REG,
     * NOT_CRED, NOT_NRO_HT, NOT_NRO_HP, NOT_NRO_AUT, NOT_CEA_COD,  NOT_ASI_COD_INS, NOT_ASI_HOMOLOGA, NOT_EST_HOMOLOGA)
     */
        function adicionarOracleHomologacion($datos) {
        //$datos['identificador']= $consecutivo;

        $cadena_sql_homologacion=$this->sql->cadena_sql("adicionar_homologacion",$datos);
        //echo "<br>cadena homologacion oracle <br>".$cadena_sql_homologacion."<br>";
        $resultado_homologacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_homologacion,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }       
    
        /**
         * 
         * @param type $proyecto
         * @return type
         */
        function insertarAccaleventos($proyecto) {            
            
            $datos= array(
                        'codProyecto'=>$proyecto['CODIGO'],
                        'tipoProyecto'=>$proyecto['TIPO'],
                        'codDependencia'=>$proyecto['COD_DEPENDENCIA'],
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo,                       
                        );                        
            
            $cadena_sql=$this->sql->cadena_sql("insertarAccaleventos",$datos);
            $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
            
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);        

        }
              
}
?>
