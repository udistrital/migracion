<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    05/09/2013
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
//include_once("sql.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_registroCalculoModelosBienestar extends funcionGeneral
{
    private $datosEstudiante;
    private $espaciosCursados;
    private $espaciosAprobados;
    private $espaciosReprobados;
    private $espaciosPlan;
    private $ano;
    private $periodo;
    private $estudiante;
    private $mensaje;
    private $estudiantesExitosos;
    //@ Método costructor
	function __construct($configuracion)
	{
                //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


		$this->configuracion = $configuracion;
                $this->cripto=new encriptar();
		//$this->tema=$tema;
                $this->sql=new sql_registroCalculoModelosBienestar($configuracion);

                 //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");

                //Conexion sga
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                //Conexion Oracle
                $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");

                $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                
                $this->formulario='registro_calculoModelosBienestar';
                $this->bloque='registro_calculoModelosBienestar';
                
                $this->procedimientos=new procedimientos();
        
                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/modeloRiesgo.class.php");
                $this->modeloRiesgo=new modeloRiesgo();

                $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
                $resultadoPeriodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $this->ano=$resultadoPeriodo[0][0];
                $this->periodo=$resultadoPeriodo[0][1];
                $this->mensaje=array();
                
	}
        
        /**
         * Funcion para solicitar formulario del proceso
         */
        function mostrarFormulario(){
            $this->consultaFacultades();
        }
        
        /**
         * Funcion que presenta el formulario para seleccionar la facultad
         */
        function consultaFacultades() {
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $facultades=$this->consultarFacultades();
            
            foreach ($facultades as $key=>$facultad) {
                $registro[$key][0]=$facultad['COD_FACULTAD'];
                $registro[$key][1]=$facultad['NOMBRE_FACULTAD'];
            }?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                    <table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
                        <tr>
                            <td>Seleccione la Facultad: 
                                <?$mi_cuadro=$this->html->cuadro_lista($registro,'facultad',$this->configuracion,-1,"",FALSE,"","facultad");
                                echo $mi_cuadro;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <input type="hidden" name="opcion" value="consultaProyectos">
                                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                <input class="boton" type="button" value="Continuar" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                            </td>
                        </tr>
                    </table>
		</form>	
            <?
            
            
        }
        
        /**
         * Funcion que presenta el formulario para seleccionar el proyecto
         */
        function consultaProyectos() {
            if(!isset($_REQUEST['facultad'])||$_REQUEST['facultad']<0)
            {
                echo "No se seleccionó facultad. Por favor regrese y seleccione una opci&oacute;n";exit;
            }
            $facultad=$_REQUEST['facultad'];
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $proyectos=$this->consultarProyectos($facultad);
                $registro[0][0]=10000+$facultad;
                $registro[0][1]="TODOS LOS PROYECTOS DE LA FACULTAD";
            foreach ($proyectos as $key=>$proyecto) {
                $registro[$key+1][0]=$proyecto['COD_PROYECTO'];
                $registro[$key+1][1]=$proyecto['COD_PROYECTO']." - ".$proyecto['NOMBRE_PROYECTO'];
            }?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                    <table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
                        <tr>
                            <td>Seleccione El Proyecto: 
                                <?$mi_cuadro=$this->html->cuadro_lista($registro,'codProyecto',$this->configuracion,-1,"",FALSE,"","codProyecto");
                                echo $mi_cuadro;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <input type="hidden" name="opcion" value="consultaEstudiantes">
                                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                <input class="boton" type="button" value="Ejecutar Proceso" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                            </td>
                        </tr>
                    </table>
		</form>	
            <?
            
                    
        }
        
        /**
         * Funcion que llama al proceso para ejecutar por estudiante. Presenta interfaz de avance del proceso global
         */
        function consultaEstudiantes() {
            if(!isset($_REQUEST['codProyecto'])||$_REQUEST['codProyecto']<0)
            {
                echo "No se seleccionó Proyecto. Por favor regrese y seleccione una opci&oacute;n";exit;
            }
            
            $estudiantes=array();
            $this->estudiantesExitosos=0;
            $codProyecto=$_REQUEST['codProyecto'];
            if ($codProyecto>10000)
            {
                $facultad=$codProyecto-10000;
                $proyectos=$this->consultarProyectos($facultad);
                foreach ($proyectos as $key => $proyecto) {
                    $estudiantesProyecto=$this->consultarEstudiantes($proyecto['COD_PROYECTO']);
                    if(is_array($estudiantesProyecto))
                    {
                        $estudiantes=array_merge($estudiantes,$estudiantesProyecto);
                    }
                }
                $total=count($estudiantes);
            }else
                {
                    $estudiantes=$this->consultarEstudiantes($codProyecto);
                    $total=count($estudiantes);
                }
            ?>
            <html><head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogress(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = vItem+' de '+vTotal+' - '+vValor ;
                 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                </script>
                <style type="text/css">
                /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
                  .ProgressBar     { width: 70%; border: 1px solid black; background: #eef; height: 1.25em; display: block; margin-left: auto;margin-right: auto }
                  .ProgressBarText { position: absolute; font-size: 1em; width: 20em; text-align: center; font-weight: normal; }
                  .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
                </style>
            </head>
    <body>
    <!-- Ahora creo la barra de progreso con etiquetas DIV -->
     <div class="ProgressBar">
          <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% </div>
          <div id="getProgressBarFill"></div>
        </div>
    </body>
        <?
            $a=1;
            if(is_array($estudiantes)&&!empty($estudiantes))
            {
                foreach ($estudiantes as $key => $estudiante) {
                    $this->estudiante=$estudiante['COD_ESTUDIANTE'];
                    $this->consultarDatos();
                    $porcentaje = $a * 100 / $total; //saco mi valor en porcentaje
                    echo "<script>callprogress(".round($porcentaje).",".$a.",".$total.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                    flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                    ob_flush();
                    $a++;
                    if(!isset($this->mensaje[$this->datosEstudiante['CODIGO']]))
                    {
                        $this->mensaje[$this->datosEstudiante['CODIGO']][]="Registro exitoso";
                        $this->estudiantesExitosos++;
                    }
                }
            }else
                {
                    echo "<script>callprogress(100,0,0)</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                }
            
            $this->mostrarReporteResultadoProceso($total);
            
        }
        
         
        /**
         * Función para consultar los datos academicos del estudiante
         */
        function consultarDatos(){
            
                    //obtenemos los codigos de los estudiantes digitados
                    $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:$this->estudiante) ;
                    if($codEstudiante && is_numeric($codEstudiante)){
                        $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);

                        $reglamentoEstudiante=$this->consultarReglamentoEstudiante($this->datosEstudiante['CODIGO']); 
                        $this->espaciosCursados=$this->consultarNotasDefinitivas();
                        $notaAprobatoria=$this->consultarNotaAprobatoria();
                        $matriculas = $this->consultarMatriculas($this->datosEstudiante['CODIGO']);
                        $this->consultarPlanEstudioEstudiante();
                        $this->espaciosAprobados=$this->buscarEspaciosAprobados($notaAprobatoria);
                        $this->espaciosReprobados=$this->buscarEspaciosReprobados($notaAprobatoria);
                        $espaciosVistos = $this->buscarEspaciosVistos(); 
                        if (strlen($this->datosEstudiante['CODIGO'])>10)
                        {
                            $semestre_ingreso = substr($this->datosEstudiante['CODIGO'], 0,5);
                        }else
                            {
                                $semestre_ingreso = substr($this->datosEstudiante['CODIGO'], 0,3);
                                $semestre_ingreso=19000+$semestre_ingreso;
                            }
                        $variables = $this->calcularVariables($espaciosVistos,$reglamentoEstudiante,$matriculas,$semestre_ingreso,$notaAprobatoria);
                        
                        $rendimiento_academico = $this->modeloRiesgo->calcularRendimientoAcademico($variables);
                        $probabilidad_riesgo = $this->modeloRiesgo->calcularProbabilidadRiesgo($variables);
                        $datosRegistro=array('codEstudiante'=>$this->datosEstudiante['CODIGO'],
                            'proyecto'=>$this->datosEstudiante['CARRERA'],
                            'ano'=>$this->ano,
                            'periodo'=>$this->periodo,
                            'pruebas'=>$variables['cantidad_pruebas_academicas'],
                            'indRepitencia'=>round($probabilidad_riesgo['indice_repitencia'],4),
                            'indPermanencia'=>round($rendimiento_academico['indice_permanencia'],4),
                            'indNivelacion'=>round($rendimiento_academico['indice_nivelacion'],4),
                            'rendimiento'=>round($rendimiento_academico['rendimiento_academico'],4),
                            'indAtraso'=>round($probabilidad_riesgo['indice_atraso'],4),
                            'edad'=>$variables['edad_ingreso'],
                            'semestres'=>$variables['cantidad_semestres_despues_grado'],
                            'indRiesgo'=>round($probabilidad_riesgo['indice_riesgo'],4));
                        $this->registrarRiesgoYRendimiento($datosRegistro);
                    }else{
                        $this->mensaje[$this->datosEstudiante['CODIGO']][]="Código de estudiante no valido";
                    }
        }
        
    /**
     * Función para consultar reglamento de un estudiante
     * @param type $cod_estudiante
     * @return type 
     */
    function consultarReglamentoEstudiante($cod_estudiante) {

        $cadena_sql = $this->sql->cadena_sql("consultar_reglamento_estudiante", $cod_estudiante);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
      }
    
        
    /**
     * Función para consultar los datos de estudiantes a partir de una cadena de codigos de estudiantes 
     * @param string $codigos_estudiantes
     * @return <array> 
     */
    function consultarPlanEstudioEstudiante() {
        $this->datosEstudiante['PENSUM']=(isset($this->datosEstudiante['PENSUM'])?$this->datosEstudiante['PENSUM']:'');
        if($this->datosEstudiante['PENSUM']){
            $datosEstudiante=array('plan'=>$this->datosEstudiante['PENSUM'],
                                    'proyecto'=>$this->datosEstudiante['CARRERA']);
            $cadena_sql=$this->sql->cadena_sql("consultar_espacios_plan_estudio_estudiante",$datosEstudiante);
            $espaciosPlan=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            $this->espaciosPlan=$espaciosPlan;
        }else{
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="No existe información del pensum";
            $anios=0;
        }
    }//fin funcion consultarDatosEstudiante
        
    
    /**
     * Funcion para consultar notas definitivas del estudiante
     * @return type
     */
    function consultarNotasDefinitivas() {
        
      $cadena_sql = $this->sql->cadena_sql("consultarEspaciosCursados", $this->datosEstudiante['CODIGO']);
      $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");        
     
      return $resultado;
      
    }
        
    /**
     * Funcion que permite consultar la nota aprobatoria para el proyecto del estudiante
     * @return <int>
     */
    function consultarNotaAprobatoria() {

        $variables=array('codProyectoEstudiante'=>  $this->datosEstudiante['CARRERA']            
                        );
        $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
      }
    
    /**
     * Funcion para consultar datos basicos del estudiante
     * @param type $cod_estudiante
     * @return type
     */
    function consultarDatosEstudiante($cod_estudiante) {
        $cadena_sql_est=$this->sql->cadena_sql("datos_estudiante", $cod_estudiante);
        $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_est,"busqueda");
        return $resultado_est[0];
    }//fin funcion consultarDatosEstudiante
      
    /**
     * Funcion para consultar las facultades de la universidad
     * @return type
     */
    function consultarFacultades() {
        $cadena_sql=$this->sql->cadena_sql("datos_facultades","");
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;
    }
    
    /**
     * Funcion para consultar los proyectos curriculares de una facultad
     * @param type $facultad
     * @return type
     */
    function consultarProyectos($facultad) {
        $cadena_sql=$this->sql->cadena_sql("datos_proyectos",$facultad);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;
    }
    
    /**
     * Funcion para consultar los estudiantes de un proyecto que se encontraban activos al finalizar el semestre.
     * @param type $codProyecto
     * @return type
     */
    function consultarEstudiantes($codProyecto) {
        $variables=array('codProyecto'=>$codProyecto,
            'ano'=>  $this->ano,
            'periodo'=>  $this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarEstudiantes",$variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;
    }
    
    /**
     * Funcion que busca los espacios aprobados por el estudiante entre el arreglo de los cursados.
     * @param type $notaAprobatoria
     * @return string 
     */
    function buscarEspaciosAprobados($notaAprobatoria){
        $aprobados=array();
        if(is_array($this->espaciosCursados))
        {
            foreach ($this->espaciosCursados as $value1)
            {
                if ((isset($value1['NOTA'])&&$value1['NOTA']>=$notaAprobatoria)||(isset($value1['CODIGO_OBSERVACION'])?$value1['CODIGO_OBSERVACION']:'')==19||(isset($value1['CODIGO_OBSERVACION'])?$value1['CODIGO_OBSERVACION']:'')==22||(isset($value1['CODIGO_OBSERVACION'])?$value1['CODIGO_OBSERVACION']:'')==24)
                {
                    $aprobados[]=array('CODIGO'=>$value1['CODIGO'],'NIVEL'=>(isset($value1['NIVEL'])?$value1['NIVEL']:''),'CREDITOS'=>(isset($value1['CREDITOS'])?$value1['CREDITOS']:''));
                }
            }

        }else
            {
                $aprobados='';
            }
        return $aprobados;
    }
 
    
      /**
     * Funcion que permite buscar los espacios reprobados por el estudiante
     * @return <string/0>
     */
    function buscarEspaciosReprobados($notaAprobatoria) {
        
          $reprobados=isset($reprobados)?$reprobados:'';
          $espacios=isset($reprobados)?$reprobados:'';
          
          if (is_array($this->espaciosCursados)){
              foreach ($this->espaciosCursados as $value) {
                  if (isset($value['NOTA'])&&($value['NOTA']<$notaAprobatoria||$value['CODIGO_OBSERVACION']==20||$value['CODIGO_OBSERVACION']==23||$value['CODIGO_OBSERVACION']==25)){
                    if ($value['CODIGO_OBSERVACION']==19||$value['CODIGO_OBSERVACION']==22||$value['CODIGO_OBSERVACION']==24)
                    {
                    }else
                        {
                            $espacios[]=$value['CODIGO'];
                        }
                 }
              }
              if(is_array($espacios)){
                  
                    $espacios=  array_unique($espacios);
                    if($this->datosEstudiante['IND_CRED']=='S'){
                    
                            foreach ($espacios as $key => $espacio) {
                                foreach ($this->espaciosCursados as $cursado) {
                                    if($espacio==$cursado['CODIGO']){
                                        $reprobados[$key]['CODIGO']=$cursado['CODIGO'];
                                        $reprobados[$key]['CREDITOS']=(isset($cursado['CREDITOS'])?$cursado['CREDITOS']:0);
                                    }
                                }

                            }
                    }else{
                        $reprobados=$espacios;
                    }
                    return $reprobados;      
                  
              }else{
             return 0;   
              }
              
          }
          else
            {
              return 0;
            }
      }    
  
   
        /** 
     * Funcion que retorna arreglo con los codigos de los espacios vistos
     * @return array
     */
    function buscarEspaciosVistos(){
        $i=0;
        $espacios_vistos=isset($espacios_vistos)?$espacios_vistos:'';
        $espacios=isset($reprobados)?$reprobados:'';
            
        if(is_array($this->espaciosCursados)){
            foreach ($this->espaciosCursados as  $espacio) {
                    $espacios[$i]=$espacio[0];
                    $i++;
            }
         }
         
         if(is_array($espacios)){
                    $espacios=  array_unique($espacios);
                    if($this->datosEstudiante['IND_CRED']=='S'){
                        foreach ($espacios as $key => $espacio) {
                                foreach ($this->espaciosCursados as $cursado) {
                                    if($espacio==$cursado['CODIGO']){
                                        $espacios_vistos[$key]['CODIGO']=$cursado['CODIGO'];
                                        $espacios_vistos[$key]['CREDITOS']=(isset($cursado['CREDITOS'])?$cursado['CREDITOS']:0);
                                    }
                                }
                            }
                    }else{
                        $espacios_vistos=$espacios;
                    }
                  
              }
        return $espacios_vistos;
    }
    
    /**
     * Funcion para consultar la cantidad de matriculas de un estudiante
     * @param type $cod_estudiante
     * @return type
     */
    function consultarMatriculas($cod_estudiante){
        $cadena_sql = $this->sql->cadena_sql("matriculas", $cod_estudiante);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }

        /**
     * Funcion para contar la cantidad de pruebas academicas de un estudiante teniendo en cuenta el acuerdo al cual esta acogido
     * @param array $reglamentoEstudiante
     * @return int
     */
    function contarPruebasAcademicas($reglamentoEstudiante){
        $this->datosEstudiante['ACUERDO']=(isset($this->datosEstudiante['ACUERDO'])?$this->datosEstudiante['ACUERDO']:'');
        if(is_array($reglamentoEstudiante) && $this->datosEstudiante['ACUERDO']){
            $cantidad=0;
            switch( $this->datosEstudiante['ACUERDO'])
                    {
                        case '2011004':
                                $cantidad = $this->contarPruebasAcuerdo2011004($reglamentoEstudiante);
                        break;
                        case '2009007':
                                $cantidad = $this->contarPruebasAcuerdo2009007($reglamentoEstudiante);
                        break;
                        case '1993027':
                                $cantidad = $this->contarPruebasAcuerdo1993027($reglamentoEstudiante);
                        break;
                    }
        }else{
            $cantidad='';
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="No existe información del acuerdo";
            
        }
        return $cantidad;
    }
    
       /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 004
     * @param type $reglamentoEstudiante
     * @return int/string
     */
    function contarPruebasAcuerdo2011004($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                $anioperiodo=$reglamento['REG_ANO'].$reglamento['REG_PER'];
                if($reglamento['REG_REGLAMENTO']=='S' && $anioperiodo>=20113){
                    $veces++;
                }
            }
        }else{
            $veces='';
        }
        return $veces;
    }
    
    /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 007
     * @param array $reglamentoEstudiante
     * @return string
     */
    function contarPruebasAcuerdo2009007($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                $anioperiodo=$reglamento['REG_ANO'].$reglamento['REG_PER'];
                if($reglamento['REG_REGLAMENTO']=='S' && $anioperiodo!=20101 && $anioperiodo!=20111 ){
                    $veces++;
                }
            }
        }else{
             $veces='';
        }
        return $veces;
    }
    
    /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 027
     * @param array $reglamentoEstudiante
     * @return int/string
     */
    function contarPruebasAcuerdo1993027($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                if($reglamento['REG_REGLAMENTO']=='S'){
                    $veces++;
                }
            }
        }else{
            $veces='';
        }
        return $veces;
    }

    /**
     * Funcion para realizar el calculo de las variables del modelo de riesgo por estudiante
     * @param type $espaciosVistos
     * @param type $reglamentoEstudiante
     * @param type $matriculas
     * @param type $semestre_ingreso
     * @param type $notaAprobatoria
     * @return type
     */ 
    function calcularVariables($espaciosVistos,$reglamentoEstudiante,$matriculas,$semestre_ingreso,$notaAprobatoria){
            
            $num_pruebas_academicas = $this->contarPruebasAcademicas($reglamentoEstudiante);
            $cantidad_matriculas = count($matriculas);
            $num_semestres_transcurridos = $this->contarSemestresTranscurridos($semestre_ingreso);
            $semestre_espacio_mas_atrasado = $this->calcularSemestreEspacioMasAtrasado();
            $edad_ingreso = $this->calcularEdadIngreso($semestre_ingreso);
            $num_semestres_despues_grado = $this->contarSemestresDespuesDeGrado($semestre_ingreso);
            
            if($this->datosEstudiante['IND_CRED']=='N'){
                $num_aprobados = count($this->espaciosAprobados);
                $num_reprobados = count($this->espaciosReprobados);
                $num_cursados = count($espaciosVistos);
                $num_adelantados = $this->contarEspaciosAdelantados($cantidad_matriculas,$notaAprobatoria,$this->espaciosCursados);
                $num_nivelado =  $this->contarEspaciosNivelados($cantidad_matriculas);
            }elseif($this->datosEstudiante['IND_CRED']=='S'){
                $num_aprobados = $this->contarCreditos($this->espaciosAprobados);
                $num_reprobados = $this->contarCreditos($this->espaciosReprobados);
                $num_cursados = $this->contarCreditos($espaciosVistos);
                $num_adelantados = $this->contarEspaciosAdelantados($cantidad_matriculas,$notaAprobatoria,$this->espaciosCursados);
                $num_nivelado =  $this->contarEspaciosNivelados($cantidad_matriculas);//creditos nivelados
            }
            
            $variables = array(
                  'cantidad_aprobados' => $num_aprobados, 
                  'cantidad_reprobados' => $num_reprobados, 
                  'cantidad_espacios_vistos' => $num_cursados,
                  'cantidad_pruebas_academicas' => $num_pruebas_academicas,
                  'promedio_acumulado' => (isset($this->datosEstudiante['PROMEDIO'])?$this->datosEstudiante['PROMEDIO']:''),
                  'cantidad_matriculas' => $cantidad_matriculas,
                  'cantidad_semestres'=>$num_semestres_transcurridos,
                  'cantidad_espacios_adelantados' => $num_adelantados,
                  'cantidad_espacios_nivelado' => $num_nivelado,
                  'semestre_espacio_mas_atrasado' => $semestre_espacio_mas_atrasado,
                  'edad_ingreso'=>  $edad_ingreso,
                  'cantidad_semestres_despues_grado' => $num_semestres_despues_grado
            );

            return $variables;
          
    }


        /**
     * Funcion para contar los semestres transcurridos desde el ingreso a la institucion
     * @param string $semestre_ingreso
     * @return int
     */
    function contarSemestresTranscurridos($semestre_ingreso){
        $cantidad='';
        $anio_actual=date('Y');
        if(date('m')<=6){
            $semestre_actual=1;
        }else{
            $semestre_actual=2;
        }
        $periodo_actual =$anio_actual.$semestre_actual;
        if($periodo_actual>=$semestre_ingreso){
            $cantidad = $this->calcularCantidadSemestres($semestre_ingreso,$periodo_actual);
        }
        if ($this->datosEstudiante['CARRERA']==97)
            $cantidad=round($cantidad/2);
        return $cantidad;
    }
 
       /**
     * Funcion para calcular  la cantidad de semestres entre 2 periodos
     * @param type $periodo_ini
     * @param type $periodo_fin
     * @return type
     */
    function calcularCantidadSemestres($periodo_ini,$periodo_fin){
	$semestres = 0;
	$anio_ini = substr($periodo_ini,0,4);
	$per_ini = substr($periodo_ini,4,1);
	$anio_fin = substr($periodo_fin,0,4);
	$per_fin = substr($periodo_fin,4,1);

	$anios = $anio_fin - $anio_ini;
	$semestres=$anios*2;
	if($per_ini>$per_fin){
		$semestres--;
	}
	if($per_ini<$per_fin){
		$semestres++;
	}
	return $semestres;
    }

    
        /**
     * Funcion para contar los espacios adelantados
     * @param int $cantidad_matriculas
     * @return int
     */
    function contarEspaciosAdelantados($cantidad_matriculas){
        $creditos=0;
        $adelantados=array();
        if(is_array($this->espaciosAprobados))
        {
            foreach ($this->espaciosAprobados as $espacio)
            {
                if($espacio['NIVEL']>$cantidad_matriculas){
                    $adelantados[]=$espacio;
                    $creditos=$creditos+(isset($espacio['CREDITOS'])?$espacio['CREDITOS']:'');
                }
            }
        }
        if($this->datosEstudiante['IND_CRED']=='N')
        {
            return count($adelantados);
        }elseif($this->datosEstudiante['IND_CRED']=='S')
            {
                return $creditos;
            }else{}
    }
  
    
       /**
     * Funcion  para calcular la edad del estudiante al momento del ingreso a la institucion 
     * @param string $semestre_ingreso
     * @return int
     */
    function calcularEdadIngreso($semestre_ingreso){
        $anios='';
        $long_codigo=  strlen($semestre_ingreso);
        $anio_ingreso = substr($semestre_ingreso, 0, 4);
        $per_ingreso = substr($semestre_ingreso, 4, 1);
        if($per_ingreso==1){
            $mes_ingreso= '02';
        }else if ($per_ingreso==2){
            $mes_ingreso= '08';
        }elseif ($per_ingreso>2){
            $mes_ingreso= '08';
        }
        $fecha_ingreso = $anio_ingreso.$mes_ingreso.'01';
        if((isset($this->datosEstudiante['FEC_NACIMIENTO'])?$this->datosEstudiante['FEC_NACIMIENTO']:'')){
            $anios=  $this->calcularAnios($this->datosEstudiante['FEC_NACIMIENTO'],$fecha_ingreso);
        }else{
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="No existe información de la fecha de nacimiento";
            $anios=0;
        }
        if($anios>99){
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="Fecha de nacimiento no valida";
            $anios=0;
        }
        return $anios;
    }
    
        /**
     * Funcion para calcular los años entre dos fechas
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return type
     */
    function calcularAnios($fecha_inicio,$fecha_fin){
        if(strtotime($fecha_inicio) > strtotime($fecha_fin)){
                $this->mensaje[$this->datosEstudiante['CODIGO']][]="La fecha de nacimiento es mayor a la fecha de ingreso";
               $anios=0;
        }else{
                $meses=$this->calcularCantidadMeses($fecha_inicio,$fecha_fin);
                $meses=(int)$meses-1;
                
                $anios=(int)($meses/12);
        }
      
        return $anios;
    }
    
    
    /**
     * Funcion para calcular la cantidad de meses entre 2 fechas
     * @param date $fecha_inicio
     * @param date $fecha_fin
     * @return int 
     */
    function calcularCantidadMeses($fecha_inicio,$fecha_fin){
        $dia_inicio= substr($fecha_inicio, -2);
        $mes_inicio= substr($fecha_inicio, 4,2);
        $ano_inicio= substr($fecha_inicio, 0,4);
        $dia_fin= substr($fecha_fin, -2);
        $mes_fin= substr($fecha_fin, 4,2);
        $ano_fin= substr($fecha_fin, 0,4);
        $dif_anios = $ano_fin- $ano_inicio;
                if($dif_anios == 1){
                    $mes_inicio = 12 - $mes_inicio;
                    $meses = $mes_fin + $mes_inicio;
                   
                   
                }
                else{
                        if($dif_anios == 0){
                            $meses=$mes_fin - $mes_inicio;
                           
                            
                        }
                        else{
                            if($dif_anios > 1){
                                $mes_inicio = 12 - $mes_inicio;
                                $meses = $mes_fin + $mes_inicio + (($dif_anios - 1) * 12);
                                
                            }
                            else { exit;    }
                        }
                    }
                    return $meses;
    }
    
    
        /**
     * Funcion para contar la cantidad de semestre despues del grado para ingresar a la institucion
     * @param type $semestre_ingreso
     * @return type
     */
    function contarSemestresDespuesDeGrado($semestre_ingreso){
        $cantidad='';
        if((isset($this->datosEstudiante['FEC_GRADO'])?$this->datosEstudiante['FEC_GRADO']:'')){
            $anio_grado=  substr($this->datosEstudiante['FEC_GRADO'], 0, 4);
            $mes_grado=  substr($this->datosEstudiante['FEC_GRADO'], 4, 2);
            if($mes_grado<=6){
                $semestre_grado=1;
            }else{
                $semestre_grado=2;
            }
            $periodo_grado =$anio_grado.$semestre_grado;
            if($semestre_ingreso>$periodo_grado){
                $periodo_grado =  $this->calcularSiguientePeriodo($periodo_grado);
                $cantidad = $this->calcularCantidadSemestres($periodo_grado,$semestre_ingreso);
            }else{
                $cantidad=0;
            }
                
        }else{
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="No tiene información de fecha de grado";
            $cantidad=0;
        }
        return $cantidad;
    }

    
        /**
     * Funcion para calcular el siguiente periodo a uno especificado
     * @param string $periodo
     * @return string
     */
    function calcularSiguientePeriodo($periodo){
	$anio = substr($periodo,0,4);
	$per = substr($periodo,4,1);
	if($per==2){
		$per=1;
		$anio++;
	}else if($per==1){
		$per++;
	}
	$periodo=$anio.$per;
	return $periodo;	

    }

    /**
     * Funcion que permite contar el numero de creditos de un grupo de espacios
     * @param type $espacios
     * @return type
     */
    function contarCreditos($espacios){
        $creditos=0;
        if(is_array($espacios)){
            foreach ($espacios as $espacio) {
                $creditos = $creditos + $espacio['CREDITOS'];
            }
        }
        return $creditos;
    }
    
    /**
     * Funcion que permite calcular el semestre del espacio que tiene mas atrasado el estudiante
     * @return type
     */
    function calcularSemestreEspacioMasAtrasado() {
        if (is_array($this->espaciosPlan))
        {
            if(is_array($this->espaciosAprobados)){
                foreach ($this->espaciosPlan as $key => $espaciosPlan) {
                    foreach ($this->espaciosAprobados as $key2 => $espaciosAprobados) {
                        if ($espaciosPlan==$espaciosAprobados)
                            unset ($this->espaciosPlan[$key]);
                    }
                }
            }
        }else{
            $this->mensaje[$this->datosEstudiante['CODIGO']][]="No existen espacios registrados en el plan de estudios del estudiante";

        }
        return $this->espaciosPlan[0]['SEMESTRE'];
    }
    
    /**
     * Funcion que permite realizar el registro de las variables e indices del modelo del riesgo
     * @param type $datosRegistro
     * @return type
     */
    function registrarRiesgoYRendimiento($datosRegistro) {
        $cadena_sql = $this->sql->cadena_sql("registrarRiesgoYRendimiento", $datosRegistro);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $resultado;
    }
    
    /**
     * Funcion que permite contar los espacios que el estudiante tiene nivelados, de acuerdo al numero de matriculas
     * @param type $cantidadMatriculas
     * @return type
     */
    function contarEspaciosNivelados($cantidadMatriculas) {
        $creditos=0;
        $nivelados=array();
        if (is_array($this->espaciosPlan))
        {
            foreach ($this->espaciosPlan as $key => $espacio) {
                if ($espacio['SEMESTRE']<=$cantidadMatriculas)
                {
                    $nivelados[]=$espacio;
                    $creditos=$creditos+(isset($espacio['CREDITOS'])?$espacio['CREDITOS']:'');
                }
            }
        }
        if($this->datosEstudiante['IND_CRED']=='N')
        {
            return count($nivelados);
        }elseif($this->datosEstudiante['IND_CRED']=='S')
            {
                return $creditos;
            }else{}
    }
  
    /**
     * Funcion que presenta el resultado del proceso de Modelo del riesgo
     * @param type $total
     */
    function mostrarReporteResultadoProceso($total){
        $estudiantesConInconvenientes=$total-$this->estudiantesExitosos;
        echo "<h1>Resultados del Proceso</h1>";
        
        $html = "<table>";
        $html .= "<tr>";
        $html .= "<td>Total Estudiantes:</td>";
        $html .= "<td>".$total."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>Estudiantes procesados exitosamente:</td>";
        $html .= "<td>".$this->estudiantesExitosos."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>Estudiantes que presentan inconvenientes:</td>";
        $html .= "<td>".$estudiantesConInconvenientes."</td>";
        $html .= "</tr>";
        $html .= "</table>";
        
        echo $html;
        
        if($estudiantesConInconvenientes>0){
            $this->mostrarDetalleProceso();
        }
        
    }
    
    /**
     * Funcion que presenta el detalle del proceso para cada estudiante
     */
    function mostrarDetalleProceso(){
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
        echo "<h1>Detalles de Proceso</h1>";
        $html = "<table id='tabla' >";
        $html .= "<thead>";
        $html .= "<tr>"; 
        $html .= "<td>Código Estudiante</td>"; 
        $html .= "<td>Observación</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach ($this->mensaje as $key => $estudiante) {
                
            $html .= "<tr>";
            $html .= "<td>".$key."</td>";
            $html .= "<td>";
            foreach ($estudiante as $key2=>$obs_estudiante) {
                if (count($estudiante)>1&&$key2>0)
                {
                    $html.="<br>";
                }
                $html .= $obs_estudiante;
            }
            $html .= "</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        
        $html .= "</table>";
        echo $html;
    }
}
?>