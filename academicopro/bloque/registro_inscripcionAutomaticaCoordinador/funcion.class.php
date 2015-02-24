<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/**
 * Funcion registroInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package Inscripciones
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 14/01/2013
 *
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Clase funcion_registroInscripcionAutomaticaCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package Inscripciones
 * @subpackage Coordinador
 */
class funcion_registroInscripcionAutomaticaCoordinador extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $codProyecto;
    private $nombreProyecto;
    private $ano;
    private $periodo;
    private $gruposProyecto;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/registro_inscripcionAutomaticaCoordinador".$configuracion["clases"]."/combinaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/registro_inscripcionAutomaticaCoordinador".$configuracion["clases"]."/horariosBinario.class.php");


        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        $this->miCombinacion=new combinacionArreglos();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_inscripcionAutomaticaCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        $this->horariosBinario=new horariosBinario();

    }

    /**
     * Funcion que verifica el cruce de horaios para el estudiante
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function ejecutarInscripcion()
    {   
        $i=0;
        $estudiantesFinal = array();
        ?>
        <script src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']  ?>/cargaFormulario.js" type="text/javascript" language="javascript"></script>
        <body onload="negative('cargando');" link="#000080" vlink="#000080" topmargin="0" leftmargin="0" >
        <?
        $this->mostrarMensajeDeEspera();
        //$this->cargarHorariosBinarios();
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        echo "<center><b>".$this->codProyecto."-".  $this->nombreProyecto."</b><br></center>";
        $this->enlaceVolverProyectos();
        $estudiantes=$this->consultarEstudiantesClasificados();
        $this->equivalentes = $this->consultarEquivalentes();
        $consultarHorarios=$this->consultarHorarios(); 
        $this->gruposProyecto=$this->consultarGruposProyectos($consultarHorarios);
        if(is_array($this->gruposProyecto)){
         foreach($estudiantes as $datosEstudiante){
                 $estudiantesFinal[] = $this->ejecutarInscripcionEstudiante($datosEstudiante['codEstudiante'],$datosEstudiante['tipo']);
                 $i++;
                
         }
        }
        $this->mostrarReporteInscripcion($estudiantesFinal);
      ?>
        </body>
        <?
  }

      
    function ejecutarInscripcionEstudiante($codEstudiante,$tipoEstudiante){
        $totalInscritos=0;
        $espaciosInscritos=$this->consultarEspaciosInscritos($codEstudiante); 
        $espaciosPreinscritosDemanda=$this->consultarEspaciosPreinscritosDemanda($codEstudiante);
        if(is_array($espaciosPreinscritosDemanda)){
                    $espaciosRanking=$this->consultarRanking($espaciosPreinscritosDemanda);
                    $gruposInscritas='';
                    if(is_array($espaciosInscritos))
                    {
                        $gruposInscritas=$this->buscarGruposEspaciosInscritos($espaciosInscritos);
                    }
                    if(is_array($espaciosPreinscritosDemanda))
                    {
                        $gruposDeEspaciosPreinscritos=$this->buscarGruposDeEspaciosPreinscritos($espaciosPreinscritosDemanda, $espaciosRanking);
                    }
                    if(is_array($gruposDeEspaciosPreinscritos))
                    {
                        $espaciosPreinscritosSinGrupo=$this->buscarEspaciosPreincritosSinGrupo($espaciosPreinscritosDemanda,$gruposDeEspaciosPreinscritos);
                    }else
                    {
                        $espaciosPreinscritosSinGrupo=$espaciosPreinscritosDemanda;
                    }
                    
                    if(is_array($espaciosPreinscritosSinGrupo) && !empty($espaciosPreinscritosSinGrupo) && $tipoEstudiante=='N'){ 
                                                
                        $gruposEspaciosEquivalentes=  $this->buscarGruposDeEspaciosEquivalentes($espaciosPreinscritosSinGrupo);
                        if(is_array($gruposEspaciosEquivalentes)&&!empty($gruposEspaciosEquivalentes[0])){
                            $gruposDeEspaciosPreinscritos=array_merge($gruposDeEspaciosPreinscritos,$gruposEspaciosEquivalentes[0]);
                        }
                    }else                       
                        {
                        
                        }
                        if(is_array($gruposDeEspaciosPreinscritos)){
                            if(isset($gruposInscritas)&&is_array($gruposInscritas))
                            {
                                $gruposDeEspaciosPreinscritos = $this->buscarEspaciosPreincritosSinInscribir($gruposDeEspaciosPreinscritos,$gruposInscritas);
                            }
                   if(count($gruposDeEspaciosPreinscritos) > 1){
                       $combinaciones=$this->miCombinacion->buscarCombinacionesHorario($gruposDeEspaciosPreinscritos,'codEspacio'); 
                   }else
                       {
                           $combinaciones[0]=$gruposDeEspaciosPreinscritos;
                       }
               }
               if(isset($combinaciones)&&is_array($combinaciones)&&!empty($combinaciones[0]))
               {
                   foreach ($combinaciones as $horarios) {
                       if(is_array($gruposInscritas))
                       {
                            $horarios=array_merge_recursive($gruposInscritas,$horarios);
                       }
                       $horarioParaEvaluar[0]=$horarios;
                       $horario[]=$this->verificarHorarios($horarioParaEvaluar);
                   }
               }else
                   {
                   $horario='';
                   }
               $conteoHorario=count($horario);
               $horarioAInscribir = '';
               if (is_array($horario)&&$conteoHorario>1)
               {
                   $horarioAInscribir=$this->ponderarHorarios($horario);
               }elseif(is_array($horario))
                   {
                        $horarioAInscribir=$horario;
                        $horarioAInscribir=$this->limpiarArreglo($horarioAInscribir[0]);
                   }
               if(is_array($horarioAInscribir)){
                   $totalInscritos = $this->inscribirHorario($horarioAInscribir,$codEstudiante);
               }
       }else{}
       $reprobadosNoInscritos =$this->consultarReprobadosNoInscritos($espaciosPreinscritosDemanda,$totalInscritos);
       if(is_array($totalInscritos)){
           $cantidadTotalInscritos = count($totalInscritos);
       }else
           {
               $cantidadTotalInscritos = 0;
           }
       if(is_array($espaciosPreinscritosDemanda)){
           $cantidadTotalPreInscritos = count($espaciosPreinscritosDemanda);
       }else
           {
               $cantidadTotalPreInscritos = 0;
           }
       $resultadoProyecto['codEstudiante'] = $codEstudiante;
       $resultadoProyecto['totalInscritosAuto'] = $cantidadTotalInscritos;
       $resultadoProyecto['totalPreinscritos'] = $cantidadTotalPreInscritos;
       $resultadoProyecto['totalReprobadosNoInscritos'] = $reprobadosNoInscritos;

       return $resultadoProyecto;
    }
    /**
     * Funcion que consulta los estudiantes con la clasificación de prioridad
     * @param <array> $datos
     * @return <int>
     */
    function consultarEstudiantesClasificados() {
        $cadena_sql=$this->sql->cadena_sql("consultarEstudiantesClasificados",$this->codProyecto);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
        return $resultado;
    }
    
/**
 *funcion que consulta los horarios de una carrera.
 * @return type 
 */
     function consultarHorarios(){
             $variables=array('codCarrera'=>$this->codProyecto,
                              'ano'=>$this->ano,
                              'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("buscarHorarios",$variables);  
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda"); 
        return $resultado_grupos;
       }
       
   /**
    *
    * @param type $codCarrera
    * @param type $consultarHorarios
    * @return type 
    */
     function consultarGruposProyectos($consultarHorarios){
        $arregloDisponibles='';
	$variables=array('ano'=>$this->ano,
			 'periodo'=>$this->periodo,
                         'codCarrera'=>$this->codProyecto);
        $cadena_sql=$this->sql->cadena_sql("buscarGrupos",$variables);        
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            if(is_array($resultado_grupos)){	
                foreach($resultado_grupos as $cupos){ 
                foreach($consultarHorarios as $horario){                        
                    $inscritos=(isset($cupos['INSCRITOS'])?$cupos['INSCRITOS']:0);
                    $disponibles=$cupos['CUPOS'];
                        if($inscritos < $disponibles && $horario['GRUPO']==$cupos['GRUPO'] && $horario['ASIGNATURA']==$cupos['ASIGNATURA']){
                        $arregloDisponibles[]=array( 'codGrupo'=>$cupos['GRUPO'],
                                                    'codEspacio'=>$cupos['ASIGNATURA'],
                                                    'cupo'=>$disponibles,
                                                    'numeroInscritos'=>(isset($cupos['INSCRITOS'])?$cupos['INSCRITOS']:0),
                                                    'codSede'=>$horario['SEDE'],
                                                    'sedeDiferente'=>$horario['SEDE_DIF'],
                                                    'lunes'=>$horario['LUNES'],
                                                    'martes'=>$horario['MARTES'],
                                                    'miercoles'=>$horario['MIERCOLES'],
                                                    'jueves'=>$horario['JUEVES'],
                                                    'viernes'=>$horario['VIERNES'],
                                                    'sabado'=>$horario['SABADO'],
                                                    'domingo'=>$horario['DOMINGO']);                                                                              
                        }
                   }
                } 
                
            }else{
                echo "No existen grupos asociados al proyecto ";exit;
            }
        return $arregloDisponibles;
   }
   
/**
    *Funcion que consulta los espacios académicos inscritos por un estudiante en (acinspre)
     * 
 * @param type $codCarrera
 * @param type $codEstudiante
 * @return type 
 */
function consultarEspaciosInscritos($codEstudiante){
        $variables=array('codCarrera'=>$this->codProyecto,
                          'ano'=>$this->ano,
                          'periodo'=>$this->periodo,
                          'codEstudiante'=>$codEstudiante);
        $cadena_sql=$this->sql->cadena_sql("buscarInscritas",$variables);  
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");        
        return $resultado;
}

/**
 *funcion que consulta los espacios académicos preinscritos por demanda a un estudiante.
 * @param type $codCarrera
 * @param type $codEstudiante
 * @return type 
 */
function consultarEspaciosPreinscritosDemanda($codEstudiante){
   	$variables=array('ano'=>$this->ano,
			 'periodo'=>$this->periodo,
                         'codCarrera'=>$this->codProyecto,
                         'codEstudiante'=>$codEstudiante);
        $cadena_sql=$this->sql->cadena_sql("buscarPreinscritas",$variables); 
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");  
        return $resultado_grupos;
      
  }
  
/**
 *Consulta el ranking de los espacios académicos preinscritos.
 * @param type $consultarPreinscritos
 * @param type $codCarrera
 * @return type 
 */
  function consultarRanking($consultarPreinscritos){  
      $ranking='';
        $variables=array('codCarrera'=>$this->codProyecto);
        $cadena_sql=$this->sql->cadena_sql("buscarRanking",$variables);
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");  
          if(is_array($consultarPreinscritos)){
           foreach ($consultarPreinscritos as $preinscritos) {               
            foreach($resultado_grupos as $posicion){ 
                if($preinscritos['REPROBADOS']=='S' && $preinscritos['ASIGNATURA']==$posicion['ASIGNATURA']){

                    $preinscritosReprobado[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
                                                'REPROBADO'=>'S',
                                                'RANKING'=>$posicion['POSICION']);


                    }elseif($preinscritos['REPROBADOS']=='N' && $preinscritos['ASIGNATURA']==$posicion['ASIGNATURA'])
                        {    $preinscritosReprobado[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
                                                            'REPROBADO'=>'N',
                                                            'RANKING'=>$posicion['POSICION']);
                        }else{}
                }
             }
             if (isset($preinscritosReprobado))
             {
             $ranking=$this->organizarRankingPreinscritos($preinscritosReprobado);
                $preinscritosSinRankin = $this->preinscritosSinRankin($consultarPreinscritos,$preinscritosReprobado);
                if(is_array($preinscritosSinRankin)){
                $ranking = $this->adicionarARankin($preinscritosSinRankin,$ranking);
                }
             }else
             {
                 $ranking= $this->adicionarARankin($consultarPreinscritos,'');
             }
           }else{}
            
            return $ranking;
  }

/**
 *Funcion que organiza los espacios académicos según su ranking.
 * @param type $preinscritos
 * @return type 
 */
  function organizarRankingPreinscritos($preinscritos) {      
         
      foreach (  $preinscritos as $key => $fila) {            
            $reprobadosPreinscritos[$key]  = $fila['REPROBADO'];            
            $rankingPreinscritos[$key]  = $fila['RANKING'];            
        }
        
        array_multisort($reprobadosPreinscritos, SORT_DESC, $rankingPreinscritos, SORT_ASC, $preinscritos);

        return $preinscritos;
       
    }
    
 /**
  *Funcion que retorna los grupos con cupo.
  * @param type $consultarGrupos 
  */
    
  function buscarGrupoEspacioConCupo(){
     if(is_array($this->gruposProyecto)){
      foreach($this->gruposProyecto as $grupos){         
         if($grupos['cupo'] > $grupos['numeroInscritos']){  
             $disponibles[]=array('cupo'=>$grupos['cupo'], 
                                  'codGrupo'=>$grupos['codGrupo']);        
         }
      }
     }
     return $disponibles;
 }

 /**
  *funcion que busca los grupos de un espacio homologo
  * 
  * @param type $parejaPrincipalHomologo
  * @return string 
  */
 function buscarGruposHomologo($codEspacioHomologo) {

    $arregloGrupoHomologo='';
    foreach($this->gruposProyecto as $grupo){
            if($grupo['codEspacio']==$codEspacioHomologo&&$grupo['cupo']>$grupo['numeroInscritos']){              
                $arregloGrupoHomologo[]=array(  'codGrupo'=>$grupo['codGrupo'],
                                                'codEspacio'=>$grupo['codEspacio'],
                                                'cupo'=>$grupo['cupo'],
                                                'numeroInscritos'=>$grupo['numeroInscritos'],
                                                'codSede'=>$grupo['codSede'],
                                                'sedeDiferente'=>$grupo['sedeDiferente'],
                                                'lunes'=>$grupo['lunes'],
                                                'martes'=>$grupo['martes'],
                                                'miercoles'=>$grupo['miercoles'],
                                                'jueves'=>$grupo['jueves'],
                                                'viernes'=>$grupo['viernes'],
                                                'sabado'=>$grupo['sabado'],
                                                'domingo'=>$grupo['domingo'],
                                                'creditos'=>'',
                                                'ht'=>'',
                                                'hp'=>'',
                                                'haut'=>'',
                                                'clasificacion'=>''
                                                );                
                                    }
                              
                        }
                        if(is_array($arregloGrupoHomologo))
                        {
                            $numeroEspacios=count($arregloGrupoHomologo);
                            array_unshift($arregloGrupoHomologo, $numeroEspacios);
                        }

                return $arregloGrupoHomologo;
 }
 /**
  *Funcion que busca los espacios Inscritos en la tabla acinspre.
  * @param type $consultarGrupos
  * @param type $consultarInscritas
  * @return type 
  */
 function buscarGruposEspaciosInscritos($consultarInscritas) {
                $arregloInscritas='';
                    foreach($this->gruposProyecto as $grupos){
                            if(is_array($consultarInscritas)){
                                foreach($consultarInscritas as $inscritas){                          
                                    if($grupos['codEspacio']==$inscritas['ASIGNATURA']  && $grupos['codGrupo']==$inscritas['GRUPO']  ){              
                                        $arregloInscritas[]=array(  'codGrupo'=>$grupos['codGrupo'],
                                                                    'codEspacio'=>$grupos['codEspacio'],
                                                                    'codSede'=>$grupos['codSede'],
                                                                    'sedeDiferente'=>$grupos['sedeDiferente'],
                                                                    'inscrito'=>1,
                                                                    'lunes'=>$grupos['lunes'],
                                                                    'martes'=>$grupos['martes'],
                                                                    'miercoles'=>$grupos['miercoles'],
                                                                    'jueves'=>$grupos['jueves'],
                                                                    'viernes'=>$grupos['viernes'],
                                                                    'sabado'=>$grupos['sabado'],
                                                                    'domingo'=>$grupos['domingo'],
                                                                    );                
                                    }
                              }
                           }else{}
                        }
                return $arregloInscritas;
 }
 
 /**
  *Funcion que busca los grupos disponibles para los espaciosacademico preinscritos 
  * 
  * @param type $consultarGrupos
  * @param type $consultarPreinscritos
  * @return type 
  */
 function buscarGruposDeEspaciosPreinscritos($consultarPreinscritos, $consultarRanking){     
 
    $arregloPreinscribir='';
    foreach($consultarRanking as $ranking){
        foreach($this->gruposProyecto as $grupos){  
            if(is_array($consultarPreinscritos)){               
            foreach($consultarPreinscritos as $preinscritos){
                if($grupos['codEspacio']==$ranking['codEspacio'] &&$grupos['codEspacio']==$preinscritos['ASIGNATURA'] && $grupos['cupo']>$grupos['numeroInscritos']){
                        $arregloPreinscribir[]=array(   'codGrupo'=>$grupos['codGrupo'],
                                                        'codEspacio'=>$grupos['codEspacio'],
                                                        'cupo'=>$grupos['cupo'],
                                                        'numeroInscritos'=>$grupos['numeroInscritos'],
                                                        'codSede'=>$grupos['codSede'],
                                                        'sedeDiferente'=>$grupos['sedeDiferente'],
                                                        'lunes'=>$grupos['lunes'],
                                                        'martes'=>$grupos['martes'],
                                                        'miercoles'=>$grupos['miercoles'],
                                                        'jueves'=>$grupos['jueves'],
                                                        'viernes'=>$grupos['viernes'],
                                                        'sabado'=>$grupos['sabado'],
                                                        'domingo'=>$grupos['domingo'],
                                                        'creditos'=>(isset($preinscritos['CREDITOS'])?$preinscritos['CREDITOS']:0),
                                                        'ht'=>$preinscritos['HT'],
                                                        'hp'=>$preinscritos['HP'],
                                                        'haut'=>$preinscritos['HAUT'],
                                                        'clasificacion'=>(isset($preinscritos['CLASIFICACION'])?$preinscritos['CLASIFICACION']:'')
                                                    ); 
                    }          
                  }                
                }                 
             }
        }
        if(is_array($arregloPreinscribir)){
        foreach ($arregloPreinscribir as $key=>$espacios) {
            if(!$espacios){
                unset($arregloPreinscribir[$key]);
            }
          }
        }
         return $arregloPreinscribir;
 }

    /**
  * Funcion que inscribe los espacios de acuerdo al horario seleccionado
  * @param type $horarioAInscribir 
  */
 function inscribirHorario($horarioAInscribir,$codEstudiante){
        $total='';
        $horarioAInscribir = $this->limpiarArregloFinal($horarioAInscribir);
        foreach ($horarioAInscribir as $key => $espacioAInscribir)
        {
            if(!isset($espacioAInscribir['inscrito'])||$espacioAInscribir['inscrito']!=1)
            {
                        $codEstudiante = $codEstudiante;
                        $codEspacio = $espacioAInscribir['codEspacio'];
                        $codGrupo = $espacioAInscribir['codGrupo'];
                        $creditos = $espacioAInscribir['creditos'];
                        $ht = $espacioAInscribir['ht'];
                        $hp = $espacioAInscribir['hp'];
                        $aut = $espacioAInscribir['haut'];
                        $cea = $espacioAInscribir['clasificacion'];
                        $inscritos = $espacioAInscribir['numeroInscritos'];
                        $resultadoInscripcion = $this->inscribirEspacio($codEstudiante,$codEspacio,$codGrupo,$creditos,$ht,$hp,$aut,$cea,$inscritos);
                        if($resultadoInscripcion==1){
                            $total[]=$espacioAInscribir;
                        }
            }
        }//fin foreach    
        return $total;
 }
 
    /**
     * Funcion que realiza la inscripcion de un espacio academico
      * Utiliza los metodos adicionarOracleInscripcion, buscarInscritos y actualizarInscritos
     * @param <array> $_REQUEST (pagina,opcion,codProyecto)
     */
    function inscribirEspacio($codEstudiante,$codEspacio,$codGrupo,$creditos,$ht,$hp,$aut,$cea,$inscritos){
        $datosRegistro= array(  'INS_CRA_COD'=>$this->codProyecto, 
                        'INS_EST_COD'=>$codEstudiante, 
                        'INS_ASI_COD'=>$codEspacio, 
                        'INS_GR'=>$codGrupo, 
                        'INS_SEM'=>0, 
                        'INS_ESTADO'=>'A', 
                        'INS_ANO'=>$this->ano, 
                        'INS_PER'=>$this->periodo, 
                        'INS_CRED'=>$creditos, 
                        'INS_NRO_HT'=>$ht, 
                        'INS_NRO_HP'=>$hp, 
                        'INS_NRO_AUT'=>$aut, 
                        'INS_CEA_COD'=>$cea);
        $inscrito = $this->adicionarOracleInscripcion($datosRegistro);
         if($inscrito>0)
            {   $datosEspacio = array(  'ano'=>$datosRegistro['INS_ANO'],
                                        'periodo'=>$datosRegistro['INS_PER'],
                                        'codProyecto'=>$datosRegistro['INS_CRA_COD'],
                                        'codEspacio'=>$datosRegistro['INS_ASI_COD'],
                                        'codGrupo'=>$datosRegistro['INS_GR']);
                $numeroInscritos = $inscritos+1;
                $this->actualizarInscritos($datosRegistro, $numeroInscritos);
             
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'57',
                                            'descripcion'=>'Realiza inscripción automatica',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['INS_ASI_COD'].", grupo->".$datosRegistro['INS_GR'].", proyecto->".$datosRegistro['INS_CRA_COD'],
                                            'afectado'=>$datosRegistro['INS_EST_COD']);
            }else{
                $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'57',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar inscripción automatica',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['INS_ASI_COD'].", grupo->".$datosRegistro['INS_GR'].", proyecto->".$datosRegistro['INS_CRA_COD'],
                                                 'afectado'=>$datosRegistro['INS_EST_COD']);
                }
                 $this->procedimientos->registrarEvento($variablesRegistro);
                 return $inscrito;
    }
    
    /**
     * Funcion que adiciona un registro de homologacion en Oracle
     * @param <array> $datos(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_ESTADO, INS_ANO, INS_PER, 
     * INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS)
     */
    function adicionarOracleInscripcion($datos) {
        $cadena_sql=$this->sql->cadena_sql("adicionar_inscripcion",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }
    
        
    /**
     * Funcion que actualiza los inscritos
     * @param <array> $datosRegistro()
     * @param <int> $numeroInscritos
     * @return <array> $resultado
     */
    function actualizarInscritos($datosRegistro,$numeroInscritos){
        $this->actualizarOracleInscritos($datosRegistro['INS_ASI_COD'],$datosRegistro['INS_GR'],$datosRegistro['INS_CRA_COD'],$numeroInscritos);
        $this->actualizarArregloInscritos($numeroInscritos,$datosRegistro);
    }


    /**
     * Funcion que actualiza en Oracle la cantidad de inscritos de un curso
     * @param <int> $ano,$periodo,$codEspacio,$codGrupo,$codProyecto,$numeroInscritos
     * @return <int> 
     */
    function actualizarOracleInscritos($codEspacio,$codGrupo,$codProyecto,$numeroInscritos){
        $datos=array('ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'codEspacio'=>$codEspacio,
                    'codGrupo'=>$codGrupo,
                    'codProyecto'=>$codProyecto,
                    'numeroInscritos'=>$numeroInscritos);
        $cadena_sql=$this->sql->cadena_sql("actualizar_cupo",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    

    /**
     * Funcion que actualiza el arreglo con la cantidad de inscritos del curso
     * @param type $numeroInscritos
     * @param type $datosGrupo 
     */
    function actualizarArregloInscritos($numeroInscritos,$datosGrupo){
        foreach ($this->gruposProyecto as $key => $grupos) {
            if($grupos['codEspacio'] == $datosGrupo['INS_ASI_COD'] && $grupos['codGrupo']== $datosGrupo['INS_GR'] ){
                $this->gruposProyecto[$key]['numeroInscritos']= $numeroInscritos;
            }
        }
    }

    function verificarHorarios($horarios) {
        $horas='';
        $horarioAnterior=$horarios;
        unset($horarios);
        $horarios=$horarioAnterior[0];
        $conteo=count($horarios);
        $b=1;
        $horario='';
        if($conteo>1)
        {
            for($a=0;$a<$conteo;$a++){
                if (is_array($horarios[$a])&&isset($horarios[$a])&&isset($horarios[$b])&&is_array($horarios[$b]))
                {
                    $and=$this->compararArreglos($horarios[$a],$horarios[$b]);
                    if($and['lunes']==0&&$and['martes']==0&&$and['miercoles']==0&&$and['jueves']==0&&$and['viernes']==0&&$and['sabado']==0&&$and['domingo']==0)
                    {
                        $horas=$this->sumarArreglos($horarios[$a],$horarios[$b]);
                        $horarios[$b]=$horas;
                        unset ($horario);
                        $horario=$horarios[$b];
                        unset($horas);
                        $b++;
                    }else
                        {
                            $horarios[$b]=$horarios[$a];
                            $b++;
                        }
                }elseif(is_array($horarios[$a])&&isset($horarios[$a]))
                    {
                        $horarios[$b]=$horarios[$a];
                        unset ($horario);
                        $horario=$horarios[$b];
                        $b++;
                    }
            }
            if(count($horario)>17)
            {
                $horarioUnico[0]=$this->limpiarArreglo($horario);
                $semana=array('lunes'=>$horario['lunes'],
                            'martes'=>$horario['martes'],
                            'miercoles'=>$horario['miercoles'],
                            'jueves'=>$horario['jueves'],
                            'viernes'=>$horario['viernes'],
                            'sabado'=>$horario['sabado'],
                            'domingo'=>$horario['domingo']);
                unset($horario);
                $horario=array_merge($horarioUnico,$semana);
            }
        }elseif($conteo==1)
        {
		if(is_array($horarios))
		{
                    $horarios=$horarios[0];
		$horarioUnico[0]=$this->limpiarArreglo($horarios);
                $semana=array('lunes'=>$horarios['lunes'],
                            'martes'=>$horarios['martes'],
                            'miercoles'=>$horarios['miercoles'],
                            'jueves'=>$horarios['jueves'],
                            'viernes'=>$horarios['viernes'],
                            'sabado'=>$horarios['sabado'],
                            'domingo'=>$horarios['domingo']);
                unset($horarios);
                $horario=array_merge($horarioUnico,$semana);
		}
        }else
            {
                $horario='';
            }
            return $horario;
    }
    
    /**
     * Funcion que compara el horaio entre dos arreglos de espacios
     * @param type $arreglo1
     * @param type $arreglo2
     * @return type
     */
    function compararArreglos($arreglo1,$arreglo2) {
        $resultado=array('lunes'=>$arreglo1['lunes']&$arreglo2['lunes'],
                    'martes'=>$arreglo1['martes']&$arreglo2['martes'],
                    'miercoles'=>$arreglo1['miercoles']&$arreglo2['miercoles'],
                    'jueves'=>$arreglo1['jueves']&$arreglo2['jueves'],
                    'viernes'=>$arreglo1['viernes']&$arreglo2['viernes'],
                    'sabado'=>$arreglo1['sabado']&$arreglo2['sabado'],
                    'domingo'=>$arreglo1['domingo']&$arreglo2['domingo']);
        return $resultado;
    }

    /**
     * Funcion que suma los horarios de dos arreglos de espacios
     * @param type $arreglo1
     * @param type $arreglo2
     * @return type
     */
    function sumarArreglos($arreglo1,$arreglo2) {
        $resultado=array('lunes'=>$arreglo1['lunes']|$arreglo2['lunes'],
                    'martes'=>$arreglo1['martes']|$arreglo2['martes'],
                    'miercoles'=>$arreglo1['miercoles']|$arreglo2['miercoles'],
                    'jueves'=>$arreglo1['jueves']|$arreglo2['jueves'],
                    'viernes'=>$arreglo1['viernes']|$arreglo2['viernes'],
                    'sabado'=>$arreglo1['sabado']|$arreglo2['sabado'],
                    'domingo'=>$arreglo1['domingo']|$arreglo2['domingo']);
        $arreglo1=$this->limpiarArreglo($arreglo1);
        $arreglo2=$this->limpiarArreglo($arreglo2);
        if (isset($arreglo1[0]))
        {
            array_push($arreglo1,$arreglo2);
        }else
        {
            $arreglo1=array($arreglo1, $arreglo2);
        }
        $resultado=array_merge($arreglo1,$resultado);
        return $resultado;
    }
          
    /**
     * Función que elimina el horario de un arreglo. Retorna la información de espacios academicos
     * @param type $arreglo
     * @return type
     */
    function limpiarArreglo($arreglo) {
        if(isset($arreglo['lunes']))
        {
            unset ($arreglo['lunes'],$arreglo['martes'],$arreglo['miercoles'],$arreglo['jueves'],$arreglo['viernes'],$arreglo['sabado'],$arreglo['domingo']);
        }
        return $arreglo;
    }
    
    /**
     * Funcion que permite hacer la ponderacion de los horarios para seleccionar el que se va a inscribir
     * @param type $horariosPosibles
     * @return type
     */
    function ponderarHorarios($horariosPosibles) {
        $totalHorarios=count($horariosPosibles);
        $horariosPosibles=$this->contarEspaciosPorHorario($horariosPosibles);
        $horariosPosibles=$this->contarHorasYHuecosPorHorario($horariosPosibles);
        $ea=$ht=$hh=$h=0;
        $horariosPosibles=$this->organizarArreglosHorarios($horariosPosibles);
        foreach ($horariosPosibles as $horario) {
            if ($horariosPosibles[0]['totalespacios']==$horario['totalespacios'])
	    {
		$ea++;
	    }
        }
        if($ea>1)
        {
            for($a=0;$a<$ea;$a++) {
                if ($horariosPosibles[0]['horasTotales']==$horariosPosibles[$a])
		{
		     $ht++;
		}
            }
            if($ht>1)
            {
                for($b=0;$b<$ht;$b++) {
                    if ($horariosPosibles[0]['horasHuecos']==$horariosPosibles[$b])
		    {
			$hh++;
		    }
                }
                if($hh>1)
                {
                    for($c=0;$c<$hh;$c++) {
                        if ($horariosPosibles[0]['huecos']==$horariosPosibles[$c])
			{
			     $h++;
			}
                    }
                    if($h>1)
                    {
                        $rnd=rand(0,$h);
                        $horarioInscribir=$horariosPosibles[$rnd-1];
                    }else
                        {
                            $horarioInscribir=$horariosPosibles[0];
                        }
                }else
                    {
                        $horarioInscribir=$horariosPosibles[0];
                    }
            }else
                {
                    $horarioInscribir=$horariosPosibles[0];
                }
        }else
            {
                $horarioInscribir=$horariosPosibles[0];
            }
            return $horarioInscribir;
    }
    
    /**
     * Funcion que retorna el numero de espacios que tiene un horario
     * @param type $horarios
     * @return type
     */
    function contarEspaciosPorHorario($horarios) {
        foreach ($horarios as $key=> $cadaHorario) {
            $totalEspacios['totalespacios']=count($cadaHorario)-7;
            $horarios[$key]=array_merge($horarios[$key], $totalEspacios);
        }
        return $horarios;
    }
    
    /**
     * Funcion que permite contar el numero de horas y huecos de un horario
     * @param type $horario
     * @return type
     */
    function contarHorasYHuecosPorHorario($horario) {
        foreach ($horario as $key=> $cadaHorario) {
            $unos=$cadaHorario['lunes'].$cadaHorario['martes'].$cadaHorario['miercoles'].$cadaHorario['jueves'].$cadaHorario['viernes'].$cadaHorario['sabado'].$cadaHorario['domingo'];
            $total['horasTotales']=substr_count($unos, '1');
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['lunes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['martes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['miercoles']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['jueves']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['viernes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['sabado']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['domingo']);
            $cuenta['huecos']=$cuenta['horasHuecos']=0;
            foreach ($ceros as $conteo) {
                $cuenta['horasHuecos']+=$conteo['cantidadHorasVacias'];
                $cuenta['huecos']+=$conteo['cantidadVacios'];
            }
            $horario[$key]=array_merge($horario[$key],$total,$cuenta);
            unset ($unos,$total,$cuenta,$ceros);
        }
        return $horario;
        
    }
    
    /**
     * Funcion que permite organizar los arreglos de horarios de acuerdo a los criterios establecidos
     * @param type $horarios
     * @return type
     */
    function organizarArreglosHorarios($horarios) {
        foreach ($horarios as $key => $fila) {
            $numeroEspacios[$key]  = $fila['totalespacios'];             
            $numeroHoras[$key]  = $fila['horasTotales'];             
            $horasHuecos[$key]  = $fila['horasHuecos'];             
            $numeroHuecos[$key]  = $fila['huecos'];
        }
        array_multisort($numeroEspacios,SORT_DESC,$numeroHoras,SORT_DESC,$horasHuecos,SORT_ASC,$numeroHuecos,SORT_DESC,$horarios);
        return $horarios;
    }    
    


   /**
     * Funcion que cuenta la cantidad de vacios o huecos y la cantidad de
horas que se encuentra en un horario
     * @param <string> $horario_bin
     * @return <array> $resultado
     */

    function contarVaciosEnHorario($horario_bin){
        $resultado = array();
        //buscamos la primer hora y la ultima que se encuentran ocupadas
        $pos_inicial = strpos($horario_bin, '1');
        $pos_final = strrpos($horario_bin, '1');
        if($pos_inicial===false && $pos_final===false){
            // No encuentra horas ocupadas
        }else{
            //extraemos la cadena desde la primer hora ocupada hasta la ultima hora ocupada
            $cantidad = ($pos_final - $pos_inicial)+1;
            $cadena = substr($horario_bin, $pos_inicial,$cantidad);
            $longitud = strlen($cadena);
            $horas = 0;
            $vacio = 0;
            $digito_anterior = 1;
            if($longitud > 1){
                    //recorremos la cadena y evaluamos cuantas horas y vacios existen
                    for($i=0; $i<$longitud; $i++){
                        $digito = substr($cadena, $i,1);
                        if($digito==0 ){
                            $horas++;
                            if($digito!=$digito_anterior){
                                $vacio++;
                            }
                        }
                        $digito_anterior=$digito;
                    }
            }
            $resultado['cantidadVacios'] = $vacio;
            $resultado['cantidadHorasVacias'] = $horas;
        }
        if(empty($resultado))
        {
            $resultado['cantidadVacios'] = 0;
            $resultado['cantidadHorasVacias'] = 0;
        }
        return $resultado;
    }    

     /**
     * Función que elimina el horario de un arreglo y totales. Retorna la información de espacios academicos
     * @param type $arreglo
     * @return type
     */
    function limpiarArregloFinal($arreglo) {
        $arreglo = $this->limpiarArreglo($arreglo);
        unset ($arreglo['totalespacios'],$arreglo['horasTotales'],$arreglo['horasHuecos'],$arreglo['huecos']);
        return $arreglo;
}

    function consultarReprobadosNoInscritos($preinscritos,$inscritos){
        $reprobadosNoInscritos = 0;
        if(is_array($preinscritos)){
            foreach ($preinscritos as $key => $espacio) {
                $band=0;
                if(is_array($inscritos)){
                    foreach ($inscritos as $key2 => $inscrito) {
                        if($espacio['ASIGNATURA']== $inscrito['codEspacio']){
                            $band=1;
                        }
                    }
                }
                if($band==0 && $espacio['REPROBADOS']=='S'){
                    $reprobadosNoInscritos++;
                }

            }
        }
        return $reprobadosNoInscritos;
    }
    
    function mostrarReporteInscripcion($estudiantes){
        $totalEstudiantes = $this->contarEstudiantesInscritos($estudiantes);
        $totalInscritos = $this->contarInscritos($estudiantes);
        $totalPerdidosNoInscritos = $this->contarPerdidosNoInscritos($estudiantes);
        echo "<br>Total estudiantes con inscripciones ".$totalEstudiantes;
        echo "<br>Total espacios inscritos ".$totalInscritos;
        echo "<br>Total espacios reprobados no inscritos ".$totalPerdidosNoInscritos."<br>";
        $this->mostrarListadoEstudiantes($estudiantes);
       
    }
    
    function contarInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            $total = $total + $datos['totalInscritosAuto'];
        }
        return $total;
    }

    function contarPerdidosNoInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalPreinscritos']>0){
            $total = $total + $datos['totalReprobadosNoInscritos'];
            }
        }
        return $total;
    }

   function contarEstudiantesInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalInscritosAuto']>0){
                $total++;
            }
        }
        return $total;
    }
    function mostrarListadoEstudiantes($estudiantes){
         echo "<table class='contenidotabla' width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
         echo "<thead class='sigma'>
                <th class='niveles centrar' width='15'>C&oacute;digo Estudiante</th>
                <th class='niveles centrar' width='15'>Preinscritas por demanda</th>
                <th class='niveles centrar' width='15'>Inscritas</th>
                <th class='niveles centrar' width='15'>% Preinscritas</th>
                <th class='niveles centrar' width='20'>Observaci&oacute;n</th>
                </thead>";
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalPreinscritos']>0){
                $porcentaje = number_format((($datos['totalInscritosAuto']/$datos['totalPreinscritos'])*100), 0)." %";
                $observacion=" ";
            }else{
                $porcentaje ="-";
                $observacion="No realiz&oacute; preinscripci&oacute;n por demanda";
            }
            
            echo "<tr>";
            echo "<td class='cuadro_plano centrar' >".$datos['codEstudiante']."</td>";
            echo "<td class='cuadro_plano centrar' >".$datos['totalPreinscritos']."</td>";
            echo "<td class='cuadro_plano centrar' >".$datos['totalInscritosAuto']."</td>";
            echo "<td class='cuadro_plano centrar' >".$porcentaje."</td>";
            echo "<td class='cuadro_plano' >".$observacion;
            echo "</tr>";
            
        }
         echo "</table>";
    }

    function mostrarMensajeDeEspera(){
        ?>
            <center><br><div id="cargando" style="z-index:2;background-color: #ffffff; layer-background-color: #336699; border: 0px double #000000; font-size: 24px; color:#CCCCCC; height:40px; width:600px ">
                <p align="center" style="margin-top: 0; margin-bottom: 0">
		    <b>
		        <img style="width: 25px;  height: 25px; border-width: 0px;" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"] ?>/ajax-loader.gif">
                        <font face="Verdana" size="3" color="#CC0000">
                            <?
  
                                echo " Ejecutando proceso ... ";
                            
                            ?>
                        </font>
		    </b>
		    </p>
                   
		</div>
                 </center>
         <?
    
    }//fin funcion mostrarMensajeDeEspera
    
    function redireccionarFormularioInicial($numeroEstudiantes) {
            
            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_inscripcionAutomaticaCoordinador";
            $variable.="&opcion=parametrosInscripcionAuto";   
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>"; 
            
            
    }
    
         /**
     * Funcion que muestra el enlace para redireccionar y realizar todas las homologaciones pendientes del proyecto curricular
     */
    function enlaceVolverProyectos() {
        $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_inscripcionAutomaticaCoordinador";
        $variable.="&opcion=parametrosInscripcionAuto";
        $variable.="&codProyecto=".$this->codProyecto;
        $variable.="&nombreProyecto=".$this->nombreProyecto;
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
       echo "<br><div align='right' > <a href='".$pagina.$variable."' class='enlaceHomologaciones'>::Volver</a></div><br>";
    }
    
    /**
     *Este metodo resta a los espacio preinscritos por demanda los espacios que se les encuenta grupo
     * con esto se obtiene los espacios que preinscritos que no tiene grupos
     * 
     * 
     * @param type $consultarPreinscritos
     * @param type $porInscribir espacios con grupo
     * @return type espacios preinscritos sin grupo
     */
    function buscarEspaciosPreincritosSinGrupo($preinscritos,$espaciosConGrupo){
        foreach ($preinscritos as $preinscrito) {
            $codPreinscritos[]=$preinscrito['ASIGNATURA'];
                }
        foreach ($espaciosConGrupo as $espacio) {
            $codEspaciosConGrupo[]=$espacio['codEspacio'];
            }
        $espaciosSinGrupo=array_diff($codPreinscritos, $codEspaciosConGrupo);               
        return $espaciosSinGrupo;
            }
 
    function consultarEquivalentes(){
        
        $cadena_sql=$this->sql->cadena_sql("consultarEquivalentes",$this->codProyecto);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;
 
        }
 
    /**
     *
     * @param type $consultarPreinscritos
     * @param type $preinscritosReprobado
     * @return type 
     */
    function preinscritosSinRankin($consultarPreinscritos,$preinscritosReprobado){
        $faltantes='';
        foreach ($consultarPreinscritos as $key => $preinscritos) {
            $existe=0;
            foreach ($preinscritosReprobado as $key2 => $espacios) {
                if($preinscritos['ASIGNATURA']==$espacios['codEspacio']){
                    $existe=1;
                }
            }
            if($existe==0){
                $faltantes[]=$preinscritos;
            }
        }
        return $faltantes;
    }
   
    /**
     *Adiciona los espacios preinscritos que no estan en la tabla ranking
     * 
     * @param type $preinscritosSinRankin
     * @param type $rankin
     * @return type 
     */
    function adicionarARankin($preinscritosSinRankin,$rankin){        
        
        if(is_array($rankin))
        {
            $ultimoRegistro=end($rankin);
            $mayorRanking=$ultimoRegistro['RANKING'];
        }else
            {
                $rankin=array();
                $mayorRanking=0;
            }
        foreach ($preinscritosSinRankin as $preinscritos) {
                        $mayorRanking=$mayorRanking+1;
                    
            $espacios[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
                        'REPROBADO'=>$preinscritos['REPROBADOS'],
                        'RANKIN'=> ''.$mayorRanking.''
                );

        }    

        $rankinCompleto= array_merge_recursive($rankin, $espacios);
               
        
        return $rankinCompleto;
    }
    
    function buscarGruposDeEspaciosEquivalentes($espacios){  
     
         $equivalentes='';
         $parejaConGrupo='';
         $equivalenteConGrupo='';
            foreach ($espacios as $espacio) {                  
                
                $equivalentes[]=$this->buscarEquivalentes($espacio);       
                
            }
        

            return $equivalentes;
        
    }
    
    /**
     *Este metodo resta a los espacio preinscritos por demanda los espacios que se encuentran inscritos
     * @param type $espaciosPreinscritos
     * @param type $inscritos
     * @return type 
     */
    function buscarEspaciosPreincritosSinInscribir($espaciosPreinscritos,$espaciosInscritos){
        $faltantes = '';
        foreach ($espaciosPreinscritos as $key => $preinscritos) {
            $existe=0;
            foreach ($espaciosInscritos as $key2 => $inscritos) {
                if($preinscritos['codEspacio']==$inscritos['codEspacio']){
                    $existe=1;
                }
            }
            if($existe==0){
                $faltantes[]=$preinscritos;
            }
        }
        return $faltantes;
    }
 

    
    /**
     *busca los espacios equivalentes de un espacio academico (actablahomologacion)
     * Retorna el arreglo con la relacion de espacios principales y equivalentes
     * 
     * @param type $codEspacio
     * @return type 
     */
    function buscarEquivalentes($codEspacio){

        $resultado='';
        $gruposEspacioEquivalente='';
        if(is_array($this->equivalentes))
        {
            foreach ($this->equivalentes as $equivalente) {
                //para el espacio principal busca los homologos
                if($equivalente['HOM_ASI_COD_PPAL']==$codEspacio){
                    //crea arreglo de ppal con homologo                
                    $resultado[]=$equivalente;
                    $gruposEspacioEquivalente[]=$this->buscarGruposHomologo($equivalente['HOM_ASI_COD_HOM']);                
                }
            }
        }
        if(is_array($gruposEspacioEquivalente)&&!empty($gruposEspacioEquivalente[0]))
        {
            $gruposHomologosOrdenados=$this->ordenarGruposHomologos($gruposEspacioEquivalente);
        unset($gruposHomologosOrdenados[0][0]);
        return $gruposHomologosOrdenados[0];//pasa los grupos del espacio homologo que tiene mayor cantidad de grupos
        }
        return '';
    }            
    
/**
 *Funcion que organiza los espacios académicos según su ranking.
 * @param type $preinscritos
 * @return type 
 */
  function ordenarGruposHomologos($gruposHomologos) {
      foreach (  $gruposHomologos as $key => $fila) {            
                    $cantidadGrupos[$key]  = $fila[0];                          
        }
        
        array_multisort($cantidadGrupos, SORT_DESC, $gruposHomologos);
        
        return $gruposHomologos;
       
    }    
    
/**
*Función que carga la tabla de horarios binarios
*/
   function cargarHorariosBinarios(){
       $borrado=$this->vaciarTablaHorariosBinarios();
       $resultadoHorarios =$this->horariosBinario->ConsultarHorarios();
       
    }    
    
  /**
   * Funcion que vacia la tabla de horarios
   * @return int 
   */
    function vaciarTablaHorariosBinarios() {
        
            $cadena_sql=$this->sql->cadena_sql("vaciarTablaHorariosBinarios",'');
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }    
    /**
     * Esta funcion permite publicar las inscripciones de los estudiantes del proyecto y registrar los eventos
     */
    function publicarInscripcion() {
        $variables=$_REQUEST;
        $inscripcion=$this->consultarInscripcionesProyecto($variables);
        $publicar=$this->publicarInscripcionesProyecto($variables);
        $datosProyecto=$this->consultarDatosProyecto($variables);
        $variables=array_merge($variables,$datosProyecto[0]);
        $eventoCierre=$this->insertarEventoCierre($variables);
        $eventosCalendario=$this->consultarEventosCalendario();
        $insertarEventosInscripciones=$this->publicarEventosInscripciones($eventosCalendario,$variables);
        echo "Se han publicado ".$publicar." Inscripciones del Proyecto ".$variables['codProyecto']." ".$variables['nombreProyecto'];
    }
    
    /**
     * Funcion que permite publicar los eventos de adiciones y cancelaciones
     * @param type $evento
     * @param type $variables
     */
    function publicarEventosInscripciones($evento,$variables) {
        for ($a=0;$a<4;$a++)
        {
            $this->insertarEventoInscripciones($evento[$a],$variables);
        }
    }

    /**
     * Funcion que permite consultar si existen las incripciones del proyecto sin publicar
     * @param type $variable
     * @return type
     */
    function consultarInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto;
    }
    
    /**
     * Funcion que permite cambiar el estado de las incripciones para publicar
     * @param type $variable
     * @return type
     */
    function publicarInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("publicarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $afectados;
    }
    
    /**
     * Funcion que permite consultar los datos del proyecto
     * @param type $variable
     * @return type
     */
    function consultarDatosProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosProyecto",$datos);
        $resultadoDatosProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoDatosProyecto;
    }
    
    /**
     * Funcion que permite consultar los eventos de inscripciones del calendario
     * @return type
     */
    function consultarEventosCalendario() {
        $datos=array('ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarEventosCalendario",$datos);
        $resultadoDatosProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoDatosProyecto;
    }
    
    /**
     * Funcion que permite insertar el evento de cierre de inscripcion automatica
     * @param type $variable
     * @return type
     */
    function insertarEventoCierre($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'facultad'=>$variable['FACULTAD'],
                    'tipo'=>$variable['TIPO']);
        
        $cadena_sql=$this->sql->cadena_sql("insertarEventoInscripcionAutomatica",$datos);
        $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $afectados;
    }
    
    /**
     * Funcion que permite insertar los eventos de adiciones y cancelaciones
     * @param type $datosEvento
     * @param type $variable
     * @return type
     */
    function insertarEventoInscripciones($datosEvento,$variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'facultad'=>$variable['FACULTAD'],
                    'tipo'=>$variable['TIPO'],
                    'evento'=>$datosEvento['EVENTO'],
                    'inicio'=>$datosEvento['INICIO'],
                    'fin'=>$datosEvento['FIN']);
        
        $cadena_sql=$this->sql->cadena_sql("insertarEventoInscripciones",$datos);
        $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $afectados;
    }    
}

?>
